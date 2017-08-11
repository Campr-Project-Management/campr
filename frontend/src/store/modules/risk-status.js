import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    riskStatuses: [],
};

const getters = {
    riskStatuses: state => state.riskStatuses,
    riskStatusesForSelect: state => {
        let statusesSelect = [{'key': null, 'label': Translator.trans('placeholder.risk_status')}];
        state.riskStatuses.map(function(item) {
            statusesSelect.push({'key': item.id, 'label': item.name});
        });
        return statusesSelect;
    },
};

const actions = {
    getRiskStatuses({commit}, id) {
        Vue.http
            .get(Routing.generate('app_api_project_risk_statuses', {'id': id})).then((response) => {
                if (response.status === 200) {
                    let statuses = response.data;
                    commit(types.SET_RISK_STATUSES, {statuses});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets opportunity statuses to state
     * @param {Object} state
     * @param {array} statuses
     */
    [types.SET_RISK_STATUSES](state, {statuses}) {
        state.riskStatuses = statuses;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
