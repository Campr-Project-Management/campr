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
    /**
     * Create project role
     * @param {function} commit
     * @param {array}    data
     */
    createProjectRole({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_project_roles_create'),
                JSON.stringify(data)
            ).then((response) => {
                if (response.body && response.body.error) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                } else {
                    let role = response.data;
                    commit(types.ADD_PROJECT_ROLE, {role});
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                }
            }, (response) => {
            });
    },
    /**
     * Edit project role
     * @param {function} commit
     * @param {array}    data
     */
    editProjectRole({commit}, data) {
        Vue.http
            .patch(
                Routing.generate('app_api_project_roles_edit', {id: data.id}),
                JSON.stringify(data)
            ).then((response) => {
                if (response.body && response.body.error) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                } else {
                    let role = response.data;
                    commit(types.EDIT_PROJECT_ROLE, {role});
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                }
            }, (response) => {
            });
    },
    /**
     * Delete a project role
     * @param {function} commit
     * @param {integer} id
     */
    deleteProjectRole({commit}, id) {
        Vue.http
            .delete(
                Routing.generate('app_api_project_role_delete', {id: id})
            ).then((response) => {
                commit(types.DELETE_PROJECT_ROLE, {id});
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
    /**
     * Add project role
     * @param {Object} state
     * @param {array} role
     */
    [types.ADD_PROJECT_ROLE](state, {role}) {
        state.projectRoles.push(role);
    },
    /**
     * Edit project role
     * @param {Object} state
     * @param {array} data
     */
    [types.EDIT_PROJECT_ROLE](state, {data}) {
        state.projectRoles.map(item => {
            if (item.id === data.id) {
                item.name = data.name;
            }
        });
    },
    /**
     * Delete project role
     * @param {Object} state
     * @param {integer} id
     */
    [types.DELETE_MEETING_DECISION](state, {id}) {
        state.projectRoles = state.projectRoles.filter((item) => {
            return item.id !== id;
        });
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
