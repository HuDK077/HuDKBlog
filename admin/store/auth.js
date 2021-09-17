// state
export const state = () => ({
  isLogin: false,
  token: "",
  timestamp: "",
  member: "",
  role: "",
  init: "",
})

// getters
export const getters = {
  isLogin: state => state.isLogin,
  token: state => state.token,
  timestamp: state => state.timestamp,
  member: state => state.member,
  role: state => state.role,
  init: state => state.init,
  isSupplier: state => {
    if (state.role && state.role.slug == "supplier") {
      return true
    } else {
      return false
    }
  }
}

// mutations
export const mutations = {
  // 设置token
  SET_TOKEN(state, token) {
    // console.error("SET_TOKEN", token);
    state.token = token;
    state.isLogin = true;
  },
  // 设置时间戳
  SET_TIMESTAMP(state, timestamp) {
    state.timestamp = timestamp;
  },
  // 更新用户信息
  UPDATE_USER(state, { member, role }) {
    if (member) {
      state.member = member;
    }
    if (role) {
      state.role = role;
    }
  },
  // 登出
  LOGOUT(state) {
    state.isLogin = false;
    state.token = "";
    state.role = "";
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
    // commit('SET_TOKEN', token);
    if (!timestamp) {
      commit('SET_TOKEN', token);
    } else {
      if (getters.timestamp < timestamp || !getters.timestamp) {
        commit('SET_TOKEN', token);
        commit('SET_TIMESTAMP', timestamp);
      }
    }
  },
  // 更新用户信息
  updateMember({ commit }, { member, role }) {
    commit('UPDATE_USER', { member, role });
  },
  // 登出
  logout({ commit }) {
    commit('LOGOUT');
  },
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