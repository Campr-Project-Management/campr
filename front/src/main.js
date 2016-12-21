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

new Vue({
    router,
    store,
    template: '<App/>',
    components: {App},
}).$mount('#app');
