<template>
    <div class="create-task page-section">
        <!-- /// DEPARTMENT MODALS /// -->
        <modal v-if="showEditDepartmentModal" @close="showEditDepartmentModal = false">
            <p class="modal-title">{{ translateText('message.edit_department') }}</p>
            <input-field v-model="editDepartmentName" :content="editDepartmentName" type="text" v-bind:label="translateText('label.department_name')"></input-field>
            <span v-if="managersForSelect.length === 0">{{ translateText('message.no_managers') }}</span>
            <multi-select-field
                    v-bind:title="translateText('label.select_managers')"
                    v-bind:options="managersForSelect"
                    v-bind:selectedOptions="editDepartmentManagers"
                    v-model="editDepartmentManagers" />
            <br />
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showEditDepartmentModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
                <a href="javascript:void(0)" @click="editSelectedDepartment()" class="btn-rounded">{{ translateText('button.edit_department') }} +</a>
            </div>
        </modal>
        <modal v-if="showDeleteDepartmentModal" @close="showDeleteDepartmentModal = false">
            <p class="modal-title">{{ translateText('message.delete_department') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showDeleteDepartmentModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="deleteSelectedDepartment()" class="btn-rounded">{{ translateText('message.yes') }}</a>
            </div>
        </modal>

        <!-- /// SUBTEAM MODALS /// -->
        <modal v-if="showEditSubteamModal" @close="showEditSubteamModal = false">
            <p class="modal-title">{{ translateText('message.edit_subteam') }}</p>
            <input-field
                v-model="editSubteamName"
                :content="editSubteamName"
                type="text"
                v-bind:label="translateText('label.subteam_name')">
            </input-field>
            <multi-select-field
                v-bind:title="translateText('label.select_users')"
                v-bind:options="projectUsersForSelect"
                v-bind:selectedOptions="editSubteamMembers"
                v-model="editSubteamMembers"
            />
            <br />
            <select-field
                v-bind:title="translateText('roles.team_leader')"
                v-bind:options="editSubteamMembers"
                v-bind:current-option="editSubteamLead"
                v-model="editSubteamLead"
            />
            <br/>
            <select-field
                v-bind:title="translateText('label.select_department')"
                v-bind:options="projectDepartmentsForSelect"
                v-model="editSubteamDepartment"
            />
            <br/>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showEditSubteamModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
                <a href="javascript:void(0)" @click="editSelectedSubteam()" class="btn-rounded">{{ translateText('button.edit_subteam') }} +</a>
            </div>
        </modal>
        <modal v-if="showDeleteSubteamModal" @close="showDeleteSubteamModal = false">
            <p class="modal-title">{{ translateText('message.delete_subteam') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showDeleteSubteamModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="deleteSelectedSubteam()" class="btn-rounded">{{ translateText('message.yes') }}</a>
            </div>
        </modal>

        <div class="row">
            <div class="col-md-9">
                <div class="header">
                    <div>
                        <router-link :to="{name: 'project-organization'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ translateText('message.back_to_organization') }}
                        </router-link>
                        <h1>{{ translateText('message.edit_organization') }}</h1>
                    </div>
                </div>

                <ul class="tabs">
                    <li role="presentation" v-for="tab in availableTabs" :key="tab" :class="{active: tab === currentTab}">
                        <button v-on:click="currentTab = tab">{{ translateText('title.' + tab) }}</button>
                    </li>
                </ul>

                <div v-if="currentTab === 'members'">
                    <div class="team-list">
                        <!--<vue-scrollbar class="table-wrapper" ref="projectOrganizationMembers">-->
                            <div class="scroll-wrapper">
                                <table class="table table-striped table-responsive">
                                    <thead>
                                        <tr>
                                            <th class="avatar"></th>
                                            <th>{{ translateText('table_header_cell.name') }}</th>
                                            <th>{{ translateText('table_header_cell.email') }}</th>
                                            <th>{{ translateText('table_header_cell.project_member') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="user in usersCurrentList" :key="user.id">
                                            <td class="avatar text-center">
                                                <div class="user-avatar-wrapper"><img :src="user.avatar || user.gravatar" /></div>
                                            </td>
                                            <td>{{ user.firstName + ' ' + user.lastName }}</td>
                                            <td>{{ user.email }}</td>
                                            <td>
                                                <switches
                                                    v-if="!isSpecial(user)"
                                                    @input="toggleUserMembership(user, $event)"
                                                    :emit-on-mount="false"
                                                    :selected="isUserMember(user)" />
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4">
                                                <pagination
                                                    :current-page="usersCurrentPage"
                                                    :number-of-pages="usersNumberOfPages"
                                                    v-on:change-page="changeUsersCurrentPage"
                                                />
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        <!--</vue-scrollbar>-->
                    </div>
                </div>

                <div v-if="currentTab === 'departments'">
                    <!-- /// Departments /// -->
                    <h3>{{ translateText('title.departments') }}</h3>
                    <vue-scrollbar class="table-wrapper">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ translateText('table_header_cell.department_name') }}</th>
                                    <th>{{ translateText('table_header_cell.managers') }}</th>
                                    <th>{{ translateText('table_header_cell.no_of_members') }}</th>
                                    <th>{{ translateText('table_header_cell.created_at') }}</th>
                                    <th>{{ translateText('table_header_cell.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr v-for="department in projectDepartments.items">
                                    <td>{{ department.name }}</td>

                                    <td class="avatar">
                                        <div v-for="manager in department.managers">
                                            <div class="avatar-image" v-tooltip.top-center="manager.userFullName">
                                                <img v-bind:src="manager.userAvatar"/>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ department.membersCount }}</td>
                                    <td>{{ moment(department.createdAt).format('DD.MM.YYYY') }}</td>
                                    <td>
                                        <button @click="initEditDepartmentModal(department)" data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                        <button @click="initDeleteDepartmentModal(department)" data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </vue-scrollbar>
                    <div class="flex flex-direction-reverse">
                        <div class="pagination flex flex-center" v-if="departmentPages > 1">
                            <span v-for="page in departmentPages" :class="{'active': page == activeDepartmentPage}" @click="changeDepartmentPage(page)">{{ page }}</span>
                        </div>
                        <span class="pagination-info" v-if="projectDepartments && projectDepartments.items">{{ translateText('message.displaying') }} {{ projectDepartments.items.length }} {{ translateText('message.results_out_of') }} {{ projectDepartments.totalItems }}</span>

                    </div>
                    <!-- /// End Departments /// -->

                    <hr>

                    <div class="form">
                        <!-- /// Add new Department /// -->
                        <div class="form-group">
                            <input-field
                                    v-model="departmentName"
                                    type="text"
                                    v-bind:label="translateText('placeholder.new_department')"/>
                            <error
                                v-if="validationMessages.departmentName && validationMessages.departmentName.length"
                                v-for="message in validationMessages.departmentName"
                                :message="message" />
                        </div>
                        <div class="flex flex-direction-reverse">
                            <a @click="createNewDepartment()" class="btn-rounded btn-auto">{{ translateText('button.add_new_department') }} +</a>
                        </div>
                        <!-- /// End Add new Department /// -->
                    </div>
                </div>


                <div v-if="currentTab === 'subteams'">
                    <!-- /// Subteams /// -->
                    <h3>{{ translateText('title.subteams') }}</h3>
                    <vue-scrollbar class="table-wrapper">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ translateText('table_header_cell.subteam_name') }}</th>
                                    <th>{{ translateText('table_header_cell.team_leader') }}</th>
                                    <th>{{ translateText('table_header_cell.no_of_members') }}</th>
                                    <th>{{ translateText('table_header_cell.department') }}</th>
                                    <th>{{ translateText('table_header_cell.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="subteam in subteams.items">
                                    <td>{{ subteam.name }}</td>
                                    <td class="avatar">
                                        <div v-for="member in subteam.subteamMembers">
                                            <div v-if="member.isLead" class="avatar-image" v-tooltip.top-center="member.userName">
                                                <img v-bind:src="member.userAvatar"/>
                                            </div>
                                        </div>
                                    </td>
                                    <td v-if="subteam.subteamMembers">{{ subteam.subteamMembers.length }}</td>
                                    <td>{{ subteamProjectDepartmentName(subteam.department) }}</td>
                                    <td>
                                        <button @click="initEditSubteamModal(subteam)" data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                        <button @click="initDeleteSubteamModal(subteam)" data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </vue-scrollbar>
                    <div class="flex flex-direction-reverse">
                        <div class="pagination flex flex-center" v-if="subteamPages > 1">
                            <span v-for="page in subteamPages" :class="{'active': page == activeSubteamPage}" @click="changeSubteamPage(page)">{{ page }}</span>
                        </div>
                        <span class="pagination-info" v-if="subteams && subteams.items">{{ translateText('message.displaying') }} {{ subteams.items.length }} {{ translateText('message.results_out_of') }} {{ subteams.totalItems }}</span>
                    </div>
                    <!-- /// End Subteams /// -->
                    <hr>
                    <div class="form">
                        <!-- /// Add new Subteam /// -->
                        <div class="form-group">
                            <input-field :content="subteamName" v-model="subteamName" type="text" v-bind:label="translateText('label.new_subteam')"></input-field>
                            <error
                                v-if="validationMessages.subteamName && validationMessages.subteamName.length"
                                v-for="message in validationMessages.subteamName"
                                :message="message" />
                        </div>
                        <div class="flex flex-direction-reverse">
                            <a @click="createNewSubteam()" class="btn-rounded btn-auto">{{ translateText('button.add_new_subteam') }} +</a>
                        </div>
                        <!-- /// End Add new Subteam /// -->
                    </div>
                </div>
            </div>
        </div>
        <alert-modal v-if="showFailed" @close="showFailed = false" body="message.unable_to_save" />
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import InputField from '../../_common/_form-components/InputField';
import ViewIcon from '../../_common/_icons/ViewIcon';
import EditIcon from '../../_common/_icons/EditIcon';
import DeleteIcon from '../../_common/_icons/DeleteIcon';
import VTooltip from 'v-tooltip';
import VueScrollbar from 'vue2-scrollbar';
import moment from 'moment';
import Modal from '../../_common/Modal';
import SelectField from '../../_common/_form-components/SelectField';
import MultiSelectField from '../../_common/_form-components/MultiSelectField';
import OrganizationDistributionItem from './OrganizationDistributionItem';
import AlertModal from '../../_common/AlertModal.vue';
import Error from '../../_common/_messages/Error.vue';
import Switches from '../../3rdparty/vue-switches';
import Pagination from '../../_common/Pagination';

export default {
    components: {
        InputField,
        ViewIcon,
        EditIcon,
        DeleteIcon,
        VTooltip,
        VueScrollbar,
        Modal,
        SelectField,
        MultiSelectField,
        OrganizationDistributionItem,
        AlertModal,
        Error,
        Switches,
        Pagination,
    },
    methods: {
        ...mapActions([
            'getProjectDepartments', 'createDepartment', 'editDepartment',
            'deleteDepartment', 'getProjectUsers', 'getSubteams', 'createSubteam',
            'editSubteam', 'deleteSubteam', 'emptyValidationMessages', 'getUsers', 'clearUsers',
            'createProjectUser', 'deleteProjectUser', 'getProjectById',
        ]),
        isSpecial(user) {
            return (this.project.projectSponsor === user.id) ||
                (this.project.projectManager === user.id);
        },
        toggleUserMembership(user, value) {
            const projectMemberData = {
                projectId: this.project.id,
                userId: user.id,
            };

            this.userMembershipCached[user.id] = value;
            if (value) {
                this.createProjectUser(projectMemberData);
            } else {
                this.deleteProjectUser(projectMemberData);
            }
        },
        isUserMember(user) {
            if (this.userMembershipCached[user.id] !== undefined) {
                return this.userMembershipCached[user.id];
            }

            if (!this.project || !this.project.projectUsers) {
                return false;
            }

            return this
                .project
                .projectUsers
                .filter((pu) => {
                    return pu.user === user.id;
                })
                .length !== 0
            ;
        },
        moment: function(date) {
            return moment(date);
        },
        translateText: function(text) {
            return this.translate(text);
        },
        changeDepartmentPage: function(page) {
            this.activeDepartmentPage = page;
            this.getProjectDepartments({project: this.$route.params.id, page: this.activeDepartmentPage});
        },
        changeSubteamPage: function(page) {
            this.activeSubteamPage = page;
            this.getSubteams({project: this.$route.params.id, page: this.activeSubteamPage});
        },
        createNewDepartment() {
            let data = {
                name: this.departmentName,
                sequence: this.projectDepartments.items ? this.projectDepartments.items.length : 0,
                rate: 0,
                abbreviation: this.departmentName.toLowerCase(),
                project: this.$route.params.id,
            };
            this.createDepartment(data)
                .then((response) => {
                    if (response.body && response.body.error && response.body.messages) {
                        this.showFailed = true;
                    } else if (this.projectDepartments.items.length > this.projectDepartments.pageSize) {
                        this.getProjectDepartments({project: this.$route.params.id, page: this.activeDepartmentPage});
                        this.departmentName = null;
                    }
                })
                .catch((err) => {
                    this.showFailed = true;
                });
        },
        initEditDepartmentModal(department) {
            this.showEditDepartmentModal = true;
            this.editDepartmentId = department.id;
            this.editDepartmentName = department.name;
            this.editDepartmentManagers = [];
            department.managers.map(manager => {
                this.editDepartmentManagers.push({key: manager.id, label: manager.userFullName});
            });
        },
        initDeleteDepartmentModal(department) {
            this.showDeleteDepartmentModal = true;
            this.deleteDepartmentId = department.id;
        },
        editSelectedDepartment() {
            let data = {
                id: this.editDepartmentId,
                name: this.editDepartmentName,
                projectUsers: this.editDepartmentManagers.map(manager => {
                    return manager.key;
                }),
            };
            this.editDepartment(data);
            this.showEditDepartmentModal = false;
        },
        deleteSelectedDepartment() {
            this.showDeleteDepartmentModal = false;
            this.deleteDepartment(this.deleteDepartmentId);
        },
        initEditSubteamModal(subteam) {
            this.showEditSubteamModal = true;
            this.editSubteamId = subteam.id;
            this.editSubteamName = subteam.name;
            this.editSubteamMembers = [];
            this.editSubteamLead = null;
            this.editSubteamDepartment = subteam.department && {
                key: subteam.department.id,
                label: subteam.department.name,
            };

            subteam.subteamMembers.map(member => {
                this.editSubteamMembers.push({key: member.user, label: member.userFullName});
                if (member.isLead) {
                    this.editSubteamLead = {key: member.user, label: member.userFullName};
                }
            });
        },
        initDeleteSubteamModal(subteam) {
            this.showDeleteSubteamModal = true;
            this.deleteSubteamId = subteam.id;
        },
        createNewSubteam() {
            let data = {
                name: this.subteamName,
                project: this.$route.params.id,
            };
            this.createSubteam(data)
                .then((response) => {
                    if (response.body && response.body.error && response.body.messages) {
                        this.showFailed = true;
                    } else if (this.subteams.items.length > this.subteams.pageSize) {
                        this.getSubteams({project: this.$route.params.id, page: this.activeSubteamPage});
                        this.subteamName = null;
                    }
                })
                .catch((response) => {
                    this.showFailed = true;
                });
        },
        editSelectedSubteam() {
            let data = {
                id: this.editSubteamId,
                name: this.editSubteamName,
                department: null,
                subteamMembers: this.editSubteamMembers.map(member => {
                    return {'user': member.key, 'isLead': !! (this.editSubteamLead && this.editSubteamLead.key === member.key)};
                }),
            };

            if (this.editSubteamDepartment) {
                data.department = this.editSubteamDepartment.key;
            }

            this.editSubteam(data);
            this.showEditSubteamModal = false;
        },
        deleteSelectedSubteam() {
            this.showDeleteSubteamModal = false;
            this.deleteSubteam(this.deleteSubteamId);
        },
        changeUsersCurrentPage(value) {
            this.usersCurrentPage = value;
        },
        subteamProjectDepartmentName(department) {
            if (department) {
                department = this.projectDepartmentById(department.id);
            }

            if (!department) {
                return '-';
            }

            return department.name;
        },
    },
    created() {
        this.getProjectDepartments({project: this.$route.params.id, page: this.activeDepartmentPage});
        this.getProjectUsers({id: this.$route.params.id});
        this.getSubteams({project: this.$route.params.id, page: this.activeSubteamPage});
        this.getUsers();
    },
    beforeDestroy() {
        this.emptyValidationMessages();
        this.clearUsers();
    },
    computed: {
        ...mapGetters([
            'projectDepartments',
            'managersForSelect',
            'projectUsersForSelect',
            'subteams',
            'validationMessages',
            'users',
            'project',
            'projectDepartmentsForSelect',
            'projectDepartmentById',
        ]),
        usersCurrentList: {
            get() {
                return this.users && this.users.length
                    ? this.users.slice(
                        (this.usersCurrentPage - 1) * 10,
                        this.usersCurrentPage * 10
                    )
                    : []
                ;
            },
        },
        usersNumberOfPages: {
            get() {
                return this.users && this.users.length
                    ? Math.ceil(this.users.length / 10)
                    : 1
                ;
            },
        },
    },
    data() {
        return {
            activeDepartmentPage: 1,
            activeSubteamPage: 1,
            availableTabs: ['members', 'departments', 'subteams'],
            currentTab: 'members',
            departmentName: '',
            departmentPages: 0,
            departmentsPerPage: 6,
            deleteSubteamId: '',
            editDepartmentId: '',
            editDepartmentName: '',
            editDepartmentManagers: [],
            editSubteamMembers: [],
            editSubteamLead: [],
            editSubteamDepartment: null,
            roleName: null,
            showEditDepartmentModal: false,
            showDeleteDepartmentModal: false,
            showEditSubteamModal: false,
            showDeleteSubteamModal: false,
            subteamPages: 0,
            subteamName: '',
            showFailed: false,
            usersCurrentPage: 1,
            userMembershipCached: {},
        };
    },
    watch: {
        projectDepartments(value) {
            this.departmentPages = Math.ceil(this.projectDepartments.totalItems / this.projectDepartments.pageSize);
        },
        subteams(value) {
            this.subteamPages = Math.ceil(this.subteams.totalItems / this.subteams.pageSize);
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_variables';
    @import '../../../css/_mixins';
    @import '../../../css/page-section';

    .team-list {
        overflow: hidden;
    }

    .table-wrapper {
        width: 100%;
        padding-bottom: 40px;
    }

    .tabs {
        margin-bottom: 0px !important;
        border-bottom: 1px solid #646EA0;

        li {
            border-top: 1px solid #646EA0;
            border-left: 1px solid #646EA0;
            display: inline-block;
            padding: 0.5em;

            &:last-child {
                border-right: 1px solid #646EA0;
            }

            button {
                background: transparent;
                border: none;
                outline: none;
                text-transform: uppercase;
            }

            &.active {
                button {
                    color: #5FC3A5;
                }
            }
        }
    }

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

    .header {
        .small-link {
            margin: 20px 0 0;
        }

        .small-link + h1 {
            padding: 10px 0 30px;
        }
    }

    h3 {
        font-size: 16px;
        text-transform: uppercase;
        font-weight: 300;
        letter-spacing: 1.6px;
        margin-bottom: 15px;
    }

    h4 {
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1.6px;
    }

    .table {
        .avatar {
            word-wrap: break-word;

            .avatar-image {
                display: inline-block;
                margin: 2px 5px 2px 0;

                img {
                    width: 30px;
                    height: 30px;
                }

                &:last-child {
                    margin-right: 0;
                }
            }
        }
    }

    .pagination-info {
        text-transform: uppercase;
        margin-top: 27px;
    }
</style>
