<template>
    <div class="project-organization page-section">
        <modal v-if="showModal" @close="showModal = false">
            <p class="modal-title">{{ translateText('message.add_distribution_list') }}</p>
            <input-field v-model="distributionTitle" type="text" v-bind:label="translateText('label.distribution_list_title')"></input-field>
            <member-search v-model="selectedDistribution" v-bind:placeholder="translateText('placeholder.search_resources')"></member-search>
            <br />
            <div class="members main-list">
                <div class="member flex"  v-for="item in distributionList">
                    <img v-bind:src="item.userAvatar">
                    <div class="info">
                        <p class="title">{{ item.userFullName }}</p>
                        <p class="description">{{ item.projectRoleName }}</p>
                    </div>
                </div>
            </div>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
                <a href="javascript:void(0)" @click="createDistributionList()" class="btn-rounded">{{ translateText('button.create_distribution') }} +</a>
            </div>
        </modal>

        <div class="header flex flex-space-between">
            <h1>{{ translateText('message.project_organization') }}</h1>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-organization-edit'}" class="btn-rounded btn-auto second-bg">{{ translateText('message.edit_project_organization') }}</router-link>
            </div>
        </div>

        <div class="team-graph">
            <vue-scrollbar class="team-graph-wrapper">
                <div class="scroll-wrapper">
                    <member-badge v-for="(item, index) in projectSponsors" v-bind:item="item" size="big first-member-badge"></member-badge>
                    <div class="flex flex-center " v-for="subteam in subteams.items">
                        <div class="member-block">
                            <div class="member-badge-wrapper" v-for="subteamMember in subteam.subteamMembers">
                                <member-badge v-if="subteamMember.isLead" v-bind:item="subteamMember" size="big"></member-badge>
                            </div>
                            <div class="flex flex-center">
                                <div v-for="child in subteam.children" class="member-block">
                                    <div class="member-badge-wrapper" v-for="member in child.subteamMembers">
                                        <member-badge v-if="member.isLead" v-bind:item="member" size="small"></member-badge>
                                    </div>
                                    <a href="javascript:void(0)" class="btn-rounded btn-empty btn-small" @click="toggleTeam(child.id)" :class="{'show-team': showTeam[child.id]}">
                                        {{ translateText('message.view_team') }}
                                        <i class="fa fa-angle-down" v-show="!showTeam[child.id]"></i>
                                        <i class="fa fa-angle-up" v-show="showTeam[child.id]"></i>
                                    </a>
                                    <div class="team" v-show="showTeam[child.id]">
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
            </vue-scrollbar>
        </div>
        <div class="flex flex-space-between actions">
            <member-search v-model="gridList" v-bind:placeholder="translateText('placeholder.search_members')"></member-search>
            <div class="flex">
                <router-link :to="{name: 'project-organization-create-member'}" class="btn-rounded btn-auto second-bg">{{ translateText('button.add_new_team_members') }}</router-link>
                <a href="javascript:void(0)" class="btn-rounded btn-empty" @click="showModal = true">{{ translateText('button.create_distribution') }}</a>
            </div>
        </div>
        <div class="team-list">
            <vue-scrollbar class="table-wrapper">
                <div class="scroll-wrapper">
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th class="avatar"></th>
                                <th class="text-center switchers">{{ translateText('table_header_cell.resource') }}</th>
                                <th>{{ translateText('table_header_cell.company') }}</th>
                                <th>{{ translateText('table_header_cell.name') }}</th>
                                <th>{{ translateText('table_header_cell.role') }}</th>
                                <th>{{ translateText('table_header_cell.subteam') }}</th>
                                <th>{{ translateText('table_header_cell.department') }}</th>
                                <th>{{ translateText('table_header_cell.contact') }}</th>
                                <th class="text-center switchers">{{ translateText('table_header_cell.raci') }}</th>
                                <th class="text-center switchers">{{ translateText('table_header_cell.org') }}</th>
                                <th v-if='project.distributionLists' :colspan="project.distributionLists.length" class="no-padding">
                                    <table>
                                        <tr>
                                            <th class="text-center " :colspan="project.distributionLists.length">{{ translateText('table_header_cell.distribution_lists') }}</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center switchers" v-for="dl in project.distributionLists">{{ dl.name }}</th>
                                        </tr>
                                    </table>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in projectUsers.items">
                                <td class="avatar text-center">
                                    <div class="user-avatar-wrapper"><img :src="item.userAvatar" /></div>
                                </td>
                                <td class="text-center switchers">
                                    <switches @click.native="updateUserOption(item, 'resource')" v-model="showInResources" :selected="item.showInResources"></switches>
                                </td>
                                <td>{{ item.company }}</td>
                                <td>{{ item.userFullName }}</td>
                                <td>
                                    <span v-for="role in item.projectRoleNames">{{ translateText(role) }}</span>
                                </td>
                                <td>{{ item.projecTeamName }}</td>
                                <td>{{ item.projectDepartmentName }}</td>
                                <td>
                                    <social-links align="left" size="20px" v-bind:facebook="item.userFacebook" v-bind:twitter="item.userTwitter" v-bind:linkedin="item.userLinkedIn" v-bind:gplus="item.userGplus" v-bind:email="item.userEmail" v-bind:phone="item.userPhone"></social-links>
                                </td>
                                <td class="text-center switchers">
                                    <switches @click.native="updateUserOption(item, 'raci')" v-model="showInRaci" :selected="item.showInRaci"></switches>
                                </td>
                                <td class="text-center switchers">
                                    <switches @click.native="updateUserOption(item, 'org')" v-model="showInOrg" :selected="item.showInOrg"></switches>
                                </td>
                                <td class="text-center switchers" v-for="dl in project.distributionLists">
                                    <switches @click.native="updateDistribution(item, dl)" v-model="inDistribution" :selected="inDistributionList(item.id, dl)"></switches>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </vue-scrollbar>

            <div class="flex flex-direction-reverse flex-v-center">
                <div class="pagination" v-if="pages > 0">
                    <span v-for="page in pages" :class="{'active': page == activePage}" @click="changePage(page)">{{ page }}</span>
                </div>
                <div v-if='projectUsers.items'>
                    <span class="pagination-info">{{ translateText('message.displaying') }} {{ projectUsers.items.length }} {{ translateText('message.results_out_of') }} {{ projectUsers.totalItems }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import MemberBadge from '../_common/MemberBadge';
import SocialLinks from '../_common/SocialLinks';
import InputField from '../_common/_form-components/InputField';
import MemberSearch from '../_common/MemberSearch';
import Switches from '../3rdparty/vue-switches';
import VueScrollbar from 'vue2-scrollbar';
import Modal from '../_common/Modal';
// import 'vue2-scrollbar/dist/style/vue2-scrollbar.css';

export default {
    components: {
        MemberBadge,
        SocialLinks,
        InputField,
        Switches,
        Modal,
        VueScrollbar,
        MemberSearch,
    },
    methods: {
        ...mapActions(['getProjectById', 'createDistribution', 'updateProjectUser',
            'getProjectUsers', 'addToDistribution', 'removeFromDistribution', 'getSubteams',
        ]),
        translateText: function(text) {
            return this.translate(text);
        },
        changePage(page) {
            this.activePage = page;
            this.getProjectUsers({id: this.$route.params.id, page: page, users: this.gridList});
        },
        toggleTeam(id) {
            this.showTeam = Object.assign({}, this.showTeam, {[id]: !this.showTeam[id]});
        },
        toggleActivation(item) {
            item.checked = !item.checked;
        },
        createDistributionList() {
            let data = {
                name: this.distributionTitle,
                sequence: 0,
                position: 0,
                projectId: this.$route.params.id,
                users: this.selectedDistribution,
            };
            this.createDistribution(data);
            this.showModal = false;
            this.distributionList = [];
        },
        inDistributionList(userId, distribution) {
            for (let i = 0; i < distribution.users.length; i++) {
                if (distribution.users[i].id == userId) {
                    return true;
                }
            }
            return false;
        },
        updateUserOption(item, value) {
            switch(value) {
            case 'raci':
                this.updateProjectUser({
                    id: item.id,
                    showInRaci: this.showInRaci,
                });
                break;
            case 'org':
                this.updateProjectUser({
                    id: item.id,
                    showInOrg: this.showInOrg,
                });
            case 'resource':
                this.updateProjectUser({
                    id: item.id,
                    showInResources: this.showInResources,
                });
                break;
            };
        },
        updateDistribution(item, distribution) {
            this.inDistribution
                ? this.addToDistribution({id: distribution.id, user: item.id})
                : this.removeFromDistribution({id: distribution.id, user: item.id})
            ;
        },
    },
    created() {
        this.getProjectById(this.$route.params.id);
        this.getProjectUsers({id: this.$route.params.id, page: this.page});
        this.getSubteams({project: this.$route.params.id, parent: false});
    },
    computed: mapGetters({
        project: 'project',
        projectUsers: 'projectUsers',
        projectSponsors: 'projectSponsors',
        subteams: 'subteams',
    }),
    data: function() {
        return {
            selectedDistribution: [],
            distributionList: [],
            gridList: [],
            showInRaci: '',
            showInOrg: '',
            showInResources: '',
            inDistribution: '',
            pages: 0,
            page: 1,
            activePage: 1,
            showTeam: {},
            showModal: false,
        };
    },
    watch: {
        projectUsers(value) {
            this.pages = this.projectUsers.totalItems / this.projectUsers.items.length;
        },
        gridList(value) {
            this.getProjectUsers({id: this.$route.params.id, users: this.gridList});
        },
        selectedDistribution(value) {
            let distribution = [];
            let selected = this.selectedDistribution;
            this.project.projectUsers.map(function(user) {
                if (selected.indexOf(user.id) > -1) distribution.push(user);
            });
            this.distributionList = distribution;
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style lang="scss">
    @import '../../css/page-section';
    @import '../../css/_variables';

    .modal .modal-inner {
        width: 600px;
    }

    .actions .search input[type=text] {
        width: 420px;
        height: 40px;
    }
</style>

<style scoped lang="scss">
    @import '../../css/_variables';
    @import '../../css/_mixins';

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
