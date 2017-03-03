import Vue from 'vue';
import * as types from '../mutation-types';
import _ from 'lodash';
import router from '../../router';

const state = {
    currentItem: {},
    items: [],
    itemsForFilter: [],
    filteredItems: [],
    filters: [],
};

const getters = {
    project: state => state.currentItem,
    projects: state => state.filteredItems.items,
    labels: state => state.items,
    currentProjectTitle: function(state) {
        return state.currentItem.title;
    },
    projectsForFilter: state => state.itemsForFilter,
};

const actions = {
    /**
    * Calls edit project API to set 'favourite' property
    * and commits TOGGLE_FAVOURITE mutation
    * @param {function} commit
    * @param {Object} project
    */
    toggleFavourite({commit}, project) {
        Vue.http
        .patch(Routing.generate('app_api_project_edit', {'id': project.id}).substr(1), {favourite: !project.favourite})
        .then(() => {
            commit(types.TOGGLE_FAVOURITE, project);
        }, (response) => {
            // TODO: REMOVE MOCK ACTION
            commit(types.TOGGLE_FAVOURITE, project);
        });
    },
    /**
     * Gets projects from the API and commits SET_PROJECTS mutation
     * @param {function} commit
     */
    getProjects({commit}) {
        commit(types.TOGGLE_LOADER, true);
        Vue.http
        .get(Routing.generate('app_api_project_list').substr(1)).then((response) => {
            if (response.status === 200) {
                let projects = response.data;
                commit(types.SET_PROJECTS, {projects});
                commit(types.TOGGLE_LOADER, false);
            }
        }, (response) => {
        });
    },
    /**
     * Gets a project by ID from the API and commits SET_PROJECT mutation
     * @param {function} commit
     * @param {number} id
     */
    getProjectById({commit}, id) {
        Vue.http
            .get(Routing.generate('app_api_project_get', {'id': id}).substr(1)).then((response) => {
                if (response.status === 200) {
                    let project = response.data;
                    commit(types.SET_PROJECT, {project});
                }
            }, (response) => {
            });
    },
    /**
     * Gets all project labels from the API and commits SET_LABELS mutation
     * @param {function} commit
     * @param {number} id
     */
    getProjectLabels({commit}, id) {
        Vue.http
            .get(Routing.generate('app_api_project_labels', {'id': id}).substr(1)).then((response) => {
                if (response.status === 200) {
                    let labels = response.data;
                    commit(types.SET_LABELS, {labels});
                }
            }, (response) => {
            });
    },
    /**
     * Creates a new label on project
     * @param {function} commit
     * @param {array} data
     */
    createProjectLabel({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_project_create_label', {'id': data.projectId}).substr(1),
                JSON.stringify(data)
            ).then((response) => {
                router.push({name: 'project-task-management-edit-labels'});
            }, (response) => {
                if (response.status === 400) {
                    // implement system to dispay errors
                    console.log(response.data);
                }
            });
    },
    /**
     * Delete a label
     * @param {function} commit
     * @param {int} id
     */
    deleteProjectLabel({commit}, id) {
        Vue.http
            .delete(Routing.generate('app_api_label_delete', {'id': id}).substr(1)).then((response) => {
                if (response.status === 204) {
                    router.push({name: 'project-task-management-edit-labels'});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets projects to state
     * @param {Object} state
     * @param {array} projects
     */
    [types.SET_PROJECTS](state, {projects}) {
        state.items = projects;
        state.filteredItems = JSON.parse(JSON.stringify(projects));
        let projectsForFilter = [{'key': '', 'label': 'All Projects'}];
        state.items.items.map( function(project) {
            projectsForFilter.push({'key': project.id, 'label': project.name});
        });
        state.itemsForFilter = projectsForFilter;
    },
    /**
     * Sets project to state
     * @param {Object} state
     * @param {Object} project
     */
    [types.SET_PROJECT](state, {project}) {
        state.currentItem = project;
    },
    /**
     * Toggles projects favourite property
     * @param {Object} state
     * @param {Object} project
     */
    [types.TOGGLE_FAVOURITE](state, project) {
        let stateProject = _.find(state.items, {id: project.id});
        stateProject.favourite = !project.favourite;
    },
    /**
     * Sets filters to state
     * @param {Object} state
     * @param {array} filter
     */
    [types.SET_FILTERS](state, filter) {
        state.filters[filter[0]] = filter[1];
    },
    /**
     * Sets labels
     * @param {Object} state
     * @param {array} labels
     */
    [types.SET_LABELS](state, {labels}) {
        state.items = labels;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
