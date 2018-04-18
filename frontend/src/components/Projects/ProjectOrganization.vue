<template>
    <div class="project-organization page-section">
        <modal v-if="showModal" @close="showModal = false">
            <p class="modal-title">{{ translateText('title.add_distribution_list') }}</p>
            <input-field v-model="distributionTitle" type="text" v-bind:label="translateText('label.distribution_list_title')"></input-field>
            <error
                v-if="validationMessages.name && validationMessages.name.length"
                v-for="message in validationMessages.name"
                :message="message" />
            <member-search v-model="selectedDistribution" v-bind:placeholder="translateText('placeholder.search_members')" v-bind:singleSelect="false"></member-search>
            <br />
            <div class="members main-list">
                <div class="member flex member-row"  v-for="item in distributionList">
                    <img class="member-img" height="60" v-bind:src="item.userAvatar">
                    <div class="info member-info">
                        <p class="title">{{ item.userFullName }}</p>
                        <p class="description">{{ item.projectRoleNames }}</p>
                    </div>
                </div>
            </div>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
                <a href="javascript:void(0)" @click="createDistributionList()" class="btn-rounded">{{ translateText('button.create_distribution') }} +</a>
            </div>
        </modal>

        <modal v-if="showDeleteMemberModal" @close="showDeleteMemberModal = false">
            <p class="modal-title">{{ translateText('message.delete_team_member') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showDeleteMemberModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="deleteMember()" class="btn-rounded">{{ translateText('message.yes') }}</a>
            </div>
        </modal>

        <div class="header flex flex-space-between">
            <h1>{{ translateText('message.project_organization') }}</h1>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-organization-edit'}" class="btn-rounded btn-auto second-bg">{{ translateText('message.edit_project_organization') }}</router-link>
            </div>
        </div>

        <div class="team-graph">
            <scrollbar>
                <div class="scroll-wrapper">
                    <member-badge v-for="(item, index) in projectSponsors" v-bind:item="item" size="big first-member-badge"></member-badge>
                    <div :class="['subteams-container', subteams.items.length > 1 ? 'multiple-teams' : '']"  v-if="countSubteamsToShow() > 0">
                        <div class="subteam-wrapper" v-for="subteam,index in subteams.items" v-if="subteamHasLeadOrChildren(subteam)" >
                            <div class="flex flex-center" :data-count="subteam.id">
                                <div class="member-block">
                                    <div class="member-badge-wrapper" v-if="subteamHasLead(subteam)">
                                        <member-badge v-for="subteamMember in subteam.subteamMembers" v-if="subteamMember.isLead" v-bind:item="subteamMember" size="big"></member-badge>
                                    </div>
                                    <div class="flex flex-center"  v-if="subteam.children.length > 0">
                                        <div v-for="child in subteam.children" class="member-block">
                                            <div class="member-badge-wrapper" v-for="member in child.subteamMembers" v-if="subteamHasLead(child)">
                                                <member-badge v-if="member.isLead" v-bind:item="member" size="small"></member-badge>
                                            </div>
                                            <a href="javascript:void(0)" class="btn-rounded btn-empty btn-small" @click="toggleTeam(child.id)" :class="{'show-team': showTeam[child.id]}">
                                                {{ translateText('message.view_team') }}
                                                <i class="fa fa-angle-down" v-show="!showTeam[child.id]"></i>
                                                <i class="fa fa-angle-up" v-show="showTeam[child.id]"></i>
                                            </a>
                                            <div class="team" :team-id="child.id" v-show="showTeam[child.id]">
                                                <div class="member flex" v-for="user in child.subteamMembers">
                                                    <div class="avatar" v-bind:style="{ backgroundImage: 'url(' + user.userAvatar + ')' }"></div>
                                                    <div class="info">
                                                        <p class="title">{{ user.userFullName }}</p>
                                                        <p class="description" v-for="role in user.subteamRoleNames">{{ role }}</p>
                                                        <social-links align="left" size="16px" v-bind:facebook="user.userFacebook" v-bind:twitter="user.userTwitter" v-bind:linkedin="user.userLinkedIn" v-bind:gplus="user.userGplus" v-bind:email="user.userEmail" v-bind:phone="user.userPhone"></social-links>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </scrollbar>
        </div>
        <div class="flex flex-space-between actions">
            <member-search ref="gridMemberSearch" v-model="gridList" v-bind:placeholder="translateText('placeholder.search_members')"></member-search>
            <div class="flex">
                <button @click="clearFilters" class="btn-rounded btn-auto second-bg">{{ translateText('button.clear_filters') }}</button>
                <a href="javascript:void(0)" class="btn-rounded btn-auto second-bg" @click="showWorkspaceMemberInviteModal = true">
                    {{ translateText('label.invite_workspace_member') }}
                </a>
                <a href="javascript:void(0)" class="btn-rounded btn-empty" @click="showModal = true">{{ translateText('button.create_distribution') }}</a>
            </div>
        </div>
        <div class="team-list">
            <scrollbar>
                <div class="scroll-wrapper">
                    <table class="table table-striped table-responsive">
                        <thead>
                            <!-- <tr v-if='project.distributionLists'>
                                <th colspan="10"></th>
                                <th class="text-center " :colspan="project.distributionLists.length">{{ translateText('table_header_cell.distribution_lists') }}</th>
                                <th></th>
                            </tr> -->
                            <tr>
                                <th class="avatar"></th>
                                <th class="text-center switchers">{{ translateText('table_header_cell.resource') }}</th>
                                <th>{{ translateText('table_header_cell.company') }}</th>
                                <th>{{ translateText('table_header_cell.name') }}</th>
                                <th>{{ translateText('table_header_cell.role') }}</th>
                                <th>{{ translateText('table_header_cell.subteam') }}</th>
                                <th>{{ translateText('table_header_cell.department') }}</th>
                                <th>{{ translateText('table_header_cell.contact') }}</th>
                                <th class="text-center switchers">{{ translateText('table_header_cell.rasci') }}</th>
                                <th class="text-center switchers">{{ translateText('table_header_cell.org') }}</th>
                                <th class="text-center switchers" v-if='project.distributionLists' v-for="dl in project.distributionLists">
                                    <span v-if="dl.sequence === -1">{{ translateText(dl.name) }}</span>
                                    <span v-else>{{ dl.name }}</span>
                                </th>
                                <th>{{ translateText('table_header_cell.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in projectUsers.items">
                                <td class="avatar text-center">
                                    <div class="user-avatar-wrapper"><img :src="item.userAvatar" /></div>
                                </td>
                                <td class="text-center switchers">
                                    <switches
                                        @click.native="updateUserOption(item, 'resource')"
                                        emit-on-mount="false"
                                        v-model="showInResources"
                                        :selected="item.showInResources" />
                                </td>
                                <td v-if="item.company">{{ item.company }}</td><td v-else>-</td>
                                <td>{{ item.userFullName }}</td>
                                <td>
                                    <span v-for="role in item.projectRoleNames">
                                        {{ translateText(role) }}<span v-if="index < item.projectRoleNames.length - 1">,</span>
                                    </span>
                                </td>
                                <td>
                                    <span v-for="(subteamName, index) in item.subteamNames">
                                        {{ subteamName }}<span v-if="index < item.subteamNames.length - 1">,</span>
                                    </span>
                                </td>
                                <td>
                                    <span v-for="(departmentName, index) in item.projectDepartmentNames">
                                        {{ departmentName }}<span v-if="index < item.projectDepartmentNames.length - 1">,</span>
                                    </span>
                                </td>
                                <td>
                                    <social-links align="left" size="20px" v-bind:facebook="item.userFacebook" v-bind:twitter="item.userTwitter" v-bind:linkedin="item.userLinkedIn" v-bind:gplus="item.userGplus" v-bind:email="item.userEmail" v-bind:phone="item.userPhone"></social-links>
                                </td>
                                <td class="text-center switchers">
                                    <switches @click.native="updateUserOption(item, 'rasci')" v-model="showInRasci" :selected="item.showInRasci"></switches>
                                </td>
                                <td class="text-center switchers">
                                    <switches @click.native="updateUserOption(item, 'org')" v-model="showInOrg" :selected="item.showInOrg"></switches>
                                </td>
                                <td class="text-center switchers" v-for="dl in project.distributionLists">
                                    <switches :modelChanged="updateDistributionItem(item, dl)" v-model="inDistribution" :selected="inDistributionList(item.user, dl)"></switches>
                                </td>
                                <td>
                                    <router-link :to="{name: 'project-organization-view-member', params: {id: projectId, userId: item.id} }" class="btn-icon">
                                        <view-icon fill="second-fill"></view-icon>
                                    </router-link>
                                    <button @click="initDeleteMemberModal(item)" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </scrollbar>

            <div class="flex flex-direction-reverse flex-v-center">
                <div class="pagination" v-if="pages > 1">
                    <span v-for="page in pages" :class="{'active': page == activePage}" @click="changePage(page)">{{ page }}</span>
                </div>
                <div v-if='projectUsers.items'>
                    <span class="pagination-info">{{ translateText('message.displaying') }} {{ projectUsers.items.length }} {{ translateText('message.results_out_of') }} {{ projectUsers.totalItems }}</span>
                </div>
            </div>
        </div>

        <alert-modal v-if="showFailed" @close="showFailed = false" body="message.unable_to_save" />
        <workspace-member-invite-modal v-if="showWorkspaceMemberInviteModal" @close="showWorkspaceMemberInviteModal = false" />
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import MemberBadge from '../_common/MemberBadge';
import SocialLinks from '../_common/SocialLinks';
import InputField from '../_common/_form-components/InputField';
import MemberSearch from '../_common/MemberSearch';
import Switches from '../3rdparty/vue-switches';
import Modal from '../_common/Modal';
import EditIcon from '../_common/_icons/EditIcon';
import DeleteIcon from '../_common/_icons/DeleteIcon';
import ViewIcon from '../_common/_icons/ViewIcon';
import AlertModal from '../_common/AlertModal.vue';
import Error from '../_common/_messages/Error.vue';
import WorkspaceMemberInviteModal from './Organization/WorkspaceMemberInviteModal.vue';

export default {
    components: {
        MemberBadge,
        SocialLinks,
        InputField,
        Switches,
        Modal,
        MemberSearch,
        EditIcon,
        DeleteIcon,
        ViewIcon,
        AlertModal,
        Error,
        WorkspaceMemberInviteModal,
    },
    methods: {
        ...mapActions([
            'getProjectById', 'createDistribution', 'updateProjectUser', 'deleteTeamMember',
            'getProjectUsers', 'addToDistribution', 'removeFromDistribution', 'getSubteams',
        ]),
        translateText: function(text) {
            return this.translate(text);
        },
        changePage: function(page) {
            this.activePage = page;
            this.getProjectUsers({id: this.$route.params.id, page: page, users: this.gridList});
        },
        toggleTeam: function(id) {
            this.showTeam = Object.assign({}, this.showTeam, {[id]: !this.showTeam[id]});
        },
        toggleActivation: function(item) {
            item.checked = !item.checked;
        },
        createDistributionList: function() {
            let data = {
                name: this.distributionTitle,
                sequence: 0,
                position: 0,
                projectId: this.$route.params.id,
                users: this.selectedDistribution,
            };
            this.createDistribution(data)
                .then(
                    (data) => {
                        if (!data.error) {
                            this.showModal = false;
                            this.distributionList = [];
                        } else {
                            this.showFailed = true;
                        }
                    },
                    () => {
                        this.showFailed = true;
                    }
                );
        },
        inDistributionList: function(userId, distribution) {
            for (let i = 0; i < distribution.users.length; i++) {
                if (distribution.users[i].id == userId) {
                    return true;
                }
            }
            return false;
        },
        updateUserOption(item, value) {
            switch(value) {
            case 'rasci':
                this.updateProjectUser({
                    id: item.id,
                    showInRasci: !item.showInRasci,
                });
                break;
            case 'org':
                this.updateProjectUser({
                    id: item.id,
                    showInOrg: !item.showInOrg,
                });
                break;
            case 'resource':
                this.updateProjectUser({
                    id: item.id,
                    showInResources: !item.showInResources,
                });
                break;
            }
        },
        updateDistributionItem: function(item, distribution) {
            const self = this;
            return function(value) {
                value
                    ? self.addToDistribution({id: distribution.id, user: item.user})
                    : self.removeFromDistribution({id: distribution.id, user: item.user})
                ;
            };
        },
        clearFilters: function() {
            this.$refs.gridMemberSearch.clearValue();
        },
        initDeleteMemberModal: function(member) {
            this.showDeleteMemberModal = true;
            this.memberId = member.id;
        },
        deleteMember: function() {
            this.deleteTeamMember(this.memberId);
            this.showDeleteMemberModal = false;
            this.memberId = null;
        },
        subteamHasLead: function(subteam) {
            for (let i = 0; i < subteam.subteamMembers.length; i++) {
                if (subteam.subteamMembers[i].isLead) {
                    return true;
                }
            }
            return false;
        },
        subteamHasLeadOrChildren: function(subteam) {
            return this.subteamHasLead(subteam) || subteam.children.length > 0;
        },
        countSubteamsToShow: function() {
            if (this.subteams.items == undefined) {
                return 0;
            }

            let nr = 0;
            for (let i = 0; i < this.subteams.items.length; i++) {
                if (this.subteamHasLeadOrChildren(this.subteams.items[i])) {
                    nr++;
                }
            }
            return nr;
        },
    },
    created() {
        this.getProjectById(this.$route.params.id);
        this.getProjectUsers({id: this.$route.params.id, page: this.activePage});
        this.getSubteams({project: this.$route.params.id, parent: false});
    },
    computed: {
        ...mapGetters({
            project: 'project',
            projectUsers: 'projectUsers',
            projectSponsors: 'projectSponsors',
            subteams: 'subteams',
            validationMessages: 'validationMessages',
        }),
        pages: function() {
            if (!this.projectUsers || !this.projectUsers.totalItems) {
                return 1;
            }

            return Math.ceil(this.projectUsers.totalItems / this.perPage);
        },
        perPage: function() {
            if (!this.projectUsers || !this.projectUsers.pageSize) {
                return 1;
            }

            return this.projectUsers.pageSize;
        },
    },
    data() {
        return {
            showWorkspaceMemberInviteModal: false,
            showFailed: false,
            selectedDistribution: [],
            distributionList: [],
            gridList: [],
            showInRasci: '',
            showInOrg: '',
            showInResources: '',
            inDistribution: '',
            activePage: 1,
            showTeam: {},
            showModal: false,
            projectId: this.$route.params.id,
            showDeleteMemberModal: false,
            memberId: null,
        };
    },
    watch: {
        gridList(value) {
            this.getProjectUsers({id: this.$route.params.id, page: this.activePage, users: this.gridList});
        },
        selectedDistribution(value) {
            let distribution = [];
            let selected = this.selectedDistribution;
            this.project.projectUsers.map(function(user) {
                if (selected.indexOf(user.user) > -1) distribution.push(user);
            });
            this.distributionList = distribution;
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style lang="scss">
    @import '../../css/page-section';

    .modal .modal-inner {
        width: 600px;
    }

    .actions .search input[type=text] {
        width: 420px;
        height: 40px;
    }
</style>

<style scoped lang="scss">
    @import '../../css/common';
    @import '../../css/variables';
    @import '../../css/mixins';

    .modal {
        .modal-title {
            text-transform: uppercase;
            text-align: center;
            font-size: 18px;
            letter-spacing: 1.8px;
            font-weight: 300;
            margin-bottom: 40px;
        }

        .input-holder {
            margin-bottom: 30px;
        }

        .main-list .member {
            border-top: 1px solid $darkColor;
        }

        .results {
            width: 600px;
        }
    }

    .project-organization {
        overflow-x: hidden;
    }

    .team-graph {
        margin: 0 auto;
        text-align: center;
        width: 100%;
        max-width: 100%;

        .vue-scrollbar__wrapper {
            padding-bottom: 50px;
            overflow: inherit;
        }

        .subteams-container {
            position: relative;
            margin-top: 55px;

            &.multiple-teams {
                &:before {
                    content: '';
                    width: 1px;
                    height: 30px;
                    background-color: $middleColor;
                    position: absolute;
                    top: -57px;
                    left: 50%;
                }
            }

            .subteam-wrapper {
                display: inline-block;
                position: relative;

                &:only-child {
                    &:before {
                        display:none;
                    }
                }

                /*&:first-child {*/
                    /*width: 50%;*/

                    /*&:before {*/
                        /*left: 50%;*/
                    /*}*/
                /*}*/

                &:last-child {
                    /*width: 50%;*/

                    &:before {
                        left: -50%;
                    }
                }

                &:before {
                    content: '';
                    width: 100%;
                    height: 1px;
                    background-color: $middleColor;
                    position: absolute;
                    top: -26px;
                }
            }
        }

        .flex-center {
            position: relative;
            margin-top: 45px;

            &:after {
                content: '';
                width: 1px;
                height: 30px;
                background-color: $middleColor;
                position: absolute;
                top: -70px;
                left: 50%;
            }

            &.social-links {
                margin-top: 5px;

                &:after {
                    display: none;
                }
            }
        }

        .member-block {
            position: relative;

            &:before {
                content: '';
                width: 100%;
                height: 1px;
                background-color: $middleColor;
                position: absolute;
                top: -40px;
                left: 0;
            }

            &:first-child {
                &:before {
                    width: 50%;
                    left: 50%;
                }
            }

            &:last-child {
                &:before {
                    width: 50%;
                }
            }

            &:only-child {
                &:before {
                    display: none;
                }
            }
        }
    }

    .search {
        position: relative;

        .scroll-list {
            max-height: 200px;
        }

        .team {
            margin-top: 0;

            .checkbox-input {
                margin-top: 13px;
                margin-right: 10px;
            }

            .footer {
                margin: 0 -20px;
                padding: 17px 20px;
                border-top: 1px solid $mainColor;
            }

            .footer p {
                margin-bottom: 11px;
                font-size: 10px;
            }

            .footer a {
                text-transform: uppercase;
            }

            .footer .cancel {
                color: $middleColor;
            }

            .footer .show {
                color: $secondColor;
            }
        }
    }

    .member-badge-wrapper {
        position: relative;
    }

    .team {
        position: absolute;
        width: 420px;
        background: $darkColor;
        top: 100%;
        margin-top: 10px;
        padding: 0 20px;
        max-height: 400px;
        z-index: 10;
        box-shadow: 0 0 10px 0 $darkColor;

        .member {
            padding: 15px 0;
            border-top: 1px solid $mainColor;

            .avatar {
                width: 46px;
                height: 46px;
                @include border-radius(50%);
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center center;
            }

            .info {
                text-transform: uppercase;
                padding: 0 0 0 10px;

                .title {
                    font-size: 10px;
                    color: $lighterColor;
                    letter-spacing: 1.5px;
                    text-align: left;
                }

                .description {
                    font-size: 8px;
                    color: $middleColor;
                    letter-spacing: 1.2px;
                    text-align: left;
                }
            }

            .social-links {
                margin-top: 0;
            }

            &:first-child {
                border-top: none;
            }
        }
    }

    .member-img {
        flex: 1;
    }

    .member-info {
        text-align: center;
        flex: 6;
    }

    .member-row {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .lead {
        margin: 0 auto 15px !important;
    }

    .team-list {
        overflow: hidden;
    }

    .btn-rounded {
        width: auto;
        margin: 0 10px;
    }

    .btn-small {
        height: 30px;
        line-height: 30px;
        width: 161px;

        &.show-team {
            background: $middleColor;
        }
    }

    .actions {
        margin: 30px 0;
    }

    .table-wrapper {
        width: 100%;
        padding-bottom: 40px;
    }
</style>
