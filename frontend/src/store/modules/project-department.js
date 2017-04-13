import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    items: [],
    itemsForSelect: [],
    loading: false,
};

const getters = {
    projectDepartments: state => state.items,
    projectDepartmentsForSelect: state => state.itemsForSelect,
    projectDepartmentsLoading: state => state.loading,
};

const actions = {
    getProjectDepartments({commit}, projectId) {
        commit(types.SET_PROJECT_DEPARTMENTS_LOADING, {loading: true});
        Vue.http
            .get(Routing.generate('app_api_project_departments_list', {projectId})).then((response) => {
                if (response.status === 200) {
                    let projectDepartments = response.data;
                    commit(types.SET_PROJECT_DEPARTMENTS, {projectDepartments});
                }
                commit(types.SET_PROJECT_DEPARTMENTS_LOADING, {loading: false});
            }, (response) => {
                commit(types.SET_PROJECT_DEPARTMENTS_LOADING, {loading: false});
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
        state.items = projectDepartments;
        let itemsForSelect = [];
        state.items.map((item) => {
            itemsForSelect.push({'key': item.id, 'label': item.name, 'rate': item.rate});
        });
        state.itemsForSelect = itemsForSelect;
    },
    /**
     * @param {Object} state
     * @param {array} loading
     */
    [types.SET_PROJECT_DEPARTMENTS_LOADING](state, {loading}) {
        state.loading = loading;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
