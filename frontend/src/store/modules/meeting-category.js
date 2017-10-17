import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    meetingCategories: [],
};

const getters = {
    meetingCategories: state => state.meetingCategories,
    meetingCategoriesForSelect: state => {
        let categoriesSelect = [];
        state.meetingCategories.map(function(item) {
            categoriesSelect.push({'key': item.id, 'label': item.name});
        });
        return categoriesSelect;
    },
};

const actions = {
    getMeetingCategories({commit}) {
        Vue.http
            .get(Routing.generate('app_api_meeting_categories_list')).then((response) => {
                if (response.status === 200) {
                    let categories = response.data;
                    commit(types.SET_MEETING_CATEGORIES, {categories});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets meeting categories to state
     * @param {Object} state
     * @param {array} categories
     */
    [types.SET_MEETING_CATEGORIES](state, {categories}) {
        state.meetingCategories = categories;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
