import Vue from 'vue';
import * as types from '../mutation-types';
import _ from 'lodash';

const state = {
    workPackageStatuses: [],
};
const STATUS_OPEN_ID = 1;
const STATUS_COMPLETED_ID = 4;

const getters = {
    workPackageStatuses: state => state.workPackageStatuses,
    workPackageStatusesForMilestone: (state) => {
        let workPackageStatusesForMilestone = [];

        const allowedStatuses = [STATUS_OPEN_ID, STATUS_COMPLETED_ID];

        state.workPackageStatuses.map(function(workPackageStatus) {
            if (allowedStatuses.indexOf(workPackageStatus.id) !== -1) {
                workPackageStatusesForMilestone.push({
                    'key': workPackageStatus.id,
                    'label': Vue.translate(workPackageStatus.name),
                });
            }
        });

        return workPackageStatusesForMilestone;
    },
    workPackageStatusesForSelect: (state) => {
        return state.workPackageStatuses.filter(item => !!item.visible).
            map((item) => ({
                key: item.id,
                label: Vue.translate(item.name),
            }));
    },
    workPackageStatusById: ({workPackageStatuses}) => (id) => {
        return _.find(workPackageStatuses, (workPackageStatus) => {
            return workPackageStatus.id === id;
        });
    },
    workPackageStatusesCount: state => state.workPackageStatuses.totalItems,
};

const actions = {
    getWorkPackageStatuses({commit}) {
        Vue.http
            .get(Routing.generate('app_api_workpackage_statuses_list'))
            .then(
                (response) => {
                    if (response.status === 200) {
                        let workPackageStatuses = response.data;
                        commit(types.SET_WORK_PACKAGE_STATUSES, {workPackageStatuses});
                    }
                },
                (response) => {
                    //
                }
            );
    },
};

const mutations = {
    [types.SET_WORK_PACKAGE_STATUSES](state, {workPackageStatuses}) {
        state.workPackageStatuses = workPackageStatuses;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
