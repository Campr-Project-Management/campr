import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    user: {},
};

const getters = {
    user: state => state.user,
};

const actions = {
    /**
     * Gets the user from the API and commits SET_USER mutation
     * @param {function} commit
     */
    getUserInfo({commit}) {
        Vue.http
        .get(Routing.generate('main_api_users_get')).then((response) => {
            let user = response.data;
            commit(types.SET_USER, {user});
            localStorage.setItem('id_token', user.apiToken);
            commit(types.TOGGLE_LOADER, false);
        }, (response) => {
            commit(types.TOGGLE_LOADER, false);
        });
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
};

export default {
    state,
    getters,
    actions,
    mutations,
};
