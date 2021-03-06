import { urls } from "@/common/app.config";
import Vue from "vue"

export default function ({ $axios }) {
  let apis = {}

  let bindUrl = (key, value, list) => {
    list[key] = (params, config) => {
      if (!config) { config = {}; }
      if (!config.method) { config.method = "POST"; }
      let axios = { url: value, ...config };
      if (config.method.toLocaleUpperCase() == "GET") {
        axios.params = params;
      } else {
        axios.data = params;
      }
      return $axios(axios);
    }
  }

  Object.keys(urls).forEach(v => {
    bindUrl(v, urls[v], apis);
  })
  Vue.prototype.$url = urls;
  Vue.prototype.$apis = apis;
}
