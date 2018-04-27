import Vue from 'vue';
import * as types from '../mutation-types';
import router from '../../router';

const state = {
    opportunities: [],
    currentOpportunity: {},
};

const getters = {
    opportunities: state => state.opportunities,
    currentOpportunity: state => state.currentOpportunity,
    currentOpportunityMeasures: state => state.currentOpportunity.measures,
};

const actions = {
    getProjectOpportunities({commit}, data) {
        Vue.http
            .get(
                Routing.generate(
                    'app_api_project_opportunities',
                    {id: data.projectId, probability: data.probability, impact: data.impact}
                )
            ).then((response) => {
                if (response.status === 200 || response.status === 204) {
                    let opportunities = response.data;
                    commit(types.SET_PROJECT_OPPORTUNITIES, {opportunities});
                }
            }, (response) => {
            });
    },
    /**
     * Gets project opportunity
     * @param {function} commit
     * @param {number} id
     */
    getProjectOpportunity({commit}, id) {
        Vue.http
            .get(Routing.generate('app_api_opportunities_get', {'id': id})).then((response) => {
                if (response.status === 200) {
                    let opportunity = response.data;
                    commit(types.SET_OPPORTUNITY, {opportunity});
                }
            }, (response) => {
            });
    },
    /**
     * Create project opportunity
     * @param {function} commit
     * @param {array}    data
     */
    createProjectOpportunity({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_project_create_opportunity', {id: data.project}),
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
     * Edit project phase
     * @param {function} commit
     * @param {array}    data
     * @return {object}
     */
    editProjectOpportunity({commit}, data) {
        return Vue.http
            .patch(
                Routing.generate('app_api_opportunities_edit', {id: data.id}),
                JSON.stringify(data)
            ).then((response) => {
                if (response.body && response.body.error) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                } else {
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                    router.push({name: 'project-opportunities-view-opportunity', params: {'opportunityId': data.id}});
                }
            }, (response) => {
            });
    },
    /**
     * Delete an opportunity
     * @param {function} commit
     * @param {integer} id
     */
    deleteProjectOpportunity({commit}, id) {
        Vue.http
            .delete(
                Routing.generate('app_api_opportunities_delete', {id: id})
            ).then((response) => {
                router.push({name: 'project-risks-and-opportunities'});
            }, (response) => {
            });
    },
    /**
     * Create opportunity measure
     * @param {function} commit
     * @param {array}    data
     * @return {object}
     */
    createOpportunityMeasure({commit}, data) {
        return Vue.http
            .post(
                Routing.generate('app_api_opportunities_create_measure', {id: data.opportunity}),
                JSON.stringify(data)
            ).then((response) => {
                if (response.body && response.body.error) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                } else {
                    let measure = response.data;
                    commit(types.ADD_MEASURE_FOR_CURRENT_OPPORTUNITY, {measure});
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                }

                return response;
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
    /**
     * Sets project opportunity to state
     * @param {Object} state
     * @param {array} opportunity
     */
    [types.SET_OPPORTUNITY](state, {opportunity}) {
        state.currentOpportunity = opportunity;
    },
    /**
     * Add opportunity measure
     * @param {Object} state
     * @param {array} measure
     */
    [types.ADD_MEASURE_FOR_CURRENT_OPPORTUNITY](state, {measure}) {
        state.currentOpportunity.measures.push(measure);
    },
    /**
     * Add opportunity measure comment
     * @param {Object} state
     * @param {array} measureComment
     */
    [types.ADD_MEASURE_COMMENT_FOR_CURRENT_OPPORTUNITY](state, {measureComment}) {
        if (state.currentOpportunity.measures) {
            state.currentOpportunity.measures.map(item => {
                if (item.id === measureComment.measure) {
                    item.comments.push(measureComment);
                }
            });
        }
    },
    /**
     * Edit measure of the current opportunity
     * @param {Object} state
     * @param {array} data
     */
    [types.EDIT_MEASURE_FOR_CURRENT_OPPORTUNITY](state, {data}) {
        if (state.currentOpportunity.measures) {
            state.currentOpportunity.measures.map(item => {
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
