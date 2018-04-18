import Vue from 'vue';
import * as types from '../mutation-types';

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
     * @return {object}
     */
    getCurrencies({commit}) {
        return Vue.http.get(Routing.generate('app_api_currencies')).then(
            (response) => {
                if (response.status === 200) {
                    commit(types.SET_CURRENCIES, response.data);
                }

                return response;
            },
            (response) => response,
        );
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
