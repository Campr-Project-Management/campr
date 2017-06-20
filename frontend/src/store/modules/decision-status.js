import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    decisionStatuses: [],
};

const getters = {
    decisionStatuses: state => state.decisionStatuses,
    decisionStatusesForSelect: state => {
        let statusesSelect = [{'key': null, 'label': Translator.trans('placeholder.status')}];
        state.decisionStatuses.map(function(item) {
            statusesSelect.push({'key': item.id, 'label': item.name});
        });
        return statusesSelect;
    },
};

const actions = {
    getDecisionStatuses({commit}) {
        Vue.http
            .get(Routing.generate('app_api_decision_statuses_list')).then((response) => {
                if (response.status === 200) {
                    let statuses = response.data;
                    commit(types.SET_DECISION_STATUSES, {statuses});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets decision statuses to state
     * @param {Object} state
     * @param {array} statuses
     */
    [types.SET_DECISION_STATUSES](state, {statuses}) {
        state.decisionStatuses = statuses;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
