import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    opportunityStatuses: [],
};

const getters = {
    opportunityStatuses: state => state.opportunityStatuses,
    opportunityStatusesForSelect: state => {
        let statusesSelect = [];
        state.opportunityStatuses.map(function(item) {
            statusesSelect.push({'key': item.id, 'label': item.name});
        });
        return statusesSelect;
    },
};

const actions = {
    getOpportunityStatuses({commit}, id) {
        Vue.http
            .get(Routing.generate('app_api_project_opportunity_statuses', {'id': id})).then((response) => {
                if (response.status === 200) {
                    let statuses = response.data;
                    commit(types.SET_OPPORTUNITY_STATUSES, {statuses});
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
    [types.SET_OPPORTUNITY_STATUSES](state, {statuses}) {
        state.opportunityStatuses = statuses;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
