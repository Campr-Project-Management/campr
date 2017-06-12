<template>
    <div class="project-task-management page-section">
        <modal v-if="showDeleteModal" @close="showDeleteModal = false">
            <p class="modal-title">{{ message.delete_task }}</p>
            <div class="flex flex-space-between">
                <a @click.preventDefault="showDeleteModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ message.no }}</a>
                <a @click.preventDefault="deleteSubtask(showDeleteModal)" class="btn-rounded">{{ message.yes }}</a>
            </div>
        </modal>

        <div class="row">
            <div class="col-md-6">

               <!-- /// Task Title and Label /// -->
                <div class="header">
                    <div>
                        <router-link :to="{name: 'project-task-management-list'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ message.back_to_task_management }}
                        </router-link>
                        <h1>{{ task.name }}</h1>
                    </div>

                    <div v-if="task.label" class="task-label" :style="'background-color:' + task.labelColor">
                        {{ task.labelName }}
                    </div>
                </div>
                <!-- /// End Task Title and Label /// -->

                <!-- /// Task Status /// -->
                <div class="task-status flex flex-v-center">
                    <div>
                        <span class="small">{{ message.status }}:</span>
                        <div class="task-status-box" :style="'background-color: '+task.colorStatusColor">{{ task.colorStatusName }}</div>
                        <a href="#open-status-edit-modal" class="simple-link small">{{ message.edit }}</a>
                    </div>
                    <div>
                        <div class="task-status-info">
                            <b>#{{ task.puid }}</b>
                            {{message.created_on}} {{ task.createdAt | moment('DD.MM.YYYY') }} {{message.by}}
                            <div class="user-avatar">
                                <img :src="task.responsibilityAvatar" :alt="task.responsibilityFullName"/>
                                <b>{{ task.responsibilityFullName }}</b>
                            </div>
                            <span class="task-subtasks">
                                &nbsp;&nbsp;|&nbsp;&nbsp; {{ getSubtaskSummary() }} {{ message.subtasks }} {{ message.completed }}
                            </span>
                        </div>
                    </div>
                </div>
                <!-- /// End Task Status /// -->

                <hr class="double">

                <!-- /// Task Description /// -->
                <div class="task-description" v-html="task.content"></div>
                <!-- /// End Task Description /// -->

                <hr class="double">

                <!-- ///Subtasks /// -->
                <h3>{{ message.subtasks }} | {{ getSubtaskSummary() }} {{ message.completed }}</h3>
                <div v-for="subtask in task.children" class="subtasks">
                    <div class="subtask flex flex-space-between">
                        <div class="checkbox-input clearfix">
                            <input :id="'subtask-'+subtask.puid" type="checkbox" name="" value="">
                            <label :for="'subtask-'+subtask.puid">{{ subtask.name }}</label>
                        </div>
                        <div>
                            <div class="text-right">
                                <router-link
                                    :to="{name: 'project-task-management-edit', params: {id: task.project, taskId: subtask.id}}"
                                    class="btn-icon">
                                    <edit-icon fill="second-fill"></edit-icon>
                                </router-link>

                                <button
                                    @click="showDeleteModal = subtask.id;"
                                    data-target="#subtask1-delete-modal"
                                    data-toggle="modal"
                                    type="button"
                                    class="btn-icon">
                                    <delete-icon fill="danger-fill"></delete-icon>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /// End Subtasks /// -->

                <hr class="double">

                <!-- /// Task History /// -->
                <div class="task-history">
                    <div v-for="item in taskHistory">

                        <!-- /// Task assignement /// -->
                        <div v-if="item.isResponsabilityAdded" class="comment">
                            <div class="comment-header">
                                <div class="user-avatar">
                                    <img :src="item.userGravatar" :alt="item.userName"/>
                                    <b>{{item.userName}}</b>
                                </div>
                                <a href="#link-to-member-page" class="simple-link">{{ item.userEmail }}</a>
                                    {{ message.assigned_to }}
                                <a href="#link-to-member-page" class="simple-link">@sandy.fc</a>
                                {{ getHumanTimeDiff(item.createdAt) }}
                            </div>
                        </div>
                        <!-- /// End Task Assignement /// -->

                        <!-- /// Task Comment /// -->
                        <div v-else-if="item.isCommentAdded" class="comment">
                            <div class="comment-header">
                                <div class="user-avatar">
                                    <img :src="item.userGravatar" :alt="item.userName"/>
                                    <b>{{item.userName}}</b>
                                </div>
                                <a href="#link-to-member-page" class="simple-link">{{ item.userEmail }}</a>
                                {{message.has_commented_task }} {{ getHumanTimeDiff(item.createdAt) }} | edited 4 hours ago
                                <button data-target="#comment-1-edit" class="simple-link edit-comment" data-toggle="modal" type="button">edit</button>
                            </div>
                            <div class="comment-body">
                                <p>Morbi lectus massa, sollicitudin quis luctus non, pulvinar sed nibh. Suspendisse id dui a sem tempus pretium. Nunc a ornare lacus. Fusce eleifend enim id euismod scelerisque. Maecenas eu consequat ligula, id mollis mauris. Mauris ac mauris sed lorem vulputate bibendum id ut orci. Maecenas lacinia eget ipsum vitae tincidunt.</p>
                                <ul>
                                    <li>Morbi at diam congue ante auctor tincidunt</li>
                                    <li>Pellentesque arcu odio</li>
                                    <li>Fusce malesuada magna et tincidunt vulputate</li>
                                </ul>
                            </div>
                        </div>
                        <!-- /// End Task Comment /// -->

                        <!-- /// Task Label added /// -->
                        <div v-else-if="item.isLabelAdded" class="comment">
                            <div class="comment-header">
                                <div class="user-avatar">
                                    <img :src="item.userGravatar" :alt="item.userName"/>
                                    <b>{{item.userName}}</b>
                                </div>
                                <a href="#link-to-member-page" class="simple-link">{{ item.userEmail }}</a>
                                added
                                <div class="task-label" :style="'background-color:#e04fcc'">
                                    High Priority
                                </div>
                                {{ getHumanTimeDiff(item.createdAt) }}
                            </div>
                        </div>
                        <!-- /// End Task Label Added /// -->

                        <!-- /// Task Edited /// -->
                        <div v-else class="comment">
                            <div class="comment-header">
                                <div class="user-avatar">
                                    <img :src="item.userGravatar" :alt="item.userName"/>
                                    <b>{{item.userName}}</b>
                                </div>
                                <a href="#link-to-member-page" class="simple-link">{{ item.userEmail }}</a>
                                {{ message.has_edited_task }} {{ getHumanTimeDiff(item.createdAt) }}
                            </div>
                        </div>
                        <!-- /// End Task Edited /// -->

                        <hr class="double">
                    </div>
                </div>    
                <!-- /// End Task History /// -->

                <!-- /// New Task Description /// -->
                <div class="new-comment">
                    <div class="user-avatar">
                        <img :src="task.responsibilityAvatar" :alt="responsibilityFullName"/>
                    </div>
                    <div class="new-comment-body">
                        <div class="vueditor-holder">
                            <div class="vueditor-header">{{message.new_comment}}</div>
                            <Vueditor ref="newCommentBody" />
                        </div>
                        <div class="footer-buttons">
                            <a href="javascript:void(0)" class="btn-rounded btn-auto" @click="createNewComment()" >{{message.comment}}</a>
                            <router-link to="" v-if="isStarted" class="btn-rounded btn-auto danger-bg">{{ message.close_task }}</router-link>
                        </div>
                    </div>
                </div>
                <!-- /// End New Task Description /// -->
            </div>

            <div class="col-md-6">
                <!-- /// Header Buttons /// -->
                <div class="header-buttons">
                    <router-link
                        to=""
                        v-if="isClosed"
                        class="btn-rounded btn-auto second-bg">
                        {{ message.start_task }}
                    </router-link>
                    <router-link
                        :to="{name: 'project-task-management-edit', params: {id: task.project, taskId: task.id}}"
                        class="btn-rounded btn-auto">
                        {{ message.edit_task }}
                    </router-link>
                    <!-- If task has not yet started, don't show the Close button and vice-versa -->
                    <router-link
                        to=""
                        v-if="isStarted"
                        class="btn-rounded btn-auto danger-bg">
                        {{ message.close_task }}
                    </router-link>
                    <router-link
                        :to="{name: 'project-task-management-create'}"
                        class="btn-rounded btn-auto second-bg">
                        {{ message.new_task }}
                    </router-link>
                </div>
                <!-- /// End Header Buttons /// -->

                <!-- /// Task Sidebar /// -->
                <div class="task-sidebar">
                    <!-- /// Phase & Milestone /// -->
                    <h3>{{ message.planning }}</h3>
                    <div class="flex flex-space-between flex-v-center margintop20">
                        <div>
                            {{message.this_task_part_of}}
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
                        <div>
                            <router-link
                                :to="{name: 'project-phases-edit-phase', params: {phaseId: task.phase}}"
                                class="btn-rounded btn-md btn-empty btn-auto">
                                {{message.edit_phase}}
                            </router-link>
                            <router-link
                                :to="{name: 'project-milestones-edit-milestone', params: {milestoneId: task.milestone}}"
                                class="btn-rounded btn-md btn-empty btn-auto">
                                {{message.edit_milestone}}
                            </router-link>
                        </div>
                    </div>
                    <!-- /// End Phase & Milestone /// -->

                    <hr class="double">

                    <!-- /// Task Schedule /// -->
                    <h3>{{message.task_schedule}}</h3>
                    <vue-scrollbar class="table-wrapper">
                        <table class="table table-small">
                            <thead>
                                <tr>
                                    <th>{{message.schedule}}</th>
                                    <th>{{message.start}}</th>
                                    <th>{{message.finish}}</th>
                                    <th>{{message.duration}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{message.base}}</td>
                                    <td v-if="task.scheduledStartAt">{{ task.scheduledStartAt|moment('DD.MM.YYYY') }}</td>
                                    <td v-else>-</td>

                                    <td v-if="task.scheduledFinishAt">{{ task.scheduledFinishAt|moment('DD.MM.YYYY') }}</td>
                                    <td v-else>-</td>

                                    <td>
                                        {{ getDuration(task.scheduledStartAt, task.scheduledFinishAt) }}
                                    </td>
                                </tr>
                                <tr class="column-warning">
                                    <td>{{message.forecast}}</td>
                                    <td v-if="task.forecastStartAt">{{ task.forecastStartAt|moment('DD.MM.YYYY') }}</td>
                                    <td v-else>-</td>

                                    <td v-if="task.forecastFinishAt">{{ task.forecastFinishAt|moment('DD.MM.YYYY') }}</td>
                                    <td v-else>-</td>

                                    <td>
                                        {{ getDuration(task.forecastStartAt, task.forecastFinishAt) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{message.actual}}</td>
                                    <td v-if="task.actualStartAt" >{{ task.actualStartAt|moment('DD.MM.YYYY') }}</td>
                                    <td v-else>-</td>

                                    <td v-if="task.actualFinishAt">{{ task.actualFinishAt|moment('DD.MM.YYYY') }}</td>
                                    <td v-else>-</td>

                                    <td>
                                        {{ getDuration(task.actualStartAt, task.actualFinishAt) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </vue-scrollbar>
                    <div v-for="dependancy in task.dependencies" class="flex flex-space-between flex-v-center margintop20">
                        <div>
                            {{message.task_precedesor}}:
                            <router-link
                                :to="{name: 'project-task-management-view', params: { id: task.project, taskId: task.id }}"
                                class="simple-link">
                                {{ dependancy.name }}
                            </router-link>
                        </div>
                        <button
                            data-target="#edit-schedule-module"
                            data-toggle="modal"
                            class="btn-rounded btn-md btn-empty btn-auto"
                            type="button">
                            Edit Schedule
                        </button>
                    </div>
                    <!-- /// Task End Schedule /// -->

                    <hr class="double">

                    <!-- /// Task Internal Costs /// -->
                    <h3>{{message.internal_costs}}</h3>
                    <vue-scrollbar class="table-wrapper">
                        <table class="table table-small">
                            <thead>
                                <tr>
                                    <th>{{message.resource}}</th>
                                    <th>{{message.daily_rate}}</th>
                                    <th>{{message.qty}}</th>
                                    <th>{{message.days}}</th>
                                    <th>{{message.total}}</th>
                                    <th>{{message.actions}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in task.costs"  v-if="item.type==0">
                                    <td>{{item.resourceName}}</td>
                                    <td>{{item.rate}}</td>
                                    <td>{{item.quantity}}</td>
                                    <td>{{item.duration}}</td>
                                    <td><b><i class="fa fa-dollar"></i> {{costTotal(item)}}</b></td>
                                    <td>
                                        <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                        <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                    </td>
                                </tr>
                               
                                <tr>
                                    <td colspan="4" class="text-right"><b>{{message.internal_costs_total}}</b></td>
                                    <td colspan="2"><b><i class="fa fa-dollar"></i>  {{totalCostsForType(0)}}</b></td>
                                </tr>
                                <tr class="column-warning">
                                    <td colspan="4" class="text-right"><b>{{message.forecast_total}}</b></td>
                                    <td><b><i class="fa fa-dollar"></i> {{task.internalForecastCost}}</b></td>
                                    <td>
                                        <button data-target="#internal-costs-forecast-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right"><b>{{message.actual_total}}</b></td>
                                    <td><b><i class="fa fa-dollar"></i> {{task.internalActualCost}}</b></td>
                                    <td>
                                        <button data-target="#internal-costs-actual-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </vue-scrollbar>
                    <div class="flex flex-space-between flex-v-center margintop20">
                        <div></div>
                        <button data-target="#edit-schedule-module" data-toggle="modal" class="btn-rounded btn-md btn-empty btn-auto" type="button">Add Internal Cost +</button>
                    </div>
                    <!-- /// Task Internal Costs /// -->

                    <hr class="double">

                    <!-- /// Task Internal Costs /// -->
                    <h3>{{message.external_costs}}</h3>
                    <vue-scrollbar class="table-wrapper">
                        <table class="table table-small">
                            <thead>
                                <tr>
                                    <th>{{message.description}}</th>
                                    <th>{{message.qty}}</th>
                                    <th>{{message.unit}}</th>
                                    <th>{{message.external_cost_unit_rate}}</th>
                                    <th>{{message.capex}}</th>
                                    <th>{{message.total}}</th>
                                    <th>{{message.actions}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                 <tr v-for="cost in task.costs"  v-if="cost.type==1">
                                    <td>{{cost.name}}</td>
                                    <td>{{cost.quantity}}</td>
                                    <td>{{cost.unit.name}}</td>
                                    <td><i class="fa fa-dollar"></i> {{cost.rate}}</td>
                                    <td><switches v-model="cost.expenseType" v-bind:selected="cost.expenseType"></switches></td>
                                    <td><b><b><i class="fa fa-dollar"></i> {{costTotal(cost)}}</b></b></td>
                                    <td>
                                        <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill" ></edit-icon></button>
                                        <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-right">{{message.capex_subtotal}}</b></td>
                                    <td colspan="2"><i class="fa fa-dollar"></i> {{totalCapex()}}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-right">{{message.opex_subtotal}}</b></td>
                                    <td colspan="2"><i class="fa fa-dollar"></i> {{totalOpex()}}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-right"><b>{{message.external_costs_total}}</b></td>
                                    <td colspan="2"><b> <i class="fa fa-dollar"></i> {{totalCostsForType(1)}}</b></td>
                                </tr>
                                <tr class="column-warning">
                                    <td colspan="5" class="text-right"><b>{{message.forecast_total}}</b></td>
                                    <td><b><i class="fa fa-dollar"></i> {{task.externalForecastCost}}</b></td>
                                    <td>
                                        <button data-target="#internal-costs-forecast-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    </td>
                                </tr>
                                <tr class="column-alert">
                                    <td colspan="5" class="text-right"><b>{{message.actual_total}}</b></td>
                                    <td><b><i class="fa fa-dollar"></i> {{task.externalActualCost}}</b></td>
                                    <td>
                                        <button data-target="#internal-costs-actual-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </vue-scrollbar>
                    <div class="flex flex-space-between flex-v-center margintop20">
                        <div></div>
                        <button data-target="#edit-schedule-module" data-toggle="modal" class="btn-rounded btn-md btn-empty btn-auto">Add External Cost +</button>
                    </div>
                    <!-- /// Task Internal Costs /// -->

                    <hr class="double">

                    <!-- /// Task Assignee /// -->
                    <h3>{{message.asignee}}</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="user-avatar flex flex-v-center">
                                <div><img :src="task.responsibilityAvatar" :alt="task.responsibilityFullName"/></div>
                                <div>
                                    <b>{{task.responsibilityFullName}}</b><br/>
                                    <a href="#path-to-member-page" class="simple-link">{{task.responsibilityEmail}}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select-field v-bind:title="'Change Assignee'"></select-field>
                        </div>
                    </div>
                    <!-- /// End Task Assignee /// -->

                    <hr class="double">

                    <!-- /// Task Completion /// -->
                    <h3>{{message.status}}</h3>
                    <div class="row">
                        <div class="col-md-12">
                            <range-slider
                            v-bind:title="message.task_completion"
                            min="0"
                            max="100"
                            minSuffix=" %"
                            type="single"
                            v-bind:value="transformToString(task.progress)" />
                        </div>
                         <div class="col-md-8">
                            <h4>{{task.colorStatusName}}</h4>
                        </div>
                        <div class="col-md-4">
                            <select-field v-bind:title="'Change Status'"></select-field>
                        </div>
                    </div>
                    <!-- /// End Task Completion /// -->

                    <hr class="double">

                    <!-- /// Task Condition /// -->
                    <h3>{{message.task_condition}}</h3>
                    <p v-for="status in colorStatuses">
                        <b class="caps" v-bind:style="{ color: status.color }" >{{ status.name }}:</b>{{status.description}}.
                    </p>
                    <div class="flex flex-space-between flex-v-center margintop20">
                        <div class="status-boxes flex flex-v-center">
                            <div v-for="status in colorStatuses" class="status-box" v-bind:style="{ background: status.color }" ></div>
                        </div>
                        <button data-target="#ajax-save-status" class="btn-rounded btn-md btn-empty btn-auto btn-disabled" type="button">Save Status</button>
                    </div>
                    <!-- /// End Task Condition /// -->

                    <hr class="double">

                    <!-- /// Task Attachmets /// -->
                    <h3>{{message.attachments}}</h3>
                    <div class="flex flex-space-between margintop20">
                        <div>
                            <div v-for="media in task.medias">
                                <a :href="media.fileName" class="simple-link">{{media.path}}</a>
                            </div>
                            <button class="btn-rounded btn-md btn-empty btn-auto flex" type="button">
                                <span>Attach a file</span>
                                <attach-icon></attach-icon>
                            </button>
                        </div>
                    </div>
                    <!-- /// End Task Attachments /// -->
                    <hr class="double">

                    <!-- /// Task Labels /// -->
                    <h3>{{message.labels}}</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="task-label-holder flex flex-v-center">
                                <div v-for="label in task.labels">
                                    <div class="task-label"  :style="'background-color:' + label.color">
                                        {{label.title}}
                                    </div>
                                    <button class="btn-icon btn-remove" type="button">
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
                            <select-field v-bind:title="'Add Label'"></select-field>
                        </div>
                    </div>
                    <!-- /// End Labels /// -->

                    <hr class="double">

                    <!-- /// Participants /// -->
                    <h3>Participants</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="flex flex-v-center">
                                <div class="user-avatar" v-tooltip.bottom-center="task.responsibilityFullName">
                                    <img :src="task.responsibilityAvatar" :alt="task.responsibilityFullName"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select-field v-bind:title="'Invite Members'"></select-field>
                        </div>
                    </div>
                    <!-- /// End Participants /// -->

                    <hr class="double">

                    <div class="footer-buttons">
                        <a href="javascript:void(0)" class="btn-rounded btn-auto second-bg">Save Changes</a>
                        <a href="javascript:void(0)" class="btn-rounded btn-auto btn-empty">Export Task</a>
                    </div>
                </div>
                <!-- /// End Task Sidebar /// -->
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import EditIcon from '../../_common/_icons/EditIcon';
import DeleteIcon from '../../_common/_icons/DeleteIcon';
import AttachIcon from '../../_common/_icons/AttachIcon';
import Switches from '../../3rdparty/vue-switches';
import VueScrollbar from 'vue2-scrollbar';
import SelectField from '../../_common/_form-components/SelectField';
import RangeSlider from '../../_common/_form-components/RangeSlider';
import Modal from '../../_common/Modal';
import router from '../../../router';
import moment from 'moment';

export default {
    components: {
        EditIcon,
        DeleteIcon,
        AttachIcon,
        Switches,
        VueScrollbar,
        SelectField,
        RangeSlider,
        Modal,
        router,
        moment,
    },
    created() {
        if (this.$route.params.taskId) {
            this.getTaskById(this.$route.params.taskId);
            this.getTaskHistory(this.$route.params.taskId);
        }
        this.getColorStatuses();
    },
    computed: {
        ...mapGetters({
            task: 'currentTask',
            taskHistory: 'taskHistory',
            colorStatuses: 'colorStatuses',
        }),
        isClosed: function() {
            return this.task.workPackageStatus === 5;
        },
        isStarted: function() {
            this.task.workPackageStatus === 3;
        },
    },
    methods: {
        ...mapActions(['getTaskById', 'getTaskHistory', 'deleteTaskSubtask', 'countCompletedSubtasks', 'addTaskComment', 'getColorStatuses']),
        countCompletedSubtasks: function() {
            let completed = 0;

            for (let task of this.task.children) {
                // TODO: use constants
                if (task.workPackageStatus === 4) {
                    completed++;
                }
            }

            return completed;
        },
        deleteSubtask: function(subtaskId) {
            this.deleteTaskSubtask(subtaskId);
            router.push({name: 'project-task-management-list'});
            this.showDeleteModal = false;
        },
        getDuration: function(startDate, endDate) {
            let end = moment(endDate);
            let start = moment(startDate);

            return !isNaN(end.diff(start, 'days')) ? end.diff(start, 'days') : '-';
        },
        getHumanTimeDiff: function(date) {
            return moment(date).from(new Date(), false);
        },
        getSubtaskSummary: function() {
            return Translator.trans(
                'message.subtasks_summary',
                {
                    'completed_tasks': this.countCompletedSubtasks(),
                    'total_tasks': this.task.children.length,
                }
            );
        },
        createNewComment: function() {
            let data = {
                task: this.task,
                payload: {
                    body: this.$refs['newCommentBody'].getContent(),
                    author: this.task.responsibility,
                },
            };
            this.addTaskComment(data);
        },
        costTotal: function(item) {
            if (item.type === 0 ) {
                return item.rate * item.quantity * item.duration;
            }
            return item.rate * item.quantity;
        },
        totalCostsForType: function(costType) {
            let totalCostForType = 0;

            for (let cost of this.task.costs) {
                if (cost.type === 1) {
                    totalCostForType += this.costTotal(cost);
                }
            }
            return totalCostForType;
        },
        totalOpex: function() {
            let totalOpexCost = 0;

            for (let cost of this.task.costs) {
                if (cost.type === 1 && cost.expenseType === 1) {
                    totalOpexCost += this.costTotal(cost);
                }
            }
            return totalOpexCost;
        },
        totalCapex: function() {
            let totalCapexCost = 0;

            for (let cost of this.task.costs) {
                if (cost.type === 1 && cost.expenseType === 0) {
                    totalCapexCost += this.costTotal(cost);
                }
            }
            return totalCapexCost;
        },
        transformToString: function(value) {
            return value ? value.toString() : '';
        },
    },
    data: function() {
        return {
            message: {
                edit_task: Translator.trans('message.edit_task'),
                close_task: Translator.trans('message.close_task'),
                delete_task: Translator.trans('message.delete_task'),
                no: Translator.trans('message.no'),
                yes: Translator.trans('message.yes'),
                start_task: Translator.trans('button.start_task'),
                new_task: Translator.trans('message.new_task'),
                planning: Translator.trans('message.planning'),
                edit: Translator.trans('message.edit'),
                back_to_task_management: Translator.trans('message.back_to_task_management'),
                status: Translator.trans('message.status'),
                subtasks: Translator.trans('message.subtasks'),
                completed: Translator.trans('label.completed'),
                assigned_to: Translator.trans('message.assigned_to'),
                has_edited_task: Translator.trans('message.has_edited_task'),
                has_commented_task: Translator.trans('message.has_commented_task'),
                new_comment: Translator.trans('message.new_comment'),
                comment: Translator.trans('button.comment'),
                created_on: Translator.trans('message.created_on'),
                by: Translator.trans('message.by'),
                this_task_part_of: Translator.trans('message.this_task_part_of'),
                edit_milestone: Translator.trans('label.edit_milestone'),
                edit_phase: Translator.trans('label.edit_phase'),
                task_schedule: Translator.trans('message.task_schedule'),
                start: Translator.trans('table_header_cell.start'),
                finish: Translator.trans('table_header_cell.finish'),
                duration: Translator.trans('table_header_cell.duration'),
                schedule: Translator.trans('table_header_cell.schedule'),
                resource: Translator.trans('table_header_cell.resource'),
                daily_rate: Translator.trans('label.daily_rate'),
                qty: Translator.trans('label.qty'),
                days: Translator.trans('label.days'),
                total: Translator.trans('message.total'),
                actions: Translator.trans('placeholder.actions'),
                base: Translator.trans('label.base'),
                forecast: Translator.trans('label.forecast'),
                actual: Translator.trans('label.actual'),
                task_precedesor: Translator.trans('subtitle.task_precedesor'),
                internal_costs: Translator.trans('message.internal_costs'),
                internal_costs_total: Translator.trans('label.internal_costs_total'),
                external_costs_total: Translator.trans('label.external_cost_total'),
                actual_total: Translator.trans('label.actual_total'),
                forecast_total: Translator.trans('label.forecast_total'),
                description: Translator.trans('placeholder.description'),
                unit: Translator.trans('table_header_cell.unit'),
                external_cost_unit_rate: Translator.trans('label.external_cost_unit_rate'),
                capex: Translator.trans('message.capex'),
                external_costs: Translator.trans('message.external_costs'),
                asignee: Translator.trans('message.asignee'),
                task_completion: Translator.trans('message.task_completion'),
                attachments: Translator.trans('message.attachments'),
                labels: Translator.trans('label.labels'),
                task_condition: Translator.trans('message.task_condition'),
                capex_subtotal: Translator.trans('message.capex_subtotal'),
                opex_subtotal: Translator.trans('message.opex_subtotal'),
            },
            button: {
                new_task: Translator.trans('button.new_task'),
            },
            showDeleteModal: false,
        };
    },
};
</script>

<style scoped lang="scss">
    @import '../../../css/page-section';
    @import '../../../css/_variables';
    @import '../../../css/_mixins';

    .btn-rounded {
        margin-left: 10px;
    }

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

        a {
            margin-left: 20px;
        }
    }

    .footer-buttons {
        margin: 20px 0 0;

        a {
            margin: 0 20px 0 0;
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
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 700;

        img {
            width: 30px;
            height: 30px;
            @include border-radius(50%);
            margin: 0 10px 0 0;
            display: inline-block;
            position: relative;
            top: -2px;
        }
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

        &:last-child {
            margin: 0;
        }
    }
</style>
