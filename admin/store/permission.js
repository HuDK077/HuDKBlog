// state
export const state = () => ({
  menus: [],
  routes: [],
})

// getters
export const getters = {
  menus: state => state.menus,
  routes: state => state.routes,
}

// mutations
export const mutations = {
  SET_PERMISSION(state, { menus }) {
    state.menus = menus
  },
  SET_ROUTES(state, { routes }) {
    state.routes = routes
  },
}

// actions
export const actions = {
  // 有权限的页面
  setPermission({ commit }, { menus }) {
    commit('SET_PERMISSION', { menus })
  },
  // 路由
  initRoutes({ commit }, { routes }) {
    commit('SET_ROUTES', { routes })
  },
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};