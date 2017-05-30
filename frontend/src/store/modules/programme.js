import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    programmes: [],
    programmesForSelect: [],
    loading: false,
};

const getters = {
    programmes: state => state.programmes,
    programmesForSelect: state => state.programmesForSelect,
    programmeLoading: state => state.loading,
    programmesForFilter: function(state) {
        let programmesForFilter = [{'key': '', 'label': Translator.trans('message.all_programmes')}];
        state.programmes.map(function(programme) {
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
        commit(types.SET_PROGRAMME_LOADING, {loading: true});
        Vue.http
            .get(Routing.generate('app_api_programmes_list')).then((response) => {
                if (response.status === 200) {
                    let programmes = response.data;
                    commit(types.SET_PROGRAMMES, {programmes});
                }
                commit(types.SET_PROGRAMME_LOADING, {loading: false});
            }, (response) => {
                commit(types.SET_PROGRAMME_LOADING, {loading: false});
            });
    },
    /**
     * Creates a new programme
     * @param {function} commit
     * @param {array} data
     */
    createProgramme({commit}, data) {
        commit(types.SET_PROGRAMME_LOADING, {loading: true});
        Vue.http
            .post(
                Routing.generate('app_api_programmes_create'),
                JSON.stringify(data)
            ).then((response) => {
                if (response.status === 201) {
                    let programme = response.data;
                    commit(types.ADD_PROGRAMME, {programme});
                }
                commit(types.SET_PROGRAMME_LOADING, {loading: false});
            }, (response) => {
                commit(types.SET_PROGRAMME_LOADING, {loading: false});
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
        state.programmes = programmes;
        let programmesForSelect = [];
        state.programmes.map((programme) => {
            programmesForSelect.push({'key': programme.id, 'label': programme.name});
        });
        state.programmesForSelect = programmesForSelect;
    },
    /**
     * @param {Object} state
     * @param {array} loading
     */
    [types.SET_PROGRAMME_LOADING](state, {loading}) {
        state.loading = loading;
    },
    [types.ADD_PROGRAMME](state, {programme}) {
        state.programmes.push(programme);
        let programmesForSelect = [];
        state.programmes.map((programme) => {
            programmesForSelect.push({'key': programme.id, 'label': programme.name});
        });
        state.programmesForSelect = programmesForSelect;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
