import Vue from 'vue';
import * as types from '../mutation-types';
import _ from 'lodash';
// import router from '../../router';

const ROLE_MANAGER = 'roles.project_manager';

const state = {
    projectUsers: [],
    sponsors: [],
    managers: [],
    currentMember: {},
};

const getters = {
    projectUsers: state => state.projectUsers,
    rasciProjectUsers: (state, getters) => {
        if (!getters.projectUsers || !getters.projectUsers.items) {
            return [];
        }

        return getters.projectUsers.items.filter(
            (projectUser) => projectUser.showInRasci);
    },
    projectUserByUserId: (state, getters) => (id) => {
        return _.find(getters.projectUsers.items, (user) => {
            return user.user === id;
        });
    },
    projectUserAvatarByUserId: (state, getters) => (id) => {
        let projectUser = getters.projectUserByUserId(id);
        if (!projectUser) {
            return null;
        }

        return projectUser.userAvatarUrl;
    },
    currentMember: state => state.currentMember,
    projectSponsors: state => state.sponsors,
    projectManagers: state => state.managers,
    projectUsersForSelect: state => {
        if (!state.projectUsers || !state.projectUsers.items) {
            return [];
        }

        return state.projectUsers.items.map(item => {
            return {
                'key': item.user,
                'label': item.userFullName,
                'hidden': item.userDeleted,
            };
        });
    },
    rasciProjectUsersForSelect: (state, getters) => {
        if (!getters.projectUsers || !getters.projectUsers.items) {
            return [];
        }

        return getters.projectUsers.items.map(item => {
            return {
                'key': item.user,
                'label': item.userFullName,
                'hidden': item.userDeleted || !item.showInRasci,
            };
        });
    },
    projectUsersForSelectOnViewTask: state => {
        let usersSelect = [];
        if (state.projectUsers && state.projectUsers.items) {
            usersSelect = state.projectUsers.items.map(item => {
                return {
                    'key': item.user,
                    'label': item.userFullName,
                    'avatar': item.userAvatarUrl,
                    'email': item.userEmail,
                };
            });
        }
        return usersSelect;
    },
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
        Vue.http.get(Routing.generate('app_api_project_project_users', data)).
            then((response) => {
                if (response.status === 200) {
                    let projectUsers = response.data;
                    commit(types.SET_PROJECT_USERS, {projectUsers});
                    commit(types.SET_MANAGERS, {projectUsers});
                }
            }, (response) => {
            });
    },
    /**
     * Gets the project user by id
     * @param {function} commit
     * @param {number} id
     */
    getProjectUser({commit}, id) {
        Vue.http.get(Routing.generate('app_api_project_users_get', {id: id})).
            then((response) => {
                let currentMember = response.data;
                commit(types.SET_CURRENT_MEMBER, {currentMember});
            }, (response) => {
            });
    },
    /**
     * Update project user
     * @param {function} commit
     * @param {array} data
     */
    updateProjectUser({commit}, data) {
        Vue.http.patch(
            Routing.generate('app_api_project_users_edit', {'id': data.id}),
            JSON.stringify(data),
        ).then((response) => {
            let currentMember = response.data;
            commit(types.SET_CURRENT_MEMBER, {currentMember});
        }, (response) => {
        });
    },
    saveProjectUser({commit}, userData) {
        const arrayItems = [
            'distributionLists',
            'roles',
            'departments',
            'subteams',
        ];

        const keys = Object.keys(userData);
        const data = new FormData();
        keys.map((key) => {
            if (arrayItems.indexOf(key) === -1) {
                data.append(key, userData[key]);
            } else {
                if (_.isArray(userData[key])) {
                    userData[key].forEach((item) => {
                        data.append(key + '[]', +item);
                    });
                }
            }
        });
        return Vue.http.post(
            Routing.generate('app_api_project_team_member_create',
                {'id': userData.project}),
            data,
        ).then(
            (response) => {
                if (response.body && response.body.error) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                }
                return response.body;
            },
            (response) => {
                return response.body;
            },
        );
    },
    /**
     * Get project sponsors
     * @param {function} commit
     * @param {array} data
     */
    async getProjectSponsors({commit}, data) {
        let response = await Vue.http.get(
            Routing.generate('app_api_project_sponsor_users', data));
        let projectUsers = response.data;
        commit(types.SET_SPONSORS, {projectUsers});
    },
    /**
     * Update team member
     * @param {function} commit
     * @param {array} data
     *
     * @return {object}
     */
    updateTeamMember({commit}, data) {
        return Vue.http.patch(
            Routing.generate('app_api_project_team_member_update',
                {'id': data.id}),
            JSON.stringify(data),
        ).then((response) => {
            return response.body;
        }, (response) => {
        });
    },
    /**
     * Edit a project sponspor
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    editProjectSponsor({commit}, data) {
        return Vue.http.patch(
            Routing.generate('app_api_project_users_update_sponsor',
                data),
            JSON.stringify(data),
        ).then((response) => {
            if (response.status === 200) {
                let projectUsers = response.data;
                commit(types.SET_SPONSORS, {projectUsers});
            }
        }, (response) => {
        });
    },
    /**
     * Delete a new objective on project
     * @param {function} commit
     * @param {integer} id
     * @return {object}
     */
    deleteTeamMember({commit}, id) {
        return Vue.http.delete(
            Routing.generate('app_api_project_users_delete', {id: id}),
        ).then(
            (response) => {
                commit(types.DELETE_TEAM_MEMBER, {id});

                return response;
            },
            (response) => {
                return response;
            },
        )
            ;
    },

    createProjectUser({commit}, {projectId, userId}) {
        return Vue.http.post(
            Routing.generate('app_api_project_project_user_create',
                {id: projectId}),
            {user: userId},
        )
            ;
    },
    deleteProjectUser({commit}, {projectId, userId}) {
        const data = {
            id: projectId,
            user: userId,
        };

        return Vue.http.delete(
            Routing.generate('app_api_project_users_delete_user', data))
            ;
    },
    /**
     * Delete a sponsor
     * @param {function} commit
     * @param {integer} id
     * @param {integer} projectUser
     */
    deleteSponsor({commit}, {id, projectUser}) {
        const data = {
            id: id,
            projectUser: projectUser,
        };

        Vue.http.delete(
            Routing.generate('app_api_project_users_delete_sponsor', data),
        ).then((response) => {
            if (response.status === 200) {
                let projectUsers = response.data;
                commit(types.SET_SPONSORS, {projectUsers});
            }
        }, (response) => {
        });
    },
    /**
     * Create a project sponsor
     * @param {function} commit
     * @param {integer} id
     * @param {integer} projectUser
     */
    createSponsor({commit}, {id, projectUser}) {
        const data = {
            id: id,
            projectUser: projectUser,
        };

        Vue.http.put(
            Routing.generate('app_api_project_users_create_sponsor', data),
        ).then((response) => {
            let projectUsers = response.data;
            commit(types.SET_SPONSORS, {projectUsers});
        }, (response) => {
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
        state.projectUsers = projectUsers;
    },
    /**
     * Set project managers
     * @param {Object} state
     * @param {Object} projectUsers
     */
    [types.SET_MANAGERS](state, {projectUsers}) {
        let managers = [];
        if (!_.isArray(projectUsers.items)) {
            projectUsers.items = [];
        }
        projectUsers.items.map(function(projectUser) {
            if (projectUser.projectRoleNames.indexOf(ROLE_MANAGER) !== -1) {
                managers.push(projectUser);
            }
        });
        state.managers = managers;
    },
    /**
     * Set project sponsors
     * @param {Object} state
     * @param {Object} projectUsers
     */
    [types.SET_SPONSORS](state, {projectUsers}) {
        if (!projectUsers || !projectUsers.items) {
            return;
        }

        state.sponsors = projectUsers.items;
    },
    /**
     * Sets the current member on member page to state
     * @param {Object} state
     * @param {Object} currentMember
     */
    [types.SET_CURRENT_MEMBER](state, {currentMember}) {
        state.currentMember = currentMember;
    },
    /**
     * Sets project to null on a project user
     * @param {Object} state
     * @param {integer} id
     */
    [types.DELETE_TEAM_MEMBER](state, {id}) {
        state.projectUsers.items = state.projectUsers.items.filter((item) => {
            return item.id !== id ? true : false;
        });
        state.projectUsers.totalItems--;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
