import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    modules: [],
};

const getters = {
    modules: state => state.modules,
};

const actions = {
    /**
     * Gets the list of modules from the API
     * @param {function} commit
     */
    getModules({commit}) {
        Vue
            .http
            .get(Routing.generate('app_api_modules_list'))
            .then(
                (response) => {
                    if (response.status === 200) {
                        let modules = response.data;
                        commit(types.SET_MODULES, {modules});
                    } else {
                        commit(types.SET_MODULES, {modules: []});
                    }
                }, (response) => {
                    commit(types.SET_MODULES, {modules: []});
                }
            )
        ;
    },
};

const mutations = {
    /**
     * Sets modules  to state
     * @param {Object} state
     * @param {array} modules
     */
    [types.SET_MODULES](state, {modules}) {
        state.modules = modules;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
