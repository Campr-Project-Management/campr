import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    decisionCategories: [],
};

const getters = {
    decisionCategories: state => state.decisionCategories,
    decisionCategoriesForSelect: state => {
        let categoriesSelect = [];
        state.decisionCategories.map(function(item) {
            categoriesSelect.push({'key': item.id, 'label': item.name});
        });
        return categoriesSelect;
    },
};

const actions = {
    getDecisionCategories({commit}) {
        Vue.http
            .get(Routing.generate('app_api_decision_categories_list')).then((response) => {
                if (response.status === 200) {
                    let categories = response.data;
                    commit(types.SET_DECISION_CATEGORIES, {categories});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets decision categories to state
     * @param {Object} state
     * @param {array} categories
     */
    [types.SET_DECISION_CATEGORIES](state, {categories}) {
        state.decisionCategories = categories;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
