import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    todoStatuses: [],
};

const getters = {
    todoStatuses: state => state.todoStatuses,
    todoStatusesForSelect: state => {
        let statusesSelect = [{'key': null, 'label': Translator.trans('placeholder.status')}];
        state.todoStatuses.map(function(item) {
            statusesSelect.push({'key': item.id, 'label': Translator.trans(item.name)});
        });
        return statusesSelect;
    },
};

const actions = {
    getTodoStatuses({commit}) {
        Vue.http
            .get(Routing.generate('app_api_todo_statuses_list')).then((response) => {
                if (response.status === 200) {
                    let statuses = response.data;
                    commit(types.SET_TODO_STATUSES, {statuses});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets todo statuses to state
     * @param {Object} state
     * @param {array} statuses
     */
    [types.SET_TODO_STATUSES](state, {statuses}) {
        state.todoStatuses = statuses;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
