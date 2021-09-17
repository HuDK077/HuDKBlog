// state
export const state = () => ({
    menus: [],
    routes: [],
    interface: [],
})

// getters
export const getters = {
    menus: state => state.menus,
    routes: state => state.routes,
    interface: state => state.interface,
}

// mutations
export const mutations = {
    SET_PERMISSION(state, { menus }) {
        state.menus = menus
    },
    SET_ROUTES(state, { routes }) {
        state.routes = routes
    },
    SET_INTERFACE(state, inter) {
        state.interface = inter
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
    // 路由
    initInterface({ commit }, inter) {
        commit('SET_INTERFACE', inter)
    },
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
