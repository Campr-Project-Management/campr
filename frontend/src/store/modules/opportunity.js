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
                if (response.status === 201) {
                    router.push({name: 'project-risks-and-opportunities'});
                }
            }, (response) => {
            });
    },
    /**
     * Edit project phase
     * @param {function} commit
     * @param {array}    data
     */
    editProjectOpportunity({commit}, data) {
        Vue.http
            .patch(
                Routing.generate('app_api_opportunities_edit', {id: data.id}),
                JSON.stringify(data)
            ).then((response) => {
                if (response.status === 202) {
                    router.push({name: 'project-opportunities-view-opportunity', params: {'opportunityId': data.id}});
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
    /**
     * Sets project opportunity to state
     * @param {Object} state
     * @param {array} opportunity
     */
    [types.SET_OPPORTUNITY](state, {opportunity}) {
        state.currentOpportunity = opportunity;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
