import Vue from 'vue';
import * as types from '../mutation-types';
import * as loading from '../loading-types';

const state = {
    modules: [],
    recommendedModules: [],
};

const getters = {
    modules: state => state.modules,
    recommendedModules: state => state.recommendedModules,
    optionalModules: (state, getters) => {
        return getters.modules.filter(module => !getters.isRecommendedModule(module));
    },
    isRecommendedModule: (state, getters) => (module) => {
        return getters.recommendedModules.indexOf(module) >= 0;
    },
};

const actions = {
    /**
     * Gets the list of modules from the API
     * @param {function} commit
     * @param {function} dispatch
     * @return {object}
     */
    async getModules({commit, dispatch}) {
        try {
            dispatch('wait/start', loading.GET_MODULES, {root: true});
            let response = await Vue.http.get(
                Routing.generate('app_api_modules_list'));

            let modules = response.data;
            commit(types.SET_MODULES, {modules});

            return response;
        } catch (e) {
            commit(types.SET_MODULES, {modules: []});
        } finally {
            dispatch('wait/end', loading.GET_MODULES, {root: true});
        }
    },
    /**
     * Gets the list of recommended modules
     * @param {function} commit
     * @param {object} data
     * @return {object}
     */
    async getRecommendedModules({commit, dispatch}, data) {
        try {
            dispatch('wait/start', loading.GET_RECOMMENDED_MODULES,
                {root: true});
            let response = await Vue.http.post(
                Routing.generate('app_api_recommended_modules_list'), data);
            let modules = response.data;
            commit(types.SET_RECOMMENDED_MODULES, modules);

            return response;
        } catch (e) {
            commit(types.SET_RECOMMENDED_MODULES, {recommendedModules: []});
        } finally {
            dispatch('wait/end', loading.GET_RECOMMENDED_MODULES, {root: true});
        }
    },
};

const mutations = {
    /**
     * Sets modules to state
     * @param {Object} state
     * @param {array} modules
     */
    [types.SET_MODULES](state, {modules}) {
        state.modules = modules;
    },
    /**
     * Sets recommended modules to state
     * @param {object} state
     * @param {array} modules
     */
    [types.SET_RECOMMENDED_MODULES](state, modules) {
        state.recommendedModules = modules;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
