// state
export const state = () => ({
  sidebar: true,
  theme: "default",
  themes: [],
  resetPwd: false,
  siteConfig: "",
  imageSize: 2048,
  keepAliveList: []

})

// getters
export const getters = {
  sidebar: state => state.sidebar,
  theme: state => state.theme,
  themes: state => state.themes,
  resetPwd: state => state.resetPwd,
  siteConfig: state => state.siteConfig,
  imageSize: state => state.imageSize,
  keepAliveList: state => state.keepAliveList,

}

// mutations
export const mutations = {
  CHANGE_BAR(state) {
    state.sidebar = !state.sidebar
  },
  CHANGE_THEME(state, { theme }) {
    state.theme = theme
  },
  SET_THEMES(state, { themes }) {
    state.themes = themes
  },
  SET_RESET_PWD(state, status) {
    state.resetPwd = status
  },
  SET_SITE_CONFIG(state, config) {
    state.siteConfig = config
  },
}

// actions
export const actions = {
  // 改变sidebar开合
  changeBarStatus({ commit }) {

    commit('CHANGE_BAR')
  },
  // 设置主题
  changeTheme({ commit, state }, theme) {
    let res = state.themes.find(v => {
      return v.key == theme
    })
    if (!res) {
      return
    }
    commit('CHANGE_THEME', { theme })
  },
  // 初始化主题列表
  initThemes({ commit }, { themes }) {
    commit('SET_THEMES', { themes })
  },
  // 改变密码弹窗
  resetPwdStatus({ commit }, status) {
    commit('SET_RESET_PWD', status)
  },
  // 设置站点信息
  setSiteConfig({ commit }, config) {
    commit('SET_SITE_CONFIG', config)
  },
  // 设置keepalive
  pushKeepAliveList({ state }, name) {
    if (!state.keepAliveList.includes(name)) {
      state.keepAliveList.push(name)
    }
  },
  // 删除keepalive
  removeKeepAliveList({ state }, baseName) {
    state.keepAliveList = state.keepAliveList.filter(f => {
      return f.indexOf(baseName) != -1
    })
  },
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
