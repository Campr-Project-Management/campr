import * as types from '../mutation-types';

const state = {
    loader: true,
};

const getters = {
    loader: state => state.loader,
};

const mutations = {
    /**
     * Sets loader value to state
     * @param {Object} state
     * @param {boolean} value
     */
    [types.TOGGLE_LOADER](state, value) {
        state.loader = value;
    },
};

export default {
    state,
    getters,
    mutations,
};
