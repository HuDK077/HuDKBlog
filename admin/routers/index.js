import Vue from 'vue'
import Router from 'vue-router'
import { scrollBehavior } from '@/common/utils'
import { routes } from "./routes";

const emptyFn = () => { }
const originalPush = Router.prototype.push
Router.prototype.push = function push(location, onComplete = emptyFn, onAbort) {
  return originalPush.call(this, location, onComplete, onAbort)
}
Vue.use(Router);

export const routerOptions = {
  mode: 'history',
  base: decodeURI('/'),
  linkActiveClass: 'nuxt-link-active',
  linkExactActiveClass: 'nuxt-link-exact-active',
  scrollBehavior,
  routes,
  fallback: false
}

export const router = new Router(routerOptions);
export function createRouter() {
  return router
}
