import Vue from "vue";
const proto = Vue.prototype
// state
export const state = () => ({
  isLogin: false,
  token: "",
  timestamp: "",
  member: "",
  roles: [],
  init: "",
})

// getters
export const getters = {
  isLogin: state => state.isLogin,
  token: state => state.token,
  timestamp: state => state.timestamp,
  member: state => state.member,
  roles: state => state.roles,
  roleIds: state => state.roles.map(m => m.id),
  init: state => state.init,
}

// mutations
export const mutations = {
  // 设置token
  SET_TOKEN(state, token) {
    state.token = token;
    state.isLogin = true;
  },
  // 设置时间戳
  SET_TIMESTAMP(state, timestamp) {
    state.timestamp = timestamp;
  },
  // 更新用户信息
  UPDATE_USER(state, { member, roles }) {
    if (member) {
      state.member = member;
    }
    if (roles) {
      state.roles = roles;
    }
  },
  // 登出
  LOGOUT(state) {
    state.isLogin = false;
    state.token = "";
    state.roles = [];
    state.member = "";
    state.init = "";
  },
  SET_INIT(state, init) {
    state.init = init
  },
}

// actions
export const actions = {
  // 设置token
  setToken({ commit, getters }, { token, timestamp }) {
    if (!timestamp) {
      commit('SET_TOKEN', token);
    } else {
      if (getters.timestamp < timestamp || !getters.timestamp) {
        commit('SET_TOKEN', token);
        commit('SET_TIMESTAMP', timestamp);
      }
    }
  },
  // 更新用户
  getAuthUser({ commit, dispatch }, cb) {
    proto.$apis.getAuthUser({ option: ["member", "roles"] })
      .then(res => {
        console.log(res.data)
        let { error_code, data } = res.data;
        if (error_code == 2001) {
          let { member, roles } = data
          commit("UPDATE_USER", { member, roles })
          dispatch("initStatus", "success");
          if (cb) { cb(true, res); }
        } else {
          if (cb) { cb(false, res); }
        }
      }).catch((res) => {
        if (cb) { cb(false, res); }
      })
  },
  // 登出
  logout({ commit }) {
    commit('LOGOUT');
  },
  // 初始化状态
  initStatus({ commit }, init) {
    if (init == "success") {
      setTimeout(() => {
        commit('SET_INIT', init)
      }, 500);
    } else {
      commit('SET_INIT', init)
    }
  },
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};