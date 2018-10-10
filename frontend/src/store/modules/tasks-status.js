import Vue from 'vue';
import * as types from '../mutation-types';
import * as loading from '../loading-types';

const state = {
    taskStatuses: [],
};

const getters = {
    taskStatuses: state => state.taskStatuses,
    taskStatusesCount: state => state.taskStatuses.totalItems,
    taskStatusesForSelect: (state) => {
        return state.taskStatuses.filter(item => !!item.visible).
            map((item) => ({
                key: item.id,
                label: Vue.translate(item.name),
            }));
    },
};

const actions = {
    /**
     * Gets task statuses from the API and commits SET_TASK_STATUSES mutation
     * @param {function} commit
     * @param {function} dispatch
     */
    async getTaskStatuses({commit, dispatch}) {
        try {
            dispatch('wait/start', loading.GET_TASK_STATUSES, {root: true});
            let response = await Vue.http.get(Routing.generate('app_api_workpackage_statuses_list'));
            if (response.status === 200) {
                let taskStatuses = response.data;
                commit(types.SET_TASK_STATUSES, {taskStatuses});
            }
        } finally {
            dispatch('wait/end', loading.GET_TASK_STATUSES, {root: true});
        }
    },
};

const mutations = {
    /**
     * Sets task statuses to state
     * @param {Object} state
     * @param {array} taskStatuses
     */
    [types.SET_TASK_STATUSES](state, {taskStatuses}) {
        state.taskStatuses = taskStatuses;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
