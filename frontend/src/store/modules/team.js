import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    current: null,
};

const getters = {
    team: state => state.current,
};

const actions = {
    async getTeam({commit}) {
        let res = await Vue.http.get(Routing.generate('app_api_team_show'));
        commit(types.SET_TEAM, res.data);
    },
    async syncTeam({commit}) {
        let res = await Vue.http.get(Routing.generate('app_api_team_sync'));
        commit(types.SET_TEAM, res.data);
    },
};

const mutations = {
    /**
     * Sets subteam to state
     * @param {Object} state
     * @param {Object} team
     */
    [types.SET_TEAM](state, team) {
        state.current = team;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
