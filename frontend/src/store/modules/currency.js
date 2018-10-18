import Vue from 'vue';
import * as types from '../mutation-types';
import * as loading from '../loading-types';

const state = {
    currencies: [],
};

const getters = {
    currencies: state => state.currencies,
    currenciesForSelect: (state, getters) => {
        return getters.currencies.map(currency => {
            return {key: currency.id, label: currency.code};
        });
    },
    currencyById: (state, getters) => (id) => {
        return getters.currencies.find(currency => currency.id === id);
    },
};

const actions = {
    /**
     * Currencies
     *
     * @param {function} commit
     * @param {function} dispatch
     * @return {object}
     */
    async getCurrencies({commit, dispatch}) {
        try {
            dispatch('wait/start', loading.GET_CURRENCIES, {root: true});
            let response = await Vue.http.get(Routing.generate('app_api_currencies'));
            commit(types.SET_CURRENCIES, response.data);

            return response;
        } finally {
            dispatch('wait/end', loading.GET_CURRENCIES, {root: true});
        }
    },
};

const mutations = {
    /**
     * Set currencies
     * @param {Object} state
     * @param {array} currencies
     */
    [types.SET_CURRENCIES](state, currencies) {
        state.currencies = currencies;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
