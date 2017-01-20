import Vue from 'vue';
import VueCharts from 'vue-charts';
import 'expose?$!expose?jQuery!jquery';
import 'normalise.scss';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.min.js';
import router from './router';
import store from './store';
import {sync} from 'vuex-router-sync';
import App from './App';
import VueResource from 'vue-resource';
import {Vue2Dragula} from 'vue2-dragula';

Vue.use(VueResource);
Vue.use(Vue2Dragula);

sync(store, router);
Vue.use(require('vue-moment'));
Vue.use(VueCharts);

Vue.http.options.root = 'https://potato.dev.campr.biz';

localStorage.setItem('id_token', 'c90d967c68656f7ac53affb2478' +
  '68158a8a5aed5a250b4d45ef22c4dd3402e0f00b9c9b8e1a36fe548d80e7ddaec319d788ffee1ee6ac55498cb612a1c5c66e5');

Vue.http.headers.common['Authorization'] =
  'Bearer ' + localStorage.getItem('id_token');

new Vue({
    router,
    store,
    template: '<App/>',
    components: {App},
}).$mount('#app');
