import * as types from '../mutation-types';

const state = {
    step1: {},
    step2: {},
    step3: {},
};

const getters = {
    projectCreateWizardStep1: state => state.step1,
    projectCreateWizardStep2: state => state.step2,
    projectCreateWizardStep3: state => state.step3,
};

const actions = {
    /**
     * Set step 1 data
     * @param {function} commit
     * @param {object} step1
     */
    setProjectCreateWizardStep1({commit}, step1) {
        commit(types.SET_PROJECT_CREATE_WIZARD_STEP1, step1);
    },
    /**
     * Set step 2 data
     * @param {function} commit
     * @param {object} step2
     */
    setProjectCreateWizardStep2({commit}, step2) {
        commit(types.SET_PROJECT_CREATE_WIZARD_STEP2, step2);
    },
    /**
     * Set step 3 data
     * @param {function} commit
     * @param {object} step3
     */
    setProjectCreateWizardStep3({commit}, step3) {
        commit(types.SET_PROJECT_CREATE_WIZARD_STEP3, step3);
    },
    /**
     * Reset project create wizard
     * @param {function} commit
     * @param {object} step3
     */
    resetProjectCreateWizard({commit}) {
        commit(types.RESET_PROJECT_CREATE_WIZARD);
    },
};

const mutations = {
    /**
     * Sets project create wizard step1 data
     * @param {Object} state
     * @param {object} step1
     */
    [types.SET_PROJECT_CREATE_WIZARD_STEP1](state, step1) {
        state.step1 = step1;
    },
    /**
     * Sets project create wizard step2 data
     * @param {Object} state
     * @param {array} step2
     */
    [types.SET_PROJECT_CREATE_WIZARD_STEP2](state, step2) {
        state.step2 = step2;
    },
    /**
     * Sets project create wizard step3 data
     * @param {Object} state
     * @param {object} step3
     */
    [types.SET_PROJECT_CREATE_WIZARD_STEP3](state, step3) {
        state.step3 = step3;
    },
    /**
     * Reset
     * @param {Object} state
     */
    [types.RESET_PROJECT_CREATE_WIZARD](state) {
        state.step1 = {};
        state.step2 = {};
        state.step3 = {};
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
