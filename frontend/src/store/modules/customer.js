import Vue from 'vue';
import * as types from '../mutation-types';
import * as loading from '../loading-types';

const state = {
    customers: [],
};

const getters = {
    customers: state => state.customers,
    customersForSelect: function(state) {
        let customersForSelect = [];
        state.customers.map(function(customer) {
            customersForSelect.push({'key': customer.id, 'label': customer.name});
        });

        return customersForSelect;
    },
    customersForFilter: function(state) {
        let customersForFilter = [{'key': '', 'label': Translator.trans('message.all_customers')}];
        state.customers.map(function(customer) {
            customersForFilter.push({'key': customer.id, 'label': customer.name});
        });

        return customersForFilter;
    },
};

const actions = {
    /**
     * Get all customers.
     * @param {function} commit
     * @param {function} dispatch
     * @return {object}
     */
    async getCustomers({commit, dispatch}) {
        dispatch('wait/start', loading.GET_CUSTOMERS, {root: true});

        try {
            let response = await Vue.http.get(Routing.generate('app_api_company_list'));
            let customers = response.data;
            commit(types.SET_CUSTOMERS, {customers});

            return response;
        } finally {
            dispatch('wait/end', loading.GET_CUSTOMERS, {root: true});
        }
    },
};

const mutations = {
    /**
     * Sets customers to state
     * @param {Object} state
     * @param {array} customers
     */
    [types.SET_CUSTOMERS](state, {customers}) {
        state.customers = customers;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
