import Vue from 'vue';
import * as types from '../mutation-types';

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
            )
            .then(
                (response) => {
                    if (response.body && !response.body.error) {
                        let measureComment = response.body;
                        commit(types.ADD_MEASURE_COMMENT_FOR_CURRENT_OPPORTUNITY, {measureComment});
                        commit(types.ADD_MEASURE_COMMENT_FOR_CURRENT_RISK, {measureComment});
                    }

                    return response;
                },
                () => {}
            )
        ;
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
            )
            .then(
                (response) => {
                    if (response.body && !response.body.error) {
                        let data = response.body;
                        commit(types.EDIT_MEASURE_FOR_CURRENT_OPPORTUNITY, {data});
                        commit(types.EDIT_MEASURE_FOR_CURRENT_RISK, {data});
                    }
                    return response;
                },
                () => {}
            )
        ;
    },
};

const mutations = {};

export default {
    state,
    getters,
    actions,
    mutations,
};
