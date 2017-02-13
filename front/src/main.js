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
import Translator from 'bazinga-translator';

Vue.use(VueResource);
Vue.use(Vue2Dragula);

sync(store, router);
Vue.use(require('vue-moment'));
Vue.use(VueCharts);

Vue.http.options.root = (window.location.hostname != 'localhost') ? window.location.protocol + '//' + window.location.hostname : 'https://m.dev.campr.biz';
Vue.http.headers.common['Authorization'] =
  'Bearer ' + localStorage.getItem('id_token');

Vue.http.get('translations/messages.json')
    .then((response) => {
        Translator.fromJSON(response.data);
        window.Translator = Translator;
    })
    .then(() => {
        new Vue({
            router,
            store,
            template: '<App/>',
            components: {App},
        }).$mount('#app');
    })
;

