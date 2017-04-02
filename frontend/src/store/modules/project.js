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
    labelsForChoice: [],
};

const getters = {
    project: state => state.currentItem,
    projects: state => state.filteredItems.items,
    labels: state => state.items,
    currentProjectTitle: function(state) {
        return state.currentItem.title;
    },
    projectsForFilter: state => state.itemsForFilter,
    labelsForChoice: state => state.labelsForChoice,
    sponsorsManagers: state => state.sponsorsManagers,
    projectResources: state => state.projectResources,
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
        .patch(Routing.generate('app_api_project_edit', {'id': project.id}), {favourite: !project.favourite})
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
        .get(Routing.generate('app_api_project_list')).then((response) => {
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
            .get(Routing.generate('app_api_project_get', {'id': id})).then((response) => {
                if (response.status === 200) {
                    let project = response.data;
                    commit(types.SET_PROJECT, {project});
                    commit(types.SET_MEMBERS, {project});
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
            .get(Routing.generate('app_api_project_labels', {'id': id})).then((response) => {
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
                Routing.generate('app_api_project_create_label', {'id': data.projectId}),
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
            .delete(Routing.generate('app_api_label_delete', {'id': id})).then((response) => {
                if (response.status === 204) {
                    router.push({name: 'project-task-management-edit-labels'});
                }
            }, (response) => {
            });
    },
    /**
     * Creates a new objective on project
     * @param {function} commit
     * @param {array} data
     */
    createObjective({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_project_create_objective', {'id': data.projectId}),
                JSON.stringify(data)
            ).then((response) => {
                let objective = response.data;
                commit(types.ADD_PROJECT_OBJECTIVE, {objective});
            }, (response) => {
                if (response.status === 400) {
                    console.log(response.data);
                }
            });
    },
    /**
     * Edit a project objective
     * @param {function} commit
     * @param {array} data
     */
    editObjective({commit}, data) {
        Vue.http
            .patch(
                Routing.generate('app_api_project_objective_edit', {'id': data.itemId}),
                JSON.stringify(data)
            ).then((response) => {
            }, (response) => {
                if (response.status === 400) {
                    console.log(response.data);
                }
            });
    },
    /**
     * Reorder project objectives
     * @param {function} commit
     * @param {array} data
     */
    reorderObjectives({commit}, data) {
        Vue.http
            .patch(
                Routing.generate('app_api_project_objective_reorder'),
                JSON.stringify(data)
            ).then((response) => {
            }, (response) => {
                if (response.status === 400) {
                    console.log(response.data);
                }
            });
    },
    /**
     * Creates a new limitation on project
     * @param {function} commit
     * @param {array} data
     */
    createLimitation({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_project_create_limitation', {'id': data.projectId}),
                JSON.stringify(data)
            ).then((response) => {
                let limitation = response.data;
                commit(types.ADD_PROJECT_LIMITATION, {limitation});
            }, (response) => {
                if (response.status === 400) {
                    console.log(response.data);
                }
            });
    },
    /**
     * Reorder project limitations
     * @param {function} commit
     * @param {array} data
     */
    reorderLimitations({commit}, data) {
        Vue.http
            .patch(
                Routing.generate('app_api_project_limitation_reorder'),
                JSON.stringify(data)
            ).then((response) => {
            }, (response) => {
                if (response.status === 400) {
                    console.log(response.data);
                }
            });
    },
    /**
     * Edit a project limitation
     * @param {function} commit
     * @param {array} data
     */
    editLimitation({commit}, data) {
        Vue.http
            .patch(
                Routing.generate('app_api_project_limitation_edit', {'id': data.itemId}),
                JSON.stringify(data)
            ).then((response) => {
            }, (response) => {
                if (response.status === 400) {
                    console.log(response.data);
                }
            });
    },
    /**
     * Creates a new deliverable on project
     * @param {function} commit
     * @param {array} data
     */
    createDeliverable({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_project_create_deliverable', {'id': data.projectId}),
                JSON.stringify(data)
            ).then((response) => {
                let deliverable = response.data;
                commit(types.ADD_PROJECT_DELIVERABLE, {deliverable});
            }, (response) => {
                if (response.status === 400) {
                    console.log(response.data);
                }
            });
    },
    /**
     * Edit a project deliverable
     * @param {function} commit
     * @param {array} data
     */
    editDeliverable({commit}, data) {
        Vue.http
            .patch(
                Routing.generate('app_api_project_deliverable_edit', {'id': data.itemId}),
                JSON.stringify(data)
            ).then((response) => {
            }, (response) => {
                if (response.status === 400) {
                    console.log(response.data);
                }
            });
    },
    /**
     * Reorder project derivables
     * @param {function} commit
     * @param {array} data
     */
    reorderDeliverables({commit}, data) {
        Vue.http
            .patch(
                Routing.generate('app_api_project_deliverable_reorder'),
                JSON.stringify(data)
            ).then((response) => {
            }, (response) => {
                if (response.status === 400) {
                    console.log(response.data);
                }
            });
    },
    /**
     * Gets project resources values for graphic
     * @param {function} commit
     * @param {number} id
     */
    getProjectResourcesForGraph({commit}, id) {
        Vue.http
            .get(Routing.generate('app_api_project_resources', {'id': id})).then((response) => {
                if (response.status === 200) {
                    let resources = response.data;
                    commit(types.SET_PROJECT_RESOURCES, {resources});
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
        let projectsForFilter = [{'key': '', 'label': Translator.trans('message.all_projects_filter')}];
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
        let choiceLabel = [];
        state.items.map( function(label) {
            choiceLabel.push({'key': label.id, 'label': label.title});
        });
        state.labelsForChoice = choiceLabel;
    },
    /**
     * Add project objective
     * @param {Object} state
     * @param {array} objective
     */
    [types.ADD_PROJECT_OBJECTIVE](state, {objective}) {
        state.currentItem.projectObjectives.push(objective);
    },
    /**
     * Add project limitation
     * @param {Object} state
     * @param {array} limitation
     */
    [types.ADD_PROJECT_LIMITATION](state, {limitation}) {
        state.currentItem.projectLimitations.push(limitation);
    },
    /**
     * Add project deliverable
     * @param {Object} state
     * @param {array} deliverable
     */
    [types.ADD_PROJECT_DELIVERABLE](state, {deliverable}) {
        state.currentItem.projectDeliverables.push(deliverable);
    },
    /**
     * set project member
     * @param {Object} state
     * @param {Object} project
     */
    [types.SET_MEMBERS](state, {project}) {
        let sponsorsManagers = [];
        project.projectUsers.map( function(projectUser) {
            if (projectUser.projectRoleName !== 'ROLE_TEAM_MEMBER') {
                sponsorsManagers.push(projectUser);
            }
        });
        state.sponsorsManagers = sponsorsManagers;
    },
    /**
     * set project resources
     * @param {Object} state
     * @param {Object} resources
     */
    [types.SET_PROJECT_RESOURCES](state, {resources}) {
        state.projectResources = resources;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
