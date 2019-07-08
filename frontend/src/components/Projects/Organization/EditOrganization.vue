<template>
    <can role="roles.project_manager|roles.project_sponsor" :subject="project">
        <div class="create-task page-section">
            <!-- /// DEPARTMENT MODALS /// -->
            <modal v-if="showEditDepartmentModal" @close="showEditDepartmentModal = false" v-bind:hasSpecificClass="true">
                <p class="modal-title">{{ translate('message.edit_department') }}</p>
                <input-field
                        v-model="editDepartmentName"
                        type="text"
                        :label="translate('label.department_name')"/>
                <error at-path="name"/>
                <br/>

                <multi-select-field
                        :title="translate('label.select_members')"
                        :options="departmentMembersOptions"
                        v-model="editDepartmentMembers"/>
                <error at-path="members"/>
                <br/>

                <select-field
                        :title="translate('roles.team_leader')"
                        :options="editDepartmentMembers"
                        v-model="editDepartmentLeader"/>
                <error at-path="leader"/>
                <br/>
                <div class="flex flex-space-between">
                    <a href="javascript:void(0)" @click="showEditDepartmentModal = false" class="btn-rounded btn-auto">{{ translate('button.cancel') }}</a>
                    <a href="javascript:void(0)" @click="editSelectedDepartment()" class="btn-rounded btn-auto second-bg">{{ translate('button.edit_department') }} +</a>
                </div>
            </modal>
            <modal v-if="showDeleteDepartmentModal" @close="showDeleteDepartmentModal = false">
                <p class="modal-title">{{ translate('message.delete_department') }}</p>
                <div class="flex flex-space-between">
                    <a href="javascript:void(0)" @click="showDeleteDepartmentModal = false" class="btn-rounded btn-auto">{{ translate('message.no') }}</a>
                    <a href="javascript:void(0)" @click="deleteSelectedDepartment()"
                       class="btn-rounded btn-empty btn-auto danger-color danger-border">{{ translate('message.yes') }}</a>
                </div>
            </modal>

            <!-- /// SUBTEAM MODALS /// -->
            <modal v-if="showEditSubteamModal" @close="showEditSubteamModal = false" v-bind:hasSpecificClass="true">
                <p class="modal-title">{{ translate('message.edit_subteam') }}</p>
                <div class="form-group">
                    <input-field
                            v-model="editSubteamName"
                            :content="editSubteamName"
                            type="text"
                            :label="translate('label.subteam_name')"/>
                </div>
                <div class="form-group">
                    <multi-select-field
                            :title="translate('label.select_users')"
                            :options="projectUsersForSelect"
                            v-model="editSubteamMembers"/>
                </div>
                <div class="form-group">
                    <select-field
                            :title="translate('roles.team_leader')"
                            :options="editSubteamMembers"
                            :current-option="editSubteamLead"
                            v-model="editSubteamLead"/>
                </div>
                <div class="form-group">
                    <select-field
                            :title="translate('label.select_department')"
                            :options="projectDepartmentsForSelect"
                            v-model="editSubteamDepartment"/>
                </div>
                <div class="flex flex-space-between">
                    <a href="javascript:void(0)" @click="showEditSubteamModal = false" class="btn-rounded btn-auto">{{ translate('button.cancel') }}</a>
                    <a href="javascript:void(0)" @click="editSelectedSubteam()" class="btn-rounded btn-auto second-bg">{{ translate('button.edit_subteam') }} +</a>
                </div>
            </modal>
            <modal v-if="showDeleteSubteamModal" @close="showDeleteSubteamModal = false">
                <p class="modal-title">{{ translate('message.delete_subteam') }}</p>
                <div class="flex flex-space-between">
                    <a href="javascript:void(0)" @click="showDeleteSubteamModal = false" class="btn-rounded btn-auto">{{ translate('message.no') }}</a>
                    <a href="javascript:void(0)" @click="deleteSelectedSubteam()"
                       class="btn-rounded btn-empty btn-auto danger-color danger-border">{{ translate('message.yes') }}</a>
                </div>
            </modal>

            <!-- /// SPONSOR MODALS /// -->
            <modal v-if="showEditSponsorModal" @close="showEditSponsorModal = false" v-bind:hasSpecificClass="true">
                <p class="modal-title">{{ translate('message.edit_sponsor') }}</p>
                <div class="form-group">
                    <select-field
                            :title="translate('label.select_user')"
                            :options="editSponsorMembers"
                            :current-option="editSponsor"
                            v-model="editSponsor"/>
                </div>
                <div class="flex flex-space-between">
                    <a href="javascript:void(0)" @click="showEditSponsorModal = false" class="btn-rounded btn-auto">{{ translate('button.cancel') }}</a>
                    <a href="javascript:void(0)" @click="editSelectedSponsor()" class="btn-rounded btn-auto second-bg">{{ translate('button.edit_sponsor') }} +</a>
                </div>
            </modal>
            <modal v-if="showCreateSponsorModal" @close="showCreateSponsorModal = false" v-bind:hasSpecificClass="true">
                <p class="modal-title">{{ translate('message.create_sponsor') }}</p>
                <div class="form-group">
                    <select-field
                            :title="translate('label.select_user')"
                            :options="sponsorMembers"
                            v-model="newSponsor"/>
                </div>
                <div class="flex flex-space-between">
                    <a href="javascript:void(0)" @click="showCreateSponsorModal = false" class="btn-rounded btn-auto">{{ translate('button.cancel') }}</a>
                    <a href="javascript:void(0)" @click="selectSponsor()" class="btn-rounded btn-auto second-bg">{{ translate('button.create_sponsor') }} +</a>
                </div>
            </modal>
            <modal v-if="showDeleteSponsorModal" @close="showDeleteSponsorModal = false">
                <p class="modal-title">{{ translate('message.delete_sponsor') }}</p>
                <div class="flex flex-space-between">
                    <a href="javascript:void(0)" @click="showDeleteSponsorModal = false" class="btn-rounded btn-auto">{{ translate('message.no') }}</a>
                    <a href="javascript:void(0)" @click="deleteSelectedSponsor()"
                       class="btn-rounded btn-empty btn-auto danger-color danger-border">{{ translate('message.yes') }}</a>
                </div>
            </modal>

            <div class="row">
                <div class="col-md-12">
                    <div class="header">
                        <div>
                            <router-link :to="{name: 'project-organization'}" class="small-link">
                                <i class="fa fa-angle-left"></i>
                                {{ translate('message.back_to_organization') }}
                            </router-link>
                            <h1>{{ translate('message.edit_organization') }}</h1>
                        </div>
                    </div>

                    <ul class="tabs">
                        <li role="presentation" v-for="tab in availableTabs" :key="tab"
                            :class="{active: tab === currentTab}">
                            <button v-on:click="currentTab = tab">{{ translate('title.' + tab) }}</button>
                        </li>
                    </ul>

                    <div v-if="currentTab === 'members'">
                        <div class="team-list">
                            <scrollbar class="customScrollbar">
                                <div class="scroll-wrapper">
                                    <table class="table table-striped table-responsive">
                                        <thead>
                                        <tr>
                                            <th class="avatar"></th>
                                            <th>{{ translate('table_header_cell.name') }}</th>
                                            <th>{{ translate('table_header_cell.email') }}</th>
                                            <th>{{ translate('table_header_cell.project_member') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="user in usersCurrentList" :key="user.id">
                                            <td class="avatar text-center">
                                                <user-avatar
                                                        size="small"
                                                        :url="user.avatarUrl"
                                                        :name="user.fullName"
                                                        :tooltip="user.fullName"/>
                                            </td>
                                            <td>{{ user.fullName }}</td>
                                            <td>{{ user.email }}</td>
                                            <td>
                                                <switches
                                                        v-if="!isSpecial(user)"
                                                        @input="toggleUserMembership(user, $event)"
                                                        :value="isUserMember(user)"/>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </scrollbar>

                            <div class="flex flex-direction-reverse flex-v-center">
                                <pagination
                                        :value="usersCurrentPage"
                                        :number-of-pages="usersNumberOfPages"
                                        @input="changeUsersCurrentPage"/>
                            </div>
                        </div>
                    </div>

                    <div v-if="currentTab === 'departments'">
                        <!-- /// Departments /// -->
                        <h3>{{ translate('title.departments') }}</h3>
                        <scrollbar class="customScrollbar">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>{{ translate('table_header_cell.department_name') }}</th>
                                    <th>{{ translate('table_header_cell.managers') }}</th>
                                    <th>{{ translate('table_header_cell.team_leader') }}</th>
                                    <th>{{ translate('table_header_cell.no_of_members') }}</th>
                                    <th>{{ translate('table_header_cell.created_at') }}</th>
                                    <th>{{ translate('table_header_cell.actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr v-for="department in projectDepartments.items">
                                    <td>{{ department.name }}</td>

                                    <td class="avatar">
                                        <div v-for="manager in department.managers">
                                            <user-avatar
                                                    size="small"
                                                    :url="manager.userAvatarUrl"
                                                    :name="manager.userFullName"
                                                    :tooltip="manager.userFullName"/>
                                        </div>
                                    </td>
                                    <td>
                                        <user-avatar
                                                v-if="department.leader"
                                                size="small"
                                                :url="department.leader.userAvatarUrl"
                                                :name="department.leader.userFullName"
                                                :tooltip="department.leader.userFullName"/>
                                    </td>
                                    <td>{{ department.members.length }}</td>
                                    <td>{{ department.createdAt | date }}</td>
                                    <td>
                                        <button @click="initEditDepartmentModal(department)"
                                                data-target="#logistics-edit-modal" data-toggle="modal" type="button"
                                                class="btn-icon">
                                            <edit-icon fill="second-fill"></edit-icon>
                                        </button>
                                        <button @click="initDeleteDepartmentModal(department)"
                                                data-target="#logistics-delete-modal" data-toggle="modal" type="button"
                                                class="btn-icon">
                                            <delete-icon fill="danger-fill"></delete-icon>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </scrollbar>
                        <div class="flex flex-direction-reverse">
                            <div class="pagination flex flex-center" v-if="departmentPages > 1">
                                <span v-for="page in departmentPages" :class="{'active': page === activeDepartmentPage}"
                                      @click="changeDepartmentPage(page)">{{ page }}</span>
                            </div>
                            <span class="pagination-info" v-if="projectDepartments && projectDepartments.items">{{ translate('message.displaying') }} {{ projectDepartments.items.length }} {{ translate('message.results_out_of') }} {{ projectDepartments.totalItems }}</span>
                        </div>
                        <!-- /// End Departments /// -->

                        <hr>

                        <div class="form">
                            <form @submit.prevent="createNewDepartment()">
                                <!-- /// Add new Department /// -->
                                <div class="form-group">
                                    <input-field
                                            v-model="departmentName"
                                            type="text"
                                            :label="translate('placeholder.new_department')"/>
                                    <error at-path="departmentName"/>
                                </div>
                                <div class="flex flex-direction-reverse">
                                    <button type="submit" class="btn-rounded btn-auto">{{ translate('button.add_new_department') }} +</button>
                                </div>
                                <!-- /// End Add new Department /// -->
                            </form>
                        </div>
                    </div>


                    <div v-if="currentTab === 'subteams'">
                        <!-- /// Subteams /// -->
                        <h3>{{ translate('title.subteams') }}</h3>
                        <scrollbar class="customScrollbar">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>{{ translate('table_header_cell.subteam_name') }}</th>
                                    <th>{{ translate('table_header_cell.team_leader') }}</th>
                                    <th>{{ translate('table_header_cell.no_of_members') }}</th>
                                    <th>{{ translate('table_header_cell.department') }}</th>
                                    <th>{{ translate('table_header_cell.actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="subteam in subteams.items">
                                    <td>{{ subteam.name }}</td>
                                    <td class="avatar">
                                        <div v-for="member in subteam.subteamMembers">
                                            <user-avatar
                                                    v-if="member.lead"
                                                    size="small"
                                                    :url="member.userAvatarUrl"
                                                    :name="member.userFullName"
                                                    :tooltip="member.userFullName"/>
                                        </div>
                                    </td>
                                    <td v-if="subteam.subteamMembers">{{ subteam.subteamMembers.length }}</td>
                                    <td>{{ subteamProjectDepartmentName(subteam.department) }}</td>
                                    <td>
                                        <button @click="initEditSubteamModal(subteam)" data-target="#logistics-edit-modal"
                                                data-toggle="modal" type="button" class="btn-icon">
                                            <edit-icon fill="second-fill"></edit-icon>
                                        </button>
                                        <button @click="initDeleteSubteamModal(subteam)"
                                                data-target="#logistics-delete-modal" data-toggle="modal" type="button"
                                                class="btn-icon">
                                            <delete-icon fill="danger-fill"></delete-icon>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </scrollbar>
                        <div class="flex flex-direction-reverse">
                            <div class="pagination flex flex-center" v-if="subteamPages > 1">
                                <span v-for="page in subteamPages" :class="{'active': page == activeSubteamPage}"
                                      @click="changeSubteamPage(page)">{{ page }}</span>
                            </div>
                            <span class="pagination-info" v-if="subteams && subteams.items">{{ translate('message.displaying') }} {{ subteams.items.length }} {{ translate('message.results_out_of') }} {{ subteams.totalItems }}</span>
                        </div>
                        <!-- /// End Subteams /// -->
                        <hr>
                        <div class="form">
                            <!-- /// Add new Subteam /// -->
                            <div class="form-group">
                                <input-field :content="subteamName" v-model="subteamName" type="text"
                                             :label="translate('label.new_subteam')"></input-field>
                                <error
                                        v-if="validationMessages.subteamName && validationMessages.subteamName.length"
                                        v-for="message in validationMessages.subteamName"
                                        :message="message"/>
                            </div>
                            <div class="flex flex-direction-reverse">
                                <a @click="createNewSubteam()" class="btn-rounded btn-auto">{{ translate('button.add_new_subteam') }} +</a>
                            </div>
                            <!-- /// End Add new Subteam /// -->
                        </div>
                    </div>

                    <div v-if="currentTab === 'sponsor'">
                        <!-- /// Sponsor /// -->
                        <h3>{{ translate('title.sponsor') }}</h3>
                        <scrollbar class="customScrollbar">
                            <table class="table table-striped table-responsive">
                                <thead>
                                <tr>
                                    <th class="avatar"></th>
                                    <th>{{ translate('table_header_cell.name') }}</th>
                                    <th>{{ translate('table_header_cell.email') }}</th>
                                    <th>{{ translate('table_header_cell.actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="projectUser in projectSponsors" :key="projectUser.id">
                                    <td class="avatar text-center">
                                        <user-avatar
                                                size="small"
                                                :url="projectUser.userAvatarUrl"
                                                :name="projectUser.userFullName"/>
                                    </td>
                                    <td>{{ projectUser.userFullName }}</td>
                                    <td>{{ projectUser.userEmail }}</td>
                                    <td>
                                        <button @click="initEditSponsorModal(projectUser)" data-target="#logistics-edit-modal"
                                                data-toggle="modal" type="button" class="btn-icon">
                                            <edit-icon fill="second-fill"></edit-icon>
                                        </button>
                                        <button @click="initDeleteSponsorModal(projectUser)"
                                                data-target="#logistics-delete-modal" data-toggle="modal" type="button"
                                                class="btn-icon">
                                            <delete-icon fill="danger-fill"></delete-icon>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </scrollbar>
                        <div class="flex flex-direction-reverse">
                            <member-badge
                                    v-for="(item, index) in sponsors"
                                    :item="item"
                                    :key="'sponsor'+index"
                                    size="small"/>
                        </div>
                        <template>
                            <!-- /// End Sponsor /// -->
                            <hr>
                            <div class="form">
                                <!-- /// Add new Sponsor /// -->
                                <div class="flex flex-direction-reverse">
                                    <a v-if="!projectSponsors || !projectSponsors.length" @click="initCreateSponsorModal"
                                       class="btn-rounded btn-auto">{{ translate('button.add_new_sponsor') }} +</a>
                                </div>
                                <!-- /// End Add new Sponsor /// -->
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </can>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex';
    import InputField from '../../_common/_form-components/InputField';
    import ViewIcon from '../../_common/_icons/ViewIcon';
    import EditIcon from '../../_common/_icons/EditIcon';
    import DeleteIcon from '../../_common/_icons/DeleteIcon';
    import VTooltip from 'v-tooltip';
    import Modal from '../../_common/Modal';
    import SelectField from '../../_common/_form-components/SelectField';
    import MultiSelectField from '../../_common/_form-components/MultiSelectField';
    import OrganizationDistributionItem from './OrganizationDistributionItem';
    import Error from '../../_common/_messages/Error.vue';
    import Switches from '../../3rdparty/vue-switches';
    import Pagination from '../../_common/Pagination';
    import UserAvatar from '../../_common/UserAvatar';

    export default {
        components: {
            UserAvatar,
            InputField,
            ViewIcon,
            EditIcon,
            DeleteIcon,
            VTooltip,
            Modal,
            SelectField,
            MultiSelectField,
            OrganizationDistributionItem,
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
                'createSponsor', 'editProjectSponsor', 'getProjectSponsors', 'deleteSponsor',
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

                return this.project.projectUsers.filter((pu) => {
                    return pu.user === user.id;
                }).length !== 0
                    ;
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
                    abbreviation: this.departmentName ? this.departmentName.toLowerCase() : null,
                    project: this.$route.params.id,
                };
                this.createDepartment(data).then((response) => {
                    if (response.body && response.body.error && response.body.messages) {
                        this.$flashError('message.unable_to_save');
                        return;
                    }

                    this.departmentName = null;

                    if (this.projectDepartments.items.length > this.projectDepartments.pageSize) {
                        this.getProjectDepartments(
                            {project: this.$route.params.id, page: this.activeDepartmentPage});
                    }
                }).catch((err) => {
                    this.$flashError('message.unable_to_save');
                });
            },
            initEditDepartmentModal(department) {
                this.showEditDepartmentModal = true;
                this.editDepartmentId = department.id;
                this.editDepartmentName = department.name;
                this.editDepartmentMembers = department.members.map(member => ({
                    key: member.projectUserId,
                    label: member.fullName,
                    hidden: member.deleted,
                }));

                this.editDepartmentLeader = null;
                if (department.leader) {
                    this.editDepartmentLeader = {
                        key: department.leader.id,
                        label: department.leader.fullName,
                        hidden: department.leader.userDeleted,
                    };
                }
            },
            initDeleteDepartmentModal(department) {
                this.showDeleteDepartmentModal = true;
                this.deleteDepartmentId = department.id;
            },
            editSelectedDepartment() {
                let data = {
                    id: this.editDepartmentId,
                    name: this.editDepartmentName,
                    members: this.editDepartmentMembers.map(member => {
                        let lead = false;
                        if (this.editDepartmentLeader && member.key === this.editDepartmentLeader.key) {
                            lead = true;
                        }

                        return {
                            projectUser: member.key,
                            lead,
                        };
                    }),
                };

                this.editDepartment(data).then((response) => {
                    if (response.body && response.body.error && response.body.messages) {
                        return;
                    }

                    this.showEditDepartmentModal = false;
                });
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
                    this.editSubteamMembers.push(
                        {key: member.user, label: member.userFullName, hidden: member.userDeleted});

                    if (member.lead) {
                        this.editSubteamLead = {
                            key: member.user,
                            label: member.userFullName,
                            hidden: member.userDeleted,
                        };
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
                this.createSubteam(data).then((response) => {
                    if (response.body && response.body.error && response.body.messages) {
                        this.$flashError('message.unable_to_save');
                    }
                    this.subteamName = null;
                    if (this.subteams.items.length > this.subteams.pageSize) {
                        this.getSubteams({project: this.$route.params.id, page: this.activeSubteamPage});
                    }
                }).catch((response) => {
                    this.$flashError('message.unable_to_save');
                });
            },
            editSelectedSubteam() {
                let data = {
                    id: this.editSubteamId,
                    name: this.editSubteamName,
                    department: null,
                    subteamMembers: this.editSubteamMembers.map(member => {
                        return {
                            'user': member.key,
                            'lead': !!(this.editSubteamLead && this.editSubteamLead.key === member.key),
                        };
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
            initEditSponsorModal(sponsor) {
                this.showEditSponsorModal = true;
                this.editSponsor = {key: sponsor.id, label: sponsor.userFullName};
                this.project.projectUsers.map(projectUser => {
                    this.editSponsorMembers.push(
                        {key: projectUser.id, label: projectUser.userFullName, hidden: projectUser.userDeleted});
                });
            },
            initDeleteSponsorModal(sponsor) {
                this.showDeleteSponsorModal = true;
                this.deleteSponsorId = sponsor.id;
            },
            initCreateSponsorModal() {
                this.showCreateSponsorModal = true;
                this.project.projectUsers.map(projectUser => {
                    this.sponsorMembers.push(
                        {key: projectUser.id, label: projectUser.userFullName, hidden: projectUser.userDeleted});
                });
            },
            selectSponsor() {
                let data = {
                    id: this.$route.params.id,
                    projectUser: this.newSponsor.key,
                };
                this.createSponsor(data).then(response => {
                    this.showCreateSponsorModal = false;
                });
            },
            editSelectedSponsor() {
                let data = {
                    id: this.$route.params.id,
                    projectUser: this.editSponsor.key,
                };

                this.editProjectSponsor(data);
                this.showEditSponsorModal = false;
            },
            deleteSelectedSponsor() {
                this.showDeleteSponsorModal = false;
                this.deleteSponsor({
                    id: this.project.id,
                    projectUser: this.deleteSponsorId,
                });
            },
        },
        created() {
            this.getProjectDepartments({project: this.$route.params.id, page: this.activeDepartmentPage});
            this.getProjectUsers({id: this.$route.params.id});
            this.getSubteams({project: this.$route.params.id, page: this.activeSubteamPage});
            this.getUsers();
            this.getProjectSponsors({id: this.$route.params.id});
        },
        beforeDestroy() {
            this.emptyValidationMessages();
            this.clearUsers();
        },
        computed: {
            ...mapGetters([
                'projectDepartments',
                'projectUsersForSelect',
                'subteams',
                'validationMessages',
                'users',
                'project',
                'projectDepartmentsForSelect',
                'projectDepartmentById',
                'projectUsers',
                'projectSponsors',
            ]),
            departmentMembersOptions() {
                return this.projectUsers.items.map(
                    (projectUser) => ({
                        key: projectUser.id,
                        label: projectUser.userFullName,
                        hidden: projectUser.userDeleted,
                    }));
            },
            usersCurrentList: {
                get() {
                    return this.users && this.users.length
                        ? this.users.slice(
                            (this.usersCurrentPage - 1) * 10,
                            this.usersCurrentPage * 10,
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
                availableTabs: ['members', 'departments', 'subteams', 'sponsor'],
                currentTab: 'members',
                departmentName: '',
                departmentPages: 0,
                departmentsPerPage: 6,
                deleteSubteamId: '',
                editDepartmentId: '',
                editDepartmentName: '',
                editDepartmentMembers: [],
                editDepartmentLeader: null,
                editSubteamMembers: [],
                editSponsorMembers: [],
                sponsorMembers: [],
                editSubteamLead: [],
                editSubteamDepartment: null,
                roleName: null,
                newSponsor: null,
                editSponsor: null,
                showEditDepartmentModal: false,
                showDeleteDepartmentModal: false,
                showEditSubteamModal: false,
                showEditSponsorModal: false,
                showCreateSponsorModal: false,
                showDeleteSubteamModal: false,
                showDeleteSponsorModal: false,
                subteamPages: 0,
                subteamName: '',
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
    @import '~theme/variables';
    @import '../../../css/_mixins';
    @import '../../../css/page-section';
    @import '../../../css/common';

    .team-list {
        overflow: hidden;
    }

    .table-wrapper {
        width: 100%;
        padding-bottom: 40px;
    }

    .tabs {
        margin: 0;
        padding: 0;
        border: none;

        li {
            display: inline-block;
            margin-right: 2px;

            button {
                background-color: rgba($lightColor, 0.25);
                border: none;
                outline: none;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                padding: 10px 15px;
                @include transition(all, 0.2s, ease);
            }

            &:hover {
                button {
                    background-color: rgba($lightColor, 0.5);
                }
            }

            &.active {
                button {
                    background-color: $secondColor;
                    color: $darkColor;
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
            font-weight: 500;
            margin-bottom: 40px;
        }

        .input-holder {
            margin: 30px 0 0;

            &:first-of-type {
                margin-top: 0;
            }
        }

        .search {
            margin-top: 30px;
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
        font-weight: 500;
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
                width: 30px;
                height: 30px;
                background-size: cover;
                background-position: center center;
                background-repeat: no-repeat;
                @include border-radius(50%);

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
