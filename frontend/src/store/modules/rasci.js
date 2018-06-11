import * as types from '../mutation-types';
import Vue from 'vue';
import _ from 'lodash';

const state = {
    rasci: {
        users: [],
        workPackages: [],
    },
};

const getters = {
    rasci: state => state.rasci,
};

const actions = {
    setRasci({commit}, {project, user, workPackage, data}) {
        return Vue
            .http
            .put(
                Routing.generate(
                    'app_api_project_rasci_put',
                    {
                        id: project,
                        workPackage,
                        user,
                    }
                ),
                {data}
            )
        ;
    },
    deleteRasci({commit}, {project, user, workPackage}) {
        return Vue
            .http
            .delete(
                Routing.generate(
                    'app_api_project_rasci_delete',
                    {
                        id: project,
                        workPackage,
                        user,
                    }
                ),
            )
        ;
    },
    getRasci({commit}, {id}) {
        return Vue
            .http
            .get(Routing.generate('app_api_project_rasci_get', {id}))
            .then(
                (response) => {
                    let rasci = response.body;
                    if (!_.isPlainObject(rasci)) {
                        rasci = {
                            users: [],
                            workPackages: [],
                        };
                    }
                    if (!_.isArray(rasci.users)) {
                        rasci.users = [];
                    }
                    if (!_.isArray(rasci.workPackages)) {
                        rasci.workPackages = [];
                    }

                    commit(types.SET_RASCI, {rasci});
                },
                () => {
                    commit(
                        types.SET_RASCI,
                        {
                            rasci: {
                                users: [],
                                workPackages: [],
                            },
                        }
                    );
                }
            )
        ;
    },
};

const mutations = {
    [types.SET_RASCI](state, {rasci}) {
        state.rasci = rasci;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
