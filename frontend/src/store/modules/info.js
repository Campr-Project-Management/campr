import Vue from 'vue';
import * as types from '../mutation-types';
import _ from 'lodash';

const state = {
    info: null,
    infos: [],
    infosFilters: {
        // actual filters
        user: null,
        infoStatus: null,
        infoCategory: null,
        // pagination stuff
        currentPage: 1,
        numberOfPages: 0,
        numberOfItems: 0,
        itemsPerPage: 10,
    },
};

const getters = {
    info: (state) => state.info,
    infos: (state) => state.infos,
    infosFilters: (state) => state.infosFilters,
};

const actions = {
    clearInfo({commit}) {
        commit(types.SET_INFO, {});
    },
    getInfo({commit}, id) {
        return Vue
            .http
            .get(Routing.generate('app_api_infos_get', {id}))
            .then(
                (response) => {
                    commit(types.SET_INFO, _.isObject(response.body) ? response.body : null);

                    return response;
                },
                () => {
                    commit(types.SET_INFO, null);
                }
            )
        ;
    },
    createInfo({commit}, {projectId, data}) {
        return Vue
            .http
            .post(Routing.generate('app_api_project_create_info', {id: projectId}), data)
            .then(
                (response) => {
                    if (response.body && response.body.error) {
                        const {messages} = response.body;
                        commit(types.SET_VALIDATION_MESSAGES, {messages});
                    } else {
                        const info = response.body;
                        commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                        commit(types.SET_INFO, info);
                        commit(types.ADD_MEETING_INFO, {info});
                    }

                    return response;
                },
                (response) => {}
            )
        ;
    },
    editInfo({commit}, {id, data}) {
        return Vue
            .http
            .patch(Routing.generate('app_api_infos_edit', {id}), data)
            .then(
                (response) => {
                    if (response.body && response.body.error) {
                        const {messages} = response.body;
                        commit(types.SET_VALIDATION_MESSAGES, {messages});
                    } else {
                        const info = response.body;
                        commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                        commit(types.SET_INFO, info);
                        commit(types.EDIT_MEETING_INFO, {info});
                    }

                    return response;
                },
                (response) => {}
            )
        ;
    },
    deleteInfo({commit}, {id}) {
        return Vue
            .http
            .delete(Routing.generate('app_api_infos_delete', {id}))
            .then(
                (response) => {
                    commit(types.DELETE_MEETING_INFO, {infoId: id});

                    return response;
                },
                () => {}
            )
        ;
    },
    getInfosByProject({commit, state}, {id}) {
        const params = {id};

        if (state.infosFilters && state.infosFilters.itemsPerPage) {
            params.per_page = state.infosFilters.itemsPerPage;
        }

        if (state.infosFilters && state.infosFilters.currentPage) {
            params.page = state.infosFilters.currentPage;
        }

        if (state.infosFilters && state.infosFilters.user) {
            params.user = state.infosFilters.user;
        }

        if (state.infosFilters && state.infosFilters.infoStatus) {
            params.info_status = state.infosFilters.infoStatus;
        }

        if (state.infosFilters && state.infosFilters.infoCategory) {
            params.info_category = state.infosFilters.infoCategory;
        }

        commit(types.SET_INFOS, {
            items: [],
            currentPage: 1,
            numberOfPages: 0,
            numberOfItems: 0,
            itemsPerPage: 10,
        });

        return Vue
            .http
            .get(Routing.generate('app_api_project_infos', params))
            .then(
                (response) => {
                    if (response && _.isObject(response.body) && _.isArray(response.body.items)) {
                        commit(types.SET_INFOS, response.body);
                    } else {
                        commit(types.SET_INFOS, {
                            items: [],
                            currentPage: 1,
                            numberOfPages: 0,
                            numberOfItems: 0,
                            itemsPerPage: 10,
                        });
                    }
                },
                () => {
                    commit(types.SET_INFOS, {
                        items: [],
                        currentPage: 1,
                        numberOfPages: 0,
                        numberOfItems: 0,
                        itemsPerPage: 10,
                    });
                }
            )
        ;
    },
    setInfoFiltersUser({commit}, {user}) {
        commit(types.SET_INFOS_FILTERS_USER, {user});
    },
    setInfoFiltersInfoStatus({commit}, {infoStatus}) {
        commit(types.SET_INFOS_FILTERS_INFO_STATUS, {infoStatus});
    },
    setInfoFiltersInfoCategory({commit}, {infoCategory}) {
        commit(types.SET_INFOS_FILTERS_INFO_CATEGORY, {infoCategory});
    },
    setInfoPage({commit}, {page}) {
        commit(types.SET_INFOS_PAGE, {page});
    },
    clearFilters({commit}) {
        commit(types.SET_INFOS_FILTERS_USER, {user: null});
        commit(types.SET_INFOS_FILTERS_INFO_STATUS, {infoStatus: null});
        commit(types.SET_INFOS_FILTERS_INFO_CATEGORY, {infoCategory: null});
    },
};

const mutations = {
    [types.SET_INFOS](state, {items, currentPage, numberOfPages, numberOfItems, itemsPerPage}) {
        state.infos = items;
        state.infosFilters.currentPage = currentPage;
        state.infosFilters.numberOfPages = numberOfPages;
        state.infosFilters.numberOfItems = numberOfItems;
        state.infosFilters.itemsPerPage = itemsPerPage;
    },
    [types.SET_INFOS_FILTERS_USER](state, {user}) {
        state.infosFilters.user = user;
    },
    [types.SET_INFOS_FILTERS_INFO_STATUS](state, {infoStatus}) {
        state.infosFilters.infoStatus = infoStatus;
    },
    [types.SET_INFOS_FILTERS_INFO_CATEGORY](state, {infoCategory}) {
        state.infosFilters.infoCategory = infoCategory;
    },
    [types.SET_INFOS_PAGE](state, {page}) {
        state.infosFilters.currentPage = page;
    },
    [types.SET_INFO](state, info) {
        state.info = info;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
