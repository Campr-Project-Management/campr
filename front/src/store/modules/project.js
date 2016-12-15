import * as types from '../mutation-types';

const state = {};

const getters = {
    projects: state => state.projects,
};

const actions = {
    /**
     * Gets projects from the API and commits SET_PROJECTS mutation
     * @param {function} commit
     */
    getProjects({commit}) {
        // TODO: API
        const projects = [{
            'status': 'IN_PROGRESS',
            'percentage': 0,
        }, {
            'status': 'IN_PROGRESS',
            'percentage': 75,
        }, {
            'status': 'IN_PROGRESS',
            'percentage': 75,
        }, {
            'status': 'IN_PROGRESS',
            'percentage': 75,
        }, {
            'status': 'IN_PROGRESS',
            'percentage': 75,
        }, {
            'status': 'IN_PROGRESS',
            'percentage': 75,
        }, {
            'status': 'FINISHED',
            'percentage': 100,
        },
        ];
        commit(types.SET_PROJECTS, {projects});
    },
};

const mutations = {
    /**
     * Sets projects to state
     * @param {Object} state
     * @param {array} projects
     */
    [types.SET_PROJECTS](state, {projects}) {
        state.projects = projects;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
