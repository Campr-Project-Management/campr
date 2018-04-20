import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    tasksByStatuses: [],
    tasksByStatusFilters: {},
};

const getters = {
    tasksByStatuses: state => state.tasksByStatuses,
    tasksByStatus: (state, getters) => (statusId) => {
        if (!getters.tasksByStatuses || !getters.tasksByStatuses[statusId]) {
            return {};
        }

        return getters.tasksByStatuses[statusId];
    },
};

const actions = {
    /**
     * Gets tasks ordered by one status from the API and
     * commits SET_TASKS_BY_STATUS mutations
     * @param {function} commit
     * @param {number} project
     * @param {number} page
     * @param {number} status
     * @param {object} criteria
     * @return {object}
     */
    getTasksByStatus({commit}, {project, page, status, criteria}) {
        criteria = Object.assign({}, criteria, {
            type: 2,
            status,
        });

        let data = {
            params: {
                criteria,
                page: page,
                pageSize: 24,
            },
        };

        return Vue.http
            .get(Routing.generate('app_api_projects_workpackages', {'id': project}), data)
            .then((response) => {
                if (response.status === 200) {
                    let tasksByStatus = response.data;
                    commit(types.SET_TASKS_BY_STATUS, {tasksByStatus, statusId: status});
                }
            }, (response) => {
            });
    },
    resetTasks({commit}) {
        commit(types.RESET_TASKS);
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
        if (!state.tasksByStatuses[statusId]) {
            Vue.set(state.tasksByStatuses, statusId, tasksByStatus);
            return;
        }

        state.tasksByStatuses[statusId].items.push(...tasksByStatus.items);
        state.tasksByStatuses[statusId].nbItems = tasksByStatus.nbItems;
        state.tasksByStatuses[statusId].nbPages = tasksByStatus.nbPages;
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
