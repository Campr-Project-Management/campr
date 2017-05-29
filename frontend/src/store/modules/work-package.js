import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    items: [],
    workPackagesForSelect: [],
};

const getters = {
    workPackages: state => state.items,
    workPackagesForSelect: state => state.items.map(item => ({key: item.id, label: item.name})),
    projectTasks: state => state.items.filter(item => item.type === 2),
    projectTasksForSelect: state => state.items.filter(item => item.type === 2)
                                               .map(item => ({key: item.id, label: item.name})),
};

const actions = {
    /**
     * Get all work packages for a project
     * @param {function} commit
     * @param {Number} projectId
     */
    getWorkPackages({commit}, projectId) {
        Vue.http
            .get(Routing.generate('app_api_project_tasks', {'id': projectId})).then((response) => {
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
        state.items = workPackages.items;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
