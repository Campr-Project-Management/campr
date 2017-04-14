import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    resources: [],
    resourcesForSelect: [],
};

const getters = {
    projectResources: state => state.resources,
    projectResourcesForSelect: state => state.resourcesForSelect,
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
        state.resources = resources;
        state.resourcesForSelect = [];
        resources.map(function(item) {
            state.resourcesForSelect.push({
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
