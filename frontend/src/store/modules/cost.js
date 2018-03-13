import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    currentCost: {},
    costs: [],
    costCount: 0,
};

const getters = {
    currentCost: state => state.currentCost,
    costs: state => state.costs,
    costsCount: state => state.costsCount,
};

const actions = {
    /**
     * Creates a new cost
     * @param {function} commit
     * @param {array} data
     * @return {Object}
     */
    createTaskCost({commit}, data) {
        return Vue.http
            .post(
                Routing.generate('app_api_cost_create'),
                data
            ).then(
                (response) => {
                    if (response.body && response.body.error && response.body.messages) {
                        const {messages} = response.body;
                        commit(types.SET_VALIDATION_MESSAGES, {messages});
                    } else {
                        let cost = response.data;
                        commit(types.ADD_TASK_COST, {cost});
                        commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                    }
                    return response;
                },
                (response) => {
                    return response;
                }
            );
    },
    /**
     * Delete cost
     * @param {function} commit
     * @param {array} costId
     */
    deleteTaskCost({commit}, costId) {
        Vue.http
            .delete(
                Routing.generate('app_api_cost_delete', {id: costId})
            ).then((response) => {
                commit(types.REMOVE_TASK_COST, {costId});
            }, (response) => {
            });
    },
    /**
     * Edit an existing cost
     * @param {function} commit
     * @param {array} data
     * @return {Object}
     */
    editTaskCost({commit}, data) {
        return Vue.http.patch(
            Routing.generate('app_api_cost_edit', {id: data.costId}),
            data.data,
        ).then(
            (response) => {
                if (response.body && response.body.error &&
                    response.body.messages) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                } else {
                    let cost = response.data;
                    commit(types.SET_TASK_COST, {cost});
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                }
                return response;
            },
            (response) => {
                return response;
            },
        );
    },
};

const mutations = {
};


export default {
    state,
    getters,
    actions,
    mutations,
};
