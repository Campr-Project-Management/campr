import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    meetingAgendas: {},
};

const getters = {
    meetingAgendas: state => state.meetingAgendas,
};

const actions = {
    /**
     * Gets meeting agendas
     * @param {function} commit
     * @param {array} data
     */
    getMeetingAgendas({commit}, data) {
        let paramObject = {params: {}};
        if (data.apiParams && data.apiParams.page !== undefined) {
            paramObject.params.page = data.apiParams.page;
        }
        Vue.http
            .get(Routing.generate('app_api_meeting_agendas_list', {'id': data.meetingId}), paramObject).then((response) => {
                if (response.status === 200) {
                    let agendas = response.data;
                    commit(types.SET_MEETING_AGENDAS, {agendas});
                }
            }, (response) => {
            });
    },
    /**
     * Creates a new meeting agenda
     * @param {function} commit
     * @param {array} data
     */
    createMeetingAgenda({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_meeting_agendas_create', {'id': data.id}),
                data
            ).then((response) => {
                if (response.body && response.body.error) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                } else {
                    let meetingAgenda = response.data;
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                    commit(types.ADD_MEETING_AGENDA, {meetingAgenda});
                }
            }, (response) => {
            });
    },
    /**
     * Edit a meeting agenda
     * @param {function} commit
     * @param {array} data
     */
    editMeetingAgenda({commit}, data) {
        Vue.http
            .patch(
                Routing.generate('app_api_meeting_agendas_edit', {'id': data.id}),
                data
            ).then((response) => {
                let meetingAgenda = response.data;
                commit(types.EDIT_MEETING_AGENDA, {meetingAgenda});
            }, (response) => {
            });
    },
    /**
     * Delete meeting agenda
     * @param {function} commit
     * @param {array} data
     */
    deleteMeetingAgenda({commit}, data) {
        Vue.http
            .delete(
                Routing.generate('app_api_meeting_agendas_delete', {id: data.id})
            ).then((response) => {
                let meetingAgendaId = data.id;
                commit(types.DELETE_MEETING_AGENDA, {meetingAgendaId});
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets meeting agendas to state
     * @param {Object} state
     * @param {array} agendas
     */
    [types.SET_MEETING_AGENDAS](state, {agendas}) {
        state.meetingAgendas = agendas;
    },
    /**
     * Add new meeting agenda
     * @param {Object} state
     * @param {Object} meetingAgenda
     */
    [types.ADD_MEETING_AGENDA](state, {meetingAgenda}) {
        if (state.meetingAgendas) {
            state.meetingAgendas.items.push(meetingAgenda);
            state.meetingAgendas.totalItems++;
        }
    },
    /**
     * Edit meeting agenda
     * @param {Object} state
     * @param {array} meetingAgenda
     */
    [types.EDIT_MEETING_AGENDA](state, {meetingAgenda}) {
        if (state.meetingAgendas) {
            state.meetingAgendas.items.map(item => {
                if (item.id === meetingAgenda.id) {
                    item.topic = meetingAgenda.topic;
                    item.responsibility = meetingAgenda.responsibility;
                    item.responsibilityAvatar = meetingAgenda.responsibilityAvatar;
                    item.responsibilityFullName = meetingAgenda.responsibilityFullName;
                    item.start = meetingAgenda.start;
                    item.end = meetingAgenda.end;
                }
            });
        }
    },
    /**
     * Delete meeting objective
     * @param {Object} state
     * @param {integer} meetingAgendaId
     */
    [types.DELETE_MEETING_AGENDA](state, {meetingAgendaId}) {
        if (state.meetingAgendas) {
            state.meetingAgendas.items = state.meetingAgendas.items.filter((item) => {
                return item.id !== meetingAgendaId;
            });
            state.meetingAgendas.totalItems--;
        }
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
