/**
 * 路由配置
 */
import {interopDefault} from '@/utils/index.js'

const index = () => interopDefault(import('@/pages/index.vue'));
const home = () => interopDefault(import('@/pages/home.vue'));
// 登陆
const login = () => interopDefault(import('@/pages/login.vue'));
// 设置
const setting_user = () => interopDefault(import('@/pages/setting/user.vue'));
const setting_role = () => interopDefault(import('@/pages/setting/role.vue'));
const setting_option = () => interopDefault(import('@/pages/setting/option.vue'));
const setting_menu = () => interopDefault(import('@/pages/setting/menu.vue'));
const setting_permission = () => interopDefault(import('@/pages/setting/permission.vue'));
const setting_user_edit = () => interopDefault(import('@/pages/setting/userEdit.vue'));
// 会员管理
const member = () => interopDefault(import('@/pages/manage/member.vue'));
//轮播
const banner = () => interopDefault(import('@/pages/banner/list.vue'));  //轮播列表

export const routes = [
    {
        path: "/",
        name: "index",
        redirect: '/login',
        meta: {hidden: true}
    },
    {
        path: "/login",
        component: login,
        name: "login",
        meta: {hidden: true, title: "登录"}
    },
    {
        path: "/home",
        component: home,
        name: "home",
        meta: {hidden: true, title: "首页", mainPage: true}
    },
    {
        path: "/member",
        component: member,
        name: "member",
        meta: {icon: "zly_icon-test37", title: "会员管理"},
    },
    {
        path: "/setting",
        component: index,
        name: "setting",
        redirect: '/setting/user',
        meta: {icon: "zly_setting", title: "系统设置"},
        children: [
            {
                path: "/setting/user",
                component: setting_user,
                name: "setting.user",
                meta: {icon: "zly_setting", title: "用户管理"}
            },
            {
                path: "/setting/role",
                component: setting_role,
                name: "setting.role",
                meta: {icon: "zly_setting", title: "角色管理"}
            },

            {
                path: "/setting/permission",
                component: setting_permission,
                name: "setting.permission",
                meta: {icon: "zly_setting", title: "权限管理"}
            },
            {
                path: "/setting/option",
                component: setting_option,
                name: "setting.option",
                meta: {icon: "zly_setting", title: "环境设置"}
            },
            {
                path: "/setting/menu",
                component: setting_menu,
                name: "setting.menu",
                meta: {icon: "zly_setting", title: "页面管理"}
            },
            {
                path: "/setting/user/:id",
                component: setting_user_edit,
                name: "setting.user:id",
                meta: {icon: "zly_setting", title: "用户编辑", hidden: true}
            },
        ]
    },
    // 轮播
    {
        path: "/banner",
        name: "banner",
        component: banner,
        meta: {icon: "zly_setting", title: "轮播管理"},
    },
];
