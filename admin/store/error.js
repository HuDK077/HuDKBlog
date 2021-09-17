// state
export const state = () => ({
  logs: [],
  show: false
})

// getters
export const getters = {
  logs: state => state.logs,
  show: state => state.show,
}

// mutations
export const mutations = {
  ADD_ERROR_LOG: (state, log) => {
    state.logs.push(log)
  },
  SHOW_ERR_DIALOG: (state, status) => {
    state.show = status;
  },
  CLEAN_ERR_LOG: (state) => {
    state.logs = [];
  }
}

// actions
export const actions = {
  addErrorLog({ commit }, log) {
    commit('ADD_ERROR_LOG', log)
  },
  switchStatus({ commit }, status) {
    if (!status) {
      commit('CLEAN_ERR_LOG')
    }
    commit('SHOW_ERR_DIALOG', status)
  },
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};