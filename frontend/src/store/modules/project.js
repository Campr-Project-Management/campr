import Vue from 'vue';
import * as types from '../mutation-types';
import _ from 'lodash';
import router from '../../router';

const state = {
    currentProject: {},
    projects: [],
    projectsForFilter: [],
    filteredProjects: {},
    projectFilters: [],
    labelsForChoice: [],
    loading: false,
    label: {},
    resources: [],
    tasksForSchedule: {},
    projectTasksStatus: {},
    risksOpportunitiesStats: [],
    costData: {},
    resourceData: {},
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
    label: state => state.label,
    tasksForSchedule: state => state.tasksForSchedule,
    projectTasksStatus: state => state.projectTasksStatus,
    risksOpportunitiesStats: state => state.risksOpportunitiesStats,
    projectsCount: state => state.projects.totalItems,
    projectsPerPage: state => state.projects.pageSize,
    costData: state => state.costData,
    resourceData: state => state.resourceData,
};

const actions = {
    /**
     * Calls edit project API to set 'favorite' property
     * and commits TOGGLE_FAVORITE mutation
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    toggleFavorite({commit}, data) {
        return Vue
            .http
            .patch(Routing.generate('app_api_project_edit', {id: data.project.id}), {favorite: data.favorite})
            .then(
                () => {
                    commit(types.TOGGLE_FAVORITE, data.project);
                },
                () => {
                    commit(types.TOGGLE_FAVORITE, data.project);
                }
            )
        ;
    },

    /**
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    editProject({commit}, data) {
        return Vue
            .http
            .patch(Routing.generate('app_api_project_edit', {id: data.projectId}), data)
            .then(
                (response) => {
                    let project = response.data;
                    commit(types.EDIT_PROJECT, project);
                }
            )
        ;
    },

    /**
     * Gets projects from the API and commits SET_PROJECTS mutation
     * @param {function} commit
     * @param {array} data
     *
     * @return {object}
     */
    getProjects({commit}, data) {
        let paramObject = {params: {}};
        if (data && data.queryParams && data.queryParams.page !== undefined) {
            paramObject.params.page = data.queryParams.page;
        }
        if (state.projectFilters && state.projectFilters.status) {
            paramObject.params.status = state.projectFilters.status;
        }
        if (state.projectFilters && state.projectFilters.programme) {
            paramObject.params.programme = state.projectFilters.programme;
        }
        if (state.projectFilters && state.projectFilters.customer) {
            paramObject.params.customer = state.projectFilters.customer;
        }
        return Vue
            .http
            .get(Routing.generate('app_api_project_list'), paramObject)
            .then(
                (response) => {
                    if (response.status === 200) {
                        let projects = response.data;
                        commit(types.SET_PROJECTS, {projects});
                    }
                },
                (response) => {}
            )
        ;
    },

    setProjectFilters({commit}, filters) {
        commit(types.SET_PROJECT_FILTERS, {filters});
    },

    /**
     * Gets a project by ID from the API and commits SET_PROJECT mutation
     * @param {function} commit
     * @param {number} id
     * @return {object}
     */
    getProjectById({commit}, id) {
        return Vue
            .http
            .get(Routing.generate('app_api_project_get', {'id': id}))
            .then(
                (response) => {
                    if (response.status === 200) {
                        let project = response.data;
                        commit(types.SET_PROJECT, {project});
                    }
                },
                (response) => {}
            )
        ;
    },

    /**
     * Gets all project labels from the API and commits SET_LABELS mutation
     * @param {function} commit
     * @param {number} id
     * @return {object}
     */
    getProjectLabels({commit}, id) {
        return Vue
            .http
            .get(Routing.generate('app_api_project_labels', {'id': id}))
            .then(
                (response) => {
                    if (response.status === 200) {
                        let labels = response.data;
                        commit(types.SET_LABELS, {labels});
                    }
                },
                (response) => {}
            )
        ;
    },

    /**
     * Gets a specific project label
     * @param {function} commit
     * @param {number} id
     * @return {object}
     */
    getProjectLabel({commit}, id) {
        return Vue
            .http
            .get(Routing.generate('app_api_label_get', {'id': id}))
            .then(
                (response) => {
                    if (response.status === 200) {
                        let label = response.data;
                        commit(types.SET_LABEL, {label});
                    }
                },
                (response) => {}
            )
        ;
    },

    /**
     * Creates a new label on project
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    createProjectLabel({commit}, data) {
        return Vue
            .http
            .post(
                Routing.generate('app_api_project_create_label', {'id': data.projectId}),
                JSON.stringify(data)
            )
            .then(
                (response) => {
                    if (response.body && response.body.error) {
                        const {messages} = response.body;
                        commit(types.SET_VALIDATION_MESSAGES, {messages});
                    } else {
                        commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                        router.push({name: 'project-task-management-edit-labels'});
                    }
                },
                () => {}
            )
        ;
    },

    /**
     * Edit a project label
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    editProjectLabel({commit}, data) {
        return Vue
            .http
            .patch(
                Routing.generate('app_api_label_edit', {'id': data.labelId}),
                JSON.stringify(data)
            )
            .then(
                (response) => {
                    if (response.body && response.body.error) {
                        const {messages} = response.body;
                        commit(types.SET_VALIDATION_MESSAGES, {messages});
                    } else {
                        commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                        router.push({name: 'project-task-management-edit-labels'});
                    }
                },
                () => {}
            )
        ;
    },

    /**
     * Delete a label
     * @param {function} commit
     * @param {int} id
     * @return {object}
     */
    deleteProjectLabel({commit}, id) {
        return Vue
            .http
            .delete(Routing.generate('app_api_label_delete', {'id': id}))
        ;
    },

    /**
     * Creates a new objective on project
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    createObjective({commit}, data) {
        return Vue.http
            .post(
                Routing.generate('app_api_project_create_objective', {'id': data.projectId}),
                JSON.stringify(data)
            ).then(
                (response) => {
                    if (response.body && response.body.error) {
                        const {messages} = response.body;
                        messages.createProjectObjectiveForm = true;
                        commit(types.SET_VALIDATION_MESSAGES, {messages});
                    } else {
                        let objective = response.body;
                        commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                        commit(types.ADD_PROJECT_OBJECTIVE, {objective});
                    }
                },
                (response) => {}
            )
        ;
    },

    /**
     * Edit a project objective
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    editObjective({commit}, data) {
        return Vue
            .http
            .patch(
                Routing.generate('app_api_project_objective_edit', {'id': data.itemId}),
                JSON.stringify(data)
            )
        ;
    },

    /**
     * Reorder project objectives
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    reorderObjectives({commit}, data) {
        return Vue
            .http
            .patch(
                Routing.generate('app_api_project_objective_reorder'),
                JSON.stringify(data)
            )
        ;
    },

    /**
     * Creates a new limitation on project
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    createLimitation({commit}, data) {
        return Vue
            .http
            .post(
                Routing.generate('app_api_project_create_limitation', {'id': data.projectId}),
                JSON.stringify(data)
            )
            .then(
                (response) => {
                    if (response.body && response.body.error) {
                        const {messages} = response.body;
                        messages.createProjectLimitationForm = true;
                        commit(types.SET_VALIDATION_MESSAGES, {messages});
                    } else {
                        let limitation = response.body;
                        commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                        commit(types.ADD_PROJECT_LIMITATION, {limitation});
                    }
                },
                (response) => {}
            )
        ;
    },

    /**
     * Reorder project limitations
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    reorderLimitations({commit}, data) {
        return Vue
            .http
            .patch(
                Routing.generate('app_api_project_limitation_reorder'),
                JSON.stringify(data)
            )
        ;
    },

    /**
     * Edit a project limitation
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    editLimitation({commit}, data) {
        return Vue
            .http
            .patch(
                Routing.generate('app_api_project_limitation_edit', {'id': data.itemId}),
                JSON.stringify(data)
            )
        ;
    },

    /**
     * Creates a new deliverable on project
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    createDeliverable({commit}, data) {
        return Vue
            .http
            .post(
                Routing.generate('app_api_project_create_deliverable', {'id': data.projectId}),
                JSON.stringify(data)
            )
            .then(
                (response) => {
                    if (response.body && response.body.error) {
                        const {messages} = response.body;
                        messages.createProjectDeliverableForm = true;
                        commit(types.SET_VALIDATION_MESSAGES, {messages});
                    } else {
                        let deliverable = response.body;
                        commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                        commit(types.ADD_PROJECT_DELIVERABLE, {deliverable});
                    }
                },
                (response) => {}
            );
    },

    /**
     * Edit a project deliverable
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    editDeliverable({commit}, data) {
        return Vue
            .http
            .patch(
                Routing.generate('app_api_project_deliverable_edit', {'id': data.itemId}),
                JSON.stringify(data)
            )
        ;
    },

    /**
     * Reorder project derivables
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    reorderDeliverables({commit}, data) {
        return Vue
            .http
            .patch(
                Routing.generate('app_api_project_deliverable_reorder'),
                JSON.stringify(data)
            )
        ;
    },

    /**
     * Creates a new project
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    createProject({commit}, data) {
        commit(types.SET_PROJECT_LOADING, {loading: true});
        return Vue
            .http
            .post(
                Routing.generate('app_api_project_create'),
                data
            )
            .then(
                (response) => {
                    if (response.status === 201) {
                        let project = response.data;
                        commit(types.SET_PROJECT, {project});
                    }
                    commit(types.SET_PROJECT_LOADING, {loading: false});
                },
                (response) => {
                    commit(types.SET_PROJECT_LOADING, {loading: false});
                }
            )
        ;
    },

    /**
     * Creates a new distribution list
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    createDistribution({commit}, data) {
        return Vue
            .http
            .post(
                Routing.generate('app_api_project_distribution_list_create', {'id': data.projectId}),
                JSON.stringify(data)
            )
            .then(
                (response) => {
                    if (response.body && response.body.error) {
                        const {messages} = response.body;
                        commit(types.SET_VALIDATION_MESSAGES, {messages});
                    } else {
                        const task = response.body;
                        commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                        commit(types.SET_TASK, {task});
                    }
                    return response.body;
                },
                (response) => {}
            )
        ;
    },

    /**
     * Gets basic tasks to determine the project schedule
     * @param {function} commit
     * @param {number} id
     * @return {object}
     */
    getTasksForSchedule({commit}, id) {
        return Vue
            .http
            .get(Routing.generate('app_api_project_schedule', {'id': id}))
            .then(
                (response) => {
                    if (response.status === 200) {
                        let tasks = response.data;
                        commit(types.SET_TASKS_FOR_SCHEDULE, {tasks});
                    }
                },
                (response) => {
                }
            )
        ;
    },

    /**
     * Gets the project tasks status
     * @param {function} commit
     * @param {number} id
     * @return {object}
     */
    getTasksStatus({commit}, id) {
        return Vue
            .http
            .get(Routing.generate('app_api_project_tasks_status', {'id': id}))
            .then(
                (response) => {
                    if (response.status === 200) {
                        let tasksStatus = response.data;
                        commit(types.SET_PROJECT_TASKS_STATUS, {tasksStatus});
                    }
                },
                (response) => {}
            )
        ;
    },
    /**
     * Gets project risks and opportunities stats
     * @param {function} commit
     * @param {number} id
     * @return {object}
     */
    getProjectRiskAndOpportunitiesStats({commit}, id) {
        return Vue
            .http
            .get(Routing.generate('app_api_project_risks_opportunities_stats', {'id': id}))
            .then(
                (response) => {
                    if (response.status === 200) {
                        let roStats = response.data;
                        commit(types.SET_PROJECT_RISKS_OPPORTUNITIES_STATS, {roStats});
                    }
                },
                (response) => {}
            )
        ;
    },

    /**
     * Gets project costs data
     * @param {function} commit
     * @param {number} data
     * @return {object}
     */
    getProjectCostsGraphData({commit}, data) {
        return Vue
            .http
            .get(Routing.generate('app_api_project_costs_graph_data', data))
            .then(
                (response) => {
                    if (response.status === 200) {
                        let costData = response.data;
                        commit(types.SET_PROJECT_COSTS_GRAPH_DATA, {costData});
                    }
                },
                (response) => {}
            )
        ;
    },
    /**
     * Gets project resource data
     * @param {function} commit
     * @param {number} data
     * @return {object}
     */
    getProjectResourcesGraphData({commit}, data) {
        return Vue
            .http
            .get(Routing.generate('app_api_project_resources_graph_data', data))
            .then(
                (response) => {
                    if (response.status === 200) {
                        let resourceData = response.data;
                        commit(types.SET_PROJECT_RESOURCES_GRAPH_DATA, {resourceData});
                    }
                },
                (response) => {}
            )
        ;
    },
};

const mutations = {
    /**
     * Sets the project filters
     * @param {Object} state
     * @param {array} filters
     */
    [types.SET_PROJECT_FILTERS](state, {filters}) {
        state.projectFilters = !filters.clear ? Object.assign({}, state.projectFilters, filters) : [];
    },
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
     * Edit project
     * @param {Object} state
     * @param {Object} project
     */
    [types.EDIT_PROJECT](state, project) {
        if (state.filteredProjects.items) {
            state.filteredProjects.items.map(item => {
                if (item.id === project.id) {
                    item.shortNote = project.shortNote;
                }
            });
        }
    },
    /**
     * Sets labels
     * @param {Object} state
     * @param {array} labels
     */
    [types.SET_LABELS](state, {labels}) {
        state.projects = labels;
        let choiceLabel = [];
        state.projects.map(function(label) {
            choiceLabel.push({'key': label.id, 'label': label.title, 'color': label.color});
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
     * Set project costs data
     * @param {Object} state
     * @param {array} costData
     */
    [types.SET_PROJECT_COSTS_GRAPH_DATA](state, {costData}) {
        state.costData = costData;
    },
    /**
     * Set project resource data
     * @param {Object} state
     * @param {array} costData
     */
    [types.SET_PROJECT_RESOURCES_GRAPH_DATA](state, {resourceData}) {
        state.resourceData = resourceData;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
