import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    todoCategories: [],
};

const getters = {
    todoCategories: state => state.todoCategories,
    todoCategoriesForSelect: state => {
        let categoriesSelect = [];
        state.todoCategories.map(function(item) {
            categoriesSelect.push({'key': item.id, 'label': item.name});
        });
        return categoriesSelect;
    },
};

const actions = {
    getTodoCategories({commit}) {
        Vue.http
            .get(Routing.generate('app_api_todo_categories_list')).then((response) => {
                if (response.status === 200) {
                    let categories = response.data;
                    commit(types.SET_TODO_CATEGORIES, {categories});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets todo categories to state
     * @param {Object} state
     * @param {array} categories
     */
    [types.SET_TODO_CATEGORIES](state, {categories}) {
        state.todoCategories = categories;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
