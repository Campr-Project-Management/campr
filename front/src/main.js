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
import Vueditor from 'vueditor';
import 'vueditor/dist/css/vueditor.min.css';
import VueCookie from 'vue-cookie';

Vue.use(VueResource);
Vue.use(Vue2Dragula);
let config = {
    // buttons on the toolbar, you can use '|' or 'divider' as the separator
    toolbar: [
        'bold', 'italic', 'underline',
        'insertOrderedList', 'insertUnorderedList',
    ],

    // the font-family select's options, 'val' refer to the actual css value, 'abbr' refer to the option's text
    // 'abbr' is optional when equals to 'val';
    fontName: [
        {val: '', abbr: ''},
        {val: 'arial black'}, {val: 'times new roman'}, {val: 'Courier New'},
    ],

    // the font-size select's options
    fontSize: ['12px', '14px', '16px', '18px', '0.8rem', '1.0rem', '1.2rem', '1.5rem', '2.0rem'],

    // the emoji list, you can get full list here: http://unicode.org/emoji/charts/full-emoji-list.html
    emoji: ['1f600', '1f601', '1f602', '1f923', '1f603'],

    // default is Chinese, to set to English use lang: 'en'
    lang: 'en',

    // mode options: default | iframe
    mode: 'default',

    // if mode is set to 'iframe', specify a HTML file path here
    iframePath: '',

    // your file upload url, the return result must be a string refer to the uploaded image, leave it empty will end up with local preview
    fileuploadUrl: '',
};
Vue.use(Vueditor, config);

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

