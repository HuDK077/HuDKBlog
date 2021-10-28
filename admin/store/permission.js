import { deepClone, routerArr, interopDefault } from "@/common/utils";
import { routes } from "@/routers/routes";
import { router } from "@/routers";
import Vue from "vue";
import Router from 'vue-router'

const proto = Vue.prototype
const indexPage = () => interopDefault(import('@/pages/index.vue'));
const staticRoutes = deepClone(routerArr(routes))

const createRoutes = function (routes, data) {
  data.forEach(v => {
    const menu = {
      name: v.slug,
      path: v.path == "#" ? `/${v.slug}` : v.path,
      component: v.path == "#" ? indexPage : () => interopDefault(import(`@/pages${v.file}`)),
      children: [],
      redirect: v.redirect,
      title: v.title,
      icon: v.icon,
      meta: v.meta ? v.meta : { title: v.title }
    }
    if (!menu.meta.title) {
      menu.meta.title = v.title
    }

    if (v.children && v.children.length) {
      createRoutes(menu.children, v.children)
    }
    routes.push(menu)
  })
}

// state
export const state = () => ({
  routes: [],
  widgets: [],
  dynamicRoutes: [],
})

// getters
export const getters = {
  routes: state => state.routes,
  widgets: state => state.widgets,
  dynamicRoutes: state => state.dynamicRoutes,
}

// mutations
export const mutations = {
  SET_PERMISSION(state, menus) {
    state.menus = menus
  },
  // 添加路由
  SET_ROUTES(state, routes) {
    state.routes = routes
  },
  // 添加组件
  SET_WIDGETS(state, widgets) {
    state.widgets = widgets
  },
  // 动态变量
  SET_DYNAMIC_ROUTES(state, dynamicRoutes) {
    state.dynamicRoutes = dynamicRoutes
  },
}

// actions
export const actions = {
  // 初始化权限数据
  initPermissionData({ commit, dispatch }) {
    return new Promise((resolve, reject) => {
      proto.$apis.getAuthUser({ option: ["widgets", "menus"] }, { progress: false })
        .then(res => {
          console.log(res.data)
          let { error_code, data } = res.data;
          if (error_code == 2001) {
            let { widgets, menus } = data
            commit('SET_WIDGETS', widgets)
            const loadMenus = deepClone(menus)
            commit("SET_DYNAMIC_ROUTES", loadMenus)
            dispatch("generateRoutes")
              .then(resolve).catch(reject)
          } else {
            reject(res)
          }
        })
        .catch((res) => {
          reject(res)
        })
    })
  },
  generateRoutes({ commit, getters }) {
    return new Promise((resolve, reject) => {
      try {
        const tempAsyncRoutes = [];
        createRoutes(tempAsyncRoutes, getters.dynamicRoutes)
        //
        router.matcher = new Router({ mode: 'history', routes: [...router.options.routes] }).matcher
        //
        router.addRoutes(tempAsyncRoutes)
        const routeList = tempAsyncRoutes.concat(staticRoutes);
        commit("SET_ROUTES", routeList);
        resolve()
      } catch (error) {
        reject(error)
      }
    })
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};