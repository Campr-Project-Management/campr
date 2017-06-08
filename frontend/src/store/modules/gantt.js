import Vue from 'vue';
import * as types from '../mutation-types';
// import router from '../../router';

const state = {
    ganttData: [],
};

const getters = {
    ganttData: (state) => state.ganttData,
};

const actions = {
    getGanttData({commit}, id) {
        Vue
            .http
            .get(Routing.generate('app_api_project_gantt', {id}))
            .then(
                (response) => { // win
                    if (response.status === 200) {
                        const data = response.body;
                        commit(types.SET_GANTT_DATA, {data});
                    }
                },
                () => { // fail
                    // display error
                },
            )
        ;
    },
};

const mutations = {
    [types.SET_GANTT_DATA](state, {data}) {
        state.ganttData = data;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
