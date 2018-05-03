import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    projectColorStatuses: [],
};

const getters = {
    projectColorStatuses: state => state.projectColorStatuses,
    projectColorStatusesForSelect: state =>
        [{'key': '', 'label': Translator.trans('message.all_statuses')}].concat(
            state.projectColorStatuses.map(colorStatus => ({'key': colorStatus.id, 'label': colorStatus.name}))
        ),
};

const actions = {
    /**
     * Gets all project color statuses
     * @param {function} commit
     */
    getProjectColorStatuses({commit}) {
        Vue.http
            .get(Routing.generate('app_api_project_color_status_list')).then((response) => {
                if (response.status === 200) {
                    let projectColorStatuses = response.data;
                    commit(types.SET_PROJECT_COLOR_STATUSES, {projectColorStatuses});
                }
            }, (response) => {

            });
    },
};

const mutations = {
    /**
     * Sets project color statuses to state
     * @param {Object} state
     * @param {array} projectColorStatuses
     */
    [types.SET_PROJECT_COLOR_STATUSES](state, {projectColorStatuses}) {
        state.projectColorStatuses = projectColorStatuses;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
