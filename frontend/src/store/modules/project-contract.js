import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    currentContract: {},
};

const getters = {
    currentContract: state => state.currentContract,
};

const actions = {
    /**
     * Create project contract
     * @param {function} commit
     * @param {array} data
     */
    createContract({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_project_create_contract', {'id': data.projectId}),
                JSON.stringify(data)
            ).then((response) => {
            }, (response) => {
                if (response.status === 400) {
                    // implement system to display errors
                }
            });
    },
    /**
     * Edit contract
     * @param {function} commit
     * @param {array} data
     * @param {number} id
     * @return {object}
     */
    updateContract({commit}, data) {
        return Vue
            .http
            .patch(
                Routing.generate('app_api_contract_edit', {'id': data.id}),
                JSON.stringify(data)
            )
        ;
    },
    /**
     * Gets a contract by project ID from the API and commits SET_CONTRACT mutation
     * @param {function} commit
     * @param {number} id
     * @return {object}
     */
    getContractByProjectId({commit}, id) {
        return Vue
            .http
            .get(Routing.generate('app_api_project_contracts', {'id': id})).then((response) => {
                if (response.status === 200) {
                    let contract = {};
                    if (response.data.length > 0) {
                        contract = response.data[0];
                    }
                    commit(types.SET_CONTRACT, {contract});
                }
            }, (response) => {
            })
        ;
    },
};

const mutations = {
    /**
     * Sets portfolio to state
     * @param {Object} state
     * @param {Object} contract
     */
    [types.SET_CONTRACT](state, {contract}) {
        state.currentContract = contract;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
