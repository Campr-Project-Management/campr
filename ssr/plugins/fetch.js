import fetch from 'isomorphic-fetch';
import Vue from 'vue';

async function doFetch(url, token) {
    return fetch(url, {
        headers: {
            'Authorization': 'Bearer ' + token
        }
    });
}

const fetchPlugin = {
    install(Vue, options) {
        Vue.doFetch = doFetch;

        Vue.mixin({
            doFetch: doFetch,
        });

        Vue.prototype.doFetch = doFetch;
    }
};

Vue.use(fetchPlugin);
