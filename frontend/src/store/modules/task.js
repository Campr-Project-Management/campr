import Vue from 'vue';
import * as types from '../mutation-types';
import router from '../../router';

const state = {
    currentTask: {},
    tasks: [],
    tasksCount: 0,
    filteredTasks: [],
    taskFilters: [],
    users: [],
    tasksFilters: {},
    allTasks: [],
};

const getters = {
    currentTask: state => state.currentTask,
    tasks: state => state.tasks,
    tasksCount: state => state.tasksCount,
    allTasks: state => state.allTasks,
};

const actions = {
    /**
     * Gets this month tasks from the API and commits SET_TASKS mutation
     * @param {function} commit
     * @param {number} page
     */
    getRecentTasks({commit}, page) {
        commit(types.TOGGLE_LOADER, true);
        if (page === undefined) {
            page = 1;
        }
        Vue.http
            .get(Routing.generate('app_api_workpackage_list', {recent: true, page: page, type: 2})).then((response) => {
                if (response.status === 200) {
                    let tasks = response.data;
                    commit(types.SET_TASKS, {tasks});
                    commit(types.TOGGLE_LOADER, false);
                }
            }, (response) => {
            });
    },
    getRecentTasksByProject({commit}, projectId) {
        commit(types.TOGGLE_LOADER, true);
        let params = {
            id: projectId,
            sort: 'updatedAt',
            order: 'desc',
            limit: 6,
        };
        Vue.http
            .get(Routing.generate('app_api_project_tasks', params)).then((response) => {
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
            .get(Routing.generate('app_api_workpackage_list', {page: page, type: 2})).then((response) => {
                if (response.status === 200) {
                    let tasks = response.data;
                    commit(types.SET_TASKS, {tasks});
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
                }
            }
        );
    },

    setFilters({commit}, taskFilters) {
        commit(types.SET_TASKS_FILTERS, taskFilters);
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
                    'pageSize': 8,
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
     * Sets tasks to state
     * @param {Object} state
     * @param {array} tasks
     */
    [types.SET_TASKS](state, {tasks}) {
        state.tasks = tasks.items;
        state.tasksCount = tasks.totalItems;
        state.filteredTasks = JSON.parse(JSON.stringify(tasks));
    },
    /**
     * Sets task to state
     * @param {Object} state
     * @param {Object} task
     */
    [types.SET_TASK](state, {task}) {
        state.currentTask = task;
    },
    /**
     * Sets taskFilters to state
     * @param {Object} state
     * @param {array} filter
     */
    [types.SET_FILTERS](state, filter) {
        state.taskFilters[filter[0]] = filter[1];
    },
    [types.SET_TASKS_FILTERS](state, taskFilters) {
        state.tasksFilters = taskFilters;
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
