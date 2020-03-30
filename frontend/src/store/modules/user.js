import Vue from 'vue';
import * as types from '../mutation-types';
import * as loading from '../loading-types';
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
    locale: (state, getters) => getters.localUser.locale,
};

const actions = {
    /**
     * Gets the user from the API and commits SET_USER mutation
     * @param {function} commit
     * @return {Object}
     */
    getUserInfo({commit}) {
        return Vue
            .http
            .get(Routing.generate('app_api_users_me'))
            .then(
                (response) => {
                    let localUser = response.data;
                    commit(types.SET_LOCAL_USER, {localUser});
                    commit(types.SET_USER, {user: localUser});

                    return response;
                },
                (response) => {
                    commit(types.SET_LOCAL_USER, {localUser: {}});

                    return response;
                },
            )
        ;
    },

    /**
     * Update local user
     * @param {object} commit
     * @param {string} data
     * @return {object}
     */
    updateLocalUser({commit}, data) {
        return Vue.http.patch(Routing.generate('app_api_users_me_edit'), data).
                   then(
                       (response) => {
                           let localUser = response.data;
                           commit(types.SET_LOCAL_USER, {localUser});
                       },
                       (response) => {
                           commit(types.SET_LOCAL_USER, {localUser: {}});
                       },
                   )
            ;
    },

    /**
     * Update local user
     * @param {object} commit
     * @param {string} locale
     * @return {object}
     */
    switchLocale({commit}, locale) {
        return Vue.http.patch(Routing.generate('app_api_switch_locale'),
            {locale}).then(
            (response) => {
                let localUser = response.data;
                commit(types.SET_LOCAL_USER, {localUser});
            },
            (response) => {
                commit(types.SET_LOCAL_USER, {localUser: {}});
            },
        )
            ;
    },

    /**
     * Update theme
     * @param {object} commit
     * @param {string} theme
     * @return {object}
     */
    async switchTheme({commit, dispatch}, theme) {
        try {
            dispatch('wait/start', loading.SWITCH_THEME, {root: true});
            let response = await Vue.http.patch(
                Routing.generate('app_api_switch_theme'), {theme});
            let localUser = response.data;
            commit(types.SET_LOCAL_USER, {localUser});
            window.location.reload();

            return response;
        } catch (e) {
            dispatch('wait/end', loading.SWITCH_THEME, {root: true});
        }
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
        return Vue.http.get(Routing.generate('app_api_users', filters)).then(
            (response) => {
                let users = response.data;
                if (!_.isArray(users)) {
                    users = [];
                }
                commit(types.SET_USERS, {users});
            },
            (response) => {
                commit(types.SET_USERS, {users: []});
            },
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
    /**
     * Sync user.
     * @param {function} commit
     * @return {object}
     */
    syncUser({commit}) {
        return Vue.http.get(Routing.generate('app_api_users_sync')).then(
            (response) => {
                let localUser = response.data;
                commit(types.SET_LOCAL_USER, {localUser});
            },
            (response) => {
                commit(types.SET_LOCAL_USER, {localUser: {}});
            },
        )
            ;
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
        if (window.user) {
            window.user.locale = localUser.locale;
        }

        if (window.Translator) {
            window.Translator.locale = localUser.locale;
        }
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
