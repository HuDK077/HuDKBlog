/**
 * 路由配置
 */
import { interopDefault } from '@/common/utils'

const index = () => interopDefault(import('@/pages/index'));
const home = () => interopDefault(import('@/pages/home'));
const test = () => interopDefault(import('@/pages/test'));
// 登陆
const login = () => interopDefault(import('@/pages/login'));
// 开发配置
const dev_pages = () => interopDefault(import("@/pages/development/pages"))

export const routes = [
  {
    path: "/",
    name: "index",
    redirect: '/login',
    meta: { hidden: true }
  },
  {
    path: "/login",
    component: login,
    name: "login",
    meta: { hidden: true, title: "登录" }
  },
  {
    path: "/test",
    component: test,
    name: "test",
    meta: { hidden: true, title: "测试" }
  },
  {
    path: "/home",
    component: home,
    name: "home",
    meta: { hidden: true, title: "首页", mainPage: true }
  },
  {
    path: "/development",
    component: index,
    name: "dev",
    redirect: "/development/pages",
    meta: { icon: "", title: "开发设置", hidden: process.env.NODE_ENV == 'production' },
    children: [
      {
        path: "/development/pages",
        component: dev_pages,
        name: "dev.pages",
        meta: { icon: "", title: "页面配置", hidden: true, equal: "dev" }
      }
    ]
  }
];
