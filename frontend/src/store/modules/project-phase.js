import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    items: [],
};

const getters = {
    projectPhases: state => state.items,
    projectPhasesForSelect: state => {
        return state.items.items.map(item => {
            return {
                'key': item.id,
                'label': item.name,
            };
        });
    },
};

const actions = {
    /**
     * Get all project phases
     * @param {function} commit
     * @param {Number} projectId
     */
    getProjectPhases({commit}, projectId) {
        Vue.http
            .get(Routing.generate('app_api_project_phases', {'id': projectId})).then((response) => {
                if (response.status === 200) {
                    let phases = response.data;
                    commit(types.SET_PROJECT_PHASES, {phases});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets project phases to state
     * @param {Object} state
     * @param {array} phases
     */
    [types.SET_PROJECT_PHASES](state, {phases}) {
        state.items = phases;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
