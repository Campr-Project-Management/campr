import Vue from 'vue';
import * as types from '../mutation-types';
import _ from 'lodash';

const state = {
    projectDepartments: {},
    projectDepartmentLoading: false,
};

const getters = {
    projectDepartments: state => state.projectDepartments,
    projectDepartmentsForSelect: ({projectDepartments}) => {
        let data = [];

        if (!projectDepartments.items) {
            return data;
        }

        projectDepartments.items.forEach((department) => {
            data.push({
                key: department.id,
                label: department.name,
                rate: department.rate,
            });
        });

        return data;
    },
    projectDepartmentsLoading: state => state.projectDepartmentLoading,
    projectDepartmentsForMultiSelect: ({}, getters) => {
        return getters.projectDepartmentsForSelect;
    },
    projectDepartmentById: ({}, getters) => (id) => {
        let departments = getters.projectDepartments.items;
        if (!departments) {
            return null;
        }

        return _.find(departments, (department) => {
            return department.id === id;
        });
    },
};

const actions = {
    getProjectDepartments({commit}, data) {
        commit(types.SET_PROJECT_DEPARTMENTS_LOADING, {loading: true});
        let paramObject = {params: {}};
        if (data && data.page !== undefined) {
            paramObject.params.page = data.page;
        }
        Vue.http
            .get(Routing.generate('app_api_project_departments', {id: data.project}), paramObject)
            .then((response) => {
                if (response.status === 200) {
                    let projectDepartments = response.data;
                    commit(types.SET_PROJECT_DEPARTMENTS, {projectDepartments});
                }
                commit(types.SET_PROJECT_DEPARTMENTS_LOADING, {loading: false});
            }, (response) => {
                commit(types.SET_PROJECT_DEPARTMENTS_LOADING, {loading: false});
            });
    },
    /**
     * Creates a new project department
     * @param {function} commit
     * @param {array} data
     * @return {Object}
     */
    createDepartment({commit}, data) {
        return Vue.http
            .post(
                Routing.generate('app_api_project_departments_create', {id: data.project}),
                JSON.stringify(data)
            ).then((response) => {
                if (response.body && response.body.error && response.body.messages) {
                    const {messages} = response.body;
                    if (messages.name) {
                        messages.departmentName = messages.name;
                    }
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                } else {
                    let department = response.data;
                    commit(types.ADD_PROJECT_DEPARTMENT, {department});
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                }
                return response;
            }, (response) => {
                return response;
            });
    },
    /**
     * Edit a project department
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    editDepartment({commit}, data) {
        return Vue.http
            .patch(
                Routing.generate('app_api_project_departments_edit', {id: data.id}),
                JSON.stringify(data)
            ).then((response) => {
                if (response.body && response.body.error &&
                    response.body.messages) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                } else {
                    let department = response.data;
                    commit(types.EDIT_PROJECT_DEPARTMENT, {department});
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                }

                return response;
            }, (response) => {
            });
    },
    /**
     * Delete a new objective on project
     * @param {function} commit
     * @param {integer} id
     */
    deleteDepartment({commit}, id) {
        Vue.http
            .delete(
                Routing.generate('app_api_project_departments_delete', {id: id})
            ).then((response) => {
                commit(types.DELETE_PROJECT_DEPARTMENT, {id});
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets project departments to state
     * @param {Object} state
     * @param {array} projectDepartments
     */
    [types.SET_PROJECT_DEPARTMENTS](state, {projectDepartments}) {
        state.projectDepartments = projectDepartments;
    },
    /**
     * @param {Object} state
     * @param {array} loading
     */
    [types.SET_PROJECT_DEPARTMENTS_LOADING](state, {loading}) {
        state.projectDepartmentLoading = loading;
    },

    /**
     * Add project department
     * @param {Object} state
     * @param {array} department
     */
    [types.ADD_PROJECT_DEPARTMENT](state, {department}) {
        state.projectDepartments.items.push(department);
        state.projectDepartments.totalItems++;
    },
    /**
     * Edit project department
     * @param {Object} state
     * @param {array} department
     */
    [types.EDIT_PROJECT_DEPARTMENT](state, {department}) {
        state.projectDepartments.items = state.projectDepartments.items.map((item) => {
            return item.id === department.id ? department : item;
        });
    },
    /**
     * Delete project department
     * @param {Object} state
     * @param {array} department
     */
    [types.DELETE_PROJECT_DEPARTMENT](state, {id}) {
        state.projectDepartments.items = state.projectDepartments.items.filter((item) => {
            return item.id !== id ? true : false;
        });
        state.projectDepartments.totalItems--;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
