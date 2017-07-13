import Vue from 'vue';
import * as types from '../mutation-types';
import _ from 'lodash';
// import router from '../../router';

const ROLE_SPONSOR = 'roles.project_sponsor';
const ROLE_MANAGER = 'roles.project_manager';

const state = {
    projectUsers: [],
    sponsors: [],
    managers: [],
};

const getters = {
    projectUsers: state => state.projectUsers,
    projectSponsors: state => state.sponsors,
    projectManagers: state => state.managers,
    projectUsersForSelect: state => {
        let usersSelect = [];
        if (state.projectUsers && state.projectUsers.items) {
            usersSelect = state.projectUsers.items.map(item => {
                return {
                    'key': item.user,
                    'label': item.userFullName,
                };
            });
        }
        usersSelect.unshift({label: Vue.translate('label.responsible'), key: null});
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
        Vue.http
            .get(Routing.generate('app_api_project_project_users', data)).then((response) => {
                if (response.status === 200) {
                    let projectUsers = response.data;
                    commit(types.SET_PROJECT_USERS, {projectUsers});
                    commit(types.SET_MANAGERS, {projectUsers});
                    commit(types.SET_SPONSORS, {projectUsers});
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
                        data.append(key + '[]', + item);
                    });
                }
            }
        });
        return Vue
            .http
            .post(
                Routing.generate('app_api_project_team_member_create', {'id': userData.project}),
                data
            )
            .then(
                (response) => {
                    if (response.body && response.body.error) {
                        const {messages} = response.body;
                        commit(types.SET_VALIDATION_MESSAGES, {messages});
                    } else {
                    }
                    return response.body;
                },
                (response) => {
                    return response.body;
                }
            );
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
        let sponsors = [];
        if (!_.isArray(projectUsers.items)) {
            projectUsers.items = [];
        }
        projectUsers.items.map(function(projectUser) {
            if (projectUser.projectRoleNames.indexOf(ROLE_SPONSOR) !== -1) {
                sponsors.push(projectUser);
            }
        });
        state.sponsors = sponsors;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
