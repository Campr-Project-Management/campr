import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    currentItem: {},
};

const getters = {
    contract: state => state.currentItem,
};

const actions = {
    /**
     * Edit contract
     * @param {function} commit
     * @param {array} data
     * @param {number} id
     */
    EditContract({commit}, data, id) {
        Vue.http
            .patch(
                Routing.generate('app_api_contract_edit', {'id': id}),
                JSON.stringify(data)
            ).then((response) => {
            }, (response) => {
                if (response.status === 400) {
                    // implement system to display errors
                }
            });
    },
    /**
     * Gets a contract by project ID from the API and commits SET_CONTRACT mutation
     * @param {function} commit
     * @param {number} id
     */
    getContractByProjectId({commit}, id) {
        Vue.http
            .get(Routing.generate('app_api_project_contracts', {'id': id})).then((response) => {
                if (response.status === 200) {
                    let contract = response.data;
                    commit(types.SET_CONTRACT, {contract});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets portfolio to state
     * @param {Object} state
     * @param {Object} contract
     */
    [types.SET_CONTRACT](state, {contract}) {
        state.currentItem = contract;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
