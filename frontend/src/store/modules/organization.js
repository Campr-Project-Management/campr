// import Vue from 'vue';
import * as types from '../mutation-types';
// import router from '../../router';

const state = {
    members: [],
    items: [],
    organizationDepartments: [],
};

const getters = {
    items: state => state.items,
    member: state => state.members,
    organizationDepartments: state => state.organizationDepartments,
    organizationDepartmentsForSelect: state => state.organizationDepartments.map(
        (item) => {
            return {
                label: item.name,
                key: item.id,
            };
        }
    ),
};

const actions = {
    /**
     * Creates a new member in organization
     * @param {function} commit
     * @param {number} member
     */
    createNewOrganizationMember({commit}, member) {
        // Vue.http
    },
    /**
     * Gets the departments for an organization
     * @param {function} commit
     * @param {number} organization
     */
    getOrganizationDepartments({commit}, organization) {
        // // mock data in place of api call
        const departments = {
            items: [
                {
                    name: 'Global Operations',
                    managers: [
                        {
                            name: 'Nelson Carr',
                            avatar: 'http://dev.campr.biz/uploads/avatars/58ae8e1f2c465.jpeg',
                        },
                        {
                            name: 'Alicia Keys',
                            avatar: 'http://dev.campr.biz/uploads/avatars/10.jpg',
                        },
                    ],
                    nrMembers: 12,
                    created: '23.03.2017',
                },
                {
                    name: 'GMP',
                    managers: [
                        {
                            name: 'David Gilmore',
                            avatar: 'http://dev.campr.biz/uploads/avatars/40.jpg',
                        },
                    ],
                    nrMembers: 10,
                    created: '15.02.2017',
                },
                {
                    name: 'Global Operations',
                    managers: [
                        {
                            name: 'Nelson Carr',
                            avatar: 'http://dev.campr.biz/uploads/avatars/58ae8e1f2c465.jpeg',
                        },
                        {
                            name: 'Alicia Keys',
                            avatar: 'http://dev.campr.biz/uploads/avatars/10.jpg',
                        },
                    ],
                    nrMembers: 12,
                    created: '23.03.2017',
                },
                {
                    name: 'GMP',
                    managers: [
                        {
                            name: 'David Gilmore',
                            avatar: 'http://dev.campr.biz/uploads/avatars/40.jpg',
                        },
                    ],
                    nrMembers: 10,
                    created: '15.02.2017',
                },
                {
                    name: 'Global Operations',
                    managers: [
                        {
                            name: 'Nelson Carr',
                            avatar: 'http://dev.campr.biz/uploads/avatars/58ae8e1f2c465.jpeg',
                        },
                        {
                            name: 'Alicia Keys',
                            avatar: 'http://dev.campr.biz/uploads/avatars/10.jpg',
                        },
                    ],
                    nrMembers: 12,
                    created: '23.03.2017',
                },
                {
                    name: 'GMP',
                    managers: [
                        {
                            name: 'David Gilmore',
                            avatar: 'http://dev.campr.biz/uploads/avatars/40.jpg',
                        },
                    ],
                    nrMembers: 10,
                    created: '15.02.2017',
                },
                {
                    name: 'Global Operations',
                    managers: [
                        {
                            name: 'Nelson Carr',
                            avatar: 'http://dev.campr.biz/uploads/avatars/58ae8e1f2c465.jpeg',
                        },
                        {
                            name: 'Alicia Keys',
                            avatar: 'http://dev.campr.biz/uploads/avatars/10.jpg',
                        },
                    ],
                    nrMembers: 12,
                    created: '23.03.2017',
                },
                {
                    name: 'GMP',
                    managers: [
                        {
                            name: 'David Gilmore',
                            avatar: 'http://dev.campr.biz/uploads/avatars/40.jpg',
                        },
                    ],
                    nrMembers: 10,
                    created: '15.02.2017',
                },
            ],
            totalItems: 12,
        };
        // // mock data in place of api call
        commit(types.SET_ORGANIZATION_DEPARTMENT, {departments});
    },
};

const mutations = {
    /**
     * Sets departments for an organization
     * @param {Object} state
     * @param {array} departments
     */
    [types.SET_ORGANIZATION_DEPARTMENT](state, {departments}) {
        state.organizationDepartments = departments;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
