/*require('./bootstrap');*/


import Vue from 'vue';

window.axios = require('axios');

import i18n from 'vue-i18n';

Vue.use(i18n);

window.NProgress = require('nprogress');
import App from './App';

// router setup
Vue.prototype.$baseURL = 'http://127.0.0.1:8000/api/v1/';
import routers from './routes';

/**
 * Import third party plugins
 */
import '~/global/rules';
import '~/global/notificaitons';
import '~/global/elements';
import '~/global/loading';

import localStorage from '~/services/localStorage';

Vue.prototype.$localStorage = localStorage;

// Add a request interceptor
axios.interceptors.request.use(
    config => {
        const token = localStorage.get('token');
        if (token) {
            config.headers['Authorization'] = 'Bearer ' + token;
        }
        // config.headers['Content-Type'] = 'application/json';
        return config;
    },
    error => {
        Promise.reject(error)
    });


// import vuex
import store from './store'

// initialize event bus
Vue.prototype.$eventBus = new Vue();

Vue.config.productionTip = false;

/* eslint-disable no-new */
const app = new Vue({
    el: '#wrapper',
    render: h => h(App),
    router: routers,
    store
});
