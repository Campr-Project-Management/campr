import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    items: [],
    totalItems: 0,
    filteredItems: [],
    filters: [],
    taskStatuses: [],
    tasksFilters: [],
};

const getters = {
    taskStatuses: state => state.items,
    taskStatusesCount: state => state.totalItems,
};

const actions = {
    /**
     * Gets task statuses from the API and commits SET_TASK_STATUSES mutation
     * @param {function} commit
     */
    getTaskStatuses({commit}) {
        commit(types.TOGGLE_LOADER, true);
        Vue.http
            .get(Routing.generate('app_api_workpackage_statuses_list')).then((response) => {
                if (response.status === 200) {
                    let taskStatuses = response.data;
                    commit(types.SET_TASK_STATUSES, {taskStatuses});
                    commit(types.TOGGLE_LOADER, false);
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets task statuses to state
     * @param {Object} state
     * @param {array} taskStatuses
     */
    [types.SET_TASK_STATUSES](state, {taskStatuses}) {
        state.items = taskStatuses;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
