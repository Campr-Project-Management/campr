import Vue from 'vue';
import * as types from '../mutation-types';
import router from '../../router';
import _ from 'lodash';

const state = {
    projectMeetings: [],
    meeting: {},
};

const getters = {
    projectMeetings: state => state.projectMeetings,
    projectMeetingsForSelect: state => {
        let meetings = [];
        if (state.projectMeetings.items) {
            state.projectMeetings.items.map(function(item) {
                meetings.push({'key': item.id, 'label': item.name});
            });
        }
        return meetings;
    },
    meeting: state => state.meeting,
};

const actions = {
    setMeetingsFilters({commit}, filters) {
        commit(types.SET_MEETINGS_FILTERS, {filters});
    },
    getProjectMeetings({commit, state}, data) {
        let paramObject = {params: {}};
        if (data.apiParams && data.apiParams.page !== undefined) {
            paramObject.params.page = data.apiParams.page;
        }
        if (state.filters && state.filters.event) {
            paramObject.params.event = state.filters.event;
        }
        if (state.filters && state.filters.category) {
            paramObject.params.category = state.filters.category;
        }
        if (state.filters && state.filters.date) {
            paramObject.params.date = state.filters.date;
        }
        Vue.http
            .get(
                Routing.generate('app_api_project_meetings', {id: data.projectId}),
                paramObject,
            ).then((response) => {
                if (response.status === 200 || response.status === 204) {
                    let projectMeetings = response.data;
                    commit(types.SET_PROJECT_MEETINGS, {projectMeetings});
                }
            }, (response) => {
            });
    },
    /**
     * Delete project meeting
     * @param {function} commit
     * @param {integer} id
     */
    deleteProjectMeeting({commit}, id) {
        Vue
            .http
            .delete(Routing.generate('app_api_meeting_delete', {id: id}))
            .then(
                () => {
                    commit(types.DELETE_PROJECT_MEETING, {id});
                    router.push({name: 'project-meetings'});
                },
                () => {}
            )
        ;
    },
    /**
     * Edit a subteam
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    editProjectMeeting({commit}, data) {
        const method = data.withPost ? 'post' : 'patch';

        return Vue
            .http[method](
                Routing.generate('app_api_meeting_edit', {id: data.id}),
                data.data
            ).then(
                (response) => {
                    if (response.body && response.body.error && response.body.messages) {
                        const {messages} = response.body;
                        commit(types.SET_VALIDATION_MESSAGES, {messages});
                    } else {
                        commit(types.SET_VALIDATION_MESSAGES, {messages: []});

                        let meeting = response.body;
                        let id = data.id;

                        if (!data.skipCommit || data.skipCommit !== true) {
                            commit(types.EDIT_PROJECT_MEETING, {id, meeting});
                        }
                    }

                    return response;
                },
                (response) => {
                    return response;
                }
            )
        ;
    },
    /**
     * Creates a new project meeting
     * @param {function} commit
     * @param {array} data
     * @return {Object}
     */
    createProjectMeeting({commit}, data) {
        return Vue.http
            .post(
                Routing.generate('app_api_project_meeting_create', {'id': data.projectId}),
                data.data
            ).then(
                (response) => {
                    if (response.body && response.body.error && response.body.messages) {
                        const {messages} = response.body;
                        commit(types.SET_VALIDATION_MESSAGES, {messages});
                    } else {
                        commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                    }

                    return response;
                },
                (response) => {
                    return response;
                }
            )
        ;
    },
    /**
     * Gets project meeting
     * @param {function} commit
     * @param {number} id
     */
    getProjectMeeting({commit}, id) {
        Vue.http
            .get(Routing.generate('app_api_meeting_get', {'id': id})).then((response) => {
                if (response.status === 200) {
                    let meeting = response.data;
                    commit(types.SET_MEETING, {meeting});
                }
            }, (response) => {
            });
    },
    /**
     * Delete meeting
     * @param {function} commit
     * @param {integer} id
     */
    sendMeetingNotifications({commit}, id) {
        Vue.http
            .get(
                Routing.generate('app_api_meeting_notifications', {id})
            ).then((response) => {
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets project meetings to state
     * @param {Object} state
     * @param {array} projectMeetings
     */
    [types.SET_PROJECT_MEETINGS](state, {projectMeetings}) {
        state.projectMeetings = projectMeetings;
    },
    /**
     * Sets the filters for meetings
     * @param {Object} state
     * @param {array} filters
     */
    [types.SET_MEETINGS_FILTERS](state, {filters}) {
        state.filters = !filters.clear ? Object.assign({}, state.filters, filters) : [];
    },
    /**
     * Delete project meeting
     * @param {Object} state
     * @param {integer} id
     */
    [types.DELETE_PROJECT_MEETING](state, {id}) {
        state.projectMeetings.items = state.projectMeetings.items.filter((item) => {
            return item.id !== id;
        });
        state.projectMeetings.totalItems--;
    },
    /**
     * Edit meeting
     * @param {Object} state
     * @param {array} meeting
     */
    [types.EDIT_PROJECT_MEETING](state, {id, meeting}) {
        if (state.projectMeetings.items) {
            state.projectMeetings.items = state.projectMeetings.items.map((item) => {
                return item.id === id ? meeting : item;
            });
        }
        if (state.meeting) {
            state.meeting = meeting;
        }
    },
    /**
     * Set meeting
     * @param {Object} state
     * @param {array} meeting
     */
    [types.SET_MEETING](state, {meeting}) {
        state.meeting = meeting;
    },
    /**
     * Add new meeting objective
     * @param {Object} state
     * @param {Object} meetingObjective
     */
    [types.ADD_MEETING_OBJECTIVE](state, {meetingObjective}) {
        state.meeting.meetingObjectives.push(meetingObjective);
    },
    /**
     * Edit meeting objective
     * @param {Object} state
     * @param {array} meetingObjective
     */
    [types.EDIT_MEETING_OBJECTIVE](state, {meetingObjective}) {
        if (state.meeting.meetingObjectives) {
            state.meeting.meetingObjectives.map(item => {
                if (item.id === meetingObjective.id) {
                    item.description = meetingObjective.description;
                }
            });
        }
    },
    /**
     * Delete meeting objective
     * @param {Object} state
     * @param {integer} meetingObjectiveId
     */
    [types.DELETE_MEETING_OBJECTIVE](state, {meetingObjectiveId}) {
        if (state.meeting.meetingObjectives) {
            state.meeting.meetingObjectives = state.meeting.meetingObjectives.filter((item) => {
                return item.id !== meetingObjectiveId;
            });
        }
    },
    /**
     * Add new meeting decision
     * @param {Object} state
     * @param {Object} decision
     */
    [types.ADD_MEETING_DECISION](state, {decision}) {
        if (state.meeting.decisions) {
            state.meeting.decisions.push(decision);
        }
    },
    /**
     * Edit meeting decision
     * @param {Object} state
     * @param {array} decision
     */
    [types.EDIT_MEETING_DECISION](state, {decision}) {
        if (!state.meeting.decisions) {
            return;
        }

        let index = _.findIndex(state.meeting.decisions, (item) => item.id === decision.id);
        if (index < 0) {
            return;
        }

        Vue.set(state.meeting.decisions, index, decision);
    },
    /**
     * Delete meeting decision
     * @param {Object} state
     * @param {integer} decisionId
     */
    [types.DELETE_MEETING_DECISION](state, {decisionId}) {
        if (state.meeting.decisions) {
            state.meeting.decisions = state.meeting.decisions.filter((item) => {
                return item.id !== decisionId;
            });
        }
    },
    [types.ADD_MEETING_INFO](state, {info}) {
        if (state.meeting && _.isArray(state.meeting.infos)) {
            state.meeting.infos.push(info);
        }
    },
    [types.EDIT_MEETING_INFO](state, {info}) {
        if (state.meeting && _.isArray(state.meeting.infos)) {
            state.meeting.infos.map(item => {
                if (item.id === info.id) {
                    _.mapKeys(info, (value, key) => {
                        item[key] = value;
                    });
                }
            });
        }
    },
    [types.DELETE_MEETING_INFO](state, {infoId}) {
        if (state.meeting && _.isArray(state.meeting.infos)) {
            state.meeting.infos = state.meeting.infos.filter((item) => {
                return item.id !== infoId;
            });
        }
    },
    /**
     * Add new meeting todo
     * @param {Object} state
     * @param {Object} todo
     */
    [types.ADD_MEETING_TODO](state, {todo}) {
        state.meeting.todos.push(todo);
    },
    /**
     * Edit meeting todo
     * @param {Object} state
     * @param {array} todo
     */
    [types.EDIT_MEETING_TODO](state, {todo}) {
        if (state.meeting.todos) {
            state.meeting.todos.map(item => {
                if (item.id === todo.id) {
                    item.title = todo.title;
                    item.description = todo.description;
                    item.responsibility = todo.responsibility;
                    item.responsibilityAvatar = todo.responsibilityAvatar;
                    item.responsibilityFullName = todo.responsibilityFullName;
                    item.dueDate = todo.dueDate;
                    item.status = todo.status;
                    item.statusName = todo.statusName;
                }
            });
        }
    },
    /**
     * Delete meeting todo
     * @param {Object} state
     * @param {integer} todoId
     */
    [types.DELETE_MEETING_TODO](state, {todoId}) {
        if (state.meeting.todos) {
            state.meeting.todos = state.meeting.todos.filter((item) => {
                return item.id !== todoId;
            });
        }
    },
    /**
     * Add new meeting note
     * @param {Object} state
     * @param {Object} note
     */
    [types.ADD_MEETING_NOTE](state, {note}) {
        state.meeting.notes.push(note);
    },
    /**
     * Edit meeting note
     * @param {Object} state
     * @param {array} note
     */
    [types.EDIT_MEETING_NOTE](state, {note}) {
        if (state.meeting.notes) {
            state.meeting.notes.map(item => {
                if (item.id === note.id) {
                    item.title = note.title;
                    item.description = note.description;
                    item.responsibility = note.responsibility;
                    item.responsibilityAvatar = note.responsibilityAvatar;
                    item.responsibilityFullName = note.responsibilityFullName;
                    item.dueDate = note.dueDate;
                    item.status = note.status;
                    item.statusName = note.statusName;
                }
            });
        }
    },
    /**
     * Delete meeting note
     * @param {Object} state
     * @param {integer} noteId
     */
    [types.DELETE_MEETING_NOTE](state, {noteId}) {
        if (state.meeting.notes) {
            state.meeting.notes = state.meeting.notes.filter((item) => {
                return item.id !== noteId;
            });
        }
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
