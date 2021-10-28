import Vue from "vue";

import { eImg, eBtn } from "@/components";
import { themes } from "@/common/theme.js";
import { deepClone, formatDate, randomString, randomNum, diffData } from "@/common/utils";

export default function ({ store, env, app }, inject) {
  // console.log(arguments);
  store.dispatch("local/initThemes", { themes })
  if (store.getters["auth/isLogin"]) {
    store.dispatch("auth/getAuthUser");
    store.dispatch("local/getSiteConfig");
  }

  app.router.afterEach(async (to, from) => {
    // console.warn(to, from);
    if (to.name && to.meta.keepAlive) {
      store.dispatch("local/pushKeepAliveList", to.name)
    }
    if (to.name && from.name) {
      let to_name = to.name.slice(0, to.name.lastIndexOf("."))
      let from_name = from.name.slice(0, from.name.lastIndexOf("."))
      // console.log(to_name, from_name);
      if (to_name != from_name) {
        store.dispatch("local/removeKeepAliveList", from_name)
      }
    }
  })

  // app.router.beforeEach((to, from, next) => {
  //   console.log(to, from);
  //   next()
  // })

  Vue.component(eImg.name, eImg)
  Vue.component(eBtn.name, eBtn)
  Vue.prototype.$env = env;
  inject("clone", deepClone)
  inject("formatDate", formatDate)
  inject("randomString", randomString)
  inject("randomNum", randomNum)
  inject("diffData", diffData)
}
