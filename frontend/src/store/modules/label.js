import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    labelsForChoice: [],
    label: {},
    labels: [],
};

const getters = {
    labels: state => state.labels,
    labelsForChoice: state => state.labelsForChoice,
    label: state => state.label,
};

const actions = {
    /**
     * Gets all project labels from the API and commits SET_LABELS mutation
     * @param {function} commit
     * @return {object}
     */
    getLabels({commit}) {
        return Vue
            .http
            .get(Routing.generate('app_api_labels_get'))
            .then(
                (response) => {
                    if (response.status === 200) {
                        let labels = response.data;
                        commit(types.SET_LABELS, {labels});
                    }
                },
                () => {},
            )
        ;
    },

    /**
     * Gets a specific project label
     * @param {function} commit
     * @param {number} id
     * @return {object}
     */
    getLabel({commit}, id) {
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
                () => {},
            )
        ;
    },

    /**
     * Creates a new label on project
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    createLabel({commit}, data) {
        return Vue
            .http
            .post(
                Routing.generate('app_api_label_create',
                    {'id': data.projectId}),
                JSON.stringify(data),
            )
            .then(
                (response) => {
                    if (response.body && response.body.error) {
                        const {messages} = response.body;
                        commit(types.SET_VALIDATION_MESSAGES, {messages});
                    } else {
                        commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                    }

                    return response;
                },
                () => {},
            )
        ;
    },

    /**
     * Edit a project label
     * @param {function} commit
     * @param {array} data
     * @return {object}
     */
    editLabel({commit}, data) {
        return Vue
            .http
            .patch(
                Routing.generate('app_api_label_edit', {'id': data.labelId}),
                JSON.stringify(data),
            )
            .then(
                (response) => {
                    if (response.body && response.body.error) {
                        const {messages} = response.body;
                        commit(types.SET_VALIDATION_MESSAGES, {messages});
                    } else {
                        commit(types.SET_VALIDATION_MESSAGES, {messages: []});
                    }

                    return response;
                },
                () => {},
            )
        ;
    },

    /**
     * Delete a label
     * @param {function} commit
     * @param {int} id
     * @return {object}
     */
    deleteLabel({commit}, id) {
        return Vue.http.delete(Routing.generate('app_api_label_delete', {'id': id}));
    },
};

const mutations = {
    /**
     * Sets labels
     * @param {Object} state
     * @param {array} labels
     */
    [types.SET_LABELS](state, {labels}) {
        state.labels = labels;
        let choiceLabel = [];
        state.labels.map(function(label) {
            choiceLabel.push(
                {'key': label.id, 'label': label.title, 'color': label.color});
        });
        state.labelsForChoice = choiceLabel;
    },

    /**
     * set project label
     * @param {Object} state
     * @param {Object} label
     */
    [types.SET_LABEL](state, {label}) {
        state.label = label;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
