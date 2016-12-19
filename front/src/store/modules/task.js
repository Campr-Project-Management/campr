import * as types from '../mutation-types';

const state = {};

const getters = {
    tasks: state => state.tasks,
};

const actions = {
    /**
     * Gets tasks from the API and commits SET_TASKS mutation
     * @param {function} commit
     */
    getTasks({commit}) {
        // TODO: API
        const tasks = [
          {'status': 'NOT_STARTED', 'percentage': 0},
          {'status': 'IN_PROGRESS', 'percentage': 75},
          {'status': 'IN_PROGRESS', 'percentage': 75},
          {'status': 'IN_PROGRESS', 'percentage': 75},
          {'status': 'IN_PROGRESS', 'percentage': 75},
          {'status': 'IN_PROGRESS', 'percentage': 75},
          {'status': 'FINISHED', 'percentage': 100},
        ];

        commit(types.SET_TASKS, {tasks});
    },
};

const mutations = {
    /**
     * Sets projects to state
     * @param {Object} state
     * @param {array} tasks
     */
    [types.SET_TASKS](state, {tasks}) {
        state.tasks = tasks;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
