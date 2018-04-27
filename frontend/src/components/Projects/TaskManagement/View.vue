<template>
    <div class="project-task-management page-section">
        <!-- /// Delete Subtask Modal /// -->
        <modal v-if="showDeleteModal" @close="showDeleteModal = false">
            <p class="modal-title">{{ translateText('message.delete_task') }}</p>
            <div class="flex flex-space-between">
                <a @click.preventDefault="showDeleteModal = false" class="btn-rounded btn-auto">{{ translateText('message.no') }}</a>
                <a @click.preventDefault="deleteSubtask(showDeleteModal)" class="btn-rounded btn-empty btn-auto danger-color danger-border">{{ translateText('message.yes') }}</a>
            </div>
        </modal>
        <!-- /// End Delete Subtask Modal /// -->

        <edit-status-modal
                v-if="showEditStatusModal"
                :value="editableData.workPackageStatus"
                @cancel="onEditStatusModalCancel"
                @input="onChangeStatus"/>

        <!-- /// End Edit Status Modal /// -->
        <task-modals
            v-bind:editExternalCostModal="showEditExternalCostModal"
            v-bind:deleteExternalCostModal="showDeleteExternalCostModal"
            v-bind:editExternalForecastCostModal="showEditExternalForecastCostModal"
            v-bind:editExternalActualCostModal="showEditExternalActualCostModal"
            v-bind:externalCostObj="editExternalCostObj"
            v-bind:editInternalCostModal="showEditInternalCostModal"
            v-bind:deleteInternalCostModal="showDeleteInternalCostModal"
            v-bind:editInternalForecastCostModal="showEditInternalForecastCostModal"
            v-bind:editInternalActualCostModal="showEditInternalActualCostModal"
            v-bind:internalCostObj="editInternalCostObj"
            v-bind:taskObj="task"
            v-bind:closeTaskModal="showCloseTaskModal"
            v-bind:openTaskModal="showOpenTaskModal"
            @input="setModals"/>

        <div class="row">
            <div class="col-lg-6">

               <!-- /// Task Title and Label /// -->
                <div class="header">
                    <div>
                        <router-link :to="{name: 'project-task-management-list'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ translateText('message.back_to_task_management') }}
                        </router-link>
                        <h1>{{ task.name }}</h1>
                    </div>

                    <div v-if="editableData.label" class="task-label" :style="'background-color:' + editableData.label.color">
                        {{ editableData.label.label }}
                    </div>
                </div>
                <!-- /// End Task Title and Label /// -->

                <!-- /// Task Status /// -->
                <div class="task-status flex flex-v-center">
                    <div v-if="editableData.workPackageStatus">
                        <span class="small">{{ translateText('table_header_cell.status') }}:</span>
                        <div class="task-status-box">{{ editableData.workPackageStatus.label }}</div>
                        <a @click="initChangeStatusModal()" class="simple-link small">{{ translateText('message.edit') }}</a>
                    </div>
                    <div>
                        <div class="task-status-info">
                            <b>#{{ task.puid }}</b>
                            {{ translateText('message.created_on') }} {{ task.createdAt | moment('DD.MM.YYYY') }} {{ translateText('message.by') }}
                            <div class="user-avatar" v-bind:style="{ backgroundImage: 'url(' + task.responsibilityAvatar + ')' }"></div>
                            <b class="uppercase">{{ task.responsibilityFullName }}</b>
                            <span class="task-subtasks" v-if="task && task.children && task.children.length">
                                &nbsp;&nbsp;|&nbsp;&nbsp; {{ getSubtaskSummary() }} {{ translateText('message.subtasks') }} {{ translateText('label.completed') }}
                            </span>
                        </div>
                    </div>
                </div>
                <!-- /// End Task Status /// -->

                <hr class="double">

                <!-- /// Task Description /// -->
                <div class="task-description" v-html="task.content"></div>
                <!-- /// End Task Description /// -->

                <template v-if="task.children && task.children.length > 0">
                    <hr class="double">
                    <!-- ///Subtasks /// -->
                    <h3>{{ translateText('message.subtasks') }} | {{ getSubtaskSummary() }} {{ translateText('label.completed') }}</h3>
                    <div class="subtasks">
                        <div
                                v-for="subtask in task.children"
                                :key="subtask.id"
                                class="subtask flex flex-space-between">
                            <div class="checkbox-input clearfix">
                                <input
                                        :id="`subtask-${subtask.id}`"
                                        type="checkbox"
                                        :value="subtask.id"
                                        v-model="completedSubtasksIds"
                                        @change="onSubtaskStatusChange"/>
                                <label :for="`subtask-${subtask.id}`">{{ subtask.name }}</label>
                            </div>
                            <div>
                                <div class="text-right">
                                    <router-link
                                            :to="{name: 'project-task-management-edit', params: {id: task.project, taskId: subtask.id}}"
                                            class="btn-icon">
                                        <edit-icon fill="second-fill"/>
                                    </router-link>

                                    <button
                                            @click="showDeleteModal = subtask.id;"
                                            data-target="#subtask1-delete-modal"
                                            data-toggle="modal"
                                            type="button"
                                            class="btn-icon">
                                        <delete-icon fill="danger-fill"/>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Subtasks /// -->
                </template>

                <hr class="double">

                <task-history :history="taskHistory" />

                <!-- /// New Task Description /// -->
                <div class="new-comment">
                    <div class="user-avatar" v-bind:style="{ backgroundImage: 'url(' + currentUserAvatar + ')' }"></div>
                    <div class="new-comment-body">
                        <editor
                            height="200px"
                            id="newComment"
                            v-model="newComment"
                            :label="'message.new_comment'"/>
                        <div class="footer-buttons">
                            <a href="javascript:void(0)" class="btn-rounded btn-auto" @click="createNewComment()" >{{ translateText('button.comment') }}</a>
                            <router-link to="" v-if="isStarted" class="btn-rounded btn-auto danger-bg">{{ translateText('message.close_task') }}</router-link>
                        </div>
                    </div>
                </div>
                <!-- /// End New Task Description /// -->
            </div>

            <div class="col-lg-6">
                <!-- /// Header Buttons /// -->
                <div class="header-buttons">
                    <button
                        @click="initOpenTaskModal"
                        data-toggle="modal"
                        v-if="isClosed"
                        class="btn-rounded btn-auto danger-bg"
                        type="button">
                        {{ translateText('button.start_task') }}
                    </button>
                    <router-link
                        v-if="task.id"
                        :to="{name: 'project-task-management-edit', params: {id: task.project, taskId: task.id}}"
                        class="btn-rounded btn-auto">
                        {{ translateText('message.edit_task') }}
                    </router-link>
                    <!-- If task has not yet started, don't show the Close button and vice-versa -->
                    <button
                        @click="initCloseTaskModal"
                        data-toggle="modal"
                        v-if="!isClosed"
                        class="btn-rounded btn-auto danger-bg"
                        type="button">
                        {{ translateText('message.close_task') }}
                    </button>
                    <router-link
                        :to="{name: 'project-task-management-create'}"
                        class="btn-rounded btn-auto second-bg">
                        {{ translateText('button.new_task') }}
                    </router-link>
                </div>
                <!-- /// End Header Buttons /// -->

                <!-- /// Task Sidebar /// -->
                <div class="task-sidebar">
                    <!-- /// Phase & Milestone /// -->
                    <h3>{{ translateText('message.planning') }}</h3>
                    <div class="flex flex-space-between flex-v-center margintop20">
                        <div v-if="task.phase || task.milestone">
                            {{ translateText('message.this_task_part_of') }}
                            <router-link
                                :to="{name: 'project-phases-view-phase', params: {phaseId: task.phase}}"
                                class="simple-link">
                                {{ task.phaseName }}
                            </router-link>
                            <router-link
                                :to="{name: 'project-phases-view-milestone', params: {milestoneId: task.milestone}}"
                                class="simple-link">
                                {{ task.milestoneName }}
                            </router-link>
                        </div>
                        <div v-else>
                            {{ translateText('message.this_task_has_no_planning') }}
                        </div>
                        <div class="buttons">
                            <router-link
                                v-if="task.phase"
                                :to="{name: 'project-phases-edit-phase', params: {phaseId: task.phase}}"
                                class="btn-rounded btn-md btn-empty btn-auto">
                                {{ translateText('message.edit_phase') }}
                            </router-link>
                            <router-link
                                v-if="task.milestone"
                                :to="{name: 'project-milestones-edit-milestone', params: {milestoneId: task.milestone}}"
                                class="btn-rounded btn-md btn-empty btn-auto">
                                {{ translateText('button.edit_milestone') }}
                            </router-link>
                        </div>
                    </div>
                    <!-- /// End Phase & Milestone /// -->

                    <hr class="double">

                    <!-- /// Task Schedule /// -->
                    <h3>{{ translateText('message.task_schedule') }}</h3>
                    <scrollbar class="customScrollbar">
                        <div class="scroll-wrapper">
                            <table class="table table-small">
                                <thead>
                                    <tr>
                                        <th>{{ translateText('table_header_cell.schedule') }}</th>
                                        <th>{{ translateText('table_header_cell.start') }}</th>
                                        <th>{{ translateText('table_header_cell.finish') }}</th>
                                        <th>{{ translateText('table_header_cell.duration') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ translateText('label.base') }}</td>
                                        <td v-if="task.scheduledStartAt">{{ task.scheduledStartAt|date }}</td>
                                        <td v-else>-</td>
                            
                                        <td v-if="task.scheduledFinishAt">{{ task.scheduledFinishAt|date }}</td>
                                        <td v-else>-</td>
                            
                                        <td>
                                            {{ getDuration(task.scheduledStartAt, task.scheduledFinishAt) }}
                                        </td>
                                    </tr>
                                    <tr class="column-warning">
                                        <td>{{ translateText('label.forecast') }}</td>
                                        <td v-if="task.forecastStartAt">{{ task.forecastStartAt|date }}</td>
                                        <td v-else>-</td>
                            
                                        <td v-if="task.forecastFinishAt">{{ task.forecastFinishAt|date }}</td>
                                        <td v-else>-</td>
                            
                                        <td>
                                            {{ getDuration(task.forecastStartAt, task.forecastFinishAt) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ translateText('label.actual') }}</td>
                                        <td v-if="task.actualStartAt" >{{ task.actualStartAt|date }}</td>
                                        <td v-else>-</td>
                            
                                        <td v-if="task.actualFinishAt">{{ task.actualFinishAt|date }}</td>
                                        <td v-else>-</td>
                            
                                        <td>
                                            {{ getDuration(task.actualStartAt, task.actualFinishAt) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </scrollbar>
                    <div v-for="(dependancy, index) in task.dependencies"
                         :key="index"
                         class="flex flex-space-between flex-v-center margintop20">

                        {{ translateText('subtitle.task_precedesor') }}:
                        <router-link
                            :to="{name: 'project-task-management-view', params: { id: task.project, taskId: task.id }}"
                            class="simple-link">
                            {{ dependancy.name }}
                        </router-link>
                    </div>
                    <div class="flex flex-space-between flex-v-center margintop20">
                        <div></div>
                        <button
                            @click="onChangeScheduleModal()"
                            data-target="#edit-schedule-module"
                            data-toggle="modal"
                            class="btn-rounded btn-md btn-empty btn-auto"
                            type="button">
                            {{ translateText('button.edit_schedule') }}
                        </button>
                    </div>
                    <!-- /// Task End Schedule /// -->

                    <hr class="double">

                    <!-- /// Task Internal Costs /// -->
                    <h3>{{ translateText('message.internal_costs') }}</h3>
                    <scrollbar class="customScrollbar">
                        <div class="scroll-wrapper">
                            <table class="table table-small">
                                <thead>
                                    <tr>
                                        <th>{{ translateText('table_header_cell.resource') }}</th>
                                        <th>{{ translateText('label.daily_rate') }}</th>
                                        <th>{{ translateText('label.qty') }}</th>
                                        <th>{{ translateText('label.days') }}</th>
                                        <th>{{ translateText('message.total') }}</th>
                                        <th>{{ translateText('placeholder.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in editableData.internalCosts" :key="index">
                                        <td>{{ item.resourceName }}</td>
                                        <td>{{ item.rate | money }}</td>
                                        <td>{{ item.quantity | formatNumber }}</td>
                                        <td>{{ item.duration | formatNumber }}</td>
                                        <td><b>{{ item.total | money }}</b></td>
                                        <td>
                                            <button @click="initEditInternalCostModal(item)" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                            <button
                                                @click="initDeleteInternalCostModal(item)"
                                                type="button"
                                                class="btn-icon">
                                                <delete-icon fill="danger-fill"></delete-icon>
                                            </button>
                                        </td>
                                    </tr>
                            
                                    <tr>
                                        <td colspan="4" class="text-right"><b>{{ translateText('label.internal_costs_total') }}</b></td>
                                        <td colspan="2"><b>{{ task.internalCostTotal | money }}</b></td>
                                    </tr>
                                    <tr class="column-warning">
                                        <td colspan="4" class="text-right"><b>{{ translateText('label.forecast_total') }}</b></td>
                                        <td><b>{{ task.internalForecastCost | money }}</b></td>
                                        <td>
                                            <button @click="initEditInternalForecastCostModal()" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right"><b>{{ translateText('label.actual_total') }}</b></td>
                                        <td><b>{{ task.internalActualCost | money }}</b></td>
                                        <td>
                                            <button @click="initEditInternalActualCostModal()" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </scrollbar>
                    <div class="flex flex-space-between flex-v-center margintop20">
                        <div></div>
                        <button
                            @click="initAddInternalCostModal()"
                            data-target="#internal-costs-add-modal"
                            data-toggle="modal"
                            class="btn-rounded btn-md btn-empty btn-auto"
                            type="button">
                            {{ translateText('message.add_internal_costs') }} +
                        </button>
                    </div>
                    <!-- /// Task Internal Costs /// -->

                    <hr class="double">

                    <!-- /// Task External Costs /// -->
                    <h3>{{ translateText('message.external_costs') }}</h3>
                    <scrollbar class="customScrollbar">
                        <div class="scroll-wrapper">
                            <table class="table table-small">
                                <thead>
                                    <tr>
                                        <th>{{ translateText('placeholder.description') }}</th>
                                        <th>{{ translateText('label.qty') }}</th>
                                        <th>{{ translateText('table_header_cell.unit') }}</th>
                                        <th>{{ translateText('label.external_cost_unit_rate') }}</th>
                                        <th>{{ translateText('message.capex') }}</th>
                                        <th>{{ translateText('message.total') }}</th>
                                        <th>{{ translateText('placeholder.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(cost, index) in editableData.externalCosts" :key="index">
                                        <td>{{ cost.name }} </td>
                                        <td>{{ cost.quantity | formatNumber }}</td>
                                        <td>{{ cost.unit }}</td>
                                        <td><i class="fa fa-dollar"></i> {{cost.rate}}</td>
                                        <td>
                                            <switch-field
                                                    @input="onUpdateCostExpenseType(cost)"
                                                    :true-value="0"
                                                    :false-value="1"
                                                    v-model.number="cost.expenseType" />
                                        </td>
                                        <td><b>{{ itemTotal(cost) | formatMoney }}</b></td>
                                        <td>
                                            <button @click="initEditExternalCostModal(cost)" data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill" ></edit-icon></button>
                                            <button
                                                data-target="#logistics-delete-modal"
                                                data-toggle="modal"
                                                @click="initDeleteExternalCostModal(cost)"
                                                type="button"
                                                class="btn-icon">
                                                <delete-icon fill="danger-fill"></delete-icon>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><b>{{ translateText('message.capex_subtotal') }}</b></td>
                                        <td colspan="2">{{ task.externalCostCAPEXTotal | money }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><b>{{ translateText('message.opex_subtotal') }}</b></td>
                                        <td colspan="2">{{ task.externalCostOPEXTotal | money }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><b>{{ translateText('label.external_cost_total') }}</b></td>
                                        <td colspan="2"><b> {{ task.externalCostTotal | money }}</b></td>
                                    </tr>
                                    <tr class="column-warning">
                                        <td colspan="5" class="text-right"><b>{{ translateText('label.forecast_total') }}</b></td>
                                        <td><b>{{ task.externalForecastCost | money }}</b></td>
                                        <td>
                                            <button @click="initEditExternalForecastCostModal()" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                        </td>
                                    </tr>
                                    <tr class="column-alert">
                                        <td colspan="5" class="text-right"><b>{{ translateText('label.actual_total') }}</b></td>
                                        <td><b>{{ task.externalActualCost | money }}</b></td>
                                        <td>
                                            <button data-toggle="modal" @click="initEditExternalActualCostModal()" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </scrollbar>
                    <div class="flex flex-space-between flex-v-center margintop20">
                        <div></div>
                        <button
                            @click="initAddExternalCostModal()"
                            data-toggle="modal"
                            class="btn-rounded btn-md btn-empty btn-auto"
                            type="button">
                            {{ translateText('button.add_external_cost') }} +
                        </button>
                    </div>
                    <!-- /// Task External Costs /// -->

                    <hr class="double">

                    <task-view-assignments
                            :value="editableData.assignments"
                            :disabled="this.updatingAssignments"
                            @input="onUpdateAssignments"/>

                    <!-- /// End Task support & informed & consulted users /// -->
                    <hr class="double">

                    <!-- /// Task Completion /// -->
                    <h3>{{ translateText('message.status') }}</h3>
                    <div class="row">
                        <div class="col-md-12">
                            <range-slider
                                    :title="translateText('message.task_completion')"
                                    minSuffix=" %"
                                    :step="25"
                                    :disabled="taskProgressEditIsDisabled"
                                    @input="onUpdateTaskStatusProgress"
                                    :value="task.progress"/>
                        </div>
                         <div class="col-md-8" v-if="editableData.workPackageStatus">
                            <h4>{{editableData.workPackageStatus.label}}</h4>
                        </div>
                        <div class="col-md-4" v-if="!task.isClosed">
                            <select-field
                                    :title="translateText('message.change_status')"
                                    :options="workPackageStatusesForSelect"
                                    :value="editableData.workPackageStatus"
                                    @input="onChangeStatus"/>
                        </div>
                    </div>
                    <!-- /// End Task Completion /// -->

                    <hr class="double">

                    <!-- /// Task Condition /// -->
                    <condition v-model="editableData.colorStatus" v-bind:selectedStatusColor="editableData.colorStatus" v-on:input="updateColorStatus"/>

                    <!-- /// End Task Condition /// -->

                    <hr class="double">

                    <!-- /// Task Attachmets /// -->

                    <attachments
                            @input="onUpdateAttachments"
                            :disabled="disableAttachments"
                            v-model="editableData.medias"/>
                    <!-- /// End Task Attachments /// -->
                    <hr class="double">

                    <!-- /// Task Labels /// -->
                    <h3>{{ translateText('label.labels') }}</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="task-label-holder flex flex-v-center">
                                <div v-if="editableData.label">
                                    <div class="task-label" :style="'background-color:' + editableData.label.color">
                                        {{editableData.label.label}}
                                    </div>
                                    <button class="btn-icon btn-remove" type="button" @click="removeLabel">
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 10 10">
                                            <path d="M9.9,9.3L5.6,5l4.3-4.3c0.2-0.2,0.2-0.4,0-0.6C9.7,0,9.5,0,9.3,0.1L5,4.4L0.7,0.1C0.5,0,0.3,0,0.1,0.1
                                            C0,0.3,0,0.5,0.1,0.7L4.4,5L0.1,9.3C0,9.5,0,9.7,0.1,9.9c0.2,0.2,0.4,0.2,0.6,0L5,5.6l4.3,4.3c0.2,0.2,0.4,0.2,0.6,0
                                            C10,9.7,10,9.5,9.9,9.3z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select-field
                                    :title="'Add Label'"
                                    :options="labelsForSelect"
                                    :value="editableData.label"
                                    @input="updateLabel"/>
                        </div>
                    </div>
                    <!-- /// End Labels /// -->
                    <hr class="double">
                    <div class="footer-buttons">
                        <a href="javascript:void(0)" @click="getXmlFile" class="btn-rounded btn-auto btn-empty">{{ translateText('label.export_task') }}</a>
                    </div>
                </div>
                <!-- /// End Task Sidebar /// -->
            </div>
        </div>
        <alert-modal v-if="showFileUploadFailed" @close="showFileUploadFailed = false" :body="fileUploadErrorMessage" />
        <edit-schedule-modal
                v-model="showEditScheduleModal"
                :schedule="editableData.schedule"
                @save="onSaveSchedule"/>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import EditIcon from '../../_common/_icons/EditIcon';
import DeleteIcon from '../../_common/_icons/DeleteIcon';
import AttachIcon from '../../_common/_icons/AttachIcon';
import TaskModals from './View/TaskModals';
import Attachments from './Create/Attachments';
import Switches from '../../3rdparty/vue-switches';
import SelectField from '../../_common/_form-components/SelectField';
import MultiSelectField from '../../_common/_form-components/MultiSelectField';
import RangeSlider from '../../_common/_form-components/RangeSlider';
import Modal from '../../_common/Modal';
import AlertModal from '../../_common/AlertModal.vue';
import Editor from '../../_common/Editor';
import router from '../../../router';
import Condition from './Create/Condition';
import EditScheduleModal from './View/EditScheduleModal';
import TaskHistory from './View/TaskHistory';
import moment from 'moment';
import {createFormData} from '../../../helpers/task';
import Vue from 'vue';
import SwitchField from '../../_common/_form-components/SwitchField';
import TaskViewAssignments from './View/Assignments';
import EditStatusModal from './View/EditStatusModal';

const TASK_STATUS_OPEN = 1;
const TASK_STATUS_ONGOING = 3;
const TASK_STATUS_COMPLETED = 4;

export default {
    name: 'task-view',
    components: {
        EditStatusModal,
        TaskViewAssignments,
        EditIcon,
        DeleteIcon,
        AttachIcon,
        TaskModals,
        Attachments,
        Switches,
        SelectField,
        MultiSelectField,
        RangeSlider,
        Modal,
        router,
        Condition,
        moment,
        AlertModal,
        SwitchField,
        EditScheduleModal,
        Editor,
        TaskHistory,
    },
    created() {
        if (this.$route.params.taskId) {
            this.getTaskById(this.$route.params.taskId);
            this.getTaskHistory(this.$route.params.taskId);
        }
        this.getColorStatuses();
        this.getProjectUsers({id: this.$route.params.id});
        this.getWorkPackageStatuses();
        this.getProjectLabels(this.$route.params.id);
    },
    computed: {
        ...mapGetters({
            task: 'currentTask',
            projectUsersForSelect: 'projectUsersForSelectOnViewTask',
            projectUsersForMultipleSelect: 'projectUsersForSelect',
            labelsForSelect: 'labelsForChoice',
            currentUser: 'user',
        }),
        ...mapGetters([
            'workPackageStatusById',
            'taskHistory',
            'colorStatuses',
            'colorStatusesForSelect',
            'workPackageStatusesForSelect',
            'projectUsers',
        ]),
        isClosed() {
            return this.task.isClosed;
        },
        taskProgressEditIsDisabled() {
            return this.task.isClosed;
        },
        completedSubtasksCount() {
            if (!this.task || !this.task.children) {
                return 0;
            }

            let count = 0;
            this.task.children.forEach((subtask) => {
                if (this.isCompleted(subtask)) {
                    count++;
                }
            });

            return count;
        },
        currentUserAvatar: function() {
            if (this.currentUser.avatar === null) {
                return this.currentUser.gravatar;
            }

            return this.currentUser.avatar;
        },
    },
    watch: {
        task(value) {
            this.editableData.colorStatus = this.task.colorStatus
                ? {id: this.task.colorStatus, name: this.task.colorStatusName}
                : null
            ;
            this.editableData.workPackageStatus = this.task.workPackageStatus
                ? {key: this.task.workPackageStatus, label: this.translateText(this.task.workPackageStatusName)}
                : null
            ;

            this.editableData.schedule = {
                baseStartDate: new Date(this.task.scheduledStartAt),
                baseEndDate: new Date(this.task.scheduledFinishAt),
                forecastStartDate: new Date(this.task.forecastStartAt),
                forecastEndDate: new Date(this.task.forecastFinishAt),
                automatic: this.task.automaticSchedule,
                successors: this.task.dependants.map((item) => {
                    return {
                        key: item.id,
                        label: item.name,
                    };
                }),
                predecessors: this.task.dependencies.map((item) => {
                    return {
                        key: item.id,
                        label: item.name,
                    };
                }),
                duration: this.task.duration,
            };

            this.editableData.label = this.task.label
                ? {key: this.task.label, label: this.task.labelName, color: this.task.labelColor}
                : null
            ;

            this.editableData.medias = this.task.medias;

            let internal = [];
            let external = [];
            let itemTotal = this.itemTotal;

            this.task.costs.map(cost => {
                if (cost.isInternal) {
                    internal.push({
                        id: cost.id,
                        resourceName: cost.resourceName,
                        resource: cost.resource,
                        rate: cost.rate,
                        quantity: cost.quantity,
                        duration: cost.duration,
                        total: itemTotal(cost),
                    });

                    return;
                }

                external.push(
                    Object.assign({}, cost, {
                        selectedUnit: cost.unit && cost.unit.id ? cost.unit.id.toString() : null,
                        unit: cost.unit && cost.unit.id ? cost.unit.name : '',
                        total: itemTotal(cost),
                        customUnit: '',
                    }),
                );
            });

            this.editableData.internalCosts = internal;
            this.editableData.externalCosts = external;

            this.editableData.assignments.responsibility = this.task.responsibility && {key: this.task.responsibility};
            this.editableData.assignments.accountability = this.task.accountability && {key: this.task.accountability};

            this.editableData.assignments.supportUsers = this.task.supportUsers.map(user => ({
                key: user.id,
                label: user.firstName + ' ' + user.lastName,
            }));

            this.editableData.assignments.informedUsers = this.task.informedUsers.map(user => ({
                key: user.id,
                label: user.firstName + ' ' + user.lastName,
            }));

            this.editableData.assignments.consultedUsers = this.task.consultedUsers.map(user => ({
                key: user.id,
                label: user.firstName + ' ' + user.lastName,
            }));

            this.completedSubtasksIds = [];
            this.task.children.forEach((subtask) => {
                if (this.isCompleted(subtask)) {
                    this.completedSubtasksIds.push(subtask.id);
                }
            });
        },
    },
    methods: {
        ...mapActions([
            'getTaskById',
            'getTaskHistory',
            'deleteTaskSubtask',
            'addTaskComment',
            'getColorStatuses',
            'editTask',
            'getProjectUsers',
            'getWorkPackageStatuses',
            'getWorkPackageStatusesForSelect',
            'getProjectLabels',
            'patchTask',
            'uploadAttachmentTask',
            'patchSubtask',
            'editTaskCost',
        ]),
        onSubtaskStatusChange(event) {
            let taskId = Number(event.target.value);
            let data = {
                workPackageStatus: null,
            };

            if (this.completedSubtasksIds.indexOf(taskId) >= 0) {
                data.workPackageStatus = TASK_STATUS_COMPLETED;
            }

            this.patchSubtask({taskId, data});
        },
        isCompleted(task) {
            return task.isCompleted;
        },
        deleteSubtask(subtaskId) {
            this.deleteTaskSubtask(subtaskId);
            this.showDeleteModal = false;
        },
        getDuration(startDate, endDate) {
            let end = moment(endDate);
            let start = moment(startDate);

            return !isNaN(end.diff(start, 'days')) ? end.diff(start, 'days') + 1 : '-';
        },
        getSubtaskSummary() {
            return Translator.trans(
                'message.subtasks_summary',
                {
                    'completed_tasks': this.completedSubtasksCount,
                    'total_tasks': this.task && this.task.children
                        ? this.task.children.length
                        : 0,
                }
            );
        },
        createNewComment() {
            let authorId = null;
            let projectUsers = this.projectUsers.items;
            for (let i = 0; i < projectUsers.length; i++) {
                if (projectUsers[i].userEmail === this.currentUser.email) {
                    authorId = projectUsers[i].user;
                }
            }

            let data = {
                task: this.task,
                payload: {
                    body: this.newComment,
                    author: authorId,
                },
            };
            this.newComment = '';
            this.addTaskComment(data);
        },
        itemTotal(item) {
            let duration = (item.duration == null || isNaN(item.duration) || item.duration == 0) ? 1 : item.duration;
            let total = item.rate * item.quantity * duration;
            return !isNaN(total) ? total : 0;
        },
        onUpdateAttachments() {
            this.disableAttachments = true;
            let data = {
                medias: this.editableData.medias,
            };

            this
                .uploadAttachmentTask({
                    data: createFormData(data),
                    taskId: this.$route.params.taskId,
                })
                .then(
                    (response) => {
                        this.disableAttachments = false;

                        if (response.body && response.body.error && response.body.messages) {
                            this.fileUploadErrorMessage = response.body.messages.medias;
                            this.showFileUploadFailed = true;
                            return;
                        }

                        if (response.status === 0) {
                            this.fileUploadErrorMessage = this.translateText('message.uploading_file_failed');
                            this.showFileUploadFailed = true;
                        }
                    }
                )
            ;
        },
        translateText(text) {
            return this.translate(text);
        },
        onUpdateTaskStatusProgress(progress) {
            let workPackageStatus = this.workPackageStatusById(TASK_STATUS_OPEN);
            let actualStartAt = null;
            let actualFinishAt = null;

            if (progress > 0) {
                workPackageStatus = this.workPackageStatusById(TASK_STATUS_ONGOING);

                actualStartAt = moment().format('D-MM-YYYY');
                if (this.task.actualStartAt) {
                    actualStartAt = moment(this.task.actualStartAt).format('D-MM-YYYY');
                }

                if (progress === 100) {
                    workPackageStatus = this.workPackageStatusById(TASK_STATUS_COMPLETED);
                    actualFinishAt = moment().format('D-MM-YYYY');
                }
            }

            this.patchTask({
                taskId: this.task.id,
                data: {
                    progress,
                    workPackageStatus: workPackageStatus.id,
                    actualStartAt,
                    actualFinishAt,
                },
            });
        },
        initChangeStatusModal() {
            this.showEditStatusModal = true;
        },
        onEditStatusModalCancel() {
            this.showEditStatusModal = false;
        },
        onChangeStatus(value) {
            this.patchTask({
                taskId: this.task.id,
                data: {
                    workPackageStatus: value.key,
                },
            });

            this.showEditStatusModal = false;
        },
        onChangeScheduleModal() {
            this.showEditScheduleModal = true;
        },
        onSaveSchedule(schedule) {
            let data = {
                forecastStartAt: moment(schedule.forecastStartDate).format('DD-MM-YYYY'),
                forecastFinishAt: moment(schedule.forecastEndDate).format('DD-MM-YYYY'),
                dependants: schedule.successors.map((item) => {
                    return item.key;
                }),
                dependencies: schedule.predecessors.map((item) => {
                    return item.key;
                }),
            };

            this.patchTask({
                data: data,
                taskId: this.$route.params.taskId,
            }).then(({body}) => {
                if (body.error) {
                    return;
                }

                this.showEditScheduleModal = false;
            });
        },
        initEditExternalCostModal(externalCost) {
            let externalCostObj = {
                id: externalCost.id,
                name: externalCost.name,
                quantity: externalCost.quantity,
                selectedUnit: externalCost.selectedUnit,
                customUnit: externalCost.customUnit,
                rate: externalCost.rate,
            };
            this.showEditExternalCostModal = true;
            this.editExternalCostObj = externalCostObj;
        },
        initAddExternalCostModal() {
            let emptyExternalCostObj = {
                id: 0,
                name: '',
                quantity: '',
                selectedUnit: null,
                customUnit: null,
                rate: 0,
            };
            this.showEditExternalCostModal = true;
            this.editExternalCostObj = emptyExternalCostObj;
        },
        initDeleteExternalCostModal(externalCost) {
            this.showDeleteExternalCostModal = true;
            this.editExternalCostObj = externalCost;
        },
        onUpdateCostExpenseType(cost) {
            let data = {
                expenseType: cost.expenseType,
            };

            this.editTaskCost({costId: cost.id, data: data}).then(() => {
                this.getTaskById(this.$route.params.taskId);
            });
        },
        initEditExternalForecastCostModal() {
            this.showEditExternalForecastCostModal = true;
        },
        initEditExternalActualCostModal() {
            this.showEditExternalActualCostModal = true;
        },
        initAddInternalCostModal() {
            let emptyInternalCostObj = {
                id: 0,
                resource: null,
                daily_rate: 0,
                quantity: '',
                duration: '',
            };
            this.showEditInternalCostModal = true;
            this.editInternalCostObj = emptyInternalCostObj;
        },
        initEditInternalCostModal(cost) {
            let internalCostObj = {
                id: cost.id,
                resource: {key: cost.resource, label: cost.resourceName, rate: cost.rate},
                daily_rate: cost.rate,
                quantity: cost.quantity,
                duration: cost.duration,
            };
            this.showEditInternalCostModal = true;
            this.editInternalCostObj = internalCostObj;
        },
        initDeleteInternalCostModal(internalCost) {
            this.showDeleteInternalCostModal = true;
            this.editInternalCostObj = internalCost;
        },
        initEditInternalForecastCostModal() {
            this.showEditInternalForecastCostModal = true;
        },
        initEditInternalActualCostModal() {
            this.showEditInternalActualCostModal = true;
        },
        setModals(value) {
            this.showEditExternalCostModal = value;
            this.showDeleteExternalCostModal = value;
            this.showEditExternalForecastCostModal = value;
            this.showEditExternalActualCostModal = value;
            this.showEditInternalCostModal = value;
            this.showDeleteInternalCostModal = value;
            this.showEditInternalForecastCostModal = value;
            this.showEditInternalActualCostModal = value;
            this.showEditScheduleModal = value;
            this.showCloseTaskModal = value;
            this.showOpenTaskModal = value;
            this.getTaskById(this.$route.params.taskId);
        },
        updateColorStatus() {
            let data = {
                colorStatus: this.editableData.colorStatus.id,
            };
            this.patchTask({
                data: data,
                taskId: this.$route.params.taskId,
            });
        },
        updateLabel() {
            let data = {
                labels: [this.editableData.label.key],
            };
            this.patchTask({
                data: data,
                taskId: this.$route.params.taskId,
            });
        },
        removeLabel() {
            this.editableData.label = null;
            let data = {
                labels: [],
            };
            this.patchTask({
                data: data,
                taskId: this.$route.params.taskId,
            });
        },
        onUpdateAssignments(value) {
            this.editableData.assignments = value;
            this.updatingAssignments = true;
            let data = {
                responsibility: value.responsibility && value.responsibility.key,
                accountability: value.accountability && value.accountability.key,
                supportUsers: value.supportUsers.map((u) => u.key),
                consultedUsers: value.consultedUsers.map((u) => u.key),
                informedUsers: value.informedUsers.map((u) => u.key),
            };

            this.patchTask({
                data,
                taskId: this.$route.params.taskId,
            }).then(() => {
                this.updatingAssignments = false;
            });
        },
        initCloseTaskModal() {
            this.showCloseTaskModal = true;
        },
        initOpenTaskModal() {
            this.showOpenTaskModal = true;
        },
        getXmlFile() {
            Vue.http
                .get(Routing.generate('app_api_workpackage_export', {id: this.task.id})).then((response) => {
                    if (response.status === 200) {
                        let blob = new Blob([response.body], {type: 'mime/type'});
                        let link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = 'task_' + this.task.id + '.xml';
                        link.click();
                    }
                }, (response) => {}
            );
        },
    },
    data() {
        return {
            fileUploadErrorMessage: '',
            showFileUploadFailed: false,
            showDeleteModal: false,
            showEditStatusModal: false,
            showEditScheduleModal: false,
            showAddInternalCostsModal: false, // remove this
            showEditExternalCostModal: false,
            showDeleteExternalCostModal: false,
            showEditExternalForecastCostModal: false,
            showEditExternalActualCostModal: false,
            showEditInternalCostModal: false,
            showDeleteInternalCostModal: false,
            showEditInternalForecastCostModal: false,
            showEditInternalActualCostModal: false,
            showCloseTaskModal: false,
            showOpenTaskModal: false,
            editableData: {
                assignments: {
                    responsibility: null,
                    accountability: null,
                    supportUsers: [],
                    consultedUsers: [],
                    informedUsers: [],
                },
                workPackageStatus: null,
                colorStatus: false,
                label: null,
                medias: [],
                schedule: {
                    baseStartDate: new Date(),
                    baseEndDate: new Date(),
                    forecastStartDate: new Date(),
                    forecastEndDate: new Date(),
                    automatic: false,
                    successors: [],
                    predecessors: [],
                    duration: 0,
                },
                internalCosts: [],
                externalCosts: [],
            },
            editExternalCostObj: {},
            editInternalCostObj: {},
            completedSubtasksIds: [],
            newComment: '',
            updatingAssignments: false,
            disableAttachments: false,
        };
    },
};
</script>

<!-- scoped css is not applied to elements added via v-html, so we add some global styles here -->
<style lang="scss">
    .task-description {
        p {
            margin-bottom: 20px;
        }

        ol {
            margin-left: 20px;
            list-style-type: decimal;
        }
    }
</style>

<style lang="scss">
    @import '../../../css/page-section';
    @import '../../../css/_variables';
    @import '../../../css/_mixins';
    @import '../../../css/common';

    .page-section {
        .header {
            justify-content: flex-start;
            align-items: center;

            .task-label {
                margin:0 0 0 20px;
            }
        }
    }

    .task-label {
        @include border-radius(3px);
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: $whiteColor;
        height: 30px;
        padding: 0 30px;
        line-height: 31px;
        display: inline-block;
    }

    .header-buttons {
        margin: 20px 0;
        text-align: right;

        .btn-rounded {
            margin-left: 10px;
        }
    }

    .footer-buttons {
        margin: 20px 0 0;

        .btn-rounded {
            margin: 0 10px 0 0;
        }
    }

    .task-status {
        text-transform: uppercase;
        letter-spacing: 0.1em;

        .small {
            display: block;
        }

        .task-status-box {
            background-color: $middleColor;
            color: $lighterColor;
            @include border-radius(3px);
            font-size: 10px;
            padding: 0 30px;
            line-height: 31px;
            margin-right: 10px;
            display: inline-block;
            white-space: nowrap;
        }

        .task-status-info {
            padding-left: 10px;

            .user-avatar {
                img {
                    margin: 0 10px;
                }
            }
        }
    }

    .user-avatar {
        width: 30px;
        height: 30px;
        display: inline-block;        
        margin: 0 5px 0;  
        position: relative;
        top: -2px;
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        vertical-align: middle;
        @include border-radius(50%);
    }

    p {
        margin-bottom: 20px;
    }

    .subtasks {
        .subtask {
            margin-bottom: 20px;
            border-bottom: 1px solid $darkColor;

            &:last-child {
                margin-bottom: 0;
                border-bottom: none;

                .checkbox-input {
                    margin: 0;

                    label {
                        margin: 0;
                    }
                }
            }
        }

        .checkbox-input {
            margin-bottom: 15px;

            label {
                position: relative;
                font-size: 12px;
                text-transform: none;
                padding-left: 30px;

                &:before {
                    position: absolute;
                    left: 0;
                }
            }

            &:last-child {
                margin-bottom: 0;
            }
        }

        .text-right {
            width: 100px;
        }

        .btn-icon {
            margin-left: 10px;
        }
    }

    .comment {
        .comment-header {
            padding-right: 60px;
            position: relative;

            .edit-comment {
                position: absolute;
                right: 0;
                top: 7px;
            }

            .task-label {
                margin: 0 20px;
            }
        }

        .comment-body {
            padding: 10px 0 0 55px;

            ul {
                list-style-type: disc;
            }

            ol {
                list-style-type: decimal;
            }

            img {
                @include box-shadow(0, 0, 20px, $darkColor);
            }
        }
    }

    .new-comment {
        position: relative;
        padding-left: 55px;

        .user-avatar {
            position: absolute;
            top: 2px;
            left: 0;
        }
    }

    .task-sidebar {
        background-color: $darkColor;
        padding: 30px;

        hr.double {
            border-top: 4px double $middleColor;
        }

        .dropdown {
            border: 1px solid $middleColor;
        }

        .slider-holder {
            margin-bottom: 15px;
        }
    }

    .buttons {
        .btn-rounded {
            margin: 0 0 10px 10px;
            
            &:last-child {
                margin-bottom: 0;
            }
        }
    }

    h3 {
        font-size: 16px;
        text-transform: uppercase;
        font-weight: 300;
        letter-spacing: 0.1em;
        margin:0 0 15px;
    }

    h4 {
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1.6px;
    }

    .table-small {
        >thead {
            >tr {
                >th {
                    height: 30px;
                    padding: 5px 15px;
                }
            }
        }

        >tbody {
            >tr {
                >td {
                    height: 30px;
                    padding: 5px 15px;
                }
            }
        }

        .btn-icon {
            margin-right: 10px;
            position: relative;
            top: 2px;
        }

        label {
            margin: 0;
        }
    }

    .status-boxes {
        .status-box {
            width: 30px;
            height: 30px;
            margin-right: 5px;
            background-color:$fadeColor;
        }
    }

    .btn-remove {
        margin-left: 10px;
        width: 10px;
        height: 10px;

        svg {
            width: 10px;
            height: 10px;
            fill: $dangerColor;
            @include transition(all, 0.2s, ease-in);
        }

        &:hover,
        &:active {
            svg {
                @include opacity(0.8);
            }
        }
    }

    .task-label-holder {
        margin-bottom: 10px;

        .btn-icon {
            position: relative;
            top: 5px;

            svg {
                width: 100%;
            }
        }

        &:last-child {
            margin: 0;
        }
    }
</style>
