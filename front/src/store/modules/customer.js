import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    items: [],
};

const getters = {
    customers: state => state.items,
    customersForFilter: function(state) {
        let customersForFilter = [{'key': '', 'label': Translator.trans('message.all_customers')}];
        state.items.map(function(customer) {
            customersForFilter.push({'key': customer.id, 'label': customer.name});
        });

        return customersForFilter;
    },
};

const actions = {
    /**
     * Get all customers.
     * @param {function} commit
     */
    getCustomers({commit}) {
        Vue.http
            .get(Routing.generate('app_api_company_list')).then((response) => {
                if (response.status === 200) {
                    let customers = response.data;
                    commit(types.SET_CUSTOMERS, {customers});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets customers to state
     * @param {Object} state
     * @param {array} customers
     */
    [types.SET_CUSTOMERS](state, {customers}) {
        state.items = customers;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
