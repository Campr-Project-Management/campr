import Vue from 'vue';
import * as types from '../mutation-types';
import _ from 'lodash';

const ROLE_MANAGER = 'roles.project_manager';

const state = {
    projectUsers: {
        items: [],
    },
    sponsors: [],
    managers: [],
};

const getters = {
    projectUsers: state => state.projectUsers,
    rasciProjectUsers: (state, getters) => {
        if (!getters.projectUsers || !getters.projectUsers.items) {
            return [];
        }

        return getters.projectUsers.items.filter(
            (projectUser) => projectUser.isRASCI);
    },
    projectUserByUserId: (state, getters) => (id) => {
        return getters.projectUsers.items.find(pu => {
            return pu.user === Number(id);
        });
    },
    projectUserById: (state, getters) => (id) => {
        return getters.projectUsers.items.find(pu => {
            return pu.id === Number(id);
        });
    },
    projectUserAvatarByUserId: (state, getters) => (id) => {
        let projectUser = getters.projectUserByUserId(id);
        if (!projectUser) {
            return null;
        }

        return projectUser.userAvatarUrl;
    },
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
                'hidden': item.userDeleted || !item.isRASCI,
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
     * @return {object}
     */
    async getProjectUser({commit}, id) {
        let res = await Vue.http.get(
            Routing.generate('app_api_project_users_get', {id: id}));

        commit(types.ADD_PROJECT_USER, res.data);

        return res;
    },
    /**
     * Update project user
     * @param {function} commit
     * @param {object} data
     * @return {object}
     */
    async updateProjectUser({commit}, data) {
        let res = await Vue.http.patch(
            Routing.generate('app_api_project_users_edit', {'id': data.id}),
            JSON.stringify(data),
        );
        if (res.data.error === true) {
            throw res;
        }

        commit(types.ADD_PROJECT_USER, res.data);

        return res;
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
    async createProjectUser({commit}, {projectId, userId}) {
        let res = await Vue.http.post(
            Routing.generate('app_api_project_project_user_create',
                {id: projectId}),
            {user: userId},
        );

        let projectUser = res.data;
        commit(types.ADD_PROJECT_USER, projectUser);
        commit(types.ADD_MANAGER, projectUser);

        return null;
    },
    async deleteProjectUser({commit}, {projectId, userId}) {
        const data = {
            id: projectId,
            user: userId,
        };

        let res = await Vue.http.delete(
            Routing.generate('app_api_project_users_delete_user', data));

        const projectUser = res.data;
        commit(types.DELETE_PROJECT_USER, projectUser);
        commit(types.DELETE_MANAGER, projectUser);

        return res;
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
        state.projectUsers = Object.assign({}, state.projectUsers,
            projectUsers);
    },
    /**
     * Add project user
     * @param {Object} state
     * @param {Object} projectUser
     */
    [types.ADD_PROJECT_USER](state, projectUser) {
        const index = state.projectUsers.items.findIndex(
            pu => pu.user === projectUser.user);
        if (index >= 0) {
            Vue.set(state.projectUsers.items, index, projectUser);
            return;
        }

        state.projectUsers.items.push(projectUser);
    },
    /**
     * Add manager
     * @param {Object} state
     * @param {Object} projectUser
     */
    [types.ADD_MANAGER](state, projectUser) {
        if (!projectUser.projectRoleNames.indexOf(ROLE_MANAGER) !== -1) {
            return;
        }

        const index = state.managers.findIndex(
            pu => pu.user === projectUser.user);
        if (index >= 0) {
            Vue.set(state.managers, index, projectUser);
            return;
        }

        state.managers.push(projectUser);
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
     * Sets project to null on a project user
     * @param {Object} state
     * @param {Integer} id
     */
    [types.DELETE_TEAM_MEMBER](state, {id}) {
        state.projectUsers.items = state.projectUsers.items.filter((item) => {
            return item.id !== id ? true : false;
        });
        state.projectUsers.totalItems--;
    },
    /**
     * Delete project user
     * @param {Object} state
     * @param {Integer} userId
     */
    [types.DELETE_PROJECT_USER](state, {user}) {
        const index = state.projectUsers.items.findIndex(
            item => item.user === user);
        if (index < 0) {
            return;
        }

        state.projectUsers.items.splice(index, 1);
    },
    /**
     * Delete manager
     * @param {Object} state
     * @param {Integer} userId
     */
    [types.DELETE_MANAGER](state, {user}) {
        const index = state.managers.findIndex(
            item => item.user === user);
        if (index < 0) {
            return;
        }

        state.managers.splice(index, 1);
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
