import Vue from 'vue';
import * as types from '../mutation-types';

export const MEETING_OBJECTIVE_VALIDATION_ORIGIN = 'meeting-objective';

const state = {};

const getters = {};

const actions = {
    /**
     * Creates a new meeting objective
     * @param {function} commit
     * @param {array} data
     */
    createMeetingObjective({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_meeting_objectives_create', {'id': data.id}),
                data
            ).then((response) => {
                if (response.body && response.body.error) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                    alert(MEETING_OBJECTIVE_VALIDATION_ORIGIN);
                    commit(types.SET_VALIDATION_ORIGIN, {MEETING_OBJECTIVE_VALIDATION_ORIGIN});
                } else {
                    let meetingObjective = response.data;
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                    commit(types.ADD_MEETING_OBJECTIVE, {meetingObjective});
                    commit(types.SET_VALIDATION_ORIGIN, '');
                }
            }, (response) => {
            });
    },
    /**
     * Edit a meeting objective
     * @param {function} commit
     * @param {array} data
     */
    editMeetingObjective({commit}, data) {
        Vue.http
            .patch(
                Routing.generate('app_api_meeting_objectives_edit', {'id': data.id}),
                data
            ).then((response) => {
                let meetingObjective = response.data;
                commit(types.EDIT_MEETING_OBJECTIVE, {meetingObjective});
            }, (response) => {
            });
    },
    /**
     * Delete meeting objective
     * @param {function} commit
     * @param {array} data
     */
    deleteMeetingObjective({commit}, data) {
        Vue.http
            .delete(
                Routing.generate('app_api_meeting_objectives_delete', {id: data.id})
            ).then((response) => {
                let meetingObjectiveId = data.id;
                commit(types.DELETE_MEETING_OBJECTIVE, {meetingObjectiveId});
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
