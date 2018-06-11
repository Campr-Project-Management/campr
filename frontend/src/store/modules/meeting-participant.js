import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    meetingParticipants: [],
};

const getters = {
    meetingParticipants: state => state.meetingParticipants,
};

const actions = {
    /**
     * Retrive meeting participants
     * @param {function} commit
     * @param {array} data
     */
    getMeetingParticipants({commit}, data) {
        Vue.http
            .get(Routing.generate('app_api_meeting_participants', {'id': data.id})).then((response) => {
                if (response.status === 200) {
                    let participants = response.data;
                    commit(types.SET_MEETING_PARTICIPANTS, {participants});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets participants to state
     * @param {Object} state
     * @param {array} participants
     */
    [types.SET_MEETING_PARTICIPANTS](state, {participants}) {
        state.meetingParticipants = participants;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
