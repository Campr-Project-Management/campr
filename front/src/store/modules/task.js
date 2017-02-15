import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    currentItem: {},
    items: [],
    filteredItems: [],
    filters: [],
};

const getters = {
    task: state => state.currentItem,
    tasks: state => state.items.items,
    count: state => state.items.totalItems,
};

const actions = {
    /**
     * Gets this month tasks from the API and commits SET_TASKS mutation
     * @param {function} commit
     * @param {number} page
     */
    getRecentTasks({commit}, page) {
        commit(types.TOGGLE_LOADER, true);
        Vue.http
            .post(Routing.generate('app_api_workpackage_list'), {'recent': true, 'page': page}).then((response) => {
                let tasks = response.data;
                commit(types.SET_TASKS, {tasks});
                commit(types.TOGGLE_LOADER, false);
            }, (response) => {
            });
    },
    /**
     * Gets tasks from the API and commits SET_TASKS mutation
     * @param {function} commit
     * @param {number} page
     */
    getTasks({commit}, page) {
        commit(types.TOGGLE_LOADER, true);
        Vue.http
            .post(Routing.generate('app_api_workpackage_list'), {'page': page}).then((response) => {
                let tasks = response.data;
                commit(types.SET_TASKS, {tasks});
                commit(types.TOGGLE_LOADER, false);
            }, (response) => {
            });
    },
    /**
     * Gets a task by ID from the API and commits SET_TASK mutation
     * @param {function} commit
     * @param {number} id
     */
    getTaskById({commit}, id) {
        Vue.http
            .get(Routing.generate('app_api_workpackage_get', {'id': id})).then((response) => {
                let task = response.data;
                commit(types.SET_TASK, {task});
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets color statuses to state
     * @param {Object} state
     * @param {array} colorStatuses
     */
    [types.SET_COLOR_STATUSES](state, {colorStatuses}) {
        state.items = colorStatuses;
        state.filteredItems = colorStatuses;
    },
    /**
     * Sets tasks to state
     * @param {Object} state
     * @param {array} tasks
     */
    [types.SET_TASKS](state, {tasks}) {
        state.items = tasks;
        state.filteredItems = tasks;
    },
    /**
     * Sets task to state
     * @param {Object} state
     * @param {Object} task
     */
    [types.SET_TASK](state, {task}) {
        state.currentItem = task;
    },
    /**
     * Sets filters to state
     * @param {Object} state
     * @param {array} filter
     */
    [types.SET_FILTERS](state, filter) {
        state.filters[filter[0]] = filter[1];
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
