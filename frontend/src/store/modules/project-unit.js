import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    projectUnits: [],
};

const getters = {
    projectUnits: state => state.projectUnits,
    projectUnitsForSelect: state => {
        let units = state.projectUnits.map(item => {
            return {
                'key': item.id,
                'label': Translator.trans(item.name),
            };
        });
        units.push({
            key: 'custom',
            label: 'Custom',
        });
        return units;
    },
};

const actions = {
    /**
     * Get all project units
     * @param {function} commit
     * @param {Number} projectId
     */
    getProjectUnits({commit}, projectId) {
        Vue.http
            .get(Routing.generate('app_api_project_units', {'id': projectId})).then((response) => {
                if (response.status === 200) {
                    let units = response.data;
                    commit(types.SET_PROJECT_UNITS, {units});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets project units to state
     * @param {Object} state
     * @param {array} units
     */
    [types.SET_PROJECT_UNITS](state, {units}) {
        state.projectUnits = units;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
