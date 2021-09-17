import Vue from "vue";

import { eImg } from "@/components";
import { themes } from "@/configs/theme.js";
import { routes } from "@/routers/routes";
import { router } from "@/routers";
import { deepClone, routerArr, formatDate, randomString, randomNum } from "@/utils";

export default function ({ store, env }, inject) {
  // console.log(arguments);
  store.dispatch("local/initThemes", { themes })
  store.dispatch("permission/initRoutes", { routes: deepClone(routerArr(routes)) })

  // 用户数据更新
  const updateUser = (cb) => {
    Vue.prototype.$apis.getAuthUser()
      .then(res => {
        let { error_code, data } = res.data;
        if (error_code == 2001) {
          let { menus, member, role } = data;
          store.dispatch("auth/updateMember", { member, role });
          store.dispatch("permission/setPermission", { menus });
          store.dispatch("auth/initStatus", "success");
          if (cb) { cb(true, res); }
        } else {
          if (cb) { cb(false, res); }
        }
      }).catch((res) => {
        if (cb) { cb(false, res); }
      })
  }
  const updateSiteConfig = () => {
    Vue.prototype.$apis.getConfigArray()
      .then(res => {
        // console.log(res);
        let { error_code, data } = res.data;
        if (error_code == 2001) {
          let { logo, logo_sm, website_icp, system_version } = data;
          store.dispatch("local/setSiteConfig", { logo, logo_sm, website_icp, system_version });
        }
      })
  }
  updateUser();
  updateSiteConfig();
  // 路由守卫 设置title
  router.beforeEach(async (to, from, next) => {
    // 设置页面标题
    let title = `${env.APP_NAME} - ${to.meta && (to.meta.title || to.meta.name)}`;
    window.document.title = title;
    next()
  })

  Vue.filter("formatDate", (v) => {
    if (v) {
      return formatDate("yyyy-MM-dd", v)
    }
    return ""
  })

  Vue.component(eImg.name, eImg)
  Vue.prototype.$env = env;
  inject("updateUser", updateUser);
  inject("updateSiteConfig", updateSiteConfig);
  inject("clone", deepClone)
  inject("formatDate", formatDate)
  inject("randomString", randomString)
  inject("randomNum", randomNum)
}
