import Vue from 'vue';
import * as types from '../mutation-types';
import router from '../../router';

const state = {
    milestones: [],
    currentMilestone: {},
    milestoneFilters: {},
    allMilestones: [],
};

const getters = {
    projectMilestones: state => state.milestones,
    currentMilestone: state => state.currentMilestone,
    projectMilestonesForSelect: state => {
        let milestonesSelect = [];
        if (state.allMilestones && state.allMilestones.items) {
            milestonesSelect = state.allMilestones.items.map(item => {
                return {
                    'key': item.id,
                    'label': item.name,
                    'parent': item.parent,
                };
            });
        }
        return milestonesSelect;
    },
    allProjectMilestones: state => state.allMilestones,
};

const actions = {
    /**
     * Get all project milestones
     * @param {function} commit
     * @param {Object} apiParams
     * @return {object}
     */
    getProjectMilestones({commit, state}, {projectId, apiParams}) {
        let paramObject = {params: {}};
        if (apiParams && apiParams.page) {
            paramObject.params.page = apiParams.page;
        }

        if (state.filters && state.filters.status) {
            paramObject.params.status = state.filters.status;
        }
        if (state.filters && state.filters.responsible) {
            paramObject.params.projectUser = state.filters.responsible;
        }
        if (state.filters && state.filters.phase) {
            paramObject.params.phase = state.filters.phase;
        }
        if (state.filters && state.filters.dueDate) {
            paramObject.params.dueDate = state.filters.dueDate;
        }
        if (state.filters && state.filters.startDate) {
            paramObject.params.startDate = state.filters.startDate;
        }
        if (state.filters && state.filters.endDate) {
            paramObject.params.endDate = state.filters.endDate;
        }
        if (state.filters && state.filters.isKeyMilestone) {
            paramObject.params.isKeyMilestone = state.filters.isKeyMilestone;
        }

        return Vue.http.get(
            Routing.generate('app_api_project_milestones', {'id': projectId}),
            paramObject,
        ).then((response) => {
            if (response.status === 200) {
                let milestones = response.data;
                if (apiParams === undefined) {
                    commit(types.SET_ALL_PROJECT_MILESTONES, {milestones});
                } else {
                    commit(types.SET_PROJECT_MILESTONES, {milestones});
                }
            }
        }, (response) => {
        });
    },
    /**
     * Creates a new project milestone
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    createProjectMilestone({commit}, data) {
        return Vue.http.post(
            Routing.generate('app_api_project_milestones_create',
                {id: data.project}),
            JSON.stringify(data),
        ).then((response) => {
            if (response.body && response.body.error) {
                const {messages} = response.body;
                commit(types.SET_VALIDATION_MESSAGES, {messages});
            } else {
                commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                router.push({name: 'project-phases-and-milestones'});
            }

            return response;
        }, (response) => {
        });
    },
    /**
     * Edit project milestone
     * @param {function} commit
     * @param {array}    data
     */
    editProjectMilestone({commit}, data) {
        Vue.http.patch(
            Routing.generate('app_api_workpackage_milestone_edit',
                {id: data.project, milestone: data.id}),
            JSON.stringify(data),
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
     * Gets project milestone
     * @param {function} commit
     * @param {number} id
     */
    getProjectMilestone({commit}, id) {
        Vue.http.get(Routing.generate('app_api_workpackage_get', {'id': id})).
            then((response) => {
                if (response.status === 200) {
                    let milestone = response.data;
                    commit(types.SET_MILESTONE, {milestone});
                }
            }, (response) => {
            });
    },
    /**
     * Delete project phase
     * @param {function} commit
     * @param {integer} id
     */
    deleteProjectMilestone({commit}, id) {
        Vue.http.delete(
            Routing.generate('app_api_workpackage_delete', {id: id}),
        ).then((response) => {
            if (response.body && response.body.error) {
                const {messages} = response.body;
                commit(types.SET_VALIDATION_MESSAGES, {messages});
            } else {
                commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                commit(types.DELETE_PROJECT_MILESTONE, {id});
            }
        }, (response) => {
        });
    },
    setMilestonesFilters({commit}, filters) {
        commit(types.SET_MILESTONES_FILTERS, {filters});
    },
};

const mutations = {
    /**
     * Sets project milestones to state
     * @param {Object} state
     * @param {array} milestones
     */
    [types.SET_PROJECT_MILESTONES](state, {milestones}) {
        state.milestones = milestones;
    },
    [types.SET_MILESTONES_FILTERS](state, {filters}) {
        state.filters = !filters.clear ? Object.assign({}, state.filters,
            filters) : [];
    },
    /**
     * Sets project milestone to state
     * @param {Object} state
     * @param {Object} milestone
     */
    [types.SET_MILESTONE](state, {milestone}) {
        state.currentMilestone = milestone;
    },
    /**
     * Delete project milestone
     * @param {Object} state
     * @param {integer} id
     */
    [types.DELETE_PROJECT_MILESTONE](state, {id}) {
        state.milestones.items = state.milestones.items.filter((item) => {
            return item.id !== id;
        });
        state.milestones.totalItems--;
    },
    [types.SET_ALL_PROJECT_MILESTONES](state, {milestones}) {
        state.allMilestones = milestones;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
