// state
export const state = () => ({
  isLogin: false,
  token: "",
  member: "",
  role: "",
  init: "",
})

// getters
export const getters = {
  isLogin: state => state.isLogin,
  token: state => state.token,
  member: state => state.member,
  role: state => state.role,
  init: state => state.init,

}

// mutations
export const mutations = {
  // 设置token
  SET_TOKEN(state, token) {
    state.token = token;
    state.isLogin = true;
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
  setToken({ commit }, { token }) {
    commit('SET_TOKEN', token);
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
