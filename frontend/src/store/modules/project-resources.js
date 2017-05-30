import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    projectResources: [],
    projectResourcesForSelect: [],
};

const getters = {
    projectResources: state => state.projectResources,
    projectResourcesForSelect: state => state.projectResourcesForSelect,
};

const actions = {
    getProjectResources({commit}, id) {
        Vue.
            http.
            get(Routing.generate('app_api_project_resources', {id})).
            then(
                (response) => {
                    if (response.status === 200) {
                        let resources = response.data;
                        commit(types.SET_PROJECT_RESOURCES, {resources});
                    } else {
                        commit(types.SET_PROJECT_RESOURCES, {resources: []});
                    }
                },
                (response) => {
                    commit(types.SET_PROJECT_RESOURCES, {resources: []});
                },
            )
        ;
    },
};

const mutations = {
    [types.SET_PROJECT_RESOURCES](state, {resources}) {
        state.projectResources = resources;
        state.projectResourcesForSelect = [];
        resources.map(function(item) {
            state.projectResourcesForSelect.push({
                key: item.id,
                label: item.name,
                rate: item.rate,
            });
        });
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
