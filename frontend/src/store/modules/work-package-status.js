import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    items: [],
};
const STATUS_OPEN_ID = 1;
const STATUS_COMPLETED_ID = 4;

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
    workPackageStatusesForMilestone: (state) => {
        let workPackageStatusesForMilestone = [];

        state.items.map(function(workPackageStatus) {
            const allowedStatuses = [STATUS_OPEN_ID, STATUS_COMPLETED_ID];

            if (allowedStatuses.indexOf(workPackageStatus.id) !== -1) {
                workPackageStatusesForMilestone.push({'key': workPackageStatus.id, 'label': Vue.translate(workPackageStatus.name)});
            }
        });

        return workPackageStatusesForMilestone;
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
