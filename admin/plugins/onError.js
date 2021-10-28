import Vue from "vue";

export default function ({ store, env }) {

  // window.addEventListener('error', function (e) {
  //   console.error(e);
  // })

  // window.onerror = function (errorMessage, scriptURI, lineNo, columnNo, error) {
  //   console.log('errorMessage: ' + errorMessage); // 异常信息
  //   // this.alert('errorMessage: ' + errorMessage); // 异常信息
  //   console.log('scriptURI: ' + scriptURI); // 异常文件路径
  //   // this.alert('scriptURI: ' + scriptURI); // 异常文件路径
  //   console.log('lineNo: ' + lineNo); // 异常行号
  //   // this.alert('lineNo: ' + lineNo); // 异常行号
  //   console.log('columnNo: ' + columnNo); // 异常列号
  //   // this.alert('columnNo: ' + columnNo); // 异常列号
  //   console.log('error: ' + error); // 异常堆栈信息
  //   // this.alert('error: ' + error); // 异常堆栈信息
  //   // errortracker.report('error', arguments);
  // }

  window.addEventListener("unhandledrejection", function (e) {
    if (e && e.reason && e.reason.message) {
      let error = {
        msg: e.reason.message,
        info: `on promise error`,
        url: window.location.href,
        stack: e.reason.stack,
        type: "promise"
      };
      store.dispatch('error/addErrorLog', error)
      if (env.NODE_ENV == "production") {
        Vue.prototype.$apis.errorPush({ error })
      }
      // window.err = e
      // console.error(e);
    }
  });
  Vue.config.errorHandler = function (err, vm, info) {
    console.error(err);
    if (vm) {
      let error = {
        msg: err.message,
        info: `${vm.$vnode.tag} error in ${info}`,
        url: window.location.href,
        stack: err.stack,
        type: "page"
      };
      store.dispatch('error/addErrorLog', error)
      if (env.NODE_ENV == "production") {
        Vue.prototype.$apis.errorPush({ error })
      }
    }
  }
}