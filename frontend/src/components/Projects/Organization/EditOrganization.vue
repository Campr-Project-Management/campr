<template>
    <div class="create-task page-section">
        <!-- /// DEPARTMENT MODALS /// -->
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

        <!-- /// SUBTEAM MODALS /// -->
        <modal v-if="showEditSubteamModal" @close="showEditSubteamModal = false">
            <p class="modal-title">{{ translateText('message.edit_subteam') }}</p>
            <input-field v-model="editSubteamName" :content="editSubteamName" type="text" v-bind:label="translateText('label.subteam_name')"></input-field>
            <multi-select-field
                    v-bind:title="translateText('label.select_users')"
                    v-bind:options="projectUsersForSelect"
                    v-bind:selectedOptions="editSubteamMembers"
                    v-model="editSubteamMembers" />
            <br />
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
            <div class="col-md-6">
                <div class="header">
                    <div>
                        <router-link :to="{name: 'project-organization'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ translateText('message.back_to_organization') }}
                        </router-link>
                        <h1>{{ translateText('message.edit_organization') }}</h1>
                    </div>
                </div>

                <div class="form">
                    <!-- /// Roles /// -->
                    <h3>{{ translateText('message.project_roles') }}</h3>
                    <!--<ul class="roles-hierarchy">
                        <organization-distribution-item :item='distributionHierarchy'></organization-distribution-item>
                    </ul>-->
                    <div class="dd" id="domenu-0">
                        <!--<button class="dd-new-item">+</button>-->
                        <li class="dd-item-blueprint">
                        <div class="dd-handle dd3-handle">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16">
                                <path d="M6.5,5.5L2.3,1.3H4c0.4,0,0.7-0.3,0.7-0.7S4.4,0,4,0H0.7C0.3,0,0,0.3,0,0.7V4 c0,0.4,0.3,0.7,0.7,0.7S1.3,4.4,1.3,4V2.3l4.2,4.2c0.3,0.3,0.7,0.3,0.9,0C6.7,6.2,6.7,5.8,6.5,5.5z"/>
                                <path d="M9.5,5.5l4.2-4.2H12c-0.4,0-0.7-0.3-0.7-0.7S11.6,0,12,0h3.3C15.7,0,16,0.3,16,0.7V4 c0,0.4-0.3,0.7-0.7,0.7c-0.4,0-0.7-0.3-0.7-0.7V2.3l-4.2,4.2c-0.3,0.3-0.7,0.3-0.9,0C9.3,6.2,9.3,5.8,9.5,5.5z"/>
                                <path d="M5.5,9.5l-4.2,4.2V12c0-0.4-0.3-0.7-0.7-0.7S0,11.6,0,12v3.3C0,15.7,0.3,16,0.7,16H4 c0.4,0,0.7-0.3,0.7-0.7c0-0.4-0.3-0.7-0.7-0.7H2.3l4.2-4.2c0.3-0.3,0.3-0.7,0-0.9C6.2,9.3,5.8,9.3,5.5,9.5z"/>
                                <path d="M10.5,9.5l4.2,4.2V12c0-0.4,0.3-0.7,0.7-0.7c0.4,0,0.7,0.3,0.7,0.7v3.3 c0,0.4-0.3,0.7-0.7,0.7H12c-0.4,0-0.7-0.3-0.7-0.7c0-0.4,0.3-0.7,0.7-0.7h1.7l-4.2-4.2c-0.3-0.3-0.3-0.7,0-0.9 C9.8,9.3,10.2,9.3,10.5,9.5z"/>
                            </svg>
                        </div>
                        <div class="dd3-content">
                            <span class="item-name">[item_name]</span>
                            <div class="dd-button-container">
                                <button class="item-remove" data-confirm-class="item-remove-confirm">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16">
                                        <path d="M15.2,2h-2.3h-2.1V0.5c0-0.3-0.2-0.5-0.5-0.5H5.1C4.8,0,4.6,0.2,4.6,0.5V2H2.5H0.8C0.5,2,0.3,2.2,0.3,2.5S0.5,3,0.8,3H2 v12.5C2,15.8,2.2,16,2.5,16h10.4c0.3,0,0.5-0.2,0.5-0.5V3h1.8c0.3,0,0.5-0.2,0.5-0.5S15.5,2,15.2,2z M5.6,1h4.2v1H5.6V1z M12.4,15 H3V3h2.1h5.2h2.1V15z"/>
                                        <path d="M5.1,4.2c-0.3,0-0.5,0.2-0.5,0.5v7.8c0,0.3,0.2,0.5,0.5,0.5s0.5-0.2,0.5-0.5V4.7C5.6,4.5,5.3,4.2,5.1,4.2z"/>
                                        <path d="M7.7,4.2c-0.3,0-0.5,0.2-0.5,0.5v7.8c0,0.3,0.2,0.5,0.5,0.5s0.5-0.2,0.5-0.5V4.7C8.2,4.5,8,4.2,7.7,4.2z"/>
                                        <path d="M9.8,4.7v7.8c0,0.3,0.2,0.5,0.5,0.5s0.5-0.2,0.5-0.5V4.7c0-0.3-0.2-0.5-0.5-0.5S9.8,4.5,9.8,4.7z"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="dd-edit-box" style="display: none;">
                            <input type="text" name="title" autocomplete="off" placeholder="Item"
                                    data-placeholder="Any nice idea for the title?"
                                    v-bind:data-default-value="departmentPage">
                            <i class="end-edit">save</i>
                            </div>
                        </div>
                        </li>
                        <ol class="dd-list"></ol>
                    </div>
                    <!-- /// End Roles /// -->

                    <hr>

                    <!-- /// Add new Role /// -->
                    <div class="form-group">
                        <input-field v-model="title" type="text" label="New Role"></input-field>
                    </div>
                    <div class="flex flex-space-between">
                        <a @click="" class="btn-rounded btn-auto second-bg">Save</a>
                        <a @click="addNewItemDistribution({title})" class="btn-rounded btn-auto">Add new role +</a>
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
                    <span class="pagination-info" v-if="projectDepartments && projectDepartments.items">{{ translateText('message.displaying') }} {{ projectDepartments.items.length }} {{ translateText('message.results_out_of') }} {{ projectDepartments.totalItems }}</span>

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
                                <td>{{ subteam.subteamMembers.length }}</td>
                                <td>-</td>
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
                        <input-field v-model="subteamName" type="text" v-bind:label="translateText('label.new_subteam')"></input-field>
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a @click="createNewSubteam()" class="btn-rounded btn-auto">{{ translateText('button.add_new_subteam') }} +</a>
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
import moment from 'moment';
import Modal from '../../_common/Modal';
import MultiSelectField from '../../_common/_form-components/MultiSelectField';
import OrganizationDistributionItem from './OrganizationDistributionItem';
import 'domenu';

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
        OrganizationDistributionItem,
    },
    methods: {
        ...mapActions([
            'getProjectDepartments', 'createDepartment', 'editDepartment',
            'deleteDepartment', 'getProjectUsers', 'getSubteams', 'createSubteam',
            'editSubteam', 'deleteSubteam',
        ]),
        moment: function(date) {
            return moment(date);
        },
        translateText: function(text) {
            return this.translate(text);
        },
        changeDepartmentPage: function(page) {
            this.activeDepartmentPage = page;
        },
        changeSubteamPage: function(page) {
            this.activeSubteamPage = page;
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
        addDistributionData() {
            const distData = this.distributionHierarchy;
            $('#domenu-0').domenu({'data': JSON.stringify(distData)}).parseJson();
        },
        addNewItemDistribution(item) {
            $('#domenu-0').domenu().createNewListItem(item);
        },
        initEditSubteamModal(subteam) {
            this.showEditSubteamModal = true;
            this.editSubteamId = subteam.id;
            this.editSubteamName = subteam.name;
            this.editSubteamMembers = [];
            subteam.subteamMembers.map(member => {
                this.editSubteamMembers.push({key: member.id, label: member.userName});
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
            this.createSubteam(data);
        },
        editSelectedSubteam() {
            let data = {
                id: this.editSubteamId,
                name: this.editSubteamName,
                subteamMembers: this.editSubteamMembers.map(member => {
                    return {'user': member.key};
                }),
            };
            this.editSubteam(data);
            this.showEditSubteamModal = false;
        },
        deleteSelectedSubteam() {
            this.showDeleteSubteamModal = false;
            this.deleteSubteam(this.deleteSubteamId);
        },
    },
    mounted: function() {
        $('#domenu-0').domenu({
            data: '[]',
        }).parseJson();

        this.addDistributionData();
    },
    created() {
        this.getProjectDepartments({projectId: this.$route.params.id, page: this.departmentPage});
        this.getProjectUsers({id: this.$route.params.id});
        this.getSubteams({project: this.$route.params.id, page: this.subteamPage});
    },
    computed: {
        ...mapGetters({
            projectDepartments: 'projectDepartments',
            managersForSelect: 'managersForSelect',
            projectUsersForSelect: 'projectUsersForSelect',
            subteams: 'subteams',
        }),
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
            subteamPage: 1,
            subteamName: '',
            deleteSubteamId: '',
            showEditSubteamModal: false,
            showDeleteSubteamModal: false,
            editSubteamMembers: [],
            subteamPages: 0,
            activeSubteamPage: 1,
            departmentsPerPage: 6,
            distributionHierarchy: [{
                title: 'Project Sponsor',
                id: 1,
                children: [
                    {
                        title: 'Project Manager',
                        id: 2,
                        children: [
                            {
                                title: 'Team Leader',
                                id: 3,
                                children: [
                                    {
                                        title: 'Team Member',
                                        id: 4,
                                        children: [],
                                    },
                                ],
                            },
                        ],
                    },
                    {
                        title: 'Coach',
                        id: 5,
                        children: [],
                    },
                ],
            }],
        };
    },
    watch: {
        projectDepartments(value) {
            this.departmentPages = Math.ceil(this.projectDepartments.totalItems / this.projectDepartments.pageSize);
        },
    },
};
</script>

<style lang="scss">
    @import '../../../css/_variables';
    @import '../../../css/_mixins';
    @import '../../../css/do-menu';
</style>

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
