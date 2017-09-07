import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    projectCloseDown: {},
};

const getters = {
    projectCloseDown: state => state.projectCloseDown,
};

const actions = {
    /**
     * Gets a Project Close Down by project ID
     * @param {function} commit
     * @param {number} projectId
     *
     * @return {object}
     */
    getProjectCloseDown({commit}, projectId) {
        return Vue.http
            .get(Routing.generate('app_api_project_close_downs', {'id': projectId})
            ).then((response) => {
                if (response.status === 200) {
                    let projectCloseDown = {};
                    if (response.data.length > 0) {
                        projectCloseDown = response.data[0];
                    }
                    commit(types.SET_PROJECT_CLOSE_DOWN, {projectCloseDown});
                }
            }, (response) => {
            });
    },
    /**
     * Creates a new project close down
     * @param {function} commit
     * @param {array} data
     *
     * @return {object}
     */
    createProjectCloseDown({commit}, data) {
        return Vue.http
            .post(
                Routing.generate('app_api_project_close_down_create', {'id': data.projectId}),
                data
            ).then((response) => {
                let projectCloseDown = response.data;
                commit(types.SET_PROJECT_CLOSE_DOWN, {projectCloseDown});
            }, (response) => {
            });
    },
    /**
     * Edit a project close down
     * @param {function} commit
     * @param {array} data
     *
     * @return {object}
     */
    editProjectCloseDown({commit}, data) {
        return Vue.http
            .put(
                Routing.generate('app_api_project_close_downs_edit', {'id': data.id}),
                data
            ).then((response) => {
                let projectCloseDown = response.data;
                commit(types.SET_PROJECT_CLOSE_DOWN, {projectCloseDown});
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets project close down to state
     * @param {Object} state
     * @param {Object} projectCloseDown
     */
    [types.SET_PROJECT_CLOSE_DOWN](state, {projectCloseDown}) {
        state.projectCloseDown = projectCloseDown;
    },
    /**
     * Adds another evaluation objective to project close down
     * @param {Object} state
     * @param {Object} evaluationObjective
     */
    [types.ADD_EVALUATION_OBJECTIVE](state, {evaluationObjective}) {
        if (state.projectCloseDown) {
            state.projectCloseDown.evaluationObjectives.push(evaluationObjective);
        }
    },
    /**
     * Adds another lesson to project close down
     * @param {Object} state
     * @param {Object} lesson
     */
    [types.ADD_LESSON](state, {lesson}) {
        if (state.projectCloseDown) {
            state.projectCloseDown.lessons.push(lesson);
        }
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
