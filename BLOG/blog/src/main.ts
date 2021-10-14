import {createApp} from 'vue';
import ElementPlus from 'element-plus';
import 'element-plus/dist/index.css';
import App from './App.vue';
import router from './router';
import store from './store';
import axiosPlugin from '@/plugins/axios';


const app = createApp(App);
app.use(ElementPlus)
    .use(router)
    .use(store)
    .use(axiosPlugin)
    .mount('#app');
