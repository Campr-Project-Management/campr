import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    items: [],
};

const getters = {
    colorStatuses: state => state.items,
    colorStatusesForFilter: function(state) {
        let colorStatusesForFilter = [{'key': '', 'label': 'All Statuses'}];
        state.items.map( function(colorStatus) {
            colorStatusesForFilter.push({'key': colorStatus.name, 'label': colorStatus.name});
        });

        return colorStatusesForFilter;
    },
};

const actions = {
    /**
     * Gets all color statuses
     * @param {function} commit
     */
    getColorStatuses({commit}) {
        Vue.http
            .get(Routing.generate('app_api_color_status_list')).then((response) => {
                let colorStatuses = response.data;
                commit(types.SET_COLOR_STATUSES, {colorStatuses});
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
        state.items = colorStatuses;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
