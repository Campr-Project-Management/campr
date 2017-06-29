import Vue from 'vue';
import * as types from '../mutation-types';

const state = {};

const getters = {};

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
                let todo = response.data;
                commit(types.ADD_MEETING_TODO, {todo});
            }, (response) => {
            });
    },
    /**
     * Edit a todo
     * @param {function} commit
     * @param {array} data
     */
    editTodo({commit}, data) {
        Vue.http
            .patch(
                Routing.generate('app_api_todos_edit', {'id': data.id}),
                data
            ).then((response) => {
                let todo = response.data;
                if (todo.meeting) {
                    commit(types.EDIT_MEETING_TODO, {todo});
                }
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
                if (data.meeting) {
                    let todoId = data.id;
                    commit(types.DELETE_MEETING_TODO, {todoId});
                }
            }, (response) => {
            });
    },
};

const mutations = {
};

export default {
    state,
    getters,
    actions,
    mutations,
};
