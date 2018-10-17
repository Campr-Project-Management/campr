import Vue from 'vue';
import store from './store';
import router from './router';
import httpConfig from './config/http';
import {sync} from 'vuex-router-sync';
import VueCharts from 'vue-charts';
import 'expose?$!expose?jQuery!jquery';
import './css/bootstrap.less';
import './css/main.scss';
// import 'normalise.scss';
// import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.min.js';
// import 'font-awesome/css/font-awesome.min.css';
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
import Templating from './plugins/templating';
import Rbac from './plugins/rbac';
import Scrollbar from './components/_common/Scrollbar';
import VueWait from 'vue-wait';
import Routing from './plugins/routing';

Vue.use(VueResource);
Vue.use(Vue2Dragula);
Vue.use(VeeValidate);
Vue.use(VTooltip);
Vue.use(Translator, {
    store,
});
Vue.use(HumanizeDuration);
Vue.use(Numeral);
Vue.use(DateFormat);
Vue.use(Templating);
Vue.use(Routing);
Vue.component(Scrollbar.name, Scrollbar);

sync(store, router);
Vue.use(require('vue-moment'));
Vue.use(VueCharts);
Vue.use(VueCookie);
Vue.use(
    Rbac,
    {
        user(store) {
            return store.getters.localUser;
        },
    }
);

Vue.http.headers.common = Object.assign(Vue.http.headers.common, httpConfig.headers);

new Vue({
    router,
    store,
    wait: new VueWait({
        useVuex: true,
    }),
    template: '<App/>',
    components: {App},
}).$mount('#app');
