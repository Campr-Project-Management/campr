import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    lastMeetingReport: {},
};

const getters = {
    lastMeetingReport: state => state.lastMeetingReport,
};

const actions = {
    /**
     * Gets last meeting report
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    getLastMeetingReport({commit}, data) {
        return Vue.http
            .get(Routing.generate('app_api_meeting_reports_last', {'id': data.meetingId})).then((response) => {
                if (response.status === 200) {
                    let report = response.data;
                    commit(types.SET_LAST_MEETING_REPORT, {report});
                }
                return response;
            }, (response) => {
                return response;
            });
    },

};

const mutations = {
    /**
     * Sets last meeting report to state
     * @param {Object} state
     * @param {array} report
     */
    [types.SET_LAST_MEETING_REPORT](state, {report}) {
        state.lastMeetingReport = report;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
