import './bootstrap';
import Vue from 'vue';
import store from './store';
import router from './router/router';
import App from './Master';
import './permission'; // permission control
import * as filters from './filters'; // global filters
import './utils/loader';
import { i18n } from "./utils/modules/i18n";
import 'element-ui/lib/theme-chalk/index.css';
import "./directive/permission/index";

/**
 * Load all components
 */
const files = require.context('./components/', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

/**
 * Register global utility filters
 */
Object.keys(filters).forEach(key => {
    Vue.filter(key, filters[key])
});

export default new Vue({
    el: '#app',
    router,
    store,
    i18n,
    render: h => h(App),
});