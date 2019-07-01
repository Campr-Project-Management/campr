import Vue from 'vue';
import * as types from '../mutation-types';
import * as loading from '../loading-types';
import {xml2WorkPackage} from '../../helpers/ms-project/task';
import _ from 'lodash';

const state = {
    currentTask: {},
    taskHistory: [],
    tasks: [],
    tasksCount: 0,
    filteredTasks: [],
    taskFilters: [],
    users: [],
    tasksFilters: {},
    allTasks: [],
    tasksPageSize: 0,
};

const getters = {
    currentTask: state => state.currentTask,
    tasks: state => state.tasks,
    tasksCount: state => state.tasksCount,
    tasksPerPage: state => state.tasksPageSize,
    allTasks: state => state.allTasks,
    taskHistory: state => state.taskHistory,
};

const actions = {
    async getRecentTasksByProject({commit, dispatch}, projectId) {
        try {
            commit(types.SET_TASKS, {tasks: []});
            dispatch('wait/start', loading.GET_RECENT_TASKS_BY_PROJECT, {root: true});

            let params = {
                id: projectId,
                page: 1,
                sorting: {updatedAt: 'desc'},
                pageSize: 6,
            };
            let response = await Vue.http.get(
                Routing.generate('app_api_projects_workpackages', params));
            if (response.status === 200) {
                let tasks = response.data;
                commit(types.SET_TASKS, {tasks});
            } else {
                commit(types.SET_TASKS, {tasks: []});
            }

            return response;
        } catch(e) {
            commit(types.SET_TASKS, {tasks: []});
        } finally {
            dispatch('wait/end', loading.GET_RECENT_TASKS_BY_PROJECT, {root: true});
        }
    },
    /**
     * Gets tasks from the API and commits SET_TASKS mutation
     * @param {function} commit
     * @param {array} data
     */
    getTasks({commit, state}, data) {
        let paramObject = {
            params: {
                type: 2,
            },
        };
        if (data && data.queryParams && data.queryParams.userRasci !== undefined) {
            paramObject.params.userRasci = data.queryParams.userRasci;
        }
        if (data && data.queryParams && data.queryParams.page !== undefined) {
            paramObject.params.page = data.queryParams.page;
        }
        if (state.taskFilters && state.taskFilters.trafficLight) {
            paramObject.params.trafficLight = state.taskFilters.trafficLight;
        }
        if (state.taskFilters && state.taskFilters.status) {
            paramObject.params.status = state.taskFilters.status;
        }
        if (state.taskFilters && state.taskFilters.project) {
            paramObject.params.project = state.taskFilters.project;
        }
        Vue.http
            .get(Routing.generate('app_api_workpackage_list'), paramObject)
            .then((response) => {
                if (response.status === 200) {
                    let tasks = response.data;
                    commit(types.SET_TASKS, {tasks});
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
    getTaskHistory({commit}, {id, page}) {
        if (!page) {
            page = 1;
        }

        let data = {
            params: {
                page: page,
            },
        };

        return Vue
            .http
            .get(Routing.generate('app_api_workpackage_history',
                {'id': id}), data)
            .then((response) => {
                if (response.status === 200) {
                    commit(types.ADD_TASK_HISTORY, response.data.items);
                }

                return response;
            }, (response) => {
                return response;
            })
        ;
    },
    resetTaskHistory({commit}) {
        commit(types.RESET_TASK_HISTORY);
    },
    /**
     * Creates a new task on project
     * @param {function} commit
     * @param {array} data
     * @param {Number} projectId
     * @return {object}
     */
    createNewTask({commit}, data) {
        return Vue
            .http
            .post(
                Routing.generate('app_api_project_tasks_create', {'id': data.projectId}),
                data.data
            )
            .then(
                (response) => {
                    if (response.body && response.body.error) {
                        const {messages} = response.body;
                        commit(types.SET_VALIDATION_MESSAGES, {messages});
                    } else {
                        const task = response.body;
                        commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                        commit(types.SET_TASK, {task});
                    }
                    return response;
                }
            )
        ;
    },

    /**
     * Edit an existing task
     * @param {function} commit
     * @param {array} data
     * @return {Object}
     */
    editTask({commit}, data) {
        return Vue.http
            .post(
                Routing.generate('app_api_workpackage_edit', {'id': data.taskId}),
                data.data
            ).then(
                (response) => {
                    if (response.body && response.body.error && response.body.messages) {
                        const {messages} = response.body;
                        commit(types.SET_VALIDATION_MESSAGES, {messages});
                    } else {
                        const task = response.body;
                        commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                        commit(types.SET_TASK, {task});
                    }
                    return response;
                }
            );
    },
    /**
     * Patch an existing task
     * @param {function} commit
     * @param {array} data
     * @return {Object}
     * @todo change this to a more suitable version
     */
    async patchTask({commit}, data) {
        try {
            let response = await Vue.http.patch(
                Routing.generate('app_api_workpackage_edit',
                    {'id': data.taskId}),
                data.data,
            );

            if (response.body && response.body.error) {
                const {messages} = response.body;
                commit(types.SET_VALIDATION_MESSAGES, {messages});
            } else {
                const task = response.body;
                commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                commit(types.SET_TASK, {task});
            }
            return response;
        } catch (e) {
            return e.response;
        }
    },

    /**
     * Patch an subtask
     * @param {function} commit
     * @param {array} data
     * @return {Object}
     * @todo change this to a more suitable version
     */
    async patchSubtask({commit}, data) {
        try {
            let response = await Vue.http.patch(
                Routing.generate('app_api_workpackage_edit',
                    {'id': data.taskId}),
                data.data,
            );

            if (response.body && response.body.error) {
                const {messages} = response.body;
                commit(types.SET_VALIDATION_MESSAGES, {messages});
            } else {
                const task = response.body;
                commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                commit(types.SET_SUBTASK, {task});
            }

            return response;
        } catch (e) {
            return e.response;
        }
    },

    /**
     * Imports a XML string into the store.
     *
     * @param {function} commit
     * @param {string} xml
     */
    importXMLTask({commit}, xml) {
        const task = xml2WorkPackage(xml);

        commit(types.SET_TASK, {task});
    },

    setFilters({commit}, filters) {
        commit(types.SET_TASKS_FILTERS, {filters});
    },

    async getAllTasksGrid({commit, dispatch}, {project, page}) {
        try {
            dispatch('wait/start', loading.GET_ALL_TASKS_GRID, {root: true});
            commit(types.SET_ALL_TASKS, {tasks: {}});

            let data = {
                params: {
                    criteria: {
                        type: 2,
                    },
                    page: page,
                    pageSize: 8,
                },
            };

            data.params.criteria = Object.assign(data.params.criteria, state.taskFilters);

            let response = await Vue.http.get(
                Routing.generate('app_api_projects_workpackages_by_status',
                    {'id': project}), data);
            if (response.status === 200) {
                let tasks = response.data;
                commit(types.SET_ALL_TASKS, {tasks});
            }
        } finally {
            dispatch('wait/end', loading.GET_ALL_TASKS_GRID, {root: true});
        }
    },
    /**
     * Delete subtask
     * @param {function} commit
     * @param {integer} taskId
     */
    deleteTaskSubtask({commit}, taskId) {
        Vue.http
            .delete(
                Routing.generate('app_api_workpackage_delete', {id: taskId})
            ).then((response) => {
                if (response.body && response.body.error) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                } else {
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                    commit(types.DELETE_TASK_SUBTASK, taskId);
                }
            }, (response) => {
                // implement alert response error here
            });
    },
    /**
     * Add new comment
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    addTaskComment({commit}, data) {
        return Vue
            .http
            .post(Routing.generate('app_api_workpackage_comments_create',
                {'id': data.task.id}), JSON.stringify(data.payload))
            .then((response) => {
                if (response.status === 200) {
                    return response;
                }
            }, (response) => {
                return response;
            });
    },
    /**
     * Import
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    importTask({commit}, data) {
        return Vue.http
            .post(
                Routing.generate('app_api_project_tasks_import', {'id': data.projectId}),
                data.data
            ).then(
                (response) => {
                    if (response.body && response.body.error) {
                        const {messages} = response.body;
                        commit(types.SET_VALIDATION_MESSAGES, {messages});
                    } else {
                        commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                    }
                    return response;
                },
                (response) => {
                    return response;
                }
            )
        ;
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
        state.tasksPageSize = tasks.pageSize;
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
     * Sets subtask of current task
     * @param {Object} state
     * @param {Object} task
     */
    [types.SET_SUBTASK](state, {task}) {
        let index = _.findIndex(state.currentTask.children, (child) => {
            return child.id === task.id;
        });

        if (index === -1) {
            state.currentTask.push(task);
            return;
        }

        Vue.set(state.currentTask.children, index, task);
    },
    /**
     * Add history elements
     * @param {Object} state
     * @param {Object} items
     */
    [types.SET_TASK_HISTORY](state, items) {
        state.taskHistory = items;
    },
    /**
     * Reset history elements
     * @param {Object} state
     */
    [types.RESET_TASK_HISTORY](state) {
        while (state.taskHistory.length > 0) {
            state.taskHistory.pop();
        }
    },
    /**
     * Add history elements
     * @param {Object} state
     * @param {Object} items
     */
    [types.ADD_TASK_HISTORY](state, items) {
        state.taskHistory.push(...items);
    },
    /**
     * Set the tasks filters
     * @param {Object} state
     * @param {array} filters
     */
    [types.SET_TASKS_FILTERS](state, {filters}) {
        state.taskFilters = !filters.clear ? Object.assign({}, state.taskFilters, filters) : [];
    },
    /**
     * Set all tasks
     * @param {Object} state
     * @param {array} tasks
     */
    [types.SET_ALL_TASKS](state, {tasks}) {
        state.allTasks = tasks;
    },
    /**
     * Delete a subtask
     * @param {Object} state
     * @param {number} taskId
     */
    [types.DELETE_TASK_SUBTASK](state, taskId) {
        state.currentTask.children = _.remove(state.currentTask.children, (item) => {
            return item.id !== taskId;
        });

        let decrementNeeded = false;
        state.tasks = state.tasks.filter((item) => {
            if (item.id === taskId) {
                decrementNeeded = true;
                return false;
            }
            return true;
        });

        if (decrementNeeded) {
            state.tasksCount--;
        }
    },
    /**
     * Add new cost for task
     * @param {Object} state
     * @param {Object} cost
     */
    [types.ADD_TASK_COST](state, cost) {
        state.currentTask.costs.push(cost);
    },
    /**
     * Removes a cost of a task
     * @param {Object} state
     * @param {Object} costId
     */
    [types.REMOVE_TASK_COST](state, costId) {
        state.currentTask.costs = state.currentTask.costs.filter((item) => {
            return item.id !== costId ? true : false;
        });
    },
    /**
     * Updates cost for task
     * @param {Object} state
     * @param {Object} cost
     */
    [types.SET_TASK_COST](state, cost) {
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
