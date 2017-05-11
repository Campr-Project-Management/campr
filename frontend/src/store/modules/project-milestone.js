import Vue from 'vue';
import * as types from '../mutation-types';
import router from '../../router';

const state = {
    items: [],
    currentItem: {},
};

const getters = {
    projectMilestones: state => state.items,
    milestone: state => state.currentItem,
    projectMilestonesForSelect: state => {
        return state.items.items.map(item => {
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
    /**
     * Create project milestone
     * @param {function} commit
     * @param {array}    data
     */
    createProjectMilestone({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_project_milestones_create', {id: data.project}),
                JSON.stringify(data)
            ).then((response) => {
                if (response.status === 201) {
                    router.push({name: 'project-phases-and-milestones'});
                }
            }, (response) => {
            });
    },
    /**
     * Edit project milestone
     * @param {function} commit
     * @param {array}    data
     */
    editProjectMilestone({commit}, data) {
        Vue.http
            .patch(
                Routing.generate('app_api_workpackage_milestone_edit', {id: data.id}),
                JSON.stringify(data)
            ).then((response) => {
                if (response.status === 202) {
                    router.push({name: 'project-phases-and-milestones'});
                }
            }, (response) => {
            });
    },
    /**
     * Gets project milestone
     * @param {function} commit
     * @param {number} id
     */
    getProjectMilestone({commit}, id) {
        Vue.http
            .get(Routing.generate('app_api_workpackage_get', {'id': id})).then((response) => {
                if (response.status === 200) {
                    let milestone = response.data;
                    commit(types.SET_MILESTONE, {milestone});
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
    /**
     * Sets project milestone to state
     * @param {Object} state
     * @param {Object} milestone
     */
    [types.SET_MILESTONE](state, {milestone}) {
        state.currentItem = milestone;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
