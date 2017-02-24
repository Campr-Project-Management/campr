import Vue from 'vue';
import VueCharts from 'vue-charts';
import 'expose?$!expose?jQuery!jquery';
import 'normalise.scss';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.min.js';
import 'font-awesome/css/font-awesome.min.css';
import router from './router';
import store from './store';
import {sync} from 'vuex-router-sync';
import App from './App';
import VueResource from 'vue-resource';
import {Vue2Dragula} from 'vue2-dragula';
import Translator from 'bazinga-translator';
import VueCookie from 'vue-cookie';

Vue.use(VueResource);
Vue.use(Vue2Dragula);

sync(store, router);
Vue.use(require('vue-moment'));
Vue.use(VueCharts);
Vue.use(VueCookie);

// For dev mode hardcode your test team URL in the bellow statement.
Vue.http.options.root = (window.location.hostname != 'localhost')
    ? window.location.protocol + '//' + window.location.hostname
    : 'https://avengers.dev.campr.biz';

Vue.http.get('translations/messages.json')
    .then((response) => {
        Translator.fromJSON(response.data);
        window.Translator = Translator;
    })
    .then(() => {
        // For dev mode hardcode your test user token.
        let token = Vue.cookie.get('user_token') ? Vue.cookie.get('user_token') : 'ae45f7534d57f545a66cd0f66c95f7283bc4c065';
        Vue.http.headers.common['Authorization'] = 'Bearer ' + token;
        new Vue({
            router,
            store,
            template: '<App/>',
            components: {App},
        }).$mount('#app');
    })
;

