import Vue from 'vue';
import * as types from '../mutation-types';

// constants!
export const PROJECT_STATUS_NOT_STARTED = 1;
export const PROJECT_STATUS_IN_PROGRESS = 2;
export const PROJECT_STATUS_PENDING = 3;
export const PROJECT_STATUS_OPEN = 4;
export const PROJECT_STATUS_CLOSED = 5;

const state = {
    projectStatuses: [],
};

const getters = {
    projectStatuses: state => state.projectStatuses,
    projectStatusesForFilter: function(state) {
        let projectStatusesForFilter = [{'key': '', 'label': Translator.trans('message.all_statuses')}];
        state.projectStatuses.map(function(projectStatus) {
            projectStatusesForFilter.push({'key': projectStatus.id, 'label': projectStatus.name});
        });

        return projectStatusesForFilter;
    },
    projectStatusesForSelect: state => state.projectStatuses.map(projectStatus => {
        return {
            'key': projectStatus.id,
            'label': projectStatus.name,
        };
    }),
};

const actions = {
    /**
     * Get all project statuses
     * @param {function} commit
     */
    getProjectStatuses({commit}) {
        Vue.http
            .get(Routing.generate('app_api_project_statuses_list')).then((response) => {
                if (response.status === 200) {
                    let projectStatuses = response.data;
                    commit(types.SET_PROJECT_STATUSES, {projectStatuses});
                }
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
        state.projectStatuses = projectStatuses;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
