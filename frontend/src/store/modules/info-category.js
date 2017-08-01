import Vue from 'vue';
import * as types from '../mutation-types';
import _ from 'lodash';

const state = {
    infoCategories: [],
};

const getters = {
    infoCategories: (state) => state.infoCategories,
    infoCategoriesForDropdown: (state) => state.infoCategories.map((item) => {
        return {
            key: item.id,
            label: Vue.translate(item.name),
        };
    }),
};

const actions = {
    getInfoCategories({commit}) {
        return Vue
            .http
            .get(Routing.generate('app_api_info_categories'))
            .then(
                (response) => {
                    if (response.body && _.isArray(response.body)) {
                        commit(types.SET_INFO_CATEGORIES, response.body);
                    } else {
                        commit(types.SET_INFO_CATEGORIES, []);
                    }
                },
                () => {
                    commit(types.SET_INFO_CATEGORIES, []);
                }
            )
        ;
    },
};

const mutations = {
    [types.SET_INFO_CATEGORIES](state, infoCategories) {
        state.infoCategories = infoCategories;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
