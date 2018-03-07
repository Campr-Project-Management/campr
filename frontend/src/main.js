import Vue from 'vue';
import store from './store';
import router from './router';
import {sync} from 'vuex-router-sync';
import VueCharts from 'vue-charts';
import 'expose?$!expose?jQuery!jquery';
import 'normalise.scss';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.min.js';
import 'font-awesome/css/font-awesome.min.css';
import App from './App';
import VueResource from 'vue-resource';
import {Vue2Dragula} from 'vue2-dragula';
import VueCookie from 'vue-cookie';
import VeeValidate from 'vee-validate';
import Translator from './util/Translator';
import VTooltip from 'v-tooltip';
import HumanizeDuration from './plugins/humanize-duration';
import Numeral from './plugins/numeral';
import DateFormat from './plugins/date-format';
import {install} from 'vueditor';
import './css/vueditor.css';
import VueditorConfig from 'components/_common/vueditorConfig';

// jquery - probabil should be optimized somehow
import $ from 'jquery';
import jqns from './plugins/jquery.nicescroll';
jqns($);

Vue.use(VueResource);
Vue.use(Vue2Dragula);
Vue.use(VeeValidate);
Vue.use(VTooltip);
Vue.use(Translator);
Vue.use(HumanizeDuration);
Vue.use(Numeral);
Vue.use(DateFormat);

sync(store, router);
Vue.use(require('vue-moment'));
Vue.use(VueCharts);
Vue.use(VueCookie);

Vue.http.headers.common['Authorization'] = 'Bearer ' + window.user.api_token;
// This is required for Symfony to actually be able to respond properly to Request::isXmlHttpRequest()
Vue.http.headers.common['X-Requested-With'] = 'XMLHttpRequest';

install(Vue, VueditorConfig);

new Vue({
    router,
    store,
    template: '<App/>',
    components: {App},
}).$mount('#app');
