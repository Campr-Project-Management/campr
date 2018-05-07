import Vue from 'vue';
import * as types from '../mutation-types';
import router from '../../router';

const state = {
    statusReports: [],
    statusReportFilters: {},
    currentStatusReport: {},
    generatedStatusReport: {},
    statusReportTrendGraph: [],
};

const getters = {
    statusReports: state => state.statusReports,
    statusReportFilters: state => state.statusReportFilters,
    currentStatusReport: state => state.currentStatusReport,
    generatedStatusReport: state => state.generatedStatusReport,
    statusReportTrendGraph: state => state.statusReportTrendGraph,
};

const actions = {
    getGeneratedStatusReport({commit}, id) {
        return Vue.http.get(
            Routing.generate('app_api_project_status_reports_generate',
                {'id': id}),
        ).then((response) => {
            commit(types.SET_GENERATED_STATUS_REPORT, response.body);

            return response;
        }, (response) => {
        });
    },

    /**
     * Creates a new status report
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    createStatusReport({commit}, {id, data}) {
        return Vue.http
            .post(
                Routing.generate('app_api_project_status_reports_create', {'id': id}),
                data
            ).then((response) => {
                if (response.body && response.body.error) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                } else {
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                    router.push({name: 'project-status-reports'});
                }

                return response;
            }, (response) => {
            });
    },
    /**
     * Gets the todos associated to a product
     * @param {function} commit
     * @param {array} data
     */
    getProjectStatusReports({commit, state}, data) {
        let paramObject = {params: {}};
        if (data.queryParams && data.queryParams.page !== undefined) {
            paramObject.params.page = data.queryParams.page;
        }
        if (data.queryParams && data.queryParams.trend !== undefined) {
            paramObject.params.trend = data.queryParams.trend;
        }
        if (state.statusReportFilters && state.statusReportFilters.createdBy && state.statusReportFilters.createdBy[0]) {
            paramObject.params.createdBy = state.statusReportFilters.createdBy[0];
        }
        if (state.statusReportFilters && state.statusReportFilters.date) {
            paramObject.params.date = state.statusReportFilters.date;
        }
        Vue.http
            .get(
                Routing.generate('app_api_project_status_reports', {id: data.projectId}),
                paramObject,
            ).then((response) => {
                if (response.status === 200) {
                    let statusReports = response.data;
                    commit(types.SET_STATUS_REPORTS, {statusReports});
                } else {
                    commit(types.SET_STATUS_REPORTS, {statusReports: []});
                }
            }, () => {
                commit(types.SET_STATUS_REPORTS, {statusReports: []});
            });
    },
    /**
     * Get status report by id
     * @param {function} commit
     * @param {integer} id
     * @return {object}
     */
    getStatusReport({commit, state}, id) {
        return Vue.http.get(
            Routing.generate('app_api_status_reports_get', {id: id}),
        ).then((response) => {
            if (response.status === 200) {
                let report = response.data;
                commit(types.SET_STATUS_REPORT, {report});
            }
        }, (response) => {
        });
    },
    /**
     * Email status report to special distribution list
     * @param {function} commit
     * @param {integer} id
     *
     * @return {object}
     */
    emailStatusReport({commit}, id) {
        return Vue.http
            .get(
                Routing.generate('app_api_status_reports_email', {id: id})
            ).then((response) => {
            }, (response) => {
            });
    },
    setStatusReportFilters({commit}, filters) {
        commit(types.SET_STATUS_REPORT_FILTERS, {filters});
    },
    /**
     * Get status report by id
     * @param {function} commit
     * @param {number} id
     * @return {object}
     */
    getStatusReportTrendGraph({commit}, id) {
        return Vue.http.get(
            Routing.generate('app_api_project_status_reports_trend_graph',
                {id: id}),
        ).then((response) => {
            if (response.status === 200) {
                commit(types.SET_STATUS_REPORT_TREND_GRAPH, response.data);
            }
        }, (response) => {
        });
    },
};

const mutations = {
    /**
     * Sets the status reports list
     * @param {Object} state
     * @param {Array} decisions
     */
    [types.SET_STATUS_REPORTS](state, {statusReports}) {
        state.statusReports = statusReports;
    },
    /**
     * Sets status report to state
     * @param {Object} state
     * @param {Object} decision
     */
    [types.SET_STATUS_REPORT](state, {report}) {
        state.currentStatusReport = report;
    },
    /**
     * Sets generated status report
     * @param {Object} state
     * @param {Object} report
     */
    [types.SET_GENERATED_STATUS_REPORT](state, report) {
        state.generatedStatusReport = report;
    },
    /**
     * Sets the decision filters
     * @param {Object} state
     * @param {array} filters
     */
    [types.SET_STATUS_REPORT_FILTERS](state, {filters}) {
        state.statusReportFilters = !filters.clear ? Object.assign({}, state.statusReportFilters, filters) : [];
    },

    /**
     * Sets the decision filters
     * @param {Object} state
     * @param {array} statusReportTrendGraph
     */
    [types.SET_STATUS_REPORT_TREND_GRAPH](state, statusReportTrendGraph) {
        state.statusReportTrendGraph = statusReportTrendGraph;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
