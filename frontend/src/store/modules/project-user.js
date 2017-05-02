import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    items: [],
};

const getters = {
    projectUsers: state => state.items,
    projectUsersForSelect: state => state.items.map(item => {
        return {
            'key': item.user,
            'label': item.userFullName,
        };
    }),
    managersForSelect: state => state.managers.map(item => {
        return {
            'key': item.id,
            'label': item.userFullName,
        };
    }),
};

const actions = {
    /**
     * Get all project users
     * @param {function} commit
     * @param {Number} data
     */
    getProjectUsers({commit}, data) {
        Vue.http
            .get(Routing.generate('app_api_project_project_users', data)).then((response) => {
                if (response.status === 200) {
                    let projectUsers = response.data;
                    commit(types.SET_PROJECT_USERS, {projectUsers});
                    commit(types.SET_MANAGERS, {projectUsers});
                }
            }, (response) => {
            });
    },
    /**
     * Update project user
     * @param {function} commit
     * @param {array} data
     */
    updateProjectUser({commit}, data) {
        Vue.http
            .patch(
                Routing.generate('app_api_project_users_edit', {'id': data.id}),
                JSON.stringify(data)
            ).then((response) => {
            }, (response) => {
            });
    },
    saveProjectUser({commit}, userData, projectId) {
        Vue.http
            .post(
                Routing.generate('app_api_project_team_member_create', {'id': userData.projectId}),
                JSON.stringify(userData)
            ).then((response) => {
            });
    },
};

const mutations = {
    /**
     * Sets project users to state
     * @param {Object} state
     * @param {array} projectUsers
     */
    [types.SET_PROJECT_USERS](state, {projectUsers}) {
        state.items = projectUsers;
    },
    /**
     * Set project managers
     * @param {Object} state
     * @param {Object} projectUsers
     */
    [types.SET_MANAGERS](state, {projectUsers}) {
        let managers = [];
        projectUsers.map(function(projectUser) {
            if (projectUser.projectRoleNames.indexOf('ROLE_MANAGER') !== -1) {
                managers.push(projectUser);
            }
        });
        state.managers = managers;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
