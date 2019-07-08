import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    distributionList: {},
    distributionLists: [],
};

const getters = {
    distributionList: state => state.distributionList,
    distributionLists: state => {
        let lists = [...state.distributionLists];
        lists.sort((a, b) => a.id - b.id);

        return lists;
    },
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
     * Get one distribution list
     *
     * @param {function} commit
     * @param {Number} id
     *
     * @return {mixed}
     */
    getDistributionList({commit}, id) {
        return Vue.http.get(
            Routing.generate('app_api_distribution_list_get', {id})).then(
            (response) => {
                commit(types.SET_DISTRIBUTION_LIST, response.data);

                return response;
            },
            () => {},
        )
            ;
    },
    /**
     * Get all distribution lists.
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    async getDistributionLists({commit}, data) {
        let res = await Vue.http.get(
            Routing.generate('app_api_project_distribution_lists',
                {'id': data.projectId}));
        let distributionLists = res.data;
        commit(types.SET_DISTRIBUTION_LISTS, {distributionLists});

        return res;
    },
    /**
     * Add new project user to distribution list
     * @param {function} commit
     * @param {array}    data
     * @return {object}
     */
    async addToDistribution({commit}, data) {
        let res = await Vue.http.patch(
            Routing.generate('app_api_distribution_list_add_user',
                {'id': data.id}), JSON.stringify(data));

        commit(types.SET_DISTRIBUTION_LIST, res.data);

        return res;
    },
    /**
     * remove project user from distribution list
     * @param {function} commit
     * @param {array}    data
     * @return {object}
     */
    async removeFromDistribution({commit}, data) {
        let res = await Vue.http.patch(
            Routing.generate('app_api_distribution_list_remove_user',
                {'id': data.id}), JSON.stringify(data));
        commit(types.SET_DISTRIBUTION_LIST, res.data);

        return res;
    },
};

const mutations = {
    /**
     * Sets the distribution list
     *
     * @param {Object} state
     * @param {Object} distributionList
     */
    [types.SET_DISTRIBUTION_LIST](state, distributionList) {
        state.distributionList = distributionList;

        if (state.distributionLists.length &&
            state.distributionLists[0].project === distributionList.project) {
            state.distributionLists = state.distributionLists.filter(
                dl => dl.id !== distributionList.id).concat([distributionList]);
        }
    },
    /**
     * Sets distribution lists to state
     * @param {Object} state
     * @param {array} customers
     */
    [types.SET_DISTRIBUTION_LISTS](state, {distributionLists}) {
        state.distributionLists = distributionLists;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
