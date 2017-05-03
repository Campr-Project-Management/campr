import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    items: [],
    itemsForSelect: [],
};

const getters = {
    subteams: state => state.items,
    subteamsForSelect: state => state.itemsForSelect,
};

const actions = {
    getSubteams({commit}, data) {
        Vue.http
            .get(Routing.generate('app_api_subteam', data)).then((response) => {
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
     */
    createSubteam({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_project_create_subteam', {id: data.project}),
                JSON.stringify(data)
            ).then((response) => {
                let subteam = response.data;
                commit(types.ADD_SUBTEAM, {subteam});
            }, (response) => {
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
        state.items = subteams;
        let itemsForSelect = [{'key': null, 'label': Translator.trans('placeholder.subteam'), 'rate': 0}];
        state.items.items.map((item) => {
            itemsForSelect.push({'key': item.id, 'label': item.name});
        });
        state.itemsForSelect = itemsForSelect;
    },
    /**
     * Add subteam
     * @param {Object} state
     * @param {array} subteam
     */
    [types.ADD_SUBTEAM](state, {subteam}) {
        state.items.items.push(subteam);
        state.items.totalItems++;
    },
    /**
     * Edit subteam
     * @param {Object} state
     * @param {array} subteam
     */
    [types.EDIT_SUBTEAM](state, {id, subteam}) {
        state.items.items = state.items.items.map((item) => {
            return item.id === id ? subteam : item;
        });
    },
    /**
     * Delete subteam
     * @param {Object} state
     * @param {integer} id
     */
    [types.DELETE_SUBTEAM](state, {id}) {
        state.items.items = state.items.items.filter((item) => {
            return item.id !== id ? true : false;
        });
        state.items.totalItems--;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
