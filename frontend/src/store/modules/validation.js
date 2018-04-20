import * as types from '../mutation-types';
import _ from 'lodash';

/**
 * Flattens the validation messages tree returning an array.
 * @param {Object|Array} object
 * @return {Array}
 */
function extractValidationMessages(object) {
    const out = [];

    switch (true) {
    case _.isString(object):
        out.push(object);
        break;
    case _.isArray(object):
        for (let c = 0; c < object.length; c++) {
            out.concat(extractValidationMessages(object[c]));
        }
        break;
    case _.isPlainObject(object):
        const keys = Object.keys(object);
        for (let c = 0; c < keys.length; c++) {
            out.concat(extractValidationMessages(object[keys[c]]));
        }
        break;
    }

    return out;
}

const state = {
    validationMessages: {},
    validationOrigin: '',
};

const getters = {
    validationMessages: state => {
        return state.validationMessages;
    },
    validationMessagesFor: (state, getters) => (atPath) => {
        let messages = getters.validationMessages;
        if (!messages || !messages[atPath]) {
            return [];
        }

        return messages[atPath];
    },
    allValidationMessages: state => extractValidationMessages(state.validationMessages),
    validationOrigin: state => state.validationOrigin,
};

const actions = {
    setValidationMessages({commit}, {messages}) {
        commit(types.SET_VALIDATION_MESSAGES, {messages});
    },
    emptyValidationMessages({commit}) {
        commit(types.SET_VALIDATION_MESSAGES, {messages: []});
    },
};

const mutations = {
    [types.SET_VALIDATION_MESSAGES](state, {messages}) {
        state.validationMessages = messages;
    },
    [types.SET_VALIDATION_ORIGIN](state, {origin}) {
        state.validationOrigin = origin;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
