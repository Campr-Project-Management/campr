import Vue from 'vue';
import * as types from '../mutation-types';
import _ from 'lodash';

const state = {
    infoStatuses: [],
};

const getters = {
    infoStatuses: (state) => state.infoStatuses,
    infoStatusesForDropdown: (state) => state.infoStatuses.map((item) => {
        return {
            key: item.id,
            label: Vue.translate(item.name),
        };
    }),
};

const actions = {
    getInfoStatuses({commit}) {
        return Vue
            .http
            .get(Routing.generate('app_api_info_statuses'))
            .then(
                (response) => {
                    if (response.body && _.isArray(response.body)) {
                        commit(types.SET_INFO_STATUSES, response.body);
                    } else {
                        commit(types.SET_INFO_STATUSES, []);
                    }
                },
                () => {
                    commit(types.SET_INFO_STATUSES, []);
                }
            )
        ;
    },
};

const mutations = {
    [types.SET_INFO_STATUSES](state, infoStatuses) {
        state.infoStatuses = infoStatuses;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
