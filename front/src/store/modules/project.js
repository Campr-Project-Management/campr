import Vue from 'vue';
import * as types from '../mutation-types';
import _ from 'lodash';

const state = {
    currentItem: {},
    items: [],
    filteredItems: [],
    filters: [],
};

const getters = {
    project: state => state.currentItem,
    projects: state => state.items,
    filteredProjects: state => state.filteredItems,
    projectsForFilter: function(state) {
        let projectsForFilter = [{'key': '', 'label': 'All Projects'}];
        state.items.map( function(project) {
            projectsForFilter.push({'key': project.id, 'label': project.name});
        });

        return projectsForFilter;
    },
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
        .post('/api/projects/' + project.id, {favourite: !project.favourite})
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
        Vue.http
        .get('/api/project/list').then((response) => {
            let projects = response.data;
            commit(types.SET_PROJECTS, {projects});
        }, (response) => {
            // TODO: REMOVE MOCK DATA
            let projects = [{
                'id': 1,
                'title': 'Campr Dashboard Design',
                'customer': 'Christoph Poll',
                'date': '2016-05-12',
                'status': 'NOT_STARTED',
                'programme': 'Campr',
                'progress': 0,
                'task_status': 77,
                'costs_status': 35.67,
                'favourite': true,
                'notes': [
                    'Lorem ipsum dolor sit amet, consectetur' +
                    ' adipiscing elit.' +
                    ' Duis at dolor sollicitudin, ' +
                    'interdum nibh quis, faucibus justo. ' +
                    'Suspendisse sed nisi id mi aliquam finibus ac sem.',
                    'Suspendisse in massa in ligula suscipit vulputate.',
                    'Sed finibus massa nec est rutrum malesuada' +
                    ' a et eros.' +
                    ' Cras volutpat leo eu lorem viverra ornare.',
                ],
            }, {
                'id': 2,
                'title': 'Campr Dashboard Design',
                'customer': 'Christoph Poll',
                'date': '2016-05-12',
                'status': 'IN_PROGRESS',
                'programme': 'Campr',
                'progress': 75,
                'task_status': 77,
                'costs_status': 35.67,
                'favourite': false,
                'notes': [
                    'Lorem ipsum dolor sit amet, consectetur' +
                    ' adipiscing elit.' +
                    ' Duis at dolor sollicitudin, ' +
                    'interdum nibh quis, faucibus justo. ' +
                    'Suspendisse sed nisi id mi aliquam finibus ac sem.',
                    'Suspendisse in massa in ligula suscipit vulputate.',
                    'Sed finibus massa nec est rutrum malesuada ' +
                    'a et eros.' +
                    ' Cras volutpat leo eu lorem viverra ornare.',
                ],
            }, {
                'id': 3,
                'title': 'Campr Dashboard Design',
                'customer': 'Christoph Poll',
                'date': '2016-05-12',
                'status': 'IN_PROGRESS',
                'programme': 'Campr',
                'progress': 75,
                'task_status': 77,
                'costs_status': 35.67,
                'favourite': true,
                'notes': [
                    'Lorem ipsum dolor sit amet, consectetur' +
                    ' adipiscing elit.' +
                    ' Duis at dolor sollicitudin, ' +
                    'interdum nibh quis, faucibus justo. ' +
                    'Suspendisse sed nisi id mi aliquam finibus ac sem.',
                    'Suspendisse in massa in ligula suscipit vulputate.',
                    'Sed finibus massa nec est rutrum malesuada ' +
                    'a et eros.' +
                    ' Cras volutpat leo eu lorem viverra ornare.',
                ],
            }, {
                'id': 4,
                'title': 'Campr Dashboard Design',
                'customer': 'Christoph Poll',
                'date': '2016-05-12',
                'status': 'IN_PROGRESS',
                'programme': 'Campr',
                'progress': 75,
                'task_status': 77,
                'costs_status': 35.67,
                'favourite': false,
                'notes': [
                    'Lorem ipsum dolor sit amet, consectetur ' +
                    'adipiscing elit.' +
                    ' Duis at dolor sollicitudin, ' +
                    'interdum nibh quis, faucibus justo. ' +
                    'Suspendisse sed nisi id mi aliquam finibus ac sem.',
                    'Suspendisse in massa in ligula suscipit vulputate.',
                    'Sed finibus massa nec est rutrum malesuada' +
                    ' a et eros.' +
                    ' Cras volutpat leo eu lorem viverra ornare.',
                ],
            }, {
                'id': 5,
                'title': 'Campr Dashboard Design',
                'customer': 'Christoph Poll',
                'date': '2016-05-12',
                'status': 'IN_PROGRESS',
                'programme': 'Campr',
                'progress': 75,
                'task_status': 77,
                'costs_status': 35.67,
                'favourite': true,
                'notes': [
                    'Lorem ipsum dolor sit amet, consectetur' +
                    ' adipiscing elit.' +
                    ' Duis at dolor sollicitudin, ' +
                    'interdum nibh quis, faucibus justo. ' +
                    'Suspendisse sed nisi id mi aliquam finibus ac sem.',
                    'Suspendisse in massa in ligula suscipit vulputate.',
                    'Sed finibus massa nec est rutrum malesuada ' +
                    'a et eros.' +
                    ' Cras volutpat leo eu lorem viverra ornare.',
                ],
            }, {
                'id': 6,
                'title': 'Campr Dashboard Design',
                'customer': 'Christoph Poll',
                'date': '2016-05-12',
                'status': 'IN_PROGRESS',
                'programme': 'Campr',
                'progress': 75,
                'task_status': 77,
                'costs_status': 35.67,
                'favourite': false,
                'notes': [
                    'Lorem ipsum dolor sit amet, consectetur' +
                    ' adipiscing elit.' +
                    ' Duis at dolor sollicitudin, ' +
                    'interdum nibh quis, faucibus justo. ' +
                    'Suspendisse sed nisi id mi aliquam finibus ac sem.',
                    'Suspendisse in massa in ligula suscipit vulputate.',
                    'Sed finibus massa nec est rutrum malesuada ' +
                    'a et eros.' +
                    ' Cras volutpat leo eu lorem viverra ornare.',
                ],
            }, {
                'id': 7,
                'title': 'Campr Dashboard Design',
                'customer': 'Christoph Poll',
                'date': '2016-05-12',
                'status': 'FINISHED',
                'programme': 'Campr',
                'progress': 100,
                'task_status': 77,
                'costs_status': 35.67,
                'favourite': true,
                'notes': [
                    'Lorem ipsum dolor sit amet, consectetur ' +
                    'adipiscing elit.' +
                    ' Duis at dolor sollicitudin, ' +
                    'interdum nibh quis, faucibus justo. ' +
                    'Suspendisse sed nisi id mi aliquam finibus ac sem.',
                    'Suspendisse in massa in ligula suscipit vulputate.',
                    'Sed finibus massa nec est rutrum malesuada ' +
                    'a et eros.' +
                    ' Cras volutpat leo eu lorem viverra ornare.',
                ],
            },
            ];
            commit(types.SET_PROJECTS, {projects});
        });
    },
    /**
     * Gets a project by ID from the API and commits SET_PROJECT mutation
     * @param {function} commit
     * @param {number} id
     */
    getProjectById({commit}, id) {
        Vue.http
            .get('/api/projects/' + id).then((response) => {
                let project = response.data;
                commit(types.SET_PROJECT, {project});
            }, (response) => {
                // TODO: REMOVE MOCK DATA
                let project = {
                    'id': 1,
                    'title': 'Campr Dashboard Design',
                    'customer': 'Christoph Poll',
                    'date': '2016-05-12',
                    'status': 'IN_PROGRESS',
                    'programme': 'Campr',
                    'progress': 0,
                    'task_status': 77,
                    'costs_status': 35.67,
                    'favourite': true,
                    'notes': [
                        'Lorem ipsum dolor sit amet, consectetur' +
                        ' adipiscing elit.' +
                        ' Duis at dolor sollicitudin, ' +
                        'interdum nibh quis, faucibus justo. ' +
                        'Suspendisse sed nisi id mi aliquam finibus ac sem.',
                        'Suspendisse in massa in ligula suscipit vulputate.',
                        'Sed finibus massa nec est rutrum malesuada' +
                        ' a et eros.' +
                        ' Cras volutpat leo eu lorem viverra ornare.',
                    ],
                };
                commit(types.SET_PROJECT, {project});
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
        state.filteredItems = projects;
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
};

export default {
    state,
    getters,
    actions,
    mutations,
};
