import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    riskStrategies: [],
};

const getters = {
    riskStrategies: state => state.riskStrategies,
    riskStrategiesForSelect: state => {
        let strategiesSelect = [{'key': null, 'label': Translator.trans('placeholder.risk_strategy')}];
        state.riskStrategies.map(function(item) {
            strategiesSelect.push({'key': item.id, 'label': item.name});
        });
        return strategiesSelect;
    },
};

const actions = {
    getRiskStrategies({commit}) {
        Vue.http
            .get(Routing.generate('app_api_risk_strategies_list')).then((response) => {
                if (response.status === 200) {
                    let strategies = response.data;
                    commit(types.SET_RISK_STRATEGIES, {strategies});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets risk strategies to state
     * @param {Object} state
     * @param {array} strategies
     */
    [types.SET_RISK_STRATEGIES](state, {strategies}) {
        state.riskStrategies = strategies;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
