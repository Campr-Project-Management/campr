import Vue from 'vue';
import * as types from '../mutation-types';
import router from '../../router';

const state = {
    projectPhases: {},
    currentPhase: {},
    phaseWorkPackages: [],
    projectPhaseFilters: {},
    allProjectPhases: {},
};

const getters = {
    projectPhases: state => state.projectPhases,
    currentPhase: state => state.currentPhase,
    phaseWorkPackages: state => state.phaseWorkPackages,
    projectPhasesForSelect: state => {
        let phaseSelect = [];
        if (state.allProjectPhases && state.allProjectPhases.items) {
            phaseSelect = state.allProjectPhases.items.map(item => {
                return {
                    'key': item.id,
                    'label': item.name,
                };
            });
        }
        phaseSelect.unshift({label: Vue.translate('label.phase'), key: null});
        return phaseSelect;
    },
    allProjectPhases: state => state.allProjectPhases,
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

        if (state.projectPhaseFilters && state.projectPhaseFilters.startDate) {
            paramObject.params.startDate = state.projectPhaseFilters.startDate;
        }
        if (state.projectPhaseFilters && state.projectPhaseFilters.endDate) {
            paramObject.params.endDate = state.projectPhaseFilters.endDate;
        }
        if (state.projectPhaseFilters && state.projectPhaseFilters.status) {
            paramObject.params.status = state.projectPhaseFilters.status;
        }
        if (state.projectPhaseFilters && state.projectPhaseFilters.responsible) {
            paramObject.params.projectUser = state.projectPhaseFilters.responsible;
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
    setPhasesFilters({commit}, filters) {
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
                if (response.body && response.body.error) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                } else {
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
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
                if (response.body && response.body.error) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                } else {
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                    router.push({name: 'project-phases-and-milestones'});
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
    /**
     * Gets phase workpackages
     * @param {function} commit
     * @param {array} data
     */
    getPhaseWorkpackages({commit}, data) {
        Vue.http
            .get(
                Routing.generate('app_api_phase_workpackages_get', {'id': data.id}),
                JSON.stringify(data)
            ).then((response) => {
                if (response.status === 200) {
                    let workPackages = response.data;
                    commit(types.SET_PHASE_WORKPACKAGES, {workPackages});
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
        state.projectPhases = phases;
    },
    /**
     * Sets project phase to state
     * @param {Object} state
     * @param {Object} phase
     */
    [types.SET_PHASE](state, {phase}) {
        state.currentPhase = phase;
    },
    [types.SET_PHASES_FILTERS](state, {filters}) {
        state.projectPhaseFilters = Object.assign({}, state.projectPhaseFilters, filters);
    },
    /**
     * Sets all project phases
     * @param {Object} state
     * @param {Object} phases
     */
    [types.SET_PROJECT_ALL_PHASES](state, {phases}) {
        state.allProjectPhases = phases;
    },
    /**
     * Delete project phase
     * @param {Object} state
     * @param {integer} id
     */
    [types.DELETE_PROJECT_PHASE](state, {id}) {
        state.projectPhases.items = state.projectPhases.items.filter((item) => {
            return item.id !== id;
        });
        state.projectPhases.totalItems--;
    },
    /**
     * Set phase workpackages
     * @param {Object} state
     * @param {integer} workPackages
     */
    [types.SET_PHASE_WORKPACKAGES](state, {workPackages}) {
        state.phaseWorkPackages = workPackages;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
