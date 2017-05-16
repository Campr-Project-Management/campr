import Vue from 'vue';
import * as types from '../mutation-types';
import router from '../../router';

const state = {
    items: [],
    currentItem: {},
    filters: {},
    currentItem: {},
    allItems: [],
};

const getters = {
    projectPhases: state => state.items,
    phase: state => state.currentItem,
    projectPhasesForSelect: state => {
        if (state.allItems.items === undefined) {
            return [];
        }
        return state.allItems.items.map(item => {
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
     * @param {Object} apiParams
     */
    getProjectPhases({commit, state}, {projectId, apiParams}) {
        let paramObject = {params: {}};
        if (apiParams && apiParams.page !== undefined) {
            paramObject.params.page = apiParams.page;
        }

        if (state.filters && state.filters.startDate) {
            paramObject.params.startDate = state.filters.startDate;
        }
        if (state.filters && state.filters.endDate) {
            paramObject.params.endDate = state.filters.endDate;
        }
        if (state.filters && state.filters.status) {
            paramObject.params.status = state.filters.status;
        }
        if (state.filters && state.filters.responsible) {
            paramObject.params.responsible = state.filters.responsible;
        }
        if (state.filters && state.filters.startDate) {
            paramObject.params.startDate = state.filters.startDate.getTime();
        }
        if (state.filters && state.filters.endDate) {
            paramObject.params.actualFinishAt = state.filters.endDate.getTime();
        }
        Vue.http
            .get(Routing.generate('app_api_project_phases', {'id': projectId}),
                paramObject,
            ).then((response) => {
                if (response.status === 200) {
                    let phases = response.data;
                    if (apiParams === undefined) {
                        commit(types.SET_PROJECT_ALL_PHASES, {phases});
                    } else {
                        commit(types.SET_PROJECT_PHASES, {phases});
                    }
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
    setPhasesFiters({commit}, filters) {
        commit(types.SET_PHASES_FILTERS, {filters});
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
    /**
     * Delete project phase
     * @param {function} commit
     * @param {integer} id
     */
    deleteProjectPhase({commit}, id) {
        Vue.http
            .delete(
                Routing.generate('app_api_workpackage_delete', {id: id})
            ).then((response) => {
                commit(types.DELETE_PROJECT_PHASE, {id});
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
    /**
     * Sets project phase to state
     * @param {Object} state
     * @param {Object} phase
     */
    [types.SET_PHASE](state, {phase}) {
        state.currentItem = phase;
    },
    [types.SET_PHASES_FILTERS](state, {filters}) {
        state.filters = Object.assign({}, state.filters, filters);
    },
    /**
     * Sets all project phases
     * @param {Object} state
     * @param {Object} phases
     */
    [types.SET_PROJECT_ALL_PHASES](state, {phases}) {
        state.allItems = phases;
    },
    /**
     * Delete project phase
     * @param {Object} state
     * @param {integer} id
     */
    [types.DELETE_PROJECT_PHASE](state, {id}) {
        state.items.items = state.items.items.filter((item) => {
            return item.id !== id ? true : false;
        });
        state.items.totalItems--;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
