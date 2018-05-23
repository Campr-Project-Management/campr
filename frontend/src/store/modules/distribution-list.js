import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    distributionLists: [],
    distributionList: {},
};

const getters = {
    distributionLists: state => state.distributionLists,
    distributionList: state => state.distributionList,
    distributionListsForSelect: state => {
        let selectLsts = [];
        state.distributionLists.map(function(item) {
            selectLsts.push({'key': item.id, 'label': item.name});
        });
        return selectLsts;
    },
};

const actions = {
    /**
     * Get all distribution lists.
     * @param {function} commit
     * @param {array} data
     */
    getDistributionLists({commit}, data) {
        Vue.http
            .get(Routing.generate('app_api_project_distribution_lists', {'id': data.projectId})).then((response) => {
                if (response.status === 200) {
                    let distributionLists = response.data;
                    commit(types.SET_DISTRIBUTION_LISTS, {distributionLists});
                }
            }, (response) => {
            });
    },
    /**
     * Add new project user to distribution list
     * @param {function} commit
     * @param {array}    data
     */
    addToDistribution({commit}, data) {
        Vue.http
            .patch(Routing.generate('app_api_distribution_list_add_user', {'id': data.id}),
                JSON.stringify(data)
            ).then((response) => {
                if (response.status === 200) {
                    let distributionList = response.data;
                    commit(types.SET_DISTRIBUTION_LIST, {distributionList});
                }
            }, (response) => {
            });
    },
    /**
     * remove project user from distribution list
     * @param {function} commit
     * @param {array}    data
     */
    removeFromDistribution({commit}, data) {
        Vue.http
            .patch(Routing.generate('app_api_distribution_list_remove_user', {'id': data.id}),
                JSON.stringify(data)
            ).then((response) => {
                if (response.status === 200) {
                    let distributionList = response.data;
                    commit(types.SET_DISTRIBUTION_LIST, {distributionList});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets distribution lists to state
     * @param {Object} state
     * @param {array} customers
     */
    [types.SET_DISTRIBUTION_LISTS](state, {distributionLists}) {
        state.distributionLists = distributionLists;
    },

    /**
     * Sets distribution list to state
     * @param {Object} state
     * @param {array} customers
     */
    [types.SET_DISTRIBUTION_LIST](state, {distributionList}) {
        state.distributionList = distributionList;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
