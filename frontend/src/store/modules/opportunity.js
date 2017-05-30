import Vue from 'vue';
import * as types from '../mutation-types';
import router from '../../router';

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
                    router.push({name: 'project-phases-and-milestones'});
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
