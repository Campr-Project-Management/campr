import Vue from 'vue';
import * as types from '../mutation-types';

const state = {};

const getters = {};

const actions = {
    /**
     * Creates a new lesson
     * @param {function} commit
     * @param {array} data
     *
     * @return {object}
     */
    createLesson({commit}, data) {
        return Vue.http
                .post(
                    Routing.generate('app_api_project_close_down_lessons_create', {'id': data.closeDownId}),
                    data
                ).then((response) => {
                    if (response.body && response.body.error) {
                        const {messages} = response.body;
                        messages.lessonForm = true;
                        commit(types.SET_VALIDATION_MESSAGES, {messages});
                    } else {
                        let lesson = response.body;
                        commit(types.ADD_LESSON, {lesson});
                        commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                    }
                }, (response) => {
                });
    },
    /**
     * Edit a lesson
     * @param {function} commit
     * @param {array} data
     *
     * @return {object}
     */
    editLesson({commit}, data) {
        return Vue.http
            .patch(
                Routing.generate('app_api_lessons_edit', {'id': data.itemId}),
                JSON.stringify(data)
            )
        ;
    },
    /**
     * Reorder lessons
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    reorderLessons({commit}, data) {
        return Vue
            .http
            .patch(
                Routing.generate('app_api_app_api_lessons_reorder'),
                JSON.stringify(data)
            )
        ;
    },
};

const mutations = {};

export default {
    state,
    getters,
    actions,
    mutations,
};
