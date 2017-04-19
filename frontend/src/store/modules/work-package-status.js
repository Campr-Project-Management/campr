import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    items: [],
};

const getters = {
    workPackageStatuses: state => state.items,
    workPackageStatusesForSelect: (state) => {
        return state.items.map((item) => {
            return {
                key: item.id,
                label: Vue.translate(item.name),
            };
        });
    },
};

const actions = {
    getWorkPackageStatuses({commit}) {
        Vue.http
            .get(Routing.generate('app_api_workpackage_statuses_list')).then((response) => {
                if (response.status === 200) {
                    let workPackageStatuses = response.data;
                    commit(types.SET_WORK_PACKAGE_STATUSES, {workPackageStatuses});
                }
            }, (response) => {
                //
            });
    },
};

const mutations = {
    [types.SET_WORK_PACKAGE_STATUSES](state, {workPackageStatuses}) {
        state.items = workPackageStatuses;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
