import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    colorStatuses: [],
    greenColorStatus: [],
};

const getters = {
    colorStatuses: state => state.colorStatuses,
    greenColorStatus: state => state.greenColorStatus,
    colorStatusesForSelect: state =>
        [{'key': '', 'label': Translator.trans('message.all_statuses')}].concat(
            state.colorStatuses.map(colorStatus => ({'key': colorStatus.id, 'label': colorStatus.name}))
        ),
};

const actions = {
    /**
     * Gets all color statuses
     * @param {function} commit
     */
    getColorStatuses({commit}) {
        Vue.http
            .get(Routing.generate('app_api_color_status_list')).then((response) => {
                if (response.status === 200) {
                    let colorStatuses = response.data;
                    commit(types.SET_COLOR_STATUSES, {colorStatuses});
                }
            }, (response) => {

            });
    },

    /**
     * Gets green color status
     * @param {function} commit
     */
    getGreenColorStatus({commit}) {
        Vue.http
            .get(Routing.generate('app_api_color_status_green')).then((response) => {
                if (response.status === 200) {
                    let greenColorStatus = response.data;
                    commit(types.SET_GREEN_COLOR_STATUSES, {greenColorStatus});
                }
            }, (response) => {

            });
    },
};

const mutations = {
    /**
     * Sets color statuses to state
     * @param {Object} state
     * @param {array} colorStatuses
     */
    [types.SET_COLOR_STATUSES](state, {colorStatuses}) {
        state.colorStatuses = colorStatuses;
    },

    /**
     * Sets green color status to state
     * @param {Object} state
     * @param {array} greenColorStatus
     */
    [types.SET_GREEN_COLOR_STATUSES](state, {greenColorStatus}) {
        state.greenColorStatus = greenColorStatus;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
