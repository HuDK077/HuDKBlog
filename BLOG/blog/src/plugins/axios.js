import { deepClone } from "@/utils";
import Vue from "vue";

export default function ({ $axios, store, env, next, error, route }) {
  // $axios.onRequest((config) => {
  //   console.log('config: ', config)
  //   return config
  // })
  // 请求带token
  $axios.interceptors.request.use((request) => {
    request.baseURL = env.APP_URL
    request.headers.common["Access-Control-Allow-Origin"] = "*";
    request.headers.common["Access-Control-Allow-Credentials"] = "true";
    request.headers.common["Access-Control-Allow-Methods"] = "GET, POST, OPTIONS";
    request.headers.common["Access-Control-Allow-Headers"] = "DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type";

    const token = store.getters['auth/token']
    if (token) {
      request.headers.common.Authorization = `Bearer ${token}`
    }
    const locale = store.getters['lang/locale']
    if (locale) {
      request.headers.common['Accept-Language'] = locale
    }
    window.req = request;
    return request
  })

  // 返回拦截
  $axios.interceptors.response.use(
    response => {
      // console.warn(response.headers);
      if (response.headers.authorization) {
        let { authorization, microtime } = response.headers
        let token = authorization.split("Bearer ")[1]
        store.dispatch("auth/setToken", { token, timestamp: Number(microtime) })
      }
      return response
    },
    (err) => {
      const { status, statusText } = err.response || {};
      // window.err = err;
      if (status) {
        if (status == 401) {
          if (store.getters['auth/isLogin']) {
            store.dispatch("auth/logout");
          }
          // console.warn(route.name, status);
          if (route.name != "login") {
            // console.warn(route.name, err.response);
            return next("/login");
          }
        } else if (status == 403) {
          error({ statusCode: 403, message: "特朗普说这个页面你不能进......" })
        } else if (status == 422) {
          // 缺少数据
          let resData = deepClone(err.response, {});
          resData.data.error_code = 2004;
          let errorKeys = Object.keys(resData.data.errors || {});
          resData.data.message = (errorKeys.length && resData.data.errors[errorKeys[0]][0]) || resData.data.message
          return Promise.resolve(resData)
        } else {
          // error({ statusCode: status, message: statusText })
          // 错误内容记录
          let resData = deepClone(err.response, {});
          if (resData.config.url != "/admin/errorLog/errorPush") {
            delete resData.data.debug.trace;
            let error = {
              msg: resData.data.message,
              info: statusText,
              status_code: resData.data.status_code,
              url: resData.config.url,
              stack: resData.data.debug,
              type: "api"
            }
            store.dispatch('error/addErrorLog', error)
            if (env.NODE_ENV == "production") {
              Vue.prototype.$apis.errorPush({ error })
            }
          }
        }
      }
      return Promise.reject(error)
    });
}
