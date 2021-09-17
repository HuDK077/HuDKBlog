// state
export const state = () => ({
  messages: [],
  count: {},
  show: false,
  productId: "",
  artworkId: "",
  agencyStatus: false,
  acquisitionStatus: false,
})

// getters
export const getters = {
  messages: state => state.messages,
  count: state => state.count,
  show: state => state.show,
  productId: state => state.productId,
  artworkId: state => state.artworkId,
  agencyStatus: state => state.agencyStatus,
  acquisitionStatus: state => state.acquisitionStatus,
}

// mutations
export const mutations = {
  SET_MESSAGE: (state, message) => {
    state.messages = message
  },
  SET_MESSAGE_COUNT: (state, num) => {
    state.count = num
  },
  SHOW_MSG_DIALOG: (state, status) => {
    state.show = status;
  },
  SET_PRODUCT_ID: (state, id) => {
    state.productId = id;
  },
  SET_ARTWORK_ID: (state, id) => {
    state.artworkId = id;
  },
  SET_AGENCY_STATUS: (state, status) => {
    state.agencyStatus = status;
  },
  SET_ACQUISITION_STATUS: (state, status) => {
    state.acquisitionStatus = status;
  },
}

// actions
export const actions = {
  setMessage({ commit }, message) {
    commit("SET_MESSAGE", message)
  },
  setCount({ commit }, num) {
    commit("SET_MESSAGE_COUNT", num)
  },
  switchStatus({ commit }, status) {
    commit('SHOW_MSG_DIALOG', status)
  },
  setArtworkId({ commit }, id) {
    commit('SET_ARTWORK_ID', id)
  },
  setProductId({ commit }, id) {
    commit('SET_PRODUCT_ID', id)
  },
  setAgencyStatus({ commit }, status) {
    commit('SET_AGENCY_STATUS', status)
  },
  setAcquisitionStatus({ commit }, status) {
    commit('SET_ACQUISITION_STATUS', status)
  },
  resetMessage({ commit }) {
    commit("SET_MESSAGE", [])
    commit("SET_MESSAGE_COUNT", {})
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};