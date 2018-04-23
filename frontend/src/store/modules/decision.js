import Vue from 'vue';
import * as types from '../mutation-types';
import router from '../../router';

export const DECISION_VALIDATION_ORIGIN = 'decision';

const state = {
    currentDecision: {},
    decisions: [],
    decisionFilters: {},
};

const getters = {
    decisions: state => state.decisions,
    currentDecision: state => state.currentDecision,
    decisionFilters: state => state.decisionFilters,
};
const actions = {
    /**
     * Creates a new decision
     * @param {function} commit
     * @param {array} data
     */
    createMeetingDecision({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_meeting_decisions_create', {'id': data.id}),
                data
            ).then((response) => {
                if (response.body && response.body.error) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                    commit(types.SET_VALIDATION_ORIGIN, {DECISION_VALIDATION_ORIGIN});
                } else {
                    let decision = response.data;
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                    commit(types.SET_VALIDATION_ORIGIN, '');
                    commit(types.ADD_MEETING_DECISION, {decision});
                }
            }, (response) => {
            });
    },
    /**
     * Creates a new meeting per project
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    createDecision({commit}, data) {
        return Vue.http
            .post(
                Routing.generate('app_api_project_decisions_create', {'id': data.projectId}),
                data
            ).then((response) => {
                if (response.body && response.body.error) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                } else {
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                    router.push({name: 'project-decisions'});
                }

                return response;
            }, (response) => {
            });
    },
    /**
     * Edit a decision
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    editDecision({commit}, data) {
        return Vue.http
            .patch(
                Routing.generate('app_api_decisions_edit', {'id': data.id}),
                data
            ).then((response) => {
                let decision = response.data;
                if (response.body && response.body.error) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                } else {
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                    if (decision.meeting && !data.redirect) {
                        commit(types.EDIT_MEETING_DECISION, {decision});
                        commit(types.SET_DECISION, {decision});
                    } else {
                        router.push({name: 'project-decisions'});
                    }
                }

                return response;
            }, (response) => {
            });
    },
    /**
     * Delete decision
     * @param {function} commit
     * @param {array} data
     */
    deleteDecision({commit}, data) {
        Vue.http
            .delete(
                Routing.generate('app_api_decisions_delete', {id: data.id})
            ).then((response) => {
                let decisionId = data.id;
                if (data.meeting) {
                    commit(types.DELETE_MEETING_DECISION, {decisionId});
                } else {
                    commit(types.DELETE_DECISION, {decisionId});
                }
            }, (response) => {
            });
    },
    /**
     * Gets the decisions associated to a project
     * @param {function} commit
     * @param {array} data
     */
    getProjectDecisions({commit}, data) {
        let paramObject = {params: {}};
        if (data.queryParams && data.queryParams.page !== undefined) {
            paramObject.params.page = data.queryParams.page;
        }
        if (state.decisionFilters && state.decisionFilters.meeting) {
            paramObject.params.meeting = state.decisionFilters.meeting;
        }
        if (state.decisionFilters && state.decisionFilters.responsibility && state.decisionFilters.responsibility[0]) {
            paramObject.params.responsibility = state.decisionFilters.responsibility[0];
        }
        if (state.decisionFilters && state.decisionFilters.decisionCategory) {
            paramObject.params.decisionCategory = state.decisionFilters.decisionCategory;
        }
        if (state.decisionFilters && state.decisionFilters.statusReport) {
            paramObject.params.statusReport = state.decisionFilters.statusReport;
        }

        Vue.http
            .get(
                Routing.generate('app_api_projects_decisions', {id: data.projectId}),
                paramObject
            ).then((response) => {
                if (response.status === 200) {
                    let decisions = response.data;
                    commit(types.SET_DECISIONS, {decisions});
                }
            }, (response) => {
            });
    },
    setDecisionsFilters({commit}, filters) {
        commit(types.SET_DECISIONS_FILTERS, {filters});
    },
    /**
     * Gets a decision by UD
     * @param {function} commit
     * @param {number} id
     */
    getDecision({commit}, id) {
        Vue.http
            .get(
                Routing.generate('app_api_decisions_get', {id: id})
            ).then((response) => {
                if (response.status === 200) {
                    let decision = response.data;
                    commit(types.SET_DECISION, {decision});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets the decisions list
     * @param {Object} state
     * @param {Array} decisions
     */
    [types.SET_DECISIONS](state, {decisions}) {
        state.decisions = decisions;
    },
    /**
     * Sets the decision filters
     * @param {Object} state
     * @param {array} filters
     */
    [types.SET_DECISIONS_FILTERS](state, {filters}) {
        state.decisionFilters = !filters.clear ? Object.assign({}, state.decisionFilters, filters) : [];
    },
    /**
     * Sets decision to state
     * @param {Object} state
     * @param {Object} decision
     */
    [types.SET_DECISION](state, {decision}) {
        state.currentDecision = decision;
    },
    /**
     * Delete decision
     * @param {Object} state
     * @param {integer} decisionId
     */
    [types.DELETE_DECISION](state, {decisionId}) {
        let doDecreaseCount = false;
        if (state.decisions.items) {
            state.decisions.items = state.decisions.items.filter((item) => {
                if (item.id === decisionId) {
                    doDecreaseCount = true;
                    return false;
                };
                return true;
            });
        }
        if (doDecreaseCount) {
            state.todosCount--;
        }
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
