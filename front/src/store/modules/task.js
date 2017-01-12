import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    currentItem: {},
    items: [],
    filteredItems: [],
    filters: [],
};

const getters = {
    task: state => state.currentItem,
    tasks: state => state.items,
    filteredTasks: state => state.filteredItems,
};

const actions = {
    /**
     * Gets tasks from the API and commits SET_TASKS mutation
     * @param {function} commit
     */
    getTasks({commit}) {
        Vue.http
            .get('/api/tasks').then((response) => {
                let tasks = response.data;
                commit(types.SET_TASKS, {tasks});
            }, (response) => {
            // TODO: REMOVE MOCK DATA
                let tasks = [
                    {
                        'id': 1,
                        'project': 1,
                        'status': 'NOT_STARTED',
                        'title': 'Task',
                        'percentage': 0,
                        'notes': [
                            'Mauris nec maximus odio.',
                            'Suspendisse eget enim finibus.',
                            'Integer euismod luctus convallis.',
                            'Quisque ut interdum risus, vitae ' +
                            'convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae ' +
                            'convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae ' +
                            'convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae ' +
                            'convallis odio. Integer vel gravida risus.',
                        ],
                        'attachments': [1, 2, 3],
                        'schedules': [
                            {
                                'name': 'Schedule Base',
                                'start': '2016-12-12',
                                'finish': '2016-12-13',
                                'duration': 4,
                            }, {
                                'name': 'Schedule Base',
                                'start': '2016-12-12',
                                'finish': '2016-12-13',
                                'duration': 6,
                            },
                        ],
                    },
                    {
                        'id': 2,
                        'status': 'IN_PROGRESS',
                        'title': 'Task',
                        'percentage': 75,
                        'notes': [
                            'Mauris nec maximus odio.',
                            'Suspendisse eget enim finibus.',
                            'Integer euismod luctus convallis.',
                            'Quisque ut interdum risus, vitae ' +
                            'convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae' +
                            ' convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae' +
                            ' convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae' +
                            ' convallis odio. Integer vel gravida risus.',
                        ],
                        'attachments': [],
                        'schedules': [
                            {
                                'name': 'Schedule Base',
                                'start': '2016-12-12',
                                'finish': '2016-12-13',
                                'duration': 4,
                            }, {
                                'name': 'Schedule Base',
                                'start': '2016-12-12',
                                'finish': '2016-12-13',
                                'duration': 6,
                            },
                        ],
                    },
                    {
                        'id': 3,
                        'status': 'IN_PROGRESS',
                        'title': 'Task',
                        'percentage': 75,
                        'notes': [
                            'Mauris nec maximus odio.',
                            'Suspendisse eget enim finibus.',
                            'Integer euismod luctus convallis.',
                            'Quisque ut interdum risus, vitae ' +
                            'convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae ' +
                            'convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae ' +
                            'convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae' +
                            ' convallis odio. Integer vel gravida risus.',
                        ],
                        'attachments': [],
                        'schedules': [
                            {
                                'name': 'Schedule Base',
                                'start': '2016-12-12',
                                'finish': '2016-12-13',
                                'duration': 4,
                            }, {
                                'name': 'Schedule Base',
                                'start': '2016-12-12',
                                'finish': '2016-12-13',
                                'duration': 6,
                            },
                        ],
                    },
                    {
                        'id': 4,
                        'status': 'IN_PROGRESS',
                        'title': 'Task',
                        'percentage': 75,
                        'notes': [
                            'Mauris nec maximus odio.',
                            'Suspendisse eget enim finibus.',
                            'Integer euismod luctus convallis.',
                            'Quisque ut interdum risus, vitae ' +
                            'convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae ' +
                            'convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae' +
                            ' convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae' +
                            ' convallis odio. Integer vel gravida risus.',
                        ],
                        'attachments': [],
                    },
                    {
                        'id': 5,
                        'status': 'IN_PROGRESS',
                        'title': 'Task',
                        'percentage': 75,
                        'notes': [
                            'Mauris nec maximus odio.',
                            'Suspendisse eget enim finibus.',
                            'Integer euismod luctus convallis.',
                            'Quisque ut interdum risus, vitae ' +
                            'convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae ' +
                            'convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae ' +
                            'convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae' +
                            ' convallis odio. Integer vel gravida risus.',
                        ],
                        'attachments': [],
                    },
                    {
                        'id': 6,
                        'status': 'IN_PROGRESS',
                        'title': 'Task',
                        'percentage': 75,
                        'notes': [
                            'Mauris nec maximus odio.',
                            'Suspendisse eget enim finibus.',
                            'Integer euismod luctus convallis.',
                            'Quisque ut interdum risus, vitae' +
                            ' convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae' +
                            ' convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae' +
                            ' convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae' +
                            ' convallis odio. Integer vel gravida risus.',
                        ],
                        'attachments': [],
                    },
                    {
                        'id': 7,
                        'status': 'FINISHED',
                        'title': 'Task',
                        'percentage': 100,
                        'notes': [
                            'Mauris nec maximus odio.',
                            'Suspendisse eget enim finibus.',
                            'Integer euismod luctus convallis.',
                            'Quisque ut interdum risus, vitae ' +
                            'convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae' +
                            ' convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae' +
                            ' convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae' +
                            ' convallis odio. Integer vel gravida risus.',
                        ],
                        'attachments': [],
                    },
                ];
                commit(types.SET_TASKS, {tasks});
            });
    },
    /**
     * Gets a task by ID from the API and commits SET_TASK mutation
     * @param {function} commit
     * @param {number} id
     */
    getTaskById({commit}, id) {
        Vue.http
            .get('/api/task/' + id).then((response) => {
                let task = response.data;
                commit(types.SET_TASK, {task});
            }, (response) => {
            // TODO: REMOVE MOCK DATA
                let task =
                    {
                        'id': 1,
                        'project': 1,
                        'status': 'NOT_STARTED',
                        'title': 'Task',
                        'percentage': 0,
                        'notes': [
                            'Mauris nec maximus odio.',
                            'Suspendisse eget enim finibus.',
                            'Integer euismod luctus convallis.',
                            'Quisque ut interdum risus, vitae ' +
                            'convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae ' +
                            'convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae ' +
                            'convallis odio. Integer vel gravida risus.',
                            'Quisque ut interdum risus, vitae ' +
                            'convallis odio. Integer vel gravida risus.',
                        ],
                        'attachments': [1, 2, 3],
                        'schedules': [
                            {
                                'name': 'Schedule Base',
                                'start': '2016-12-12',
                                'finish': '2016-12-13',
                                'duration': 4,
                            }, {
                                'name': 'Schedule Base',
                                'start': '2016-12-12',
                                'finish': '2016-12-13',
                                'duration': 6,
                            },
                        ],
                    };
                commit(types.SET_TASK, {task});
            });
    },
};

const mutations = {
    /**
     * Sets tasks to state
     * @param {Object} state
     * @param {array} tasks
     */
    [types.SET_TASKS](state, {tasks}) {
        state.items = tasks;
        state.filteredItems = tasks;
    },
    /**
     * Sets task to state
     * @param {Object} state
     * @param {Object} task
     */
    [types.SET_TASK](state, {task}) {
        state.currentItem = task;
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
