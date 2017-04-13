import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    items: [],
};

const getters = {
    projectMilestones: state => state.items,
    projectMilestonesForSelect: state => {
        return state.items.map(item => {
            return {
                'key': item.id,
                'label': item.name,
                'parent': item.parent,
            };
        });
    },
};

const actions = {
    /**
     * Get all project milestones
     * @param {function} commit
     * @param {Number} projectId
     */
    getProjectMilestones({commit}, projectId) {
        Vue.http
            .get(Routing.generate('app_api_project_milestones', {'id': projectId})).then((response) => {
                if (response.status === 200) {
                    let milestones = response.data;
                    commit(types.SET_PROJECT_MILESTONES, {milestones});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets project milestones to state
     * @param {Object} state
     * @param {array} milestones
     */
    [types.SET_PROJECT_MILESTONES](state, {milestones}) {
        state.items = milestones;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
