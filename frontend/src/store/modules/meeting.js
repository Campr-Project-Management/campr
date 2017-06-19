import Vue from 'vue';
import * as types from '../mutation-types';
// import router from '../../router';

const state = {
    projectMeetings: [],
};

const getters = {
    projectMeetings: state => state.projectMeetings,
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
        Vue.http
            .delete(
                Routing.generate('app_api_meeting_delete', {id: id})
            ).then((response) => {
                commit(types.DELETE_PROJECT_MEETING, {id});
            }, (response) => {
            });
    },
    /**
     * Edit a subteam
     * @param {function} commit
     * @param {array} data
     */
    editProjectMeeting({commit}, data) {
        Vue.http
            .patch(
                Routing.generate('app_api_meeting_edit', {id: data.id}),
                JSON.stringify(data)
            ).then((response) => {
                let meeting = response.data;
                let id = data.id;
                commit(types.EDIT_PROJECT_MEETING, {id, meeting});
            }, (response) => {
            });
    },
    /**
     * Creates a new project meeting
     * @param {function} commit
     * @param {array} data
     */
    createProjectMeeting({commit}, data) {
        Vue.http
            .post(
                Routing.generate('app_api_project_meeting_create', {'id': data.projectId}),
                data.data
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
        state.filters = Object.assign({}, state.filters, filters);
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
        state.projectMeetings.items = state.projectMeetings.items.map((item) => {
            return item.id === id ? meeting : item;
        });
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
