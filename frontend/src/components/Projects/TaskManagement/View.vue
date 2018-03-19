<template>
    <div class="project-task-management page-section">
        <!-- /// Delete Subtask Modal /// -->
        <modal v-if="showDeleteModal" @close="showDeleteModal = false">
            <p class="modal-title">{{ translateText('message.delete_task') }}</p>
            <div class="flex flex-space-between">
                <a @click.preventDefault="showDeleteModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                <a @click.preventDefault="deleteSubtask(showDeleteModal)" class="btn-rounded">{{ translateText('message.yes') }}</a>
            </div>
        </modal>
        <!-- /// End Delete Subtask Modal /// -->

        <!-- /// Edit Status Modal /// -->
        <modal v-if="showEditStatusModal" @close="showEditStatusModal = false">
            <p class="modal-title">{{ translateText('title.status.edit') }}</p>
            <select-field
                    v-bind:title="translateText('title.status.edit')"
                    v-bind:options="workPackageStatusesForSelect"
                    v-bind:currentOption="editableData.workPackageStatus"
                    v-model="editableData.workPackageStatus"/>
            <br />
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showEditStatusModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
                <a href="javascript:void(0)" @click="changeStatus()" class="btn-rounded">{{ translateText('title.status.edit') }} +</a>
            </div>
        </modal>
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
            <div class="col-md-6">

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
                            <div class="user-avatar">
                                <img :src="task.responsibilityAvatar" :alt="task.responsibilityFullName"/>
                                <b>{{ task.responsibilityFullName }}</b>
                            </div>
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

                <hr class="double">

                <!-- ///Subtasks /// -->
                <h3>{{ translateText('message.subtasks') }} | {{ getSubtaskSummary() }} {{ translateText('label.completed') }}</h3>
                <div class="subtasks">
                    <div v-for="subtask in task.children" :key="subtask.id" class="subtask flex flex-space-between">
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

                <hr class="double">

                <!-- /// Task History /// -->
                <div class="task-history">
                    <div v-for="(item, index) in taskHistory" :key="index">

                        <!-- /// Task assignement /// -->
                        <div v-if="item.isResponsibilityAdded">
                            <div class="comment">
                                <div class="comment-header">
                                    <div class="user-avatar">
                                        <img :src="item.userGravatar" :alt="item.userFullName"/>
                                        <b>{{item.userFullName}}</b>
                                    </div>
                                    <router-link
                                        :to="{name: 'project-organization-view-member', params: {userId: item.userId} }"
                                        class="simple-link">
                                        @{{ item.userUsername }}
                                    </router-link>
                                    {{ translateText('message.assigned_to') }}
                                     <router-link
                                        :to="{name: 'project-organization-view-member', params: {userId: item.newValue.responsibility[1]} }"
                                        class="simple-link">
                                        @{{getResponsibityUsername(item.newValue.responsibility[1])}}
                                    </router-link>
                                    {{ getHumanTimeDiff(item.createdAt) }}
                                </div>
                            </div>
                            <hr class="double">
                        </div>
                        <!-- /// End Task Assignement /// -->

                        <!-- /// Task Comment /// -->
                        <div v-else-if="item.isCommentAdded">
                            <div class="comment">
                                <div class="comment-header">
                                    <div class="user-avatar">
                                        <img :src="item.userGravatar" :alt="item.userFullName"/>
                                        <b>{{item.userFullName}}</b>
                                    </div>
                                    <router-link
                                        :to="{name: 'project-organization-view-member', params: {userId: item.userId} }"
                                        class="simple-link">
                                        @{{ item.userUsername }}
                                    </router-link>
                                    {{ translateText('message.has_commented_task') }} {{ getHumanTimeDiff(item.createdAt) }}
                                </div>
                                <div class="comment-body" v-html="item.newValue.comment">
                                </div>
                            </div>
                            <hr class="double">
                        </div>
                        <!-- /// End Task Comment /// -->

                        <!-- /// Task Label added /// -->
                        <div v-else-if="item.isLabelAdded">
                            <div class="comment">
                                <div class="comment-header">
                                    <div class="user-avatar">
                                        <img :src="item.userGravatar" :alt="item.userFullName"/>
                                        <b>{{item.userFullName}}</b>
                                    </div>
                                    <router-link
                                        :to="{name: 'project-organization-view-member', params: {userId: item.userId} }"
                                        class="simple-link">
                                        @{{ item.userUsername }}
                                    </router-link>
                                    <div class="task-label" :style="'background-color:#e04fcc'">
                                        High Priority
                                    </div>
                                    {{ getHumanTimeDiff(item.createdAt) }}
                                </div>
                            </div>
                            <hr class="double">
                        </div>
                        <!-- /// End Task Label Added /// -->

                        <!-- /// Task Edited /// -->
                        <div v-else-if="item.isFieldEdited" >
                            <div class="comment">
                                <div class="comment-header">
                                    <div class="user-avatar">
                                        <img :src="item.userGravatar" :alt="item.userFullName"/>
                                        <b>{{item.userFullName}}</b>
                                    </div>
                                    <router-link
                                        :to="{name: 'project-organization-view-member', params: {userId: item.userId} }"
                                        class="simple-link">
                                        @{{ item.userUsername }}
                                    </router-link>
                                    {{ translateText('message.has_edited_task') }} {{ getHumanTimeDiff(item.createdAt) }}
                                </div>
                            </div>
                            <hr class="double">
                        </div>
                        <!-- /// End Task Edited /// -->
                    </div>
                </div>
                <!-- /// End Task History /// -->

                <!-- /// New Task Description /// -->
                <div class="new-comment">
                    <div class="user-avatar">
                        <img :src="task.responsibilityAvatar" :alt="responsibilityFullName"/>
                    </div>
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

            <div class="col-md-6">
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
                        <div>
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
                    <vue-perfect-scrollbar class="table-wrapper">
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
                                    <td v-if="task.scheduledStartAt">{{ task.scheduledStartAt|moment('DD.MM.YYYY') }}</td>
                                    <td v-else>-</td>

                                    <td v-if="task.scheduledFinishAt">{{ task.scheduledFinishAt|moment('DD.MM.YYYY') }}</td>
                                    <td v-else>-</td>

                                    <td>
                                        {{ getDuration(task.scheduledStartAt, task.scheduledFinishAt) }}
                                    </td>
                                </tr>
                                <tr class="column-warning">
                                    <td>{{ translateText('label.forecast') }}</td>
                                    <td v-if="task.forecastStartAt">{{ task.forecastStartAt|moment('DD.MM.YYYY') }}</td>
                                    <td v-else>-</td>

                                    <td v-if="task.forecastFinishAt">{{ task.forecastFinishAt|moment('DD.MM.YYYY') }}</td>
                                    <td v-else>-</td>

                                    <td>
                                        {{ getDuration(task.forecastStartAt, task.forecastFinishAt) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ translateText('label.actual') }}</td>
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
                    </vue-perfect-scrollbar>
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
                    <vue-perfect-scrollbar class="table-wrapper">
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
                                    <td>{{ item.rate | formatMoney }}</td>
                                    <td>{{item.quantity}}</td>
                                    <td>{{item.duration}}</td>
                                    <td><b>{{ item.total | formatMoney }}</b></td>
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
                    </vue-perfect-scrollbar>
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
                    <vue-perfect-scrollbar class="table-wrapper">
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
                                    <td>{{cost.name}} </td>
                                    <td>{{cost.quantity}}</td>
                                    <td>{{cost.unit}}</td>
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
                    </vue-perfect-scrollbar>
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

                    <!-- /// Task Assignee /// -->
                    <h3>{{ translateText('message.asignee')}}</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="user-avatar flex flex-v-center" v-if="responsibilityObj">
                                <div><img :src="responsibilityObj.avatar" :alt="responsibilityObj.label"/></div>
                                <div>
                                    <b> {{responsibilityObj.label}}</b><br/>
                                    <router-link
                                        :to="{name: 'project-organization-view-member', params: {userId: responsibilityObj.key} }"
                                        class="simple-link">
                                        {{ responsibilityObj.email }}
                                    </router-link>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select-field
                                v-bind:title="translateText('message.change_assignee')"
                                v-bind:options="projectUsersForSelect"
                                v-model="editableData.assignee"
                                v-bind:currentOption="responsibilityObj"
                                v-on:input="updateAssignee" />
                        </div>
                    </div>
                    <!-- /// End Task Assignee /// -->
                    <hr class="double">
                    <!-- /// Task Accountable /// -->
                    <h3>{{ translateText('label.accountable')}}</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="user-avatar flex flex-v-center" v-if="accountabilityObj">
                                <div><img :src="accountabilityObj.avatar" :alt="accountabilityObj.label"/></div>
                                <div>
                                    <b> {{accountabilityObj.label}}</b><br/>
                                    <router-link
                                        :to="{name: 'project-organization-view-member', params: {userId: accountabilityObj.key} }"
                                        class="simple-link">
                                        {{ accountabilityObj.email }}
                                    </router-link>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select-field
                                v-bind:title="translateText('message.change_accountable')"
                                v-bind:options="projectUsersForSelect"
                                v-model="editableData.accountable"
                                v-bind:currentOption="accountabilityObj"
                                v-on:input="updateAccountable" />
                        </div>
                    </div>
                    <!-- /// End Task Accountable /// -->
                    <hr class="double">
                    <!-- /// Task support & informed & consulted users  /// -->
                    <h3>{{ translateText('label.select_sci_users')}}</h3>
                    <div class="row">
                        <div class="col-md-4">
                            <multi-select-field
                                v-bind:title="translateText('label.select_support_users')"
                                v-bind:options="projectUsersForSupportSelect"
                                v-bind:selectedOptions="editableData.supportUsers"
                                v-model="editableData.supportUsers"
                                v-on:input="updateSupportUsers" />
                        </div>
                        <div class="col-md-4">
                            <multi-select-field
                                v-bind:title="translateText('label.select_consulted_users')"
                                v-bind:options="projectUsersForConsultedSelect"
                                v-bind:selectedOptions="editableData.consultedUsers"
                                v-model="editableData.consultedUsers"
                                v-on:input="updateConsultedUsers" />

                        </div>
                        <div class="col-md-4">
                            <multi-select-field
                                v-bind:title="translateText('label.select_informed_users')"
                                v-bind:options="projectUsersForInformedSelect"
                                v-bind:selectedOptions="editableData.informedUsers"
                                v-model="editableData.informedUsers"
                                v-on:input="updateInformedUsers" />

                        </div>
                    </div>
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
                                    @input="updateTaskStatusProgress"
                                    :value="task.progress"/>
                        </div>
                         <div class="col-md-8" v-if="editableData.workPackageStatus">
                            <h4>{{editableData.workPackageStatus.label}}</h4>
                        </div>
                        <div class="col-md-4">
                            <select-field
                                v-bind:title="translateText('message.change_status')"
                                v-bind:options="workPackageStatusesForSelect"
                                v-model="editableData.workPackageStatus"
                                v-bind:currentOption="editableData.workPackageStatus"
                                v-on:input="changeStatus"
                                ref="projectStatus" />
                        </div>
                    </div>
                    <!-- /// End Task Completion /// -->

                    <hr class="double">

                    <!-- /// Task Condition /// -->
                    <condition v-model="editableData.colorStatus" v-bind:selectedStatusColor="editableData.colorStatus" v-on:input="updateColorStatus"/>

                    <!-- /// End Task Condition /// -->

                    <hr class="double">

                    <!-- /// Task Attachmets /// -->

                    <attachments v-on:input="updateMedias" v-bind:editMedias="editableData.medias" v-model="editableData.medias" />
                    <!-- /// End Task Attachments /// -->
                    <hr class="double">

                    <!-- /// Task Labels /// -->
                    <h3>{{ translateText('label.labels') }}</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="task-label-holder flex flex-v-center">
                                <div  v-if="editableData.label">
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
                                v-bind:title="'Add Label'"
                                v-bind:options="labelsForSelect"
                                v-model="editableData.label"
                                v-bind:currentOption="editableData.label"
                                v-on:input="updateLabel" />
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
import moment from 'moment';
import {createFormData} from '../../../helpers/task';
import Vue from 'vue';
import SwitchField from '../../_common/_form-components/SwitchField';
import VuePerfectScrollbar from 'vue-perfect-scrollbar';

const TASK_STATUS_CLOSED = 5;
const TASK_STATUS_COMPLETED = 4;

export default {
    components: {
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
        VuePerfectScrollbar,
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
            taskHistory: 'taskHistory',
            colorStatuses: 'colorStatuses',
            colorStatusesForSelect: 'colorStatusesForSelect',
            projectUsersForSelect: 'projectUsersForSelectOnViewTask',
            projectUsersForMultipleSelect: 'projectUsersForSelect',
            workPackageStatusesForSelect: 'workPackageStatusesForSelect',
            labelsForSelect: 'labelsForChoice',
            projectUsers: 'projectUsers',
            currentUser: 'user',
        }),
        isClosed() {
            return this.task.workPackageStatus === TASK_STATUS_CLOSED;
        },
        responsibilityObj() {
            for (let user of this.projectUsersForSelect) {
                if(user.key == this.task.responsibility) {
                    return user;
                }
            }
        },
        accountabilityObj() {
            for (let user of this.projectUsersForSelect) {
                if(user.key == this.task.accountability) {
                    return user;
                }
            }
        },
        projectUsersForSupportSelect() {
            let usersForSelect = JSON.parse(JSON.stringify(this.projectUsersForMultipleSelect));

            let selectedIds = [];
            for( let i =0; i< this.editableData.supportUsers.length; i++) {
                selectedIds.push(this.editableData.supportUsers[i].key);
            }
            return usersForSelect.filter((item) => {
                return selectedIds.indexOf(item.key) === -1;
            });
        },
        projectUsersForConsultedSelect() {
            let usersForSelect = JSON.parse(JSON.stringify(this.projectUsersForMultipleSelect));

            let selectedIds = [];
            for( let i =0; i< this.editableData.consultedUsers.length; i++) {
                selectedIds.push(this.editableData.consultedUsers[i].key);
            }

            return usersForSelect.filter((item) => {
                return selectedIds.indexOf(item.key) === -1;
            });
        },
        projectUsersForInformedSelect() {
            let usersForSelect = JSON.parse(JSON.stringify(this.projectUsersForMultipleSelect));

            let selectedIds = [];
            for( let i =0; i< this.editableData.informedUsers.length; i++) {
                selectedIds.push(this.editableData.informedUsers[i].key);
            }

            return usersForSelect.filter((item) => {
                return selectedIds.indexOf(item.key) === -1;
            });
        },
        taskProgressEditIsDisabled() {
            return this.task.parent == null && this.task.noSubtasks > 0;
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
                if (cost.internal) {
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

            let supportUsers = [];
            this.task.supportUsers.map(user => {
                supportUsers.push({
                    key: user.id,
                    label: user.firstName + ' ' + user.lastName,
                });
            });
            this.editableData.supportUsers = supportUsers;

            let informedUsers = [];
            this.task.informedUsers.map(user => {
                informedUsers.push({
                    key: user.id,
                    label: user.firstName + ' ' + user.lastName,
                });
            });
            this.editableData.informedUsers = informedUsers;

            let consultedUsers = [];
            this.task.consultedUsers.map(user => {
                consultedUsers.push({
                    key: user.id,
                    label: user.firstName + ' ' + user.lastName,
                });
            });
            this.editableData.consultedUsers = consultedUsers;

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
            return Number(task.workPackageStatus) === TASK_STATUS_COMPLETED;
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
        getHumanTimeDiff(date) {
            return moment(date).from(new Date(), false);
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
        updateMedias() {
            let data = {
                project: this.$route.params.id,
                name: this.task.name,
                type: 2,
                description: this.task.content,
                schedule: this.editableData.schedule,
                details: {
                    assignee: this.task.responsibility
                        ? {key: this.task.responsibility, label: this.task.responsibilityFullName}
                        : null,
                    accountable: this.task.accountability
                        ? {key: this.task.accountability, label: this.task.accountabilityFullName}
                        : null,
                    supportUsers: this.editableData.supportUsers,
                    consultedUsers: this.editableData.consultedUsers,
                    informedUsers: this.editableData.informedUsers,
                    status: this.task.workPackageStatus
                        ? {key: this.task.workPackageStatus, label: ''}
                        : null,
                    label: this.task.label
                        ? {key: this.task.label, label: this.task.labelName}
                        : null,
                },
                planning: {
                    milestone: this.task.milestone
                        ? {key: this.task.milestone.toString(), label: this.task.milestoneName}
                        : null,
                    phase: this.task.phase
                        ? {key: this.task.phase.toString(), label: this.task.phaseName}
                        : null,
                    parent: this.task.parent
                        ? {key: this.task.parent.toString(), label: this.task.parentName}
                        : null,
                },
                internalCosts: {items: [], actual: 0, forecast: 0},
                externalCosts: {items: [], actual: 0, forecast: 0},
                subtasks: [],
                medias: this.editableData.medias,
                statusColor: {id: this.task.colorStatus},
            };

            this
                .editTask({
                    data: createFormData(data),
                    taskId: this.$route.params.taskId,
                })
                .then(
                    (response) => {
                        if (response.body && response.body.error && response.body.messages) {
                            this.fileUploadErrorMessage = response.body.messages.medias;
                            this.showFileUploadFailed = true;
                            this.editableData.medias.pop();
                        } else if (response.status == 0) {
                            this.fileUploadErrorMessage = this.translateText('message.uploading_file_failed');
                            this.showFileUploadFailed = true;
                            this.editableData.medias.pop();
                        }
                    },
                    () => {
                        this.fileUploadErrorMessage = this.translateText('message.uploading_file_failed');
                        this.showFileUploadFailed = true;
                        this.editableData.medias.pop();
                    }
                )
            ;
        },
        totalCostsForType(costType) {
            let totalCostForType = 0;
            // to be removed and replace with a computed prop
            for (let cost of this.task.costs) {
                if (cost.type === 1) {
                    totalCostForType += this.itemTotal(cost);
                }
            }
            return totalCostForType;
        },
        transformToString(value) {
            return value ? value.toString() : '';
        },
        translateText(text) {
            return this.translate(text);
        },
        updateTaskStatusProgress(progress) {
            let params = {
                taskId: this.task.id,
                data: {
                    progress,
                },
            };

            if (progress === 100) {
                // set workPackageStatus to 'Completed'
                this.editableData.workPackageStatus = this.$refs.projectStatus.options.filter(item => item.key === 4)[0];
                params.data.workPackageStatus = this.editableData.workPackageStatus.key;
            } else if (progress > 0) {
                // set workPackageStatus to 'Ongoing'
                this.editableData.workPackageStatus = this.$refs.projectStatus.options.filter(item => item.key === 3)[0];
                params.data.workPackageStatus = this.editableData.workPackageStatus.key;
            }

            if (this.editableData.workPackageStatus.key === 4) {
                // status 'Completed'
                params.data.actualFinishAt = moment().format('D-MM-YYYY');
            } else if (this.editableData.workPackageStatus.key === 3) {
                // status 'Ongoing'
                params.data.actualStartAt = moment().format('D-MM-YYYY');
                params.data.actualFinishAt = '';
            }

            this.patchTask(params);
        },
        initChangeStatusModal() {
            this.showEditStatusModal = true;
        },
        changeStatus() {
            let data = {
                taskId: this.task.id,
                data: {
                    workPackageStatus: this.editableData.workPackageStatus.key,
                },
            };

            this.patchTask(data);

            this.showEditStatusModal = false;
        },
        onChangeScheduleModal() {
            this.showEditScheduleModal = true;
        },
        onSaveSchedule(schedule) {
            let data = {
                scheduledStartAt: moment(schedule.baseStartDate).format('DD-MM-YYYY'),
                scheduledFinishAt: moment(schedule.baseEndDate).format('DD-MM-YYYY'),
                forecastStartAt: moment(schedule.forecastStartDate).format('DD-MM-YYYY'),
                forecastFinishAt: moment(schedule.forecastEndDate).format('DD-MM-YYYY'),
                automaticSchedule: schedule.automatic,
                duration: schedule.duration,
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
        updateAssignee() {
            let data = {
                responsibility: this.editableData.assignee.key,
            };
            this.patchTask({
                data: data,
                taskId: this.$route.params.taskId,
            });
        },
        updateAccountable() {
            let data = {
                accountability: this.editableData.accountable.key,
            };
            this.patchTask({
                data: data,
                taskId: this.$route.params.taskId,
            });
        },
        updateSupportUsers() {
            let data = {
                supportUsers: this.editableData.supportUsers.map(item => {
                    return item.key;
                }),
            };
            this.patchTask({
                data: data,
                taskId: this.$route.params.taskId,
            });
        },
        updateConsultedUsers() {
            let data = {
                consultedUsers: this.editableData.consultedUsers.map(item => {
                    return item.key;
                }),
            };
            this.patchTask({
                data: data,
                taskId: this.$route.params.taskId,
            });
        },
        updateInformedUsers() {
            let data = {
                informedUsers: this.editableData.informedUsers.map(item => {
                    return item.key;
                }),
            };
            this.patchTask({
                data: data,
                taskId: this.$route.params.taskId,
            });
        },
        initCloseTaskModal() {
            this.showCloseTaskModal = true;
        },
        initOpenTaskModal() {
            this.showOpenTaskModal = true;
        },
        getResponsibityUsername(userId) {
            let users = this.projectUsers.items;
            if(users != undefined) {
                for (let i = 0; i < users.length; i++) {
                    if (users[i].user == userId) {
                        return users[i].userFullName;
                    };
                }
            }
            return '-';
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
                workPackageStatus: false,
                assignee: null,
                accountable: null,
                supportUsers: [],
                consultedUsers: [],
                informedUsers: [],
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
