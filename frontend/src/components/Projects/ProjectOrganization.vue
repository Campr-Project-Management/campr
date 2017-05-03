<template>
    <div class="project-organization page-section">
        <modal v-if="showModal" @close="showModal = false">
            <p class="modal-title">{{ message.add_distribution_list }}</p>
            <input-field v-model="distributionTitle" type="text" v-bind:label="label.distribution_list_title"></input-field>
            <member-search v-model="selectedDistribution" v-bind:placeholder="placeholder.search_resources"></member-search>
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
                <a href="javascript:void(0)" @click="showModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ button.cancel }}</a>
                <a href="javascript:void(0)" @click="createDistributionList()" class="btn-rounded">{{ button.create_distribution }} +</a>
            </div>
        </modal>

        <div class="header flex flex-space-between">
            <h1>{{ message.project_organization }}</h1>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-organization-edit'}" class="btn-rounded btn-auto second-bg">{{ message.edit_project_organization }}</router-link>
            </div>
        </div>

        <div class="team-graph">
            <div class="flex flex-space-between">
                <div>
                    <div class="flex">
                        <div>
                            <a href="" class="btn-rounded btn-empty btn-small">
                                {{ message.view_team }}
                            </a>
                        </div>
                        <div>
                            <a href="" class="btn-rounded btn-empty btn-small">
                                {{ message.view_team }}
                            </a>
                        </div>
                        <div class="member-badge-wrapper">
                            <a href="javascript:void(0)" class="btn-rounded btn-empty btn-small" @click="toggleTeam()" :class="{'show-team': showTeam}">
                                {{ message.view_team }}
                                <i class="fa fa-angle-down" v-show="!showTeam"></i>
                                <i class="fa fa-angle-up" v-show="showTeam"></i>
                            </a>
                            <div class="team" v-show="showTeam">
                                <div class="member flex" v-for="user in projectUsers.items">
                                    <img :src="user.userAvatar">
                                    <div class="info">
                                        <p class="title">{{user.userFullName}}</p>
                                        <p class="description">{{user.projectRoleName}}</p>
                                        <social-links align="left" size="16px" v-bind:facebook="user.userFacebook" v-bind:twitter="user.userTwitter" v-bind:linkedin="user.userLinkedIn" v-bind:gplus="user.userGplus" v-bind:email="user.userEmail" v-bind:phone="user.userPhone"></social-links>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="flex">
                        <div>
                            <a href="" class="btn-rounded btn-empty btn-small">
                                {{ message.view_team }}
                            </a>
                        </div>
                        <div>
                            <a href="" class="btn-rounded btn-empty btn-small">
                                {{ message.view_team }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-space-between actions">
            <member-search v-model="gridList" v-bind:placeholder="placeholder.search_members"></member-search>
            <div class="flex">
                <router-link :to="{name: 'project-organization-create-member'}" class="btn-rounded btn-auto second-bg">{{ message.add_new_team_members }}</router-link>
                <a href="javascript:void(0)" class="btn-rounded btn-empty" @click="showModal = true">{{ button.create_distribution }}</a>
            </div>
        </div>
        <div class="team-list">
            <vue-scrollbar class="table-wrapper">
                <div class="scroll-wrapper">
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th class="avatar"></th>
                                <th class="text-center switchers">{{ table_header_cell.resource }}</th>
                                <th>{{ table_header_cell.company }}</th>
                                <th>{{ table_header_cell.name }}</th>
                                <th>{{ table_header_cell.role }}</th>
                                <th>{{ table_header_cell.subteam }}</th>
                                <th>{{ table_header_cell.department }}</th>
                                <th>{{ table_header_cell.contact }}</th>
                                <th class="text-center switchers">{{ table_header_cell.raci }}</th>
                                <th class="text-center switchers">{{ table_header_cell.org }}</th>
                                <th v-if='project.distributionLists' :colspan="project.distributionLists.length" class="no-padding">
                                    <table>
                                        <tr>
                                            <th class="text-center " :colspan="project.distributionLists.length">{{ table_header_cell.distribution_lists }}</th>
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
                                <td>{{ item.projectRoleName }}</td>
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
                    <span class="pagination-info">{{ message.displaying }} {{ projectUsers.items.length }} {{ message.results_out_of }} {{ projectUsers.totalItems }}</span>
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
import 'vue2-scrollbar/dist/style/vue2-scrollbar.css';

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
        ...mapActions(['getProjectById', 'createDistribution', 'updateProjectUser', 'getProjectUsers', 'addToDistribution', 'removeFromDistribution']),
        changePage(page) {
            this.activePage = page;
            this.getProjectUsers({id: this.$route.params.id, page: page, users: this.gridList});
        },
        toggleTeam() {
            this.showTeam = !this.showTeam;
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
    },
    computed: mapGetters({
        project: 'project',
        projectUsers: 'projectUsers',
    }),
    data: function() {
        return {
            message: {
                add_distribution_list: this.translate('add.distribution_list'),
                add_new_team_members: this.translate('add.new_team_members'),
                project_organization: this.translate('message.project_organization'),
                view_team: this.translate('message.view_team'),
                displaying: this.translate('message.displaying'),
                results_out_of: this.translate('message.results_out_of'),
                edit_project_organization: this.translate('message.edit_project_organization'),
            },
            table_header_cell: {
                resource: this.translate('table_header_cell.resource'),
                company: this.translate('table_header_cell.company'),
                name: this.translate('table_header_cell.name'),
                role: this.translate('table_header_cell.role'),
                subteam: this.translate('table_header_cell.subteam'),
                department: this.translate('table_header_cell.department'),
                contact: this.translate('table_header_cell.contact'),
                raci: this.translate('table_header_cell.raci'),
                org: this.translate('table_header_cell.org'),
                distribution_lists: this.translate('table_header_cell.distribution_lists'),
            },
            label: {
                distribution_list_title: this.translate('label.distribution_list_title'),
            },
            placeholder: {
                search_resources: this.translate('placeholder.search_resources'),
                search_members: this.translate('placeholder.search_members'),
            },
            button: {
                cancel: this.translate('button.cancel'),
                create_distribution: this.translate('button.create_distribution'),
            },
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
            showTeam: false,
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

    .st0 {
        stroke: $lighterColor;
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
        box-shadow: 0 0 8px -2px #000;
    }

    .member {
        padding: 20px 0;
        border-top: 1px solid $mainColor;

        &:first-child {
            border-bottom: none;
        }
    }

    img {
        width: 46px;
        height: 46px;
    }

    .info {
        text-transform: uppercase;
        padding-left: 10px;
    }

    .title {
        font-size: 10px;
        color: $lighterColor;
        letter-spacing: 1.5px;
    }

    .description {
        font-size: 8px;
        color: $middleColor;
        letter-spacing: 1.2px;
    }

    .social-links {
        margin-top: 5px;
    }

    .member-badge.small {
        margin: 0 auto 13px;
    }

    .team-graph {
        width: 70%;
        margin: 0 auto;
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
        margin: 54px 0 30px;
    }

    .table-wrapper {
        width: 100%;
        padding-bottom: 40px;
    }    
</style>
