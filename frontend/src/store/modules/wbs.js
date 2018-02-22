import * as types from '../mutation-types';
import Vue from 'vue';
// import _ from 'lodash';

const state = {
    wbs: [],
};

/**
 * Gets stuff.
 *
 * @param {object} workPackage
 * @param {integer} level
 * @return {Array}
 */
function getPhasesAndMilestones(workPackage, level) {
    let out = [];

    workPackage
        .children
        .filter(wp => [0, 1, 2].indexOf(wp.type) !== -1)
        .map(wp => {
            let newWp = {
                id: wp.id,
                name: wp.name,
                type: wp.type,
                phase: wp.phase,
                phaseName: wp.phaseName,
                milestone: wp.milestone,
                milestoneName: wp.milestoneName,
                parent: wp.parent,
                parentName: wp.parentName,
                level,
            };

            out.push(newWp);
            out = out.concat(getPhasesAndMilestones(wp, level + 1));
        })
    ;

    return out;
}

const getters = {
    wbs: state => state.wbs,
    wbsPhasesAndMilestones: (state) => {
        if (!state.wbs || !state.wbs.children) {
            return [];
        }

        let out = [];

        state
            .wbs
            .children
            .filter(wp => [0, 1, 2].indexOf(wp.type) !== -1)
            .map(wp => {
                let newWp = {
                    id: wp.id,
                    name: wp.name,
                    type: wp.type,
                    phase: wp.phase,
                    phaseName: wp.phaseName,
                    milestone: wp.milestone,
                    milestoneName: wp.milestoneName,
                    parent: wp.parent,
                    parentName: wp.parentName,
                    level: 0,
                };
                out.push(newWp);
                out = out.concat(getPhasesAndMilestones(wp, 1));
            })
        ;

        return out;
    },
};

const actions = {
    getWBSByProjectID({commit}, id) {
        return Vue
            .http
            .get(Routing.generate('app_api_project_wbs', {id}))
            .then(
                (response) => {
                    commit(types.SET_WBS, {wbs: response.body});
                },
                () => {
                    commit(types.SET_WBS, {wbs: []});
                }
            )
        ;
    },
};

const mutations = {
    [types.SET_WBS](state, {wbs}) {
        state.wbs = wbs;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
