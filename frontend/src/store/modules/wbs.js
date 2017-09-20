import * as types from '../mutation-types';
import Vue from 'vue';

const state = {
    wbs: [],
};

const getters = {
    wbs: state => state.wbs,
};

const actions = {
    getWBSByProjectID({commit}, id) {
        // app_api_project_wbs
        return Vue
            .http
            .get(Routing.generate('app_api_project_wbs', {id}))
            .then(
                (response) => {
                    commit(types.SET_WBS, {wbs: response.body});
                },
                () => {
                    commit(types.SET_WBS, {wbs: []});
                }
            )
        ;
    },
};

const mutations = {
    [types.SET_WBS](state, {wbs}) {
        state.wbs = wbs;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
