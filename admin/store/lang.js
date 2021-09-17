import Vue from "vue";

// state
export const state = () => ({
  locale: "",
  locales: {
    'zh-CN': '中文',
    'en': 'EN',
    // 'es': 'ES'
  }
})

// getters
export const getters = {
  locale: state => state.locale,
  locales: state => state.locales
}

// mutations
export const mutations = {
  SET_LOCALE(state, { locale }) {
    state.locale = locale
  }
}

// actions
export const actions = {
  setLocale({ commit }, { locale }) {
    if (Vue.prototype.$loadMessages) {
      Vue.prototype.$loadMessages(locale)
    }
    commit('SET_LOCALE', { locale })
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};