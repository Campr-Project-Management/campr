import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    risks: [],
};

const getters = {
    risks: state => state.risks,
};

const actions = {
    getProjectRisks({commit}, data) {
        Vue.http
            .get(Routing.generate('app_api_project_risks', {id: data.projectId})).then((response) => {
                if (response.status === 200) {
                    let risks = response.data;
                    commit(types.SET_PROJECT_RISKS, {risks});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets project risks to state
     * @param {Object} state
     * @param {array} risks
     */
    [types.SET_PROJECT_RISKS](state, {risks}) {
        state.risks = risks;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
