import Vue from 'vue';
import * as types from '../mutation-types';
import * as loading from '../loading-types';

const state = {
    projectScopes: [],
    projectScopesForSelect: [],
};

const getters = {
    projectScopes: state => state.projectScopes,
    projectScopesForSelect: state => state.projectScopesForSelect,
};

const actions = {
    /**
     * Get all project scopes.
     * @param {function} commit
     * @param {function} dispatch
     * @return {object}
     */
    async getProjectScopes({commit, dispatch}) {
        try {
            dispatch('wait/start', loading.GET_PROJECT_SCOPES, {root: true});
            let response = await Vue.http.get(
                Routing.generate('app_api_project_scopes_list'));
            let projectScopes = response.data;
            commit(types.SET_PROJECT_SCOPES, {projectScopes});

            return response;
        } finally {
            dispatch('wait/end', loading.GET_PROJECT_SCOPES, {root: true});
        }
    },
};

const mutations = {
    /**
     * Sets project scopes to state
     * @param {Object} state
     * @param {array} projectScopes
     */
    [types.SET_PROJECT_SCOPES](state, {projectScopes}) {
        state.projectScopes = projectScopes;
        let projectScopesForSelect = [];
        state.projectScopes.map((projectScope) => {
            projectScopesForSelect.push(
                {'key': projectScope.id, 'label': projectScope.name});
        });
        state.projectScopesForSelect = projectScopesForSelect;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
