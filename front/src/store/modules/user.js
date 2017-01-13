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
        .get('/api/user').then((response) => {
            let user = response.data;
            commit(types.SET_USER, {user});
        }, (response) => {
            // TODO: REMOVE MOCK DATA
            let user = {
                'id': 1,
                'name': 'John Doe',
                'avatar':
                  'http://cdn-01.belfasttelegraph.co.uk/opinion/columnists/jane-graham/article34424816.ece/5c02f/AUTOCROP/w620/2016-02-05_opi_16642636_I1.JPG',
                'notifications': 24,
            };
            commit(types.SET_USER, {user});
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
