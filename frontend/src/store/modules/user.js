import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    user: {},
    currentMember: {},
};

const getters = {
    user: state => state.user,
    currentMember: state => state.currentMember,
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
    /**
     * Gets the user info from the API based on id and not the info of the current user
     * @param {function} commit
     * @param {number} id
     */
    getMemberInfo({commit}, id) {
        let params = id !== undefined ? {id: id} : null;
        Vue.http
        .get(Routing.generate('main_api_users_get', params)).then((response) => {
            let currentMember = response.data;
            commit(types.SET_CURRENT_MEMBER, {currentMember});
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
    /**
     * Sets the current member on member page to state
     * @param {Object} state
     * @param {Object} currentMember
     */
    [types.SET_CURRENT_MEMBER](state, {currentMember}) {
        state.currentMember = currentMember;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
