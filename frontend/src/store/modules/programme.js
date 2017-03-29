import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    items: [],
};

const getters = {
    programmes: state => state.items,
    programmesForFilter: function(state) {
        let programmesForFilter = [{'key': '', 'label': Translator.trans('message.all_programmes')}];
        state.items.map(function(programme) {
            programmesForFilter.push({'key': programme.id, 'label': programme.name});
        });

        return programmesForFilter;
    },
};

const actions = {
    /**
     * Get all programmes.
     * @param {function} commit
     */
    getProgrammes({commit}) {
        Vue.http
            .get(Routing.generate('app_api_programmes_list')).then((response) => {
                if (response.status === 200) {
                    let programmes = response.data;
                    commit(types.SET_PROGRAMMES, {programmes});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets programmes to state
     * @param {Object} state
     * @param {array} programmes
     */
    [types.SET_PROGRAMMES](state, {programmes}) {
        state.items = programmes;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
