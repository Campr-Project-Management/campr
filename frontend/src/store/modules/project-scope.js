import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    projectScopes: [],
    projectScopesForSelect: [],
    loading: false,
};

const getters = {
    projectScopes: state => state.projectScopes,
    projectScopesForSelect: state => state.projectScopesForSelect,
    projectScopesLoading: state => state.loading,
};

const actions = {
    /**
     * Get all project scopes.
     * @param {function} commit
     */
    getProjectScopes({commit}) {
        commit(types.SET_PROJECT_SCOPES_LOADING, {loading: true});
        Vue.http
            .get(Routing.generate('app_api_project_scopes_list')).then((response) => {
                if (response.status === 200) {
                    let projectScopes = response.data;
                    commit(types.SET_PROJECT_SCOPES, {projectScopes});
                }
                commit(types.SET_PROJECT_SCOPES_LOADING, {loading: false});
            }, (response) => {
                commit(types.SET_PROJECT_SCOPES_LOADING, {loading: false});
            });
    },
};

const mutations = {
    /**
     * Sets project scopes to state
     * @param {Object} state
     * @param {array} programmes
     */
    [types.SET_PROJECT_SCOPES](state, {projectScopes}) {
        state.projectScopes = projectScopes;
        let projectScopesForSelect = [];
        state.projectScopes.map((projectScope) => {
            projectScopesForSelect.push({'key': projectScope.id, 'label': projectScope.name});
        });
        state.projectScopesForSelect = projectScopesForSelect;
    },
    /**
     * @param {Object} state
     * @param {array} loading
     */
    [types.SET_PROJECT_SCOPES_LOADING](state, {loading}) {
        state.loading = loading;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
