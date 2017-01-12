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

Vue.http.options.root = 'https://dev.team.campr.biz';

localStorage.setItem('id_token', 'eyJhbGciOiJSUzI1NiIsInR' +
  '5cCI6IkpXUyJ9.eyJleH' +
  'AiOjE0ODI0ODYxMjUsImVtYWlsIjoiaW9udXQubW9sZ' +
  'G92YW5AdHJpc29mdC5ybyIsImlhdCI6IjE0ODIzOTk3MjUifQ.jh08' +
  'BHzxLHfm4-w_dX-g66v' +
  '2zFpC1cGPGyVvUnLHHt-kRyersL1kOxo5u7VQTbHK9b' +
  'qeNCDR6BZMU3WltUmiPOT9W61SuFh7BM2BGClJKODXxd_nHb46gTK' +
  'lnX3R4vdYl3C_pzJereL3' +
  'DVd4w4XwPAFp7p_Ahqo_Aj4V1C-ofwleTj-35rxp6-' +
  'n-0kRO3jMKRktjImqwOjyY8yo3v9MNz2nzX5W9r53qaYgaejEz9' +
  'q-wsDgq2kPsmX5SZWiqtssvEk' +
  'NmzMYwzUe-x59dHS61HU5hThzEV6USSbl2_zmbGr' +
  'MPQVb6jWq5PqN--rXLTsQtmdNDsQFB81ZZX0OvZ6uGryl4Zxs' +
  'kRDijv_UZWxTSq6J1VktlWKYjoKFJ' +
  'wSJyRjy_nKkkMu7Aqt6bfifiKs1XTtqm9luX9q' +
  'RfycF8M19qF1MZB-GIHOwE3SevI2kkQeYLyHzFHPQZZH9eJw-H' +
  'wvEVmQTBVgiDBWLLPiqYr_YgGmJN' +
  'y9llMz8_qg09_0p2xW1QJ3O2urQp4e0kYEiXX' +
  'mcWyW1Hj63uiQT5G8SPAY-EHVyW4Jjwf3wdPqe-sK5U-UFYFs2' +
  '0F9sGnBuvdF2WRXW74S_SWeCQ8J' +
  'IPtXBzObcO83AUArKb_uYd3iuUBp8Ska96JckO' +
  'eaScrrXSIryKnYxaBCfNAsrMiT2Z_aJhzf_nR_w');

Vue.http.headers.common['Authorization'] =
  'Bearer ' + localStorage.getItem('id_token');

new Vue({
    router,
    store,
    template: '<App/>',
    components: {App},
}).$mount('#app');
