import {createRouter, createWebHistory, RouteRecordRaw} from 'vue-router';
import Home from '../views/Home.vue';
import About from '../views/About.vue';
import Layout from '../components/Layout.vue';
import HomeIndex from '../views/home/index.vue';

const routes: Array<RouteRecordRaw> = [
    {
        path: '/',
        name: 'Layout',
        component: Layout
    },
    {
        path: '/about',
        name: 'About',
        component: About
    },
    {
        path: '/home/index',
        name: 'HomeIndex',
        component: HomeIndex
    },
];

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
});

export default router;
