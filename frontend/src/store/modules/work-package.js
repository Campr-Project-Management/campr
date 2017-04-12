import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    items: [],
    workPackagesForSelect: [],
    projectTasks: [],
    projectTasksForSelect: [],
};

const getters = {
    workPackages: state => state.items,
    workPackagesForSelect: state => state.workPackagesForSelect,
    projectTasks: state => state.projectTasks,
    projectTasksForSelect: state => state.projectTasksForSelect,
};

const actions = {
    /**
     * Get all work packages for a project
     * @param {function} commit
     * @param {Number} projectId
     */
    getWorkPackages({commit}, projectId) {
        Vue.http
            .get(Routing.generate('app_api_project_work_packages', {'id': projectId})).then((response) => {
                if (response.status === 200) {
                    let workPackages = response.data;
                    commit(types.SET_WORK_PACKAGES, {workPackages});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets work packages to state
     * @param {Object} state
     * @param {array} work packages
     */
    [types.SET_WORK_PACKAGES](state, {workPackages}) {
        state.items = workPackages;
        let tasks = state.items.filter(item => item.type === 2);
        let tasksForSelect = [];
        tasks.map(item => tasksForSelect.push({'key': item.id, 'label': item.name}));
        state.projectTasks = tasks;
        state.projectTasksForSelect = tasksForSelect;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
