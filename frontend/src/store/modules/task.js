import Vue from 'vue';
import * as types from '../mutation-types';
import router from '../../router';

const state = {
    currentItem: {},
    items: [],
    filteredItems: [],
    filters: [],
    taskStatuses: [],
    tasksByStatuses: [],
};

const getters = {
    task: state => state.currentItem,
    tasks: state => state.filteredItems.items,
    count: state => state.filteredItems.totalItems,
    taskStatuses: state => state.taskStatuses,
    tasksByStatuses: state => state.tasksByStatuses,
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
            .get(Routing.generate('app_api_workpackage_list'), {'recent': true, 'page': page}).then((response) => {
                if (response.status === 200) {
                    let tasks = response.data;
                    commit(types.SET_TASKS, {tasks});
                    commit(types.TOGGLE_LOADER, false);
                }
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
            .get(Routing.generate('app_api_workpackage_list'), {'page': page}).then((response) => {
                if (response.status === 200) {
                    let tasks = response.data;
                    commit(types.SET_TASKS, {tasks});
                    commit(types.TOGGLE_LOADER, false);
                }
            }, (response) => {
            });
    },
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
                    'type': 2,
                    'pageSize': 8,
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
    getTasksByStatus({commit}, {project, status, page}) {
        commit(types.TOGGLE_LOADER, true);
        Vue.http
            .get(Routing.generate('app_api_projects_workpackages', {'id': project}), {
                params: {
                    status,
                    'type': 2,
                    'page': page,
                    'pageSize': 8,
                },
            })
            .then((response) => {
                if (response.status === 200) {
                    let tasksByStatus = response.data;
                    commit(types.SET_TASKS_BY_STATUS, {tasksByStatus, status});
                    commit(types.TOGGLE_LOADER, false);
                }
            }, (response) => {
            });
    },
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
    /**
     * Gets a task by ID from the API and commits SET_TASK mutation
     * @param {function} commit
     * @param {number} id
     */
    getTaskById({commit}, id) {
        Vue.http
            .get(Routing.generate('app_api_workpackage_get', {'id': id})).then((response) => {
                if (response.status === 200) {
                    let task = response.data;
                    commit(types.SET_TASK, {task});
                }
            }, (response) => {
            });
    },
    /**
     * Creates a new task on project
     * @param {function} commit
     * @param {array} data
     * @param {Number} projectId
     */
    createNewTask({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_project_tasks_create', {'id': data.projectId}),
                data.data
            ).then((response) => {
                router.push({name: 'project-task-management-list'});
            }, (response) => {
                if (response.status === 400) {
                    // implement system to dispay errors
                    console.log(response.data);
                }
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
        state.filteredItems = JSON.parse(JSON.stringify(tasks));
    },
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
    [types.SET_TASKS_BY_STATUS](state, {tasksByStatus, status}) {
        console.log('action', {tasksByStatus, status});
        state.tasksByStatuses[status].items.concat(tasksByStatus);
    },
    /**
     * Sets task statuses to state
     * @param {Object} state
     * @param {array} taskStatuses
     */
    [types.SET_TASK_STATUSES](state, {taskStatuses}) {
        state.taskStatuses = taskStatuses;
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
