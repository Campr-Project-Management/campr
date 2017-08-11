import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    opportunityStrategies: [],
};

const getters = {
    opportunityStrategies: state => state.opportunityStrategies,
    opportunityStrategiesForSelect: state => {
        let strategiesSelect = [{'key': null, 'label': Translator.trans('placeholder.opportunity_strategy')}];
        state.opportunityStrategies.map(function(item) {
            strategiesSelect.push({'key': item.id, 'label': item.name});
        });
        return strategiesSelect;
    },
};

const actions = {
    getOpportunityStrategies({commit}, id) {
        Vue.http
            .get(Routing.generate('app_api_project_opportunity_strategies', {'id': id})).then((response) => {
                if (response.status === 200) {
                    let strategies = response.data;
                    commit(types.SET_OPPORTUNITY_STRATEGIES, {strategies});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets opportunity strategies to state
     * @param {Object} state
     * @param {array} strategies
     */
    [types.SET_OPPORTUNITY_STRATEGIES](state, {strategies}) {
        state.opportunityStrategies = strategies;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
