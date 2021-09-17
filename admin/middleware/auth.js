import Vue from "vue";

export default async function ({ $axios, route, next, store, error, env }) {
  if (route.name == "test") {
    if (env.NODE_ENV == "dev") {
    } else {
      error({ statusCode: 404, message: "页面找不到" })
    }
    return
  }
  let { error_code } = (await Vue.prototype.$apis.authentication()).data;
  if (error_code == 2001) {
    if (route.name == "index" || route.name == "login") {
      next("/home");
    } else {
      let menus = store.getters["permission/menus"];
      if (!menus.includes(route.name) && route.name != "home") {
        error({ statusCode: 403, message: "没有权限" })
      }
    }
  } else {
    if (route.name != "login") {
      return next("/login");
    }
  }

}