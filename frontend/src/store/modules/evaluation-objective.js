import Vue from 'vue';
import * as types from '../mutation-types';

const state = {};

const getters = {};

const actions = {
    /**
     * Creates a new evaluation objective
     * @param {function} commit
     * @param {array} data
     *
     * @return {object}
     */
    createEvaluationObjective({commit}, data) {
        return Vue.http
                .post(
                    Routing.generate('app_api_project_close_down_evaluation_objectives_create', {'id': data.closeDownId}),
                    data
                ).then((response) => {
                    if (response.body && response.body.error) {
                        const {messages} = response.body;
                        messages.evaluationForm = true;
                        commit(types.SET_VALIDATION_MESSAGES, {messages});
                    } else {
                        let evaluationObjective = response.body;
                        commit(types.ADD_EVALUATION_OBJECTIVE, {evaluationObjective});
                        commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                    }
                }, (response) => {
                });
    },
    /**
     * Edit an evaluation objective
     * @param {function} commit
     * @param {array} data
     *
     * @return {object}
     */
    editEvaluationObjective({commit}, data) {
        return Vue.http
            .patch(
                Routing.generate('app_api_evaluation_objectives_edit', {'id': data.itemId}),
                JSON.stringify(data)
            )
        ;
    },
    /**
     * Reorder evaluation objectives
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    reorderEvaluationObjectives({commit}, data) {
        return Vue
            .http
            .patch(
                Routing.generate('app_api_app_api_evaluation_objectives_reorder'),
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
