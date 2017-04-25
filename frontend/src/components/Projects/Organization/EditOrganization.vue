<template>
    <div class="create-task page-section">
        <modal v-if="showEditDepartmentModal" @close="showEditDepartmentModal = false">
            <p class="modal-title">{{ translateText('message.edit_department') }}</p>
            <input-field v-model="editDepartmentName" :content="editDepartmentName" type="text" v-bind:label="translateText('label.department_name')"></input-field>
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

        <div class="row">
            <div class="col-md-6">
                <div class="header">
                    <div>
                        <router-link :to="{name: 'project-organization'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{message.back_to_organization}}
                        </router-link>
                        <h1>{{message.edit_organization}}</h1>
                    </div>
                </div>

                <div class="form">
                    <!-- /// Roles /// -->
                    <h3>{{message.distribution_lists}}</h3>                
                    <ul class="roles-hierarchy">
                        <li>
                            <span class="role">
                                Project Sponsor
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16">
                                    <g>
                                        <path d="M6.5,5.5L2.3,1.3H4c0.4,0,0.7-0.3,0.7-0.7S4.4,0,4,0H0.7C0.3,0,0,0.3,0,0.7V4 c0,0.4,0.3,0.7,0.7,0.7S1.3,4.4,1.3,4V2.3l4.2,4.2c0.3,0.3,0.7,0.3,0.9,0C6.7,6.2,6.7,5.8,6.5,5.5z"/>
                                        <path d="M9.5,5.5l4.2-4.2H12c-0.4,0-0.7-0.3-0.7-0.7S11.6,0,12,0h3.3C15.7,0,16,0.3,16,0.7V4 c0,0.4-0.3,0.7-0.7,0.7c-0.4,0-0.7-0.3-0.7-0.7V2.3l-4.2,4.2c-0.3,0.3-0.7,0.3-0.9,0C9.3,6.2,9.3,5.8,9.5,5.5z"/>
                                        <path d="M5.5,9.5l-4.2,4.2V12c0-0.4-0.3-0.7-0.7-0.7S0,11.6,0,12v3.3C0,15.7,0.3,16,0.7,16H4 c0.4,0,0.7-0.3,0.7-0.7c0-0.4-0.3-0.7-0.7-0.7H2.3l4.2-4.2c0.3-0.3,0.3-0.7,0-0.9C6.2,9.3,5.8,9.3,5.5,9.5z"/>
                                        <path d="M10.5,9.5l4.2,4.2V12c0-0.4,0.3-0.7,0.7-0.7c0.4,0,0.7,0.3,0.7,0.7v3.3 c0,0.4-0.3,0.7-0.7,0.7H12c-0.4,0-0.7-0.3-0.7-0.7c0-0.4,0.3-0.7,0.7-0.7h1.7l-4.2-4.2c-0.3-0.3-0.3-0.7,0-0.9 C9.8,9.3,10.2,9.3,10.5,9.5z"/>
                                    </g>
                                </svg>
                            </span>
                            <ul>
                                <li>
                                    <span class="role">
                                        Project Manager
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16">
                                            <g>
                                                <path d="M6.5,5.5L2.3,1.3H4c0.4,0,0.7-0.3,0.7-0.7S4.4,0,4,0H0.7C0.3,0,0,0.3,0,0.7V4 c0,0.4,0.3,0.7,0.7,0.7S1.3,4.4,1.3,4V2.3l4.2,4.2c0.3,0.3,0.7,0.3,0.9,0C6.7,6.2,6.7,5.8,6.5,5.5z"/>
                                                <path d="M9.5,5.5l4.2-4.2H12c-0.4,0-0.7-0.3-0.7-0.7S11.6,0,12,0h3.3C15.7,0,16,0.3,16,0.7V4 c0,0.4-0.3,0.7-0.7,0.7c-0.4,0-0.7-0.3-0.7-0.7V2.3l-4.2,4.2c-0.3,0.3-0.7,0.3-0.9,0C9.3,6.2,9.3,5.8,9.5,5.5z"/>
                                                <path d="M5.5,9.5l-4.2,4.2V12c0-0.4-0.3-0.7-0.7-0.7S0,11.6,0,12v3.3C0,15.7,0.3,16,0.7,16H4 c0.4,0,0.7-0.3,0.7-0.7c0-0.4-0.3-0.7-0.7-0.7H2.3l4.2-4.2c0.3-0.3,0.3-0.7,0-0.9C6.2,9.3,5.8,9.3,5.5,9.5z"/>
                                                <path d="M10.5,9.5l4.2,4.2V12c0-0.4,0.3-0.7,0.7-0.7c0.4,0,0.7,0.3,0.7,0.7v3.3 c0,0.4-0.3,0.7-0.7,0.7H12c-0.4,0-0.7-0.3-0.7-0.7c0-0.4,0.3-0.7,0.7-0.7h1.7l-4.2-4.2c-0.3-0.3-0.3-0.7,0-0.9 C9.8,9.3,10.2,9.3,10.5,9.5z"/>
                                            </g>
                                        </svg>
                                    </span>

                                    <ul>
                                        <li>
                                            <span class="role">
                                                Team Leader
                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16">
                                                    <g>
                                                        <path d="M6.5,5.5L2.3,1.3H4c0.4,0,0.7-0.3,0.7-0.7S4.4,0,4,0H0.7C0.3,0,0,0.3,0,0.7V4 c0,0.4,0.3,0.7,0.7,0.7S1.3,4.4,1.3,4V2.3l4.2,4.2c0.3,0.3,0.7,0.3,0.9,0C6.7,6.2,6.7,5.8,6.5,5.5z"/>
                                                        <path d="M9.5,5.5l4.2-4.2H12c-0.4,0-0.7-0.3-0.7-0.7S11.6,0,12,0h3.3C15.7,0,16,0.3,16,0.7V4 c0,0.4-0.3,0.7-0.7,0.7c-0.4,0-0.7-0.3-0.7-0.7V2.3l-4.2,4.2c-0.3,0.3-0.7,0.3-0.9,0C9.3,6.2,9.3,5.8,9.5,5.5z"/>
                                                        <path d="M5.5,9.5l-4.2,4.2V12c0-0.4-0.3-0.7-0.7-0.7S0,11.6,0,12v3.3C0,15.7,0.3,16,0.7,16H4 c0.4,0,0.7-0.3,0.7-0.7c0-0.4-0.3-0.7-0.7-0.7H2.3l4.2-4.2c0.3-0.3,0.3-0.7,0-0.9C6.2,9.3,5.8,9.3,5.5,9.5z"/>
                                                        <path d="M10.5,9.5l4.2,4.2V12c0-0.4,0.3-0.7,0.7-0.7c0.4,0,0.7,0.3,0.7,0.7v3.3 c0,0.4-0.3,0.7-0.7,0.7H12c-0.4,0-0.7-0.3-0.7-0.7c0-0.4,0.3-0.7,0.7-0.7h1.7l-4.2-4.2c-0.3-0.3-0.3-0.7,0-0.9 C9.8,9.3,10.2,9.3,10.5,9.5z"/>
                                                    </g>
                                                </svg>
                                            </span>
                                            <ul>
                                                <li>
                                                    <span class="role">
                                                        Team Member
                                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16">
                                                            <g>
                                                                <path d="M6.5,5.5L2.3,1.3H4c0.4,0,0.7-0.3,0.7-0.7S4.4,0,4,0H0.7C0.3,0,0,0.3,0,0.7V4 c0,0.4,0.3,0.7,0.7,0.7S1.3,4.4,1.3,4V2.3l4.2,4.2c0.3,0.3,0.7,0.3,0.9,0C6.7,6.2,6.7,5.8,6.5,5.5z"/>
                                                                <path d="M9.5,5.5l4.2-4.2H12c-0.4,0-0.7-0.3-0.7-0.7S11.6,0,12,0h3.3C15.7,0,16,0.3,16,0.7V4 c0,0.4-0.3,0.7-0.7,0.7c-0.4,0-0.7-0.3-0.7-0.7V2.3l-4.2,4.2c-0.3,0.3-0.7,0.3-0.9,0C9.3,6.2,9.3,5.8,9.5,5.5z"/>
                                                                <path d="M5.5,9.5l-4.2,4.2V12c0-0.4-0.3-0.7-0.7-0.7S0,11.6,0,12v3.3C0,15.7,0.3,16,0.7,16H4 c0.4,0,0.7-0.3,0.7-0.7c0-0.4-0.3-0.7-0.7-0.7H2.3l4.2-4.2c0.3-0.3,0.3-0.7,0-0.9C6.2,9.3,5.8,9.3,5.5,9.5z"/>
                                                                <path d="M10.5,9.5l4.2,4.2V12c0-0.4,0.3-0.7,0.7-0.7c0.4,0,0.7,0.3,0.7,0.7v3.3 c0,0.4-0.3,0.7-0.7,0.7H12c-0.4,0-0.7-0.3-0.7-0.7c0-0.4,0.3-0.7,0.7-0.7h1.7l-4.2-4.2c-0.3-0.3-0.3-0.7,0-0.9 C9.8,9.3,10.2,9.3,10.5,9.5z"/>
                                                            </g>
                                                        </svg>
                                                    </span>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="role">
                                        Coach
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16">
                                            <g>
                                                <path d="M6.5,5.5L2.3,1.3H4c0.4,0,0.7-0.3,0.7-0.7S4.4,0,4,0H0.7C0.3,0,0,0.3,0,0.7V4 c0,0.4,0.3,0.7,0.7,0.7S1.3,4.4,1.3,4V2.3l4.2,4.2c0.3,0.3,0.7,0.3,0.9,0C6.7,6.2,6.7,5.8,6.5,5.5z"/>
                                                <path d="M9.5,5.5l4.2-4.2H12c-0.4,0-0.7-0.3-0.7-0.7S11.6,0,12,0h3.3C15.7,0,16,0.3,16,0.7V4 c0,0.4-0.3,0.7-0.7,0.7c-0.4,0-0.7-0.3-0.7-0.7V2.3l-4.2,4.2c-0.3,0.3-0.7,0.3-0.9,0C9.3,6.2,9.3,5.8,9.5,5.5z"/>
                                                <path d="M5.5,9.5l-4.2,4.2V12c0-0.4-0.3-0.7-0.7-0.7S0,11.6,0,12v3.3C0,15.7,0.3,16,0.7,16H4 c0.4,0,0.7-0.3,0.7-0.7c0-0.4-0.3-0.7-0.7-0.7H2.3l4.2-4.2c0.3-0.3,0.3-0.7,0-0.9C6.2,9.3,5.8,9.3,5.5,9.5z"/>
                                                <path d="M10.5,9.5l4.2,4.2V12c0-0.4,0.3-0.7,0.7-0.7c0.4,0,0.7,0.3,0.7,0.7v3.3 c0,0.4-0.3,0.7-0.7,0.7H12c-0.4,0-0.7-0.3-0.7-0.7c0-0.4,0.3-0.7,0.7-0.7h1.7l-4.2-4.2c-0.3-0.3-0.3-0.7,0-0.9 C9.8,9.3,10.2,9.3,10.5,9.5z"/>
                                            </g>
                                        </svg>
                                    </span>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <!-- /// End Roles /// -->

                    <hr>

                    <!-- /// Add new Role /// -->
                    <div class="form-group">
                        <input-field v-model="title" type="text" label="New Role"></input-field>
                    </div>
                    <div class="flex flex-space-between">
                        <a @click="" class="btn-rounded btn-auto second-bg">Save</a>
                        <a @click="" class="btn-rounded btn-auto">Add new role +</a>
                    </div>
                    <!-- /// End Add new Role /// -->
                </div>

                <hr class="double">

                <!-- /// Departments /// -->
                <h3>{{ translateText('message.departments') }}</h3>
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
                    <span class="pagination-info">Displaying {{ projectDepartments.items.length }} results out of {{ projectDepartments.totalItems }}</span>

                </div>
                <!-- /// End Departments /// -->

                <hr>

                <div class="form">
                    <!-- /// Add new Department /// -->
                    <div class="form-group">
                        <input-field v-model="departmentName" type="text" v-bind:label="translateText('placeholder.new_department')"></input-field>
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a @click="createNewDepartment()" class="btn-rounded btn-auto">{{ translateText('button.add_new_department') }} +</a>
                    </div>
                    <!-- /// End Add new Department /// -->
                </div>

                <hr class="double">

                <!-- /// Subteams /// -->
                <h3>Subteams</h3> 
                <vue-scrollbar class="table-wrapper">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Subteam Name</th>
                                <th>Team Leader</th>
                                <th>Np. of Members</th>
                                <th>Department</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>PMO</td>
                                <td class="avatar">
                                    <div class="avatar-image" v-tooltip.top-center="'Nelson Carr'">
                                        <img src="http://dev.campr.biz/uploads/avatars/58ae8e1f2c465.jpeg"/>
                                    </div>
                                </td>
                                <td>12</td>
                                <td>GMP</td>
                                <td>
                                    <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </vue-scrollbar>
                <!-- /// End Subteams /// -->
                <hr>
                <div class="form">
                    <!-- /// Add new Subteam /// -->
                    <div class="form-group">
                        <input-field v-model="title" type="text" label="New Subteam"></input-field>
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a @click="" class="btn-rounded btn-auto">Add new Subteam +</a>
                    </div>
                    <!-- /// End Add new Subteam /// -->
                </div>
            </div>
        </div>               
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
import {mapGetters, mapActions} from 'vuex';
import moment from 'moment';
import Modal from '../../_common/Modal';
import MultiSelectField from '../../_common/_form-components/MultiSelectField';

export default {
    components: {
        InputField,
        ViewIcon,
        EditIcon,
        DeleteIcon,
        VTooltip,
        VueScrollbar,
        Modal,
        MultiSelectField,
    },
    methods: {
        ...mapActions(['getProjectDepartments', 'createDepartment', 'editDepartment', 'deleteDepartment', 'getProjectUsers']),
        moment: function(date) {
            return moment(date);
        },
        translateText(text) {
            return this.translate(text);
        },
        changeDepartmentPage(page) {
            this.activeDepartmentPage = page;
            this.getProjectDepartments({projectId: this.$route.params.id, page: page});
        },
        createNewDepartment() {
            let data = {
                name: this.departmentName,
                sequence: this.projectDepartments.items.length,
                rate: 0,
                abbreviation: this.departmentName.toLowerCase(),
            };
            this.createDepartment(data);
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
    },
    created() {
        this.getProjectDepartments({projectId: this.$route.params.id, page: this.departmentPage});
        this.getProjectUsers({id: this.$route.params.id});
    },
    computed: {
        ...mapGetters({
            projectDepartments: 'projectDepartments',
            managersForSelect: 'managersForSelect',
        }),
    },
    methods: {
        ...mapActions(['getOrganizationDepartments']),
        changeDepartmentPage: function(page) {
            this.activeDepartmentPage = page;
            this.getOrganizationDepartments(this.$route.params.id, page);
        },
        changeSubteamPage: function(page) {
            this.activeSubteamPage = page;
            this.getOrganizationSubteams(this.$route.params.id, page);
        },
    },
    computed: {
        ...mapGetters({
            organizationDepartmentsForSelect: 'organizationDepartmentsForSelect',
            organizationDepartments: 'organizationDepartments',
        }),
        departmentPages: function() {
            return this.organizationDepartments.totalItems / this.departmentsPerPage;
        },
    },
    created() {
        this.getOrganizationDepartments(this.$route.params.id, 1);
    },
    data() {
        return {
            departmentPage: 1,
            activeDepartmentPage: 1,
            departmentPages: 0,
            departmentName: '',
            showEditDepartmentModal: false,
            editdepartmentId: '',
            editDepartmentName: '',
            showDeleteDepartmentModal: false,
            editDepartmentManagers: [],
            message: {
                back_to_organization: 'Back to Project Organization',
                edit_organization: 'Edit Project Organization',
                distribution_lists: 'Distribution Lists',
                displaying: this.translate('message.displaying'),
                results_out_of: this.translate('message.results_out_of'),
            },
            departmentsPerPage: 6,
            activeDepartmentPage: 1,
        };
    },
    watch: {
        projectDepartments(value) {
            this.departmentPages = Math.ceil(this.projectDepartments.totalItems / this.projectDepartments.pageSize);
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_variables';
    @import '../../../css/_mixins';
    @import '../../../css/page-section';

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

    ul.roles-hierarchy {
        margin: 0;
        padding: 0;
        list-style: none;

        li {
            span.role {
                display: block;
                background-color: $darkColor;
                font-size: 12px;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                color: $lightColor;
                line-height: 1em;
                padding: 17px 56px 13px 20px;
                position: relative;
                cursor: grab;
                margin-bottom: 20px;

                svg {
                    width: 16px;
                    height: 16px;
                    fill: $lightColor;
                    position: absolute;
                    right: 20px;
                    top: 13px;
                }

                &:active {
                    cursor: grabbing;
                }
            }

            ul {
                li {
                    padding-left: 30px;
                }
            }
        }
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
