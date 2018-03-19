import Vue from 'vue';

const state = {};
const getters = {};
const actions = {
    inviteMemberToProject({commit}, {id, email}) {
        return Vue
            .http
            .post(
                Routing.generate('app_api_project_invite', {id}),
                {email}
            )
            .then((response) => {
                let {data} = response;

                return data;
            }, (response) => {
                return false;
            })
        ;
    },
    inviteMemberToWorkspace({commit}, email) {
        return Vue
            .http
            .post(
                Routing.generate('main_api_workspace_invite_member', {workspace: window.location.host.split('.')[0]}),
                {email}
            )
            .then((response) => {
                let {data} = response;

                return data;
            }, (response) => {
                return false;
            })
        ;
    },
};
const mutations = {};

export default {
    state,
    getters,
    actions,
    mutations,
};
