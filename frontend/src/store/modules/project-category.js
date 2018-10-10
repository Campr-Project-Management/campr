import Vue from 'vue';
import * as types from '../mutation-types';
import * as loading from '../loading-types';

const state = {
    projectCategories: [],
    projectCategoriesForSelect: [],
};

const getters = {
    projectCategories: state => state.projectCategories,
    projectCategoriesForSelect: state => state.projectCategoriesForSelect,
};

const actions = {
    /**
     * Get all project categories.
     * @param {function} commit
     * @param {function} dispatch
     * @return {object}
     */
    async getProjectCategories({commit, dispatch}) {
        try {
            dispatch('wait/start', loading.GET_PROJECT_CATEGORIES, {root: true});
            let response = await Vue.http.get(Routing.generate('app_api_project_categories_list'));

            let projectCategories = response.data;
            commit(types.SET_PROJECT_CATEGORIES, {projectCategories});

            return response;
        } finally {
            dispatch('wait/end', loading.GET_PROJECT_CATEGORIES, {root: true});
        }
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
};

export default {
    state,
    getters,
    actions,
    mutations,
};
