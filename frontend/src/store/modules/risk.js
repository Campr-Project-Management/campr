import Vue from 'vue';
import * as types from '../mutation-types';
import router from '../../router';

const state = {
    risks: [],
    currentRisk: {},
};

const getters = {
    risks: state => state.risks,
    currentRisk: state => state.currentRisk,
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
    /**
     * Gets project risk
     * @param {function} commit
     * @param {number} id
     */
    getProjectRisk({commit}, id) {
        Vue.http
            .get(Routing.generate('app_api_risks_get', {'id': id})).then((response) => {
                if (response.status === 200) {
                    let risk = response.data;
                    commit(types.SET_RISK, {risk});
                }
            }, (response) => {
            });
    },
    /**
     * Create project risk
     * @param {function} commit
     * @param {array}    data
     */
    createProjectRisk({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_project_create_risk', {id: data.project}),
                JSON.stringify(data)
            ).then((response) => {
                if (response.status === 201) {
                    router.push({name: 'project-risks-and-opportunities'});
                }
            }, (response) => {
            });
    },
    /**
     * Edit project risk
     * @param {function} commit
     * @param {array}    data
     */
    editProjectRisk({commit}, data) {
        Vue.http
            .patch(
                Routing.generate('app_api_risks_edit', {id: data.id}),
                JSON.stringify(data)
            ).then((response) => {
                if (response.status === 202) {
                    router.push({name: 'project-risks-view-risk', params: {'riskId': data.id}});
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
    /**
     * Sets project risk to state
     * @param {Object} state
     * @param {array} risk
     */
    [types.SET_RISK](state, {risk}) {
        state.currentRisk = risk;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
