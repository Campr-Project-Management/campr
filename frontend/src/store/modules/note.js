import Vue from 'vue';
import * as types from '../mutation-types';

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
                let note = response.data;
                commit(types.ADD_MEETING_NOTE, {note});
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
