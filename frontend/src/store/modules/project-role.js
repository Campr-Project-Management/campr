import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    projectRoles: [],
};

const getters = {
    projectRoles: state => state.projectRoles,
    projectRolesForSelect: state => {
        let projectRolesSelect = [{'key': null, 'label': Translator.trans('placeholder.role')}];
        state.projectRoles.map(function(item) {
            projectRolesSelect.push({'key': item.id, 'label': item.name});
        });
        return projectRolesSelect;
    },
    projectRolesForMultiSelect: state => {
        let projectRolesSelect = [];
        state.projectRoles.map(function(item) {
            projectRolesSelect.push({'key': item.id, 'label': item.name});
        });
        return projectRolesSelect;
    },
};

const actions = {
    /**
     * Get all project role
     * @param {function} commit
     */
    getProjectRoles({commit}) {
        Vue.http
            .get(Routing.generate('app_api_project_roles_list')).then((response) => {
                if (response.status === 200) {
                    let roles = response.data;
                    commit(types.SET_PROJECT_ROLES, {roles});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets project roles to state
     * @param {Object} state
     * @param {array} roles
     */
    [types.SET_PROJECT_ROLES](state, {roles}) {
        state.projectRoles = roles;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
