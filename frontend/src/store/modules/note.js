import Vue from 'vue';
import * as types from '../mutation-types';

const NOTE_VALIDATION_ORIGIN = 'note';

const state = {};

const getters = {};

const actions = {
    /**
     * Creates a new note
     * @param {function} commit
     * @param {array} data
     */
    createMeetingNote({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_meeting_notes_create', {'id': data.id}),
                data
            ).then((response) => {
                if (response.body && response.body.error) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                    commit(types.SET_VALIDATION_ORIGIN, {NOTE_VALIDATION_ORIGIN});
                } else {
                    let note = response.data;
                    commit(types.ADD_MEETING_NOTE, {note});
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                    commit(types.SET_VALIDATION_ORIGIN, '');
                }
            }, (response) => {
            });
    },
    /**
     * Edit a note
     * @param {function} commit
     * @param {array} data
     */
    editNote({commit}, data) {
        Vue.http
            .patch(
                Routing.generate('app_api_notes_edit', {'id': data.id}),
                data
            ).then((response) => {
                let note = response.data;
                if (note.meeting) {
                    commit(types.EDIT_MEETING_NOTE, {note});
                }
            }, (response) => {
            });
    },
    /**
     * Delete note
     * @param {function} commit
     * @param {array} data
     */
    deleteNote({commit}, data) {
        Vue.http
            .delete(
                Routing.generate('app_api_notes_delete', {id: data.id})
            ).then((response) => {
                if (data.meeting) {
                    let noteId = data.id;
                    commit(types.DELETE_MEETING_NOTE, {noteId});
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
