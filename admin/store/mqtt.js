// state
export const state = () => ({
  connected: false,
  connecting: false,
  timestamp: "",
  helloMsg: "",
  receive: {}
})

// getters
export const getters = {
  connected: state => state.connected,
  connecting: state => state.connecting,
  timestamp: state => state.timestamp,
  helloMsg: state => state.helloMsg,
  receive: state => state.receive,

}

// mutations
export const mutations = {
  // 连接
  CONNECTED(state) {
    state.connected = true;
  },
  // 断开连接
  DISCONNECTED(state) {
    state.connected = false;
  },
  // 连接中/断开连接中
  CONNECTING(state, status) {
    state.connecting = status;
  },
  TIMESTAMP(state) {
    state.timestamp = Date.now();
  },
  CLEAN_TIMESTAMP(state) {
    state.timestamp = "";
  },
  SET_HELLO_MSG(state, data) {
    state.helloMsg = data;
  },
  SET_RECEIVE(state, data) {
    state.receive = data;
  },
}

// actions
export const actions = {
  // 设置连接状态
  setConnect({ commit }, status) {
    commit("TIMESTAMP")

    switch (status) {
      case "connect":
        commit("CONNECTING", true)
        break;
      case "connected":
        commit("CONNECTING", false)
        commit("CONNECTED")
        break;
      case "disconnect":
        commit("CONNECTING", true)
        commit("CLEAN_TIMESTAMP")
        break;
      case "disconnected":
        commit("CONNECTING", false)
        commit("DISCONNECTED")
        commit("CLEAN_TIMESTAMP")
        break;
      default:
        break;
    }
  },
  refreshTime({ commit }) {
    commit("TIMESTAMP")
  },
  setHelloMsg({ commit }, data) {
    commit("SET_HELLO_MSG", data)
  },
  setReceive({ commit }, data) {
    commit("SET_RECEIVE", data)
  },

}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};