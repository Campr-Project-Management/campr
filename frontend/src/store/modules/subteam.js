import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    subteams: [],
    subteamsForSelect: [],
};

const getters = {
    subteams: state => state.subteams,
    subteamsForSelect: state => state.subteamsForSelect,
    subteamsCount: state => state.subteams.totalItems,
};

const actions = {
    getSubteams({commit}, data) {
        let paramObject = {params: {}};
        if (data.page && data.page !== undefined) {
            paramObject.params.page = data.page;
        }
        Vue.http
            .get(Routing.generate('app_api_project_subteams', {id: data.project}), paramObject)
            .then((response) => {
                if (response.status === 200) {
                    let subteams = response.data;
                    commit(types.SET_SUBTEAMS, {subteams});
                }
            }, (response) => {
            });
    },
    /**
     * Creates a new subteam
     * @param {function} commit
     * @param {array} data
     * @return {Object}
     */
    createSubteam({commit}, data) {
        return Vue.http
            .post(
                Routing.generate('app_api_project_create_subteam', {id: data.project}),
                JSON.stringify(data)
            ).then((response) => {
                if (response.body && response.body.error && response.body.messages) {
                    const {messages} = response.body;
                    if (messages.name) {
                        messages.subteamName = messages.name;
                    }
                    commit(types.SET_VALIDATION_MESSAGES, {messages});
                } else {
                    let subteam = response.body;
                    commit(types.ADD_SUBTEAM, {subteam});
                    commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                }
                return response;
            }, (response) => {
                return response;
            });
    },
    /**
     * Edit a subteam
     * @param {function} commit
     * @param {array} data
     */
    editSubteam({commit}, data) {
        Vue.http
            .patch(
                Routing.generate('app_api_subteam_edit', {id: data.id}),
                JSON.stringify(data)
            ).then((response) => {
                let subteam = response.data;
                let id = data.id;
                commit(types.EDIT_SUBTEAM, {id, subteam});
            }, (response) => {
            });
    },
    /**
     * Delete a subteam
     * @param {function} commit
     * @param {integer} id
     */
    deleteSubteam({commit}, id) {
        Vue.http
            .delete(
                Routing.generate('app_api_subteam_delete', {id: id})
            ).then((response) => {
                commit(types.DELETE_SUBTEAM, {id});
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets subteam to state
     * @param {Object} state
     * @param {array} subteams
     */
    [types.SET_SUBTEAMS](state, {subteams}) {
        state.subteams = subteams;
        let subteamsForSelect = [{'key': null, 'label': Translator.trans('placeholder.subteam'), 'rate': 0}];
        state.subteams.items.map((item) => {
            subteamsForSelect.push({'key': item.id, 'label': item.name});
        });
        state.subteamsForSelect = subteamsForSelect;
    },
    /**
     * Add subteam
     * @param {Object} state
     * @param {array} subteam
     */
    [types.ADD_SUBTEAM](state, {subteam}) {
        state.subteams.items.push(subteam);
        state.subteams.totalItems++;
    },
    /**
     * Edit subteam
     * @param {Object} state
     * @param {array} subteam
     */
    [types.EDIT_SUBTEAM](state, {id, subteam}) {
        state.subteams.items = state.subteams.items.map((item) => {
            return item.id === id ? subteam : item;
        });
    },
    /**
     * Delete subteam
     * @param {Object} state
     * @param {integer} id
     */
    [types.DELETE_SUBTEAM](state, {id}) {
        state.subteams.items = state.subteams.items.filter((item) => {
            return item.id !== id ? true : false;
        });
        state.subteams.totalItems--;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
