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
};

const getters = {
    validationMessages: state => {
        return state.validationMessages;
    },
    allValidationMessages: state => extractValidationMessages(state.validationMessages),
};

const actions = {
    setValidationMessages({commit}, {messages}) {
        commit(types.SET_VALIDATION_MESSAGES, {messages});
    },
};

const mutations = {
    [types.SET_VALIDATION_MESSAGES](state, {messages}) {
        state.validationMessages = messages;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
