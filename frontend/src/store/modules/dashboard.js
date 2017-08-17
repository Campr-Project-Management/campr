import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    sidebarStats: [],
};

const getters = {
    sidebarStats: state => state.sidebarStats,
};

const actions = {
    /**
     * Get sidebar info.
     * @param {function} commit
     */
    getSidebarInformation({commit}) {
        Vue.http
            .get(Routing.generate('app_api_dashboard_sidebar')).then((response) => {
                if (response.status === 200) {
                    let info = response.data;
                    commit(types.SET_SIDEBAR_INFORMATION, {info});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets sidebar info to state
     * @param {Object} state
     * @param {array} customers
     */
    [types.SET_SIDEBAR_INFORMATION](state, {info}) {
        state.sidebarStats = info;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
