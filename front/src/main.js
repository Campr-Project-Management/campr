import Vue from 'vue';
import 'expose?$!expose?jQuery!jquery';
import 'normalise.scss';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.min.js';
import router from './router';
import store from './store';
import {sync} from 'vuex-router-sync';
import App from './App';
import VueResource from 'vue-resource';

Vue.use(VueResource);

sync(store, router);
Vue.use(require('vue-moment'));

Vue.http.options.root = 'https://avengers.dev.campr.biz';

localStorage.setItem('id_token', 'ae45f7534d57f545a66cd0f66c95f7283bc4c065');

Vue.http.headers.common['Authorization'] =
  'Bearer ' + localStorage.getItem('id_token');

new Vue({
    router,
    store,
    template: '<App/>',
    components: {App},
}).$mount('#app');
