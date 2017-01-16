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
    tasks: state => state.items,
    filteredTasks: state => state.filteredItems,
};

const actions = {
    /**
     * Gets this month tasks from the API and commits SET_TASKS mutation
     * @param {function} commit
     */
    getRecentTasks({commit}) {
        Vue.http
            .post('api/workpackage/list', {'recent': true}).then((response) => {
                let tasks = response.data;
                commit(types.SET_TASKS, {tasks});
            }, (response) => {
            });
    },
    /**
     * Gets tasks from the API and commits SET_TASKS mutation
     * @param {function} commit
     */
    getTasks({commit}) {
        Vue.http
            .get('api/workpackage/list').then((response) => {
                let tasks = response.data;
                commit(types.SET_TASKS, {tasks});
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
            .get('/api/workpackage/' + id).then((response) => {
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
