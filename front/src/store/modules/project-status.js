import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    items: [],
};

const getters = {
    projectStatuses: state => state.items,
    projectStatusesForFilter: function(state) {
        let projectStatusesForFilter = [{'key': '', 'label': 'All Statuses'}];
        state.items.map(function(projectStatus) {
            projectStatusesForFilter.push({'key': projectStatus.id, 'label': projectStatus.name});
        });

        return projectStatusesForFilter;
    },
};

const actions = {
    /**
     * Get all project statuses
     * @param {function} commit
     */
    getProjectStatuses({commit}) {
        Vue.http
            .get(Routing.generate('app_api_project_statuses_list')).then((response) => {
                let projectStatuses = response.data;
                commit(types.SET_PROJECT_STATUSES, {projectStatuses});
            }, (response) => {

            });
    },
};

const mutations = {
    /**
     * Sets project statuses to state
     * @param {Object} state
     * @param {array} projectStatuses
     */
    [types.SET_PROJECT_STATUSES](state, {projectStatuses}) {
        state.items = projectStatuses;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
