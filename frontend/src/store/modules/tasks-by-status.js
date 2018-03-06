import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    tasksByStatuses: [],
    tasksByStatusFilters: {},
};

const getters = {
    tasksByStatuses: state => state.tasksByStatuses,
    tasksByStatusesCount: state => state.tasksByStatuses.length,
};

const actions = {
    /**
     * Gets tasks ordered by statuses from the API and
     * commits SET_TASKS_BY_STATUSES mutations
     * @param {function} commit
     * @param {number} project
     */
    getTasksByStatuses({commit}, project) {
        commit(types.TOGGLE_LOADER, true);
        Vue.http
            .get(Routing.generate('app_api_projects_workpackages', {'id': project}), {
                params: {
                    criteria: {
                        type: 2,
                    },
                    pageSize: 8,
                },
            })
            .then((response) => {
                if (response.status === 200) {
                    let tasksByStatuses = response.data;
                    commit(types.SET_TASKS_BY_STATUSES, {tasksByStatuses});
                    commit(types.TOGGLE_LOADER, false);
                }
            }, (response) => {
            });
    },
    /**
     * Gets tasks ordered by one status from the API and
     * commits SET_TASKS_BY_STATUS mutations
     * @param {function} commit
     * @param {number} project
     * @param {number} status
     * @param {number} page
     */
    getTasksByStatus({commit, state}, {project, statusId, page, callback}) {
        // commit(types.TOGGLE_LOADER, true);
        const projectUser = state.tasksByStatusFilters.assignee;
        const colorStatus = state.tasksByStatusFilters.condition;
        const searchString = state.tasksByStatusFilters.searchString;
        Vue.http
            .get(Routing.generate('app_api_projects_workpackages', {'id': project}), {
                params: {
                    'status': statusId,
                    'type': 2,
                    'page': page,
                    'pageSize': 1,
                    projectUser,
                    colorStatus,
                    searchString,
                },
            })
            .then((response) => {
                if (response.status === 200) {
                    let tasksByStatus = response.data;
                    commit(types.SET_TASKS_BY_STATUS, {tasksByStatus, statusId});
                    // commit(types.TOGGLE_LOADER, false);
                }
                callback();
            }, (response) => {
            });
    },
    resetTasks({commit}, project) {
        commit(types.RESET_TASKS);

        const projectUser = state.tasksByStatusFilters.assignee;
        const colorStatus = state.tasksByStatusFilters.condition;
        const searchString = state.tasksByStatusFilters.searchString;

        Vue.http
            .get(Routing.generate('app_api_projects_workpackages', {'id': project}), {
                params: {
                    'type': 2,
                    'pageSize': 2,
                    projectUser,
                    colorStatus,
                    searchString,
                },
            })
            .then((response) => {
                if (response.status === 200) {
                    let tasksByStatuses = response.data;
                    commit(types.SET_TASKS_BY_STATUSES, {tasksByStatuses});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets tasks ordered by statuses to state
     * @param {Object} state
     * @param {array} tasksByStatuses
     */
    [types.SET_TASKS_BY_STATUSES](state, {tasksByStatuses}) {
        state.tasksByStatuses = tasksByStatuses;
    },
    /**
     * Sets tasks filtered by status to state
     * @param {Object} state
     * @param {array} tasksByStatus
     * @param {number} status
     */
    [types.SET_TASKS_BY_STATUS](state, {tasksByStatus, statusId}) {
        state.tasksByStatuses[statusId].items = state.tasksByStatuses[statusId].items.concat(tasksByStatus.items);
        state.tasksByStatuses[statusId].totalItems = tasksByStatus.totalItems;
    },
    [types.RESET_TASKS](state) {
        state.tasksByStatuses = [];
    },
    [types.SET_TASKS_FILTERS](state, filters) {
        state.tasksByStatusFilters = filters;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
