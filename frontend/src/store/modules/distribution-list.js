import Vue from 'vue';

const state = {};

const getters = {};

const actions = {
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
            }, (response) => {
            });
    },
};

const mutations = {};

export default {
    state,
    getters,
    actions,
    mutations,
};
