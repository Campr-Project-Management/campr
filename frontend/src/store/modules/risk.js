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
    measures: state => state.currentRisk.measures,
};

const actions = {
    getProjectRisks({commit}, data) {
        Vue.http
            .get(
                Routing.generate(
                    'app_api_project_risks',
                    {id: data.projectId, probability: data.probability, impact: data.impact}
                )
            ).then((response) => {
                if (response.status === 200 || response.status === 204) {
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
                if (response.body && response.body.error) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                } else {
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
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
                if (response.body && response.body.error) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                } else {
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                    router.push({name: 'project-risks-view-risk', params: {'riskId': data.id}});
                }
            }, (response) => {
            });
    },
    /**
     * Delete a risk
     * @param {function} commit
     * @param {integer} id
     */
    deleteProjectRisk({commit}, id) {
        Vue.http
            .delete(
                Routing.generate('app_api_risks_delete', {id: id})
            ).then((response) => {
                router.push({name: 'project-risks-and-opportunities'});
            }, (response) => {
            });
    },
    /**
     * Create risk measure
     * @param {function} commit
     * @param {array}    data
     * @return {object}
     */
    createRiskMeasure({commit}, data) {
        return Vue.http
            .post(
                Routing.generate('app_api_risks_create_measure', {id: data.risk}),
                JSON.stringify(data)
            ).then((response) => {
                if (response.body && response.body.error) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                } else {
                    let measure = response.data;
                    commit(types.ADD_MEASURE_FOR_CURRENT_RISK, {measure});
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                }

                return response;
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
    /**
     * Add risk measure
     * @param {Object} state
     * @param {array} measure
     */
    [types.ADD_MEASURE_FOR_CURRENT_RISK](state, {measure}) {
        state.currentRisk.measures.push(measure);
    },
    /**
     * Add risk measure comment
     * @param {Object} state
     * @param {array} measureComment
     */
    [types.ADD_MEASURE_COMMENT_FOR_CURRENT_RISK](state, {measureComment}) {
        if (state.currentRisk.measures) {
            state.currentRisk.measures.map(item => {
                if (item.id === measureComment.measure) {
                    item.comments.push(measureComment);
                }
            });
        }
    },
    /**
     * Edit measure of the current risk
     * @param {Object} state
     * @param {array} data
     */
    [types.EDIT_MEASURE_FOR_CURRENT_RISK](state, {data}) {
        if (state.currentRisk.measures) {
            state.currentRisk.measures.map(item => {
                if (item.id === data.id) {
                    item.title = data.title;
                    item.description = data.description;
                    item.cost = data.cost;
                }
            });
        }
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
