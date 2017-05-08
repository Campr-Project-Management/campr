import Vue from 'vue';
import * as types from '../mutation-types';
import router from '../../router';

const state = {
    items: [],
    currentItem: {},
};

const getters = {
    projectPhases: state => state.items,
    phase: state => state.currentItem,
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
    /**
     * Create project phase
     * @param {function} commit
     * @param {array}    data
     */
    createProjectPhase({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_project_phases_create', {id: data.project}),
                JSON.stringify(data)
            ).then((response) => {
                if (response.status === 201) {
                    router.push({name: 'project-phases-and-milestones'});
                }
            }, (response) => {
            });
    },
    /**
     * Edit project phase
     * @param {function} commit
     * @param {array}    data
     */
    editProjectPhase({commit}, data) {
        Vue.http
            .patch(
                Routing.generate('app_api_workpackage_phase_edit', {id: data.id}),
                JSON.stringify(data)
            ).then((response) => {
                if (response.status === 202) {
                    router.push({name: 'project-phases-and-milestones'});
                }
            }, (response) => {
            });
    },
    /**
     * Gets project phase
     * @param {function} commit
     * @param {number} id
     */
    getProjectPhase({commit}, id) {
        Vue.http
            .get(Routing.generate('app_api_workpackage_get', {'id': id})).then((response) => {
                if (response.status === 200) {
                    let phase = response.data;
                    commit(types.SET_PHASE, {phase});
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
        console.log('set itms', phases);
        state.items = phases;
    },
    /**
     * Sets project phase to state
     * @param {Object} state
     * @param {Object} phase
     */
    [types.SET_PHASE](state, {phase}) {
        state.currentItem = phase;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
