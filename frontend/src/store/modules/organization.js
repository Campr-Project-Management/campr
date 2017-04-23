// import Vue from 'vue';
// import * as types from '../mutation-types';
// import router from '../../router';

const state = {
    members: [],
    items: [],
};

const getters = {
    items: state => state.items,
    member: state => state.members,
};

const actions = {
    /**
     * Creates a new member in organization
     * @param {function} commit
     * @param {number} member
     */
    createNewOrganizationMember({commit}, member) {
        // Vue.http
    },
};

const mutations = {
};

export default {
    state,
    getters,
    actions,
    mutations,
};
