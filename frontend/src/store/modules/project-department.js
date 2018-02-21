import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    projectDepartments: {},
    projectDepartmentsForSelect: [],
    projectDepartmentsForMultiSelect: [],
    projectDepartmentLoading: false,
};

const getters = {
    projectDepartments: state => state.projectDepartments,
    projectDepartmentsForSelect: state => state.projectDepartmentsForSelect,
    projectDepartmentsLoading: state => state.projectDepartmentLoading,
    projectDepartmentsForMultiSelect: state => state.projectDepartmentsForMultiSelect,
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
     */
    editDepartment({commit}, data) {
        Vue.http
            .patch(
                Routing.generate('app_api_project_departments_edit', {id: data.id}),
                JSON.stringify(data)
            ).then((response) => {
                let department = response.data;
                let id = data.id;
                commit(types.EDIT_PROJECT_DEPARTMENT, {id, department});
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
        let projectDepartmentsForSelect = [];
        let projectDepartmentsForMultiSelect = [];
        state.projectDepartments.items.map((item) => {
            projectDepartmentsForSelect.push({'key': item.id, 'label': item.name, 'rate': item.rate});
            projectDepartmentsForMultiSelect.push({'key': item.id, 'label': item.name, 'rate': item.rate});
        });
        state.projectDepartmentsForSelect = projectDepartmentsForSelect;
        state.projectDepartmentsForMultiSelect = projectDepartmentsForMultiSelect;
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
    [types.EDIT_PROJECT_DEPARTMENT](state, {id, department}) {
        state.projectDepartments.items = state.projectDepartments.items.map((item) => {
            return item.id === id ? department : item;
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
