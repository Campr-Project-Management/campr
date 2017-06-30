import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    colorStatuses: [],
};

const getters = {
    colorStatuses: state => state.colorStatuses,
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
};

export default {
    state,
    getters,
    actions,
    mutations,
};
