import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    opportunities: [],
};

const getters = {
    opportunities: state => state.opportunities,
};

const actions = {
    getProjectOpportunities({commit}, data) {
        Vue.http
            .get(Routing.generate('app_api_project_opportunities', {id: data.projectId})).then((response) => {
                if (response.status === 200) {
                    let opportunities = response.data;
                    commit(types.SET_PROJECT_OPPORTUNITIES, {opportunities});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets project opportunities to state
     * @param {Object} state
     * @param {array} opportunities
     */
    [types.SET_PROJECT_OPPORTUNITIES](state, {opportunities}) {
        state.opportunities = opportunities;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
