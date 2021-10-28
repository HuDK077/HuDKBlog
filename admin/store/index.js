import Vue from 'vue'
import Vuex from 'vuex'
import VuexPersistence from 'vuex-persist'
import auth from "./auth"; // 用户登录信息
import error from "./error"; // 错误信息
import lang from "./lang"; // 多语言
import local from "./local"; // 本地配置
import permission from "./permission"; // 权限
import tagsView from "./tagsView"; // 历史记录
import message from "./message"; // 通知消息
import mqtt from "./mqtt"; // MQTT服务
Vue.use(Vuex)
// 模块导入
const modules = {
  auth,
  error,
  lang,
  local,
  permission,
  tagsView,
  message,
  mqtt
}
// 持久化插件
const vuexLocal = new VuexPersistence({
  storage: window.localStorage,
  reducer: (state) => {
    let filter = { local: ["resetPwd"], error: [], permission: ["routes"], auth: ["init"], message: [], mqtt: ["connected", "connecting"] };
    let keys = Object.keys(filter);
    let temp = drop(state, keys);
    keys.forEach(k => {
      if (filter[k] && filter[k].length) {
        temp[k] = drop(state[k], filter[k]);
      }
    })
    return temp
  }
})
// 排除
let drop = (obj, filter) => {
  let temp = {}
  let keys = Object.keys(obj);
  keys.forEach(v => {
    if (!filter.includes(v)) {
      temp[v] = obj[v];
    }
  })
  return temp;
}
// store 对象
const store = () => new Vuex.Store({
  modules,
  plugins: [
    vuexLocal.plugin
  ]
})

export default store