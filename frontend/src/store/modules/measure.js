import Vue from 'vue';

const state = {};

const getters = {};

const actions = {
    /**
     * Create measure comment
     * @param {function} commit
     * @param {array}    data
     * @return {object}
     */
    createMeasureComment({commit}, data) {
        return Vue.http
            .post(
                Routing.generate('app_api_measures_create_comment', {id: data.measure}),
                JSON.stringify(data)
            );
    },
    /**
     * Edit measure
     * @param {function} commit
     * @param {array}    data
     * @return {object}
     */
    editMeasure({commit}, data) {
        return Vue.http
            .patch(
                Routing.generate('app_api_measures_edit', {id: data.id}),
                JSON.stringify(data)
            );
    },
};

const mutations = {};

export default {
    state,
    getters,
    actions,
    mutations,
};
