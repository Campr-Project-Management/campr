import Vue from 'vue';
import * as types from '../mutation-types';
import _ from 'lodash';

const state = {
    user: {},
    users: [],
    localUser: {},
};

const getters = {
    user: state => state.user,
    users: state => state.users,
    localUser: state => state.localUser,
};

const actions = {
    /**
     * Gets the user from the API and commits SET_USER mutation
     * @param {function} commit
     * @return {Object}
     */
    getUserInfo({commit}) {
        Vue
            .http
            .get(Routing.generate('app_api_users_me'))
            .then(
                (response) => {
                    let localUser = response.data;
                    commit(types.SET_LOCAL_USER, {localUser});
                },
                (response) => {
                    commit(types.SET_LOCAL_USER, {localUser: {}});
                }
            )
        ;

        return Vue
            .http
            .get(Routing.generate('main_api_users_get'))
            .then((response) => {
                let user = response.data;
                commit(types.SET_USER, {user});
                localStorage.setItem('id_token', user.apiToken);
                commit(types.TOGGLE_LOADER, false);
            }, (response) => {
                commit(types.TOGGLE_LOADER, false);
            })
        ;
    },
    /**
     * Gets users.
     * @param {function} commit
     * @param {Object} filters
     * @return {Object}
     */
    getUsers({commit}, filters) {
        if (!filters) {
            filters = {};
        }
        return Vue
            .http
            .get(Routing.generate('app_api_users', filters))
            .then(
                (response) => {
                    let users = response.data;
                    if (!_.isArray(users)) {
                        users = [];
                    }
                    commit(types.SET_USERS, {users});
                },
                (response) => {
                    commit(types.SET_USERS, {users: []});
                }
            )
        ;
    },
    /**
     * Clears users.
     * @param {function} commit
     */
    clearUsers({commit}) {
        commit(types.SET_USERS, {users: []});
    },
};

const mutations = {
    /**
     * Sets user to state
     * @param {Object} state
     * @param {Object} user
     */
    [types.SET_USER](state, {user}) {
        state.user = user;
    },
    /**
     * Sets users.
     * @param {Object} state
     * @param {Object} users
     */
    [types.SET_USERS](state, {users}) {
        state.users = users;
    },
    /**
     * Sets local user (local from the team domain).
     * @param {Object} state
     * @param {Object} users
     */
    [types.SET_LOCAL_USER](state, {localUser}) {
        state.localUser = localUser;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
