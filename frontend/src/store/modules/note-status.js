import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    noteStatuses: [],
};

const getters = {
    noteStatuses: state => state.noteStatuses,
    noteStatusesForSelect: state => {
        let statusesSelect = [];
        state.noteStatuses.map(function(item) {
            statusesSelect.push({'key': item.id, 'label': item.name});
        });
        return statusesSelect;
    },
};

const actions = {
    getNoteStatuses({commit}) {
        Vue.http
            .get(Routing.generate('app_api_note_statuses_list')).then((response) => {
                if (response.status === 200) {
                    let statuses = response.data;
                    commit(types.SET_NOTE_STATUSES, {statuses});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets note statuses to state
     * @param {Object} state
     * @param {array} statuses
     */
    [types.SET_NOTE_STATUSES](state, {statuses}) {
        state.noteStatuses = statuses;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
