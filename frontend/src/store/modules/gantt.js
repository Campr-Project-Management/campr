import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    ganttData: [],
};

const getters = {
    ganttData: (state) => state.ganttData,
};

const actions = {
    getGanttData({commit}, id) {
        return Vue
            .http
            .get(Routing.generate('app_api_project_gantt', {id}))
            .then(
                (response) => {
                    commit(types.SET_GANTT_DATA, {data: []});
                    if (response.status === 200) {
                        const data = response.body;
                        commit(types.SET_GANTT_DATA, {data});
                    }
                },
                () => {}
            )
        ;
    },
    clearGanttData({commit}) {
        commit(types.CLEAR_GANTT_DATA);
    },
};

const mutations = {
    [types.SET_GANTT_DATA](state, {data}) {
        state.ganttData = data;
    },
    [types.CLEAR_GANTT_DATA](state) {
        state.ganttData = [];
    },
    [types.SET_GANTT_DATUM](state, {datum}) {
        state.ganttData = state
            .ganttData
            .filter(item => item.id !== datum.id)
            .concat([datum])
        ;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
