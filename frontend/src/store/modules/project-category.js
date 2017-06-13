import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    projectCategories: [],
    projectCategoriesForSelect: [],
    projectCategoryLoading: false,
};

const getters = {
    projectCategories: state => state.projectCategories,
    projectCategoriesForSelect: state => state.projectCategoriesForSelect,
    projectCategoriesLoading: state => state.projectCategoryLoading,
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
        state.projectCategories = projectCategories;
        let projectCategoriesForSelect = [];
        state.projectCategories.map((projectCategory) => {
            projectCategoriesForSelect.push({'key': projectCategory.id, 'label': projectCategory.name});
        });
        state.projectCategoriesForSelect = projectCategoriesForSelect;
    },
    /**
     * @param {Object} state
     * @param {array} loading
     */
    [types.SET_PROJECT_CATEGORIES_LOADING](state, {loading}) {
        state.projectCategoryLoading = loading;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
