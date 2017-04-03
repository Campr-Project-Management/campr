import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    items: [],
    itemsForSelect: [],
    loading: false,
};

const getters = {
    projectCategories: state => state.items,
    projectCategoriesForSelect: state => state.itemsForSelect,
    projectCategoriesLoading: state => state.loading,
};

const actions = {
    /**
     * Get all project categories.
     * @param {function} commit
     */
    getProjectCategories({commit}) {
        commit(types.SET_PROJECT_CATEGORIES_LOADING, {loading: true});
        Vue.http
            .get(Routing.generate('app_api_project_categories_list')).then((response) => {
                if (response.status === 200) {
                    let projectCategories = response.data;
                    commit(types.SET_PROJECT_CATEGORIES, {projectCategories});
                }
                commit(types.SET_PROJECT_CATEGORIES_LOADING, {loading: false});
            }, (response) => {
                commit(types.SET_PROJECT_CATEGORIES_LOADING, {loading: false});
            });
    },
};

const mutations = {
    /**
     * Sets project categories to state
     * @param {Object} state
     * @param {array} programmes
     */
    [types.SET_PROJECT_CATEGORIES](state, {projectCategories}) {
        state.items = projectCategories;
        let projectCategoriesForSelect = [];
        state.items.map((projectCategory) => {
            projectCategoriesForSelect.push({'key': projectCategory.id, 'label': projectCategory.name});
        });
        state.itemsForSelect = projectCategoriesForSelect;
    },
    /**
     * @param {Object} state
     * @param {array} loading
     */
    [types.SET_PROJECT_CATEGORIES_LOADING](state, {loading}) {
        state.loading = loading;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
