import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    workPackages: [],
    workPackagesForSelect: [],
};

const getters = {
    workPackages: state => state.workPackages,
    workPackagesForSelect: state => state.workPackages.map(item => ({key: item.id, label: item.name})),
    projectTasks: state => state.workPackages.filter(item => item.type === 2),
    projectTasksForSelect: state => state.workPackages.filter(item => item.type === 2)
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
    /**
     * Set color status for work package
     * @param {function} commit
     * @param {Number} id
     * @param {Number} colorStatus
     * @return {object}
     */
    setWorkPackageColorStatus({commit}, {id, colorStatus}) {
        return actions.patchWorkPackage({commit}, {id, data: {colorStatus}});
    },
    /**
     * Set color status for work package
     * @param {function} commit
     * @param {Number} id
     * @param {Number} progress
     * @return {object}
     */
    setWorkPackageProgress({commit}, {id, progress}) {
        return actions.patchWorkPackage({commit}, {id, data: {progress}});
    },
    /**
     * Set color status for work package
     * @param {function} commit
     * @param {Number} id
     * @param {object} data
     * @return {object}
     */
    patchWorkPackage({commit}, {id, data}) {
        return Vue
            .http
            .patch(
                Routing.generate('app_api_workpackage_edit', {id}),
                data
            )
        ;
    },
};

const mutations = {
    /**
     * Sets work packages to state
     * @param {Object} state
     * @param {array} work packages
     */
    [types.SET_WORK_PACKAGES](state, {workPackages}) {
        state.workPackages = workPackages.items;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
