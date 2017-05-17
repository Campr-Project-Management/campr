import Vue from 'vue';
import * as types from '../mutation-types';
import router from '../../router';

const state = {
    items: [],
    currentItem: {},
    totalItems: 0,
    filters: {},
    allItems: [],
};

const getters = {
    projectMilestones: state => state.items,
    milestone: state => state.currentItem,
    projectMilestonesForSelect: state => {
        return state.items.items.map(item => {
            return {
                'key': item.id,
                'label': item.name,
                'parent': item.parent,
            };
        });
    },
    allProjectMilestones: state => state.allItems,
};

const actions = {
    /**
     * Get all project milestones
     * @param {function} commit
     * @param {Object} apiParams
     */
    getProjectMilestones({commit, state}, {projectId, apiParams}) {
        let paramObject = {params: {}};
        if (apiParams && apiParams.page) {
            paramObject.params.page = apiParams.page;
        }

        if (state.filters.status) {
            paramObject.params.status = state.filters.status;
        }

        if (state.filters.responsible) {
            paramObject.params.responsible = state.filters.responsible;
        }

        if (state.filters.phase) {
            paramObject.params.phase = state.filters.phase;
        }

        if (state.filters.dueDate) {
            paramObject.params.dueDate = state.filters.dueDate;
        }

        Vue.http
            .get(Routing.generate('app_api_project_milestones', {'id': projectId}),
                paramObject,
            ).then((response) => {
                if (response.status === 200) {
                    let milestones = response.data;
                    commit(types.SET_PROJECT_MILESTONES, {milestones});
                    if (!apiParams) {
                        commit(types.SET_ALL_PROJECT_MILESTONES, {milestones});
                    }
                }
            }, (response) => {
            });
    },
    /**
     * Create project milestone
     * @param {function} commit
     * @param {array}    data
     */
    createProjectMilestone({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_project_milestones_create', {id: data.project}),
                JSON.stringify(data)
            ).then((response) => {
                if (response.status === 201) {
                    router.push({name: 'project-phases-and-milestones'});
                }
            }, (response) => {
            });
    },
    /**
     * Edit project milestone
     * @param {function} commit
     * @param {array}    data
     */
    editProjectMilestone({commit}, data) {
        Vue.http
            .patch(
                Routing.generate('app_api_workpackage_milestone_edit', {id: data.id}),
                JSON.stringify(data)
            ).then((response) => {
                if (response.status === 202) {
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
        Vue.http
            .get(Routing.generate('app_api_workpackage_get', {'id': id})).then((response) => {
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
        Vue.http
            .delete(
                Routing.generate('app_api_workpackage_delete', {id: id})
            ).then((response) => {
                commit(types.DELETE_PROJECT_MILESTONE, {id});
            }, (response) => {
            });
    },
    setMilestonesFiters({commit}, filters) {
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
        state.items = milestones;
    },
    [types.SET_MILESTONES_FILTERS](state, {filters}) {
        state.filters = Object.assign({}, state.filters, filters);
    },
    /**
     * Sets project milestone to state
     * @param {Object} state
     * @param {Object} milestone
     */
    [types.SET_MILESTONE](state, {milestone}) {
        state.currentItem = milestone;
    },
    /**
     * Delete project milestone
     * @param {Object} state
     * @param {integer} id
     */
    [types.DELETE_PROJECT_MILESTONE](state, {id}) {
        state.items.items = state.items.items.filter((item) => {
            return item.id !== id ? true : false;
        });
        state.items.totalItems--;
    },
    [types.SET_ALL_PROJECT_MILESTONES](state, {milestones}) {
        state.allItems = milestones;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
