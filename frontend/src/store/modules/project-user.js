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
};

const actions = {
    /**
     * Get all project users
     * @param {function} commit
     * @param {Number} projectId
     */
    getProjectUsers({commit}, projectId) {
        Vue.http
            .get(Routing.generate('app_api_project_project_users', {'id': projectId})).then((response) => {
                if (response.status === 200) {
                    let projectUsers = response.data;
                    commit(types.SET_PROJECT_USERS, {projectUsers});
                }
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
        state.items = projectUsers;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
