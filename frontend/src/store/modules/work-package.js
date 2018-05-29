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
     * Get one work package by ID
     * @param {Number} id
     * @return {Promise}
     */
    getWorkPackage({}, id) {
        return Vue
            .http
            .get(Routing.generate('app_api_workpackage_get', {id}))
        ;
    },

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
     * @param {mixed} actualStartAt
     * @param {mixed} actualFinishAt
     * @param {Number} workPackageStatus
     * @return {object}
     */
    setWorkPackageProgress({commit}, {id, progress, actualStartAt, actualFinishAt, workPackageStatus}) {
        return actions.patchWorkPackage({commit}, {id, data: {progress, actualStartAt, actualFinishAt, workPackageStatus}});
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
            .then(
                response => {
                    if (response.body && !response.body.error) {
                        commit(types.SET_GANTT_DATUM, {datum: response.body});
                    }

                    return response;
                },
                response => {
                    if (response.status === 202) {
                        commit(types.SET_GANTT_DATUM, {datum: response.body});
                    }

                    return response;
                },
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
