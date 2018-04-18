import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    organizationTree: {},
};

const getters = {
    organizationTree: (state) => state.organizationTree,
};

const actions = {
    getOrganizationTree({commit}, id) {
        return Vue
            .http
            .get(Routing.generate('app_api_project_organization_tree', {id}))
            .then(
                (response) => {
                    commit(types.SET_PROJECT_ORGANIZATION_TREE, response.body);
                },
                (response) => {
                    commit(types.SET_PROJECT_ORGANIZATION_TREE, {});
                }
            )
        ;
    },
    clearOrganizationTree({commit}) {
        commit(types.SET_PROJECT_ORGANIZATION_TREE, {});
    },
};

const mutations = {
    [types.SET_PROJECT_ORGANIZATION_TREE](state, organizationTree) {
        state.organizationTree = organizationTree;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
