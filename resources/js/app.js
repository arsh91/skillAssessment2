import './bootstrap';
import {createApp} from "vue/dist/vue.esm-bundler.js";
import Router from '@/router/index.js';
import store from '@/store';


const app = createApp({});
app.use(Router);
app.use(store);
app.mount('#app');