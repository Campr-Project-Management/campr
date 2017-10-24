import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    projectModules: [],
};

const getters = {
    projectModules: (state) => state.projectModules,
};

const actions = {
    getProjectModules({commit}) {
        return Vue
            .http
            .get(Routing.generate('app_api_project_modules'))
            .then(
                (response) => {
                    commit(types.SET_PROJECT_MODULES, {projectModules: response.body});
                },
                () => {
                    commit(types.SET_PROJECT_MODULES, {projectModules: []});
                }
            )
        ;
    },
};

const mutations = {
    [types.SET_PROJECT_MODULES](state, {projectModules}) {
        state.projectModules = projectModules;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
