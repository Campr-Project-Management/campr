import Vue from 'vue';
import * as types from '../mutation-types';
import router from '../../router';

export const TODO_VALIDATION_ORIGIN = 'todo';

const state = {
    currentTodo: {},
    todos: [],
    todosCount: 0,
    todoFilters: {},
};

const getters = {
    currentTodo: state => state.currentTodo,
    todos: state => state.todos,
    todosCount: state => state.todosCount,
    todoFilters: state => state.todoFilters,
};

const actions = {
    /**
     * Creates a new todo
     * @param {function} commit
     * @param {array} data
     */
    createMeetingTodo({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_meeting_todos_create', {'id': data.id}),
                data
            ).then((response) => {
                if (response.body && response.body.error) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                    commit(types.SET_VALIDATION_ORIGIN, TODO_VALIDATION_ORIGIN);
                } else {
                    let todo = response.data;
                    commit(types.ADD_MEETING_TODO, {todo});
                    commit(types.SET_VALIDATION_ORIGIN, '');
                }
            }, (response) => {
            });
    },
    /**
     * Creates a new todo per project
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    createTodo({commit}, data) {
        return Vue.http.post(
            Routing.generate('app_api_projects_todo_create',
                {'id': data.projectId}),
            data.data,
        ).then((response) => {
            if (response.body && response.body.error) {
                const {messages} = response.body;
                commit(types.SET_VALIDATION_MESSAGES, {messages});

                return response;
            }

            const todo = response.body;
            commit(types.SET_VALIDATION_MESSAGES, {messages: []});
            commit(types.SET_TODO, {todo});
            router.push({name: 'project-todos'});

            return response;
        }, (response) => {
        });
    },
    /**
     * Edit a todo
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    editTodo({commit}, data) {
        return Vue.http
            .patch(
                Routing.generate('app_api_todos_edit', {'id': data.id}),
                data
            ).then((response) => {
                if (response.body && response.body.error) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});

                    return response;
                }

                const todo = response.body;
                if (todo.meeting) {
                    commit(types.EDIT_MEETING_TODO, {todo});
                } else {
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                    commit(types.SET_TODO, {todo});
                    router.push({name: 'project-todos'});
                }

                return response;
            }, (response) => {
            });
    },
    /**
     * Delete todo
     * @param {function} commit
     * @param {array} data
     */
    deleteTodo({commit}, data) {
        Vue.http
            .delete(
                Routing.generate('app_api_todos_delete', {id: data.id})
            ).then((response) => {
                let todoId = data.id;
                if (data.meeting) {
                    commit(types.DELETE_MEETING_TODO, {todoId});
                } else {
                    commit(types.DELETE_TODO, {todoId});
                }
            }, (response) => {
            });
    },
    /**
     * Gets a todo by ID from the API
     * @param {function} commit
     * @param {number} id
     */
    getTodoById({commit}, id) {
        Vue.http
            .get(
                 Routing.generate('app_api_todos_get', {id: id})
            ).then((response) => { // remove this
                if (response.status === 200) {
                    let todo = response.data;
                    commit(types.SET_TODO, {todo});
                }
            }, (response) => {
            });
    },
    /**
     * Gets the todos associated to a product
     * @param {function} commit
     * @param {array} data
     */
    getProjectTodos({commit, state}, data) {
        let paramObject = {params: {}};
        if (data && data.queryParams && data.queryParams.page !== undefined) {
            paramObject.params.page = data.queryParams.page;
        }
        if (state.todoFilters && state.todoFilters.status) {
            paramObject.params.status = state.todoFilters.status;
        }
        if (state.todoFilters && state.todoFilters.dueDate) {
            paramObject.params.dueDate = state.todoFilters.dueDate;
        }
        if (state.todoFilters && state.todoFilters.responsibility && state.todoFilters.responsibility[0]) {
            paramObject.params.responsibility = state.todoFilters.responsibility[0];
        }
        if (state.todoFilters && state.todoFilters.todoCategory) {
            paramObject.params.todoCategory = state.todoFilters.todoCategory;
        }
        if (state.todoFilters && state.todoFilters.statusReport) {
            paramObject.params.statusReport = state.todoFilters.statusReport;
        }
        Vue.http
            .get(
                Routing.generate('app_api_projects_todos', {id: data.projectId}),
                paramObject,
            ).then((response) => {
                if (response.status === 200) {
                    let todos = response.data;
                    commit(types.SET_TODOS, {todos});
                } else {
                    commit(types.SET_TODOS, {todos: []});
                }
            }, () => {
                commit(types.SET_TODOS, {todos: []});
            });
    },
    setTodosFilters({commit}, filters) {
        commit(types.SET_TODOS_FILTERS, {filters});
    },
};

const mutations = {
    /**
     * Sets todo to state
     * @param {Object} state
     * @param {Object} todo
     */
    [types.SET_TODO](state, {todo}) {
        state.currentTodo = todo;
    },
    /**
     * Sets the current selection of todos to state
     * @param {Object} state
     * @param {Array} todos
     */
    [types.SET_TODOS](state, {todos}) {
        state.todos = todos;
    },
    /**
     * Delete  todo
     * @param {Object} state
     * @param {integer} todoId
     */
    [types.DELETE_TODO](state, {todoId}) {
        let doDecreaseCount = false;
        if (state.todos.items) {
            state.todos.items = state.todos.items.filter((item) => {
                if (item.id === todoId) {
                    doDecreaseCount = true;
                    return false;
                };
                return true;
            });
        }
        if (doDecreaseCount) {
            state.todosCount--;
        }
    },
    /**
     * Sets the todo filters
     * @param {Object} state
     * @param {array} filters
     */
    [types.SET_TODOS_FILTERS](state, {filters}) {
        state.todoFilters = !filters.clear ? Object.assign({}, state.todoFilters, filters) : [];
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
