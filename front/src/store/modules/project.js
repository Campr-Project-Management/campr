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
    currentProjectTitle: function(state) {
        return state.currentItem.title;
    },
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
            let projects = response.data;
            commit(types.SET_PROJECTS, {projects});
            commit(types.TOGGLE_LOADER, false);
        }, (response) => {
            let projects = [
                {
                    'id': 1,
                    'title': 'Tesla SpaceX Mars Project',
                    'description': 'Aenean mollis nulla eu leo feugiat gravida',
                    'customer': 'Christoph Poll',
                    'date': '2016-05-12',
                    'status': 'IN_PROGRESS',
                    'programme': 'Campr',
                    'progress': 0,
                    'task_status': 77,
                    'costs_status': 35.67,
                    'favourite': true,
                    'objectives': [
                        {
                            'title': 'Aenean mollis nulla eu leo feugiat gravida',
                            'content': 'Proin vitae lacinia arcu, ut tempus nulla.' +
                            ' Suspendisse ornare enim nec enim tristique pharetra.' +
                            ' Nam at tincidunt sapien. Proin aliquam neque ac felis ' +
                            'congue rhoncus. Aliquam eu lorem ex. Sed quis lacus sem.' +
                            ' Aliquam auctor felis sed tellus placerat lacinia.'},
                        {
                            'title': 'Integer dictum, enim quis iaculis venenatis,' +
                            ' ipsum lorem ornare diam, aliquam rutrum neque felis vel lectus',
                            'content': 'Suspendisse sit amet tortor tempus, vehicula odio in,' +
                            ' posuere erat. Nam elementum porttitor justo ac cursus. Praesent' +
                            ' blandit id lectus vel dapibus. Vestibulum suscipit turpis nec dui' +
                            ' ultrices condimentum. Aenean et auctor eros. Suspendisse sagittis' +
                            ' aliquam sollicitudin. Vestibulum aliquam mi tempus fringilla sagittis. ' +
                            'Nam commodo ultricies faucibus. Proin interdum ac orci a vestibulum. Aenean' +
                            ' ullamcorper mi et magna aliquet laoreet. Pellentesque ultrices porta dolor,' +
                            ' non dignissim purus dapibus a. Nullam a lacinia leo, vestibulum finibus eros.',
                        },
                    ],
                    'limitations': [
                        {
                            'content': 'Proin vitae lacinia arcu, ut tempus nulla.' +
                            ' Suspendisse ornare enim nec enim tristique pharetra.' +
                            ' Nam at tincidunt sapien. Proin aliquam neque ac felis ' +
                            'congue rhoncus. Aliquam eu lorem ex. Sed quis lacus sem.' +
                            ' Aliquam auctor felis sed tellus placerat lacinia.'},
                        {
                            'content': 'Suspendisse sit amet tortor tempus, vehicula odio in,' +
                            ' posuere erat. Nam elementum porttitor justo ac cursus. Praesent' +
                            ' blandit id lectus vel dapibus. Vestibulum suscipit turpis nec dui' +
                            ' ultrices condimentum. Aenean et auctor eros. Suspendisse sagittis' +
                            ' aliquam sollicitudin. Vestibulum aliquam mi tempus fringilla sagittis. ' +
                            'Nam commodo ultricies faucibus. Proin interdum ac orci a vestibulum. Aenean' +
                            ' ullamcorper mi et magna aliquet laoreet. Pellentesque ultrices porta dolor,' +
                            ' non dignissim purus dapibus a. Nullam a lacinia leo, vestibulum finibus eros.',
                        },
                    ],
                    'deliverables': [
                        {
                            'content': 'Proin vitae lacinia arcu, ut tempus nulla.' +
                            ' Suspendisse ornare enim nec enim tristique pharetra.' +
                            ' Nam at tincidunt sapien. Proin aliquam neque ac felis ' +
                            'congue rhoncus. Aliquam eu lorem ex. Sed quis lacus sem.' +
                            ' Aliquam auctor felis sed tellus placerat lacinia.'},
                        {
                            'content': 'Suspendisse sit amet tortor tempus, vehicula odio in,' +
                            ' posuere erat. Nam elementum porttitor justo ac cursus. Praesent' +
                            ' blandit id lectus vel dapibus. Vestibulum suscipit turpis nec dui' +
                            ' ultrices condimentum. Aenean et auctor eros. Suspendisse sagittis' +
                            ' aliquam sollicitudin. Vestibulum aliquam mi tempus fringilla sagittis. ' +
                            'Nam commodo ultricies faucibus. Proin interdum ac orci a vestibulum. Aenean' +
                            ' ullamcorper mi et magna aliquet laoreet. Pellentesque ultrices porta dolor,' +
                            ' non dignissim purus dapibus a. Nullam a lacinia leo, vestibulum finibus eros.',
                        },
                    ],
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
                },
                {
                    'id': 2,
                    'title': 'Tesla SpaceX Mars Project',
                    'description': 'Aenean mollis nulla eu leo feugiat gravida',
                    'customer': 'Christoph Poll',
                    'date': '2016-05-12',
                    'status': 'IN_PROGRESS',
                    'programme': 'Campr',
                    'progress': 0,
                    'task_status': 77,
                    'costs_status': 35.67,
                    'favourite': true,
                    'objectives': [
                        {
                            'title': 'Aenean mollis nulla eu leo feugiat gravida',
                            'content': 'Proin vitae lacinia arcu, ut tempus nulla.' +
                            ' Suspendisse ornare enim nec enim tristique pharetra.' +
                            ' Nam at tincidunt sapien. Proin aliquam neque ac felis ' +
                            'congue rhoncus. Aliquam eu lorem ex. Sed quis lacus sem.' +
                            ' Aliquam auctor felis sed tellus placerat lacinia.'},
                        {
                            'title': 'Integer dictum, enim quis iaculis venenatis,' +
                            ' ipsum lorem ornare diam, aliquam rutrum neque felis vel lectus',
                            'content': 'Suspendisse sit amet tortor tempus, vehicula odio in,' +
                            ' posuere erat. Nam elementum porttitor justo ac cursus. Praesent' +
                            ' blandit id lectus vel dapibus. Vestibulum suscipit turpis nec dui' +
                            ' ultrices condimentum. Aenean et auctor eros. Suspendisse sagittis' +
                            ' aliquam sollicitudin. Vestibulum aliquam mi tempus fringilla sagittis. ' +
                            'Nam commodo ultricies faucibus. Proin interdum ac orci a vestibulum. Aenean' +
                            ' ullamcorper mi et magna aliquet laoreet. Pellentesque ultrices porta dolor,' +
                            ' non dignissim purus dapibus a. Nullam a lacinia leo, vestibulum finibus eros.',
                        },
                    ],
                    'limitations': [
                        {
                            'content': 'Proin vitae lacinia arcu, ut tempus nulla.' +
                            ' Suspendisse ornare enim nec enim tristique pharetra.' +
                            ' Nam at tincidunt sapien. Proin aliquam neque ac felis ' +
                            'congue rhoncus. Aliquam eu lorem ex. Sed quis lacus sem.' +
                            ' Aliquam auctor felis sed tellus placerat lacinia.'},
                        {
                            'content': 'Suspendisse sit amet tortor tempus, vehicula odio in,' +
                            ' posuere erat. Nam elementum porttitor justo ac cursus. Praesent' +
                            ' blandit id lectus vel dapibus. Vestibulum suscipit turpis nec dui' +
                            ' ultrices condimentum. Aenean et auctor eros. Suspendisse sagittis' +
                            ' aliquam sollicitudin. Vestibulum aliquam mi tempus fringilla sagittis. ' +
                            'Nam commodo ultricies faucibus. Proin interdum ac orci a vestibulum. Aenean' +
                            ' ullamcorper mi et magna aliquet laoreet. Pellentesque ultrices porta dolor,' +
                            ' non dignissim purus dapibus a. Nullam a lacinia leo, vestibulum finibus eros.',
                        },
                    ],
                    'deliverables': [
                        {
                            'content': 'Proin vitae lacinia arcu, ut tempus nulla.' +
                            ' Suspendisse ornare enim nec enim tristique pharetra.' +
                            ' Nam at tincidunt sapien. Proin aliquam neque ac felis ' +
                            'congue rhoncus. Aliquam eu lorem ex. Sed quis lacus sem.' +
                            ' Aliquam auctor felis sed tellus placerat lacinia.'},
                        {
                            'content': 'Suspendisse sit amet tortor tempus, vehicula odio in,' +
                            ' posuere erat. Nam elementum porttitor justo ac cursus. Praesent' +
                            ' blandit id lectus vel dapibus. Vestibulum suscipit turpis nec dui' +
                            ' ultrices condimentum. Aenean et auctor eros. Suspendisse sagittis' +
                            ' aliquam sollicitudin. Vestibulum aliquam mi tempus fringilla sagittis. ' +
                            'Nam commodo ultricies faucibus. Proin interdum ac orci a vestibulum. Aenean' +
                            ' ullamcorper mi et magna aliquet laoreet. Pellentesque ultrices porta dolor,' +
                            ' non dignissim purus dapibus a. Nullam a lacinia leo, vestibulum finibus eros.',
                        },
                    ],
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
                },
            ];
            commit(types.SET_PROJECTS, {projects});
            commit(types.TOGGLE_LOADER, false);
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
                let project = response.data;
                commit(types.SET_PROJECT, {project});
            }, (response) => {
                // TODO: REMOVE MOCK DATA
                let project = {
                    'id': 1,
                    'title': 'Tesla SpaceX Mars Project',
                    'description': 'Aenean mollis nulla eu leo feugiat gravida',
                    'customer': 'Christoph Poll',
                    'date': '2016-05-12',
                    'status': 'IN_PROGRESS',
                    'programme': 'Campr',
                    'progress': 0,
                    'task_status': 77,
                    'costs_status': 35.67,
                    'favourite': true,
                    'objectives': [
                        {
                            'title': 'Aenean mollis nulla eu leo feugiat gravida',
                            'content': 'Proin vitae lacinia arcu, ut tempus nulla.' +
                            ' Suspendisse ornare enim nec enim tristique pharetra.' +
                            ' Nam at tincidunt sapien. Proin aliquam neque ac felis ' +
                            'congue rhoncus. Aliquam eu lorem ex. Sed quis lacus sem.' +
                            ' Aliquam auctor felis sed tellus placerat lacinia.'},
                        {
                            'title': 'Integer dictum, enim quis iaculis venenatis,' +
                            ' ipsum lorem ornare diam, aliquam rutrum neque felis vel lectus',
                            'content': 'Suspendisse sit amet tortor tempus, vehicula odio in,' +
                            ' posuere erat. Nam elementum porttitor justo ac cursus. Praesent' +
                            ' blandit id lectus vel dapibus. Vestibulum suscipit turpis nec dui' +
                            ' ultrices condimentum. Aenean et auctor eros. Suspendisse sagittis' +
                            ' aliquam sollicitudin. Vestibulum aliquam mi tempus fringilla sagittis. ' +
                            'Nam commodo ultricies faucibus. Proin interdum ac orci a vestibulum. Aenean' +
                            ' ullamcorper mi et magna aliquet laoreet. Pellentesque ultrices porta dolor,' +
                            ' non dignissim purus dapibus a. Nullam a lacinia leo, vestibulum finibus eros.',
                        },
                    ],
                    'limitations': [
                        {
                            'content': 'Proin vitae lacinia arcu, ut tempus nulla.' +
                            ' Suspendisse ornare enim nec enim tristique pharetra.' +
                            ' Nam at tincidunt sapien. Proin aliquam neque ac felis ' +
                            'congue rhoncus. Aliquam eu lorem ex. Sed quis lacus sem.' +
                            ' Aliquam auctor felis sed tellus placerat lacinia.'},
                        {
                            'content': 'Suspendisse sit amet tortor tempus, vehicula odio in,' +
                            ' posuere erat. Nam elementum porttitor justo ac cursus. Praesent' +
                            ' blandit id lectus vel dapibus. Vestibulum suscipit turpis nec dui' +
                            ' ultrices condimentum. Aenean et auctor eros. Suspendisse sagittis' +
                            ' aliquam sollicitudin. Vestibulum aliquam mi tempus fringilla sagittis. ' +
                            'Nam commodo ultricies faucibus. Proin interdum ac orci a vestibulum. Aenean' +
                            ' ullamcorper mi et magna aliquet laoreet. Pellentesque ultrices porta dolor,' +
                            ' non dignissim purus dapibus a. Nullam a lacinia leo, vestibulum finibus eros.',
                        },
                    ],
                    'deliverables': [
                        {
                            'content': 'Proin vitae lacinia arcu, ut tempus nulla.' +
                            ' Suspendisse ornare enim nec enim tristique pharetra.' +
                            ' Nam at tincidunt sapien. Proin aliquam neque ac felis ' +
                            'congue rhoncus. Aliquam eu lorem ex. Sed quis lacus sem.' +
                            ' Aliquam auctor felis sed tellus placerat lacinia.'},
                        {
                            'content': 'Suspendisse sit amet tortor tempus, vehicula odio in,' +
                            ' posuere erat. Nam elementum porttitor justo ac cursus. Praesent' +
                            ' blandit id lectus vel dapibus. Vestibulum suscipit turpis nec dui' +
                            ' ultrices condimentum. Aenean et auctor eros. Suspendisse sagittis' +
                            ' aliquam sollicitudin. Vestibulum aliquam mi tempus fringilla sagittis. ' +
                            'Nam commodo ultricies faucibus. Proin interdum ac orci a vestibulum. Aenean' +
                            ' ullamcorper mi et magna aliquet laoreet. Pellentesque ultrices porta dolor,' +
                            ' non dignissim purus dapibus a. Nullam a lacinia leo, vestibulum finibus eros.',
                        },
                    ],
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
                commit(types.TOGGLE_LOADER, false);
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
