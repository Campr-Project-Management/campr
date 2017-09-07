import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    closeDownActions: [],
    currentCloseDownAction: {},
};

const getters = {
    closeDownActions: state => state.closeDownActions,
    currentCloseDownAction: state => state.currentCloseDownAction,
};

const actions = {
    /**
     * Gets a specific close down action
     * @param {function} commit
     * @param {integer} id
     *
     * @return {object}
     */
    getCloseDownAction({commit}, id) {
        return Vue.http
            .get(Routing.generate('app_api_close_down_actions_get', {'id': id}))
            .then((response) => {
                if (response.status === 200) {
                    let closeDownAction = response.data;
                    commit(types.SET_CURRENT_CLOSE_DOWN_ACTION, {closeDownAction});
                }
            }, (response) => {
            });
    },
    /**
     * Gets a Project Close Down by project ID
     * @param {function} commit
     * @param {array} data
     *
     * @return {object}
     */
    getCloseDownActions({commit}, data) {
        let paramObject = {params: {}};
        if (data.queryParams && data.queryParams.page !== undefined) {
            paramObject.params.page = data.queryParams.page;
        }
        return Vue.http
            .get(
                Routing.generate('app_api_project_close_down_actions', {'id': data.closeDownId}),
                paramObject
            ).then((response) => {
                if (response.status === 200) {
                    let closeDownActions = response.data;
                    commit(types.SET_PROJECT_CLOSE_DOWN_ACTIONS, {closeDownActions});
                }
            }, (response) => {
            });
    },
    /**
     * Creates a new close down action
     * @param {function} commit
     * @param {array} data
     *
     * @return {object}
     */
    createCloseDownAction({commit}, data) {
        return Vue.http
                .post(
                    Routing.generate('app_api_project_close_down_actions_create', {'id': data.closeDownId}),
                    data
                ).then((response) => {
                    if (response.body && response.body.error) {
                        const {messages} = response.body;
                        messages.actionForm = true;
                        commit(types.SET_VALIDATION_MESSAGES, {messages});
                    } else {
                        let closeDownAction = response.data;
                        commit(types.ADD_PROJECT_CLOSE_DOWN_ACTION, {closeDownAction});
                        commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                    }
                }, (response) => {
                });
    },
    /**
     * Edit a close down action
     * @param {function} commit
     * @param {array} data
     *
     * @return {Object}
     */
    editCloseDownAction({commit}, data) {
        return Vue.http
            .patch(
                Routing.generate('app_api_close_down_actions_edit', {'id': data.id}),
                data
            ).then((response) => {
                if (response.body && response.body.error) {
                    const {messages} = response.body;
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                } else {
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                }
                return response;
            }, (response) => {
            });
    },
    /**
     * Delete close down action
     * @param {function} commit
     * @param {integer} id
     *
     * @return {Object}
     */
    deleteCloseDownAction({commit}, id) {
        return Vue.http
            .delete(
                Routing.generate('app_api_close_down_actions_delete', {id: id})
            ).then((response) => {
                commit(types.DELETE_CLOSE_DOWN_ACTION, {id});
                return response;
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets close down action
     * @param {Object} state
     * @param {Object} closeDownAction
     */
    [types.SET_CURRENT_CLOSE_DOWN_ACTION](state, {closeDownAction}) {
        state.currentCloseDownAction = closeDownAction;
    },
    /**
     * Sets close down actions
     * @param {Object} state
     * @param {array} closeDownActions
     */
    [types.SET_PROJECT_CLOSE_DOWN_ACTIONS](state, {closeDownActions}) {
        state.closeDownActions = closeDownActions;
    },
    /**
     * Add new close down action
     * @param {Object} state
     * @param {object} closeDownAction
     */
    [types.ADD_PROJECT_CLOSE_DOWN_ACTION](state, {closeDownAction}) {
        if (state.closeDownActions.items.length < state.closeDownActions.pageSize) {
            state.closeDownActions.items.push(closeDownAction);
        }
        state.closeDownActions.totalItems++;
    },
    /**
     * Delete close down action
     * @param {Object} state
     * @param {integer} id
     */
    [types.DELETE_CLOSE_DOWN_ACTION](state, {id}) {
        if (state.closeDownActions) {
            state.closeDownActions.items = state.closeDownActions.items.filter((item) => {
                return item.id !== id;
            });
            state.closeDownActions.totalItems--;
        }
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
