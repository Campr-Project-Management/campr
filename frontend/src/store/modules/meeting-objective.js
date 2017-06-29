import Vue from 'vue';
import * as types from '../mutation-types';

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
                let meetingObjective = response.data;
                commit(types.ADD_MEETING_OBJECTIVE, {meetingObjective});
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
