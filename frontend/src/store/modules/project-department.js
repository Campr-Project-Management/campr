import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    projectDepartments: [],
    projectDepartmentsForSelect: [],
    projectDepartmentsForMultiSelect: [],
    loading: false,
};

const getters = {
    projectDepartments: state => state.projectDepartments,
    projectDepartmentsForSelect: state => state.projectDepartmentsForSelect,
    projectDepartmentsLoading: state => state.loading,
    projectDepartmentsForMultiSelect: state => state.projectDepartmentsForMultiSelect,
};

const actions = {
    getProjectDepartments({commit}, data) {
        commit(types.SET_PROJECT_DEPARTMENTS_LOADING, {loading: true});
        Vue.http
            .get(Routing.generate('app_api_project_departments_list', data)).then((response) => {
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
     */
    createDepartment({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_project_departments_create'),
                JSON.stringify(data)
            ).then((response) => {
                let department = response.data;
                commit(types.ADD_PROJECT_DEPARTMENT, {department});
            }, (response) => {
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
     * @param {array} programmes
     */
    [types.SET_PROJECT_DEPARTMENTS](state, {projectDepartments}) {
        state.projectDepartments = projectDepartments;
        let projectDepartmentsForSelect = [{'key': null, 'label': Translator.trans('placeholder.department'), 'rate': 0}];
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
        state.loading = loading;
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
