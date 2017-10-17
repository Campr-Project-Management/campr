import Vue from 'vue';
import * as types from '../mutation-types';
import _ from 'lodash';

const state = {
    riskStrategies: [],
};

const getters = {
    riskStrategies: state => state.riskStrategies,
    riskStrategiesForSelect: state => {
        let strategiesSelect = [];
        _.isArray(state.riskStrategies) && state.riskStrategies.map(function(item) {
            strategiesSelect.push({'key': item.id, 'label': item.name});
        });
        return strategiesSelect;
    },
};

const actions = {
    getRiskStrategies({commit}, id) {
        Vue.http
            .get(Routing.generate('app_api_project_risk_strategies', {'id': id})).then((response) => {
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
