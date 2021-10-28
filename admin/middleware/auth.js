import Vue from "vue";
export default async function ({ store, error, from, route, next, redirect }) {
  // console.log("middleware", store, from, route);
  if (store.getters["auth/isLogin"]) {
    if (!store.getters["permission/routes"].length) {
      const res = await store.dispatch("permission/initPermissionData")
      // console.log(res);
      if (res) {
        console.error(error);
        error({ statusCode: 500, message: "获取菜单权限异常" })
      } else {
        if (!from.name) {
          next(route.path)
        } else {
          if (from.name == "login" && route.name == "home") {
            return redirect("/home")
          }
        }
      }
    }
  }

  let { error_code } = (await Vue.prototype.$apis.authentication()).data;
  if (error_code == 2001) {
    if (route.name == "index" || route.name == "login") {
      next("/home");
    }
  } else {
    if (route.name != "login") {
      return next("/login");
    }
  }

  // if (route.name == "index" || (route.name == "login" && store.getters["auth/isLogin"])) {
  //   next("/home");
  // }
}