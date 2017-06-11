import Vue from 'vue';
import * as types from '../mutation-types';
import _ from 'lodash';
import router from '../../router';

const state = {
    currentProject: {},
    projects: [],
    projectsForFilter: [],
    filteredProjects: [],
    filters: [],
    labelsForChoice: [],
    loading: false,
    label: {},
    resources: [],
    projectResourcesForGraph: {},
    tasksForSchedule: {},
    projectTasksStatus: {},
    risksOpportunitiesStats: [],
    projectCostsAndResources: {},
};

const getters = {
    project: state => state.currentProject,
    projects: state => state.filteredProjects.items,
    projectLoading: state => state.loading,
    labels: state => state.projects,
    currentProjectTitle: function(state) {
        return state.currentProject.title;
    },
    projectsForFilter: state => state.projectsForFilter,
    labelsForChoice: state => state.labelsForChoice,
    projectResourcesForGraph: (state) => _.merge(
        {
            internal: {},
            external: {},
        },
        state.projectResourcesForGraph
    ),
    label: state => state.label,
    tasksForSchedule: state => state.tasksForSchedule,
    projectTasksStatus: state => state.projectTasksStatus,
    risksOpportunitiesStats: state => state.risksOpportunitiesStats,
    projectsCount: state => state.projects.totalItems,
    projectCostsAndResources: state => state.projectCostsAndResources,
};

const actions = {
    /**
    * Calls edit project API to set 'favorite' property
    * and commits TOGGLE_FAVORITE mutation
    * @param {function} commit
    * @param {array} data
    */
    toggleFavorite({commit}, data) {
        Vue.http
        .patch(Routing.generate('app_api_project_edit', {id: data.project.id}), {favorite: data.favorite})
        .then(() => {
            commit(types.TOGGLE_FAVORITE, data.project);
        }, (response) => {
            // TODO: REMOVE MOCK ACTION
            commit(types.TOGGLE_FAVORITE, data.project);
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
     * Gets a specific project label
     * @param {function} commit
     * @param {number} id
     */
    getProjectLabel({commit}, id) {
        Vue.http
            .get(Routing.generate('app_api_label_get', {'id': id})).then((response) => {
                if (response.status === 200) {
                    let label = response.data;
                    commit(types.SET_LABEL, {label});
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
     * Edit a project label
     * @param {function} commit
     * @param {array} data
     */
    editProjectLabel({commit}, data) {
        Vue.http
            .patch(
                Routing.generate('app_api_label_edit', {'id': data.labelId}),
                JSON.stringify(data)
            ).then((response) => {
                router.push({name: 'project-task-management-edit-labels'});
            }, (response) => {
                if (response.status === 400) {
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
            .get(Routing.generate('app_api_project_resources_graph', {'id': id})).then((response) => {
                if (response.status === 200) {
                    let resources = response.data;
                    commit(types.SET_PROJECT_RESOURCES_GRAPH, {resources});
                }
            }, (response) => {
            });
    },

    /**
     * Creates a new project
     * @param {function} commit
     * @param {array} data
     */
    createProject({commit}, data) {
        commit(types.SET_PROJECT_LOADING, {loading: true});
        Vue.http
            .post(
                Routing.generate('app_api_project_create'),
                data
            ).then((response) => {
                if (response.status === 201) {
                    let project = response.data;
                    commit(types.SET_PROJECT, {project});
                }
                commit(types.SET_PROJECT_LOADING, {loading: false});
            }, (response) => {
                commit(types.SET_PROJECT_LOADING, {loading: false});
            });
    },
    /**
     * Creates a new distribution list
     * @param {function} commit
     * @param {array} data
     */
    createDistribution({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_project_distribution_list_create', {'id': data.projectId}),
                JSON.stringify(data)
            ).then((response) => {
            }, (response) => {
                if (response.status === 400) {
                    console.log(response.data);
                }
            });
    },
    /**
     * Gets basic tasks to determine the project schedule
     * @param {function} commit
     * @param {number} id
     */
    getTasksForSchedule({commit}, id) {
        Vue.http
            .get(Routing.generate('app_api_project_schedule', {'id': id})).then((response) => {
                if (response.status === 200) {
                    let tasks = response.data;
                    commit(types.SET_TASKS_FOR_SCHEDULE, {tasks});
                }
            }, (response) => {
            });
    },
    /**
     * Gets the project tasks status
     * @param {function} commit
     * @param {number} id
     */
    getTasksStatus({commit}, id) {
        Vue.http
            .get(Routing.generate('app_api_project_tasks_status', {'id': id})).then((response) => {
                if (response.status === 200) {
                    let tasksStatus = response.data;
                    commit(types.SET_PROJECT_TASKS_STATUS, {tasksStatus});
                }
            }, (response) => {
            });
    },
    /**
     * Gets project risks and opportunities stats
     * @param {function} commit
     * @param {number} id
     */
    getProjectRiskAndOpportunitiesStats({commit}, id) {
        Vue.http
            .get(Routing.generate('app_api_project_risks_opportunities_stats', {'id': id})).then((response) => {
                if (response.status === 200) {
                    let roStats = response.data;
                    commit(types.SET_PROJECT_RISKS_OPPORTUNITIES_STATS, {roStats});
                }
            }, (response) => {
            });
    },

    /**
     * Gets project costs and resources data
     * @param {function} commit
     * @param {number} data
     */
    getProjectCostsResources({commit}, data) {
        Vue.http
            .get(Routing.generate('app_api_project_costs_resources', data)).then((response) => {
                if (response.status === 200) {
                    let projectCostsAndResources = response.data;
                    commit(types.SET_PROJECT_COSTS_AND_RESOURCES, {projectCostsAndResources});
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
        state.projects = projects;
        state.filteredProjects = JSON.parse(JSON.stringify(projects));
        let projectsForFilter = [{'key': '', 'label': Translator.trans('message.all_projects_filter')}];
        state.projects.items.map( function(project) {
            projectsForFilter.push({'key': project.id, 'label': project.name});
        });
        state.projectsForFilter = projectsForFilter;
    },

    /**
     * Sets project to state
     * @param {Object} state
     * @param {Object} project
     */
    [types.SET_PROJECT](state, {project}) {
        state.currentProject = project;
    },

    /**
     * Toggles projects favourite property
     * @param {Object} state
     * @param {Object} project
     */
    [types.TOGGLE_FAVORITE](state, project) {
        let stateProject = _.find(state.projects.items, {id: project.id});
        stateProject.favorite = !project.favorite;
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
        state.projects = labels;
        let choiceLabel = [];
        state.projects.map( function(label) {
            choiceLabel.push({'key': label.id, 'label': label.title});
        });
        state.labelsForChoice = choiceLabel;
    },

    [types.SET_PROJECT_LOADING](state, {loading}) {
        state.loading = loading;
    },

    /**
     * Add project objective
     * @param {Object} state
     * @param {array} objective
     */
    [types.ADD_PROJECT_OBJECTIVE](state, {objective}) {
        state.currentProject.projectObjectives.push(objective);
    },

    /**
     * Add project limitation
     * @param {Object} state
     * @param {array} limitation
     */
    [types.ADD_PROJECT_LIMITATION](state, {limitation}) {
        state.currentProject.projectLimitations.push(limitation);
    },

    /**
     * Add project deliverable
     * @param {Object} state
     * @param {array} deliverable
     */
    [types.ADD_PROJECT_DELIVERABLE](state, {deliverable}) {
        state.currentProject.projectDeliverables.push(deliverable);
    },

    /**
     * set project resources
     * @param {Object} state
     * @param {Object} resources
     */
    [types.SET_PROJECT_RESOURCES_GRAPH](state, {resources}) {
        state.projectResourcesForGraph = resources;
    },

    /**
     * set project label
     * @param {Object} state
     * @param {Object} label
     */
    [types.SET_LABEL](state, {label}) {
        state.label = label;
    },

    /**
     * Set project tasks for schedule
     * @param {Object} state
     * @param {array} tasks
     */
    [types.SET_TASKS_FOR_SCHEDULE](state, {tasks}) {
        state.tasksForSchedule = tasks;
    },

    /**
     * Set project tasks status
     * @param {Object} state
     * @param {array} tasksStatus
     */
    [types.SET_PROJECT_TASKS_STATUS](state, {tasksStatus}) {
        state.projectTasksStatus = tasksStatus;
    },

    /**
     * Set project risks and opporunities stats
     * @param {Object} state
     * @param {array} roStats
     */
    [types.SET_PROJECT_RISKS_OPPORTUNITIES_STATS](state, {roStats}) {
        state.risksOpportunitiesStats = roStats;
    },
    /**
     * Set project costs and resources data
     * @param {Object} state
     * @param {array} projectCostsAndResources
     */
    [types.SET_PROJECT_COSTS_AND_RESOURCES](state, {projectCostsAndResources}) {
        state.projectCostsAndResources = projectCostsAndResources;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
