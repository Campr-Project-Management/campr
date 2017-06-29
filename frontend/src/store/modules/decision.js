import Vue from 'vue';
import * as types from '../mutation-types';

const state = {};

const getters = {};

const actions = {
    /**
     * Creates a new decision
     * @param {function} commit
     * @param {array} data
     */
    createMeetingDecision({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_meeting_decisions_create', {'id': data.id}),
                data
            ).then((response) => {
                let decision = response.data;
                commit(types.ADD_MEETING_DECISION, {decision});
            }, (response) => {
            });
    },
    /**
     * Edit a decision
     * @param {function} commit
     * @param {array} data
     */
    editDecision({commit}, data) {
        Vue.http
            .patch(
                Routing.generate('app_api_decisions_edit', {'id': data.id}),
                data
            ).then((response) => {
                let decision = response.data;
                if (decision.meeting) {
                    commit(types.EDIT_MEETING_DECISION, {decision});
                }
            }, (response) => {
            });
    },
    /**
     * Delete decision
     * @param {function} commit
     * @param {array} data
     */
    deleteDecision({commit}, data) {
        Vue.http
            .delete(
                Routing.generate('app_api_decisions_delete', {id: data.id})
            ).then((response) => {
                if (data.meeting) {
                    let decisionId = data.id;
                    commit(types.DELETE_MEETING_DECISION, {decisionId});
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
