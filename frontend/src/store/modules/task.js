import Vue from 'vue';
import * as types from '../mutation-types';
import router from '../../router';

const state = {
    currentItem: {},
    items: [],
    totalItems: 0,
    filteredItems: [],
    filters: [],
    taskStatuses: [],
    tasksByStatuses: [],
    users: [],
    tasksFilters: [],
    allTasks: [],
    requests: {},
};

const getters = {
    task: state => state.currentItem,
    tasks: state => state.items,
    count: state => state.totalItems,
    taskStatuses: state => state.taskStatuses,
    tasksByStatuses: state => state.tasksByStatuses,
    allTasks: state => state.allTasks,
    requests: state => state.requests,
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
            .get(Routing.generate('app_api_workpackage_list', {'recent': true, 'page': page})).then((response) => {
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
            .get(Routing.generate('app_api_workpackage_list', {'page': page})).then((response) => {
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
                    'pageSize': 1,
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
        const projectUser = state.tasksFilters.assignee;
        const colorStatus = state.tasksFilters.condition;
        const searchString = state.tasksFilters.searchString;
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
                // @TODO: use this redirect when we figure out why this is getting broken
                // router.push({
                //     name: 'project-task-management-edit',
                //     params: {
                //         id: data.projectId,
                //         taskId: response.data.id,
                //     },
                // });
            }, (response) => {
                if (response.status === 400) {
                    // implement system to display errors
                }
            });
    },

    /**
     * Edit an existing task
     * @param {function} commit
     * @param {array} data
     */
    editTask({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_workpackage_edit', {'id': data.taskId}),
                data.data
            ).then(
                (response) => {
                    // router.push({name: 'project-task-management-list'});
                    if (response.status === 200) {
                        alert('Saved!');
                    } else {
                        alert(response.status);
                    }
                },
                (response) => {
                    alert('Validation issues');
                    if (response.status === 400) {
                        // implement system to dispay errors
                        // console.log(response.data);
                    }
                }
            );
    },

    getUsers({commit}) {
        Vue.http
            .post(
                Routing.generate('app_api_workpackage_edit', {'id': data.taskId}),
                data.data
            ).then(
            (response) => {
                if (response.status === 200) {
                    alert('Saved!');
                } else {
                    alert(response.status);
                }
            },
            (response) => {
                alert('Validation issues');
                if (response.status === 400) {
                    // implement system to dispay errors
                    // console.log(response.data);
                }
            }
        );
    },

    setFilters({commit}, filters) {
        commit(types.SET_TASKS_FILTERS, filters);
    },

    resetTasks({commit}, project) {
        commit(types.RESET_TASKS);

        const projectUser = state.tasksFilters.assignee;
        const colorStatus = state.tasksFilters.condition;
        const searchString = state.tasksFilters.searchString;

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
    getAllTasksGrid({commit, state}, {project, page}) {
        const projectUser = state.tasksFilters.assignee;
        const colorStatus = state.tasksFilters.condition;
        const searchString = state.tasksFilters.searchString;
        const status = state.tasksFilters.status;

        Vue.http
            .get(Routing.generate('app_api_projects_workpackages', {'id': project}), {
                params: {
                    'type': 2,
                    'page': page,
                    'pageSize': 4,
                    projectUser,
                    colorStatus,
                    searchString,
                    'isGrid': true,
                    status,
                },
            })
            .then((response) => {
                if (response.status === 200) {
                    let tasks = response.data;
                    commit(types.SET_ALL_TASKS, tasks);
                    // commit(types.TOGGLE_LOADER, false);
                }
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
        // state.items = {
        //     items: tasks.items,
        //     totalNumber: tasks.totalNumber,
        // };
        state.items = tasks.items;
        state.totalItems = tasks.totalItems;
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
    [types.SET_TASKS_BY_STATUS](state, {tasksByStatus, statusId}) {
        state.tasksByStatuses[statusId].items = state.tasksByStatuses[statusId].items.concat(tasksByStatus.items);
        state.tasksByStatuses[statusId].totalItems = tasksByStatus.totalItems;
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
    [types.SET_TASKS_FILTERS](state, filters) {
        state.tasksFilters = filters;
    },
    [types.RESET_TASKS](state) {
        state.tasksByStatuses = [];
    },
    [types.SET_ALL_TASKS](state, tasks) {
        state.allTasks = tasks;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
