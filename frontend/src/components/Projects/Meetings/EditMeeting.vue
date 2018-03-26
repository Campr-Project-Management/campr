<template>
    <div class="row">
        <!-- /// Modals /// -->
        <meeting-modals ref="meetingmodal"
            v-bind:editObjectiveModal="showEditObjectiveModal"
            v-bind:deleteObjectiveModal="showDeleteObjectiveModal"
            v-bind:objectiveObject="editObjectiveObject"
            v-bind:editAgendaModal="showEditAgendaModal"
            v-bind:deleteAgendaModal="showDeleteAgendaModal"
            v-bind:agendaObject="editAgendaObject"
            v-bind:editDecisionModal="showEditDecisionModal"
            v-bind:deleteDecisionModal="showDeleteDecisionModal"
            v-bind:decisionObject="editDecisionObject"
            v-bind:editTodoModal="showEditTodoModal"
            v-bind:deleteTodoModal="showDeleteTodoModal"
            v-bind:todoObject="editTodoObject"
            v-bind:editInfoModal="showEditInfoModal"
            v-bind:deleteInfoModal="showDeleteInfoModal"
            v-bind:infoObject="editInfoObject"
            v-on:input="setModals" />
        <!-- /// End Modals /// -->

        <div class="col-md-6">
            <div class="create-meeting page-section">
                <!-- /// Header /// -->
                <div class="header flex-v-center">
                    <div>
                        <router-link :to="{name: 'project-meetings'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ translateText('message.back_to_meetings') }}
                        </router-link>
                        <h1>{{ translateText('message.edit') }} <b>{{ meeting.name }}</b></h1>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <div class="form">
                    <!-- /// Meeting Distribution List (Event Name) and Category /// -->
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <multi-select-field
                                    v-bind:title="translateText('placeholder.distribution_list')"
                                    v-bind:options="distributionListsForSelect"
                                    v-bind:selectedOptions="details.distributionLists"
                                    v-model="details.distributionLists" />
                            </div>
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="translateText('label.category')"
                                    v-bind:options="meetingCategoriesForSelect"
                                    v-model="details.category"
                                    v-bind:currentOption="details.category" />
                            </div>
                        </div>
                    </div>
                    <!-- /// End Meeting Distribution List (Event Name) and Category /// -->

                    <hr class="double">

                    <!-- /// Meeting Schedule /// -->
                    <h3>{{ translateText('message.schedule') }}</h3>
                    <div class="row">
                        <div class="form-group form-group">
                            <div class="col-md-4">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.select_date') }}</label>
                                    <datepicker v-model="schedule.meetingDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill"/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.start_time') }}</label>
                                    <vue-timepicker v-model="schedule.startTime" hide-clear-button></vue-timepicker>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.finish_time') }}</label>
                                    <vue-timepicker v-model="schedule.endTime" hide-clear-button></vue-timepicker>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a @click="saveSchedule()" class="btn-rounded btn-auto">{{ translateText('message.save_schedule') }}</a>
                    </div>
                    <!-- /// End Meeting Schedule /// -->

                    <hr class="double">

                    <!-- /// Meeting Location /// -->
                    <h3>{{ translateText('message.location') }}</h3>
                    <input-field type="text" v-bind:label="translateText('placeholder.location')" v-model="location" v-bind:content="location" />
                    <!-- /// End Meeting Location /// -->

                    <hr class="double">

                    <!-- /// Meeting Objectives /// -->
                    <h3>{{ translateText('message.objectives') }}</h3>
                    <ul class="action-list" v-if="meeting.meetingObjectives">
                        <li v-for="objective in meeting.meetingObjectives">
                            <div class="list-item-description">
                                {{ objective.description }}
                            </div>
                            <div class="list-item-actions">
                                <a @click="initEditObjective(objective)" class="btn-icon" v-tooltip.top-center="translateText('message.edit_objective')"><edit-icon fill="second-fill"></edit-icon></a>
                                <a @click="initDeleteObjective(objective)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_objective')"><delete-icon fill="danger-fill"></delete-icon></a>
                            </div>
                        </li>
                    </ul>
                    <div class="form-group">
                        <input-field type="text" v-bind:label="translateText('message.new_objective')" v-model="objectiveDescription" :content="objectiveDescription" />
                        <error
                            v-if="validationOrigin==MEETING_OBJECTIVE_VALIDATION_ORIGIN && validationMessages.description && validationMessages.description.length"
                            v-for="message in validationMessages.description"
                            :message="message" />
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a @click="addObjective()" class="btn-rounded btn-auto">{{ translateText('message.add_new_objective') }}</a>
                    </div>
                    <!-- /// End Meeting Objectives /// -->

                    <hr class="double">

                    <!-- /// Meeting Agenda /// -->
                    <h3>{{ translateText('message.agenda') }}</h3>
                    <div class="overflow-hidden">
                        <scrollbar class="table-wrapper">
                            <div class="scroll-wrapper">
                                <table class="table table-striped table-responsive">
                                    <thead>
                                        <tr>
                                            <th>{{ translateText('table_header_cell.topic') }}</th>
                                            <th>{{ translateText('table_header_cell.responsible') }}</th>
                                            <th>{{ translateText('table_header_cell.start') }}</th>
                                            <th>{{ translateText('table_header_cell.finish') }}</th>
                                            <th>{{ translateText('table_header_cell.duration') }}</th>
                                            <th>{{ translateText('table_header_cell.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="meetingAgendas">
                                        <tr v-for="agenda in meetingAgendas.items">
                                            <td class="topic">{{ agenda.topic }}</td>
                                            <td>
                                                <div class="avatars collapse in" id="tp-meeting-20032017-1">
                                                    <div>
                                                        <div class="avatar" v-tooltip.top-center="agenda.responsibilityFullName" :style="{ backgroundImage: 'url('+agenda.responsibilityAvatar+')' }"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ agenda.start }}</td>
                                            <td>{{ agenda.end }}</td>
                                            <td>{{ getDuration(agenda.start, agenda.end) }} {{ translateText('message.min') }}</td>
                                            <td>
                                                <div class="text-right">
                                                    <a @click="initEditAgenda(agenda)"  class="btn-icon" v-tooltip.top-center="translateText('label.edit_topic')"><edit-icon fill="second-fill"></edit-icon></a>
                                                    <a @click="initDeleteAgenda(agenda)" class="btn-icon" v-tooltip.top-center="translateText('label.delete_topic')"><delete-icon fill="danger-fill"></delete-icon></a>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </scrollbar>
                    </div>
                    <div v-if="meetingAgendas && meetingAgendas.items" class="flex flex-direction-reverse flex-v-center">
                        <div class="pagination flex flex-center" v-if="meetingAgendas && meetingAgendas.totalItems > 0">
                            <span v-if="agendasPages > 1" v-for="page in agendasPages" v-bind:class="{'active': page == agendasActivePage}" @click="changeAgendasPage(page)">{{ page }}</span>
                        </div>
                        <div>
                            <span class="pagination-info">{{ translateText('message.displaying') }} {{ meetingAgendas.items.length }} {{ translateText('message.results_out_of') }} {{ meetingAgendas.totalItems }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <input-field type="text" v-bind:label="translateText('placeholder.topic')" v-model="agenda.topic" v-bind:content="agenda.topic" />
                        <error
                            v-if="validationMessages.topic && validationMessages.topic.length"
                            v-for="message in validationMessages.topic"
                            :message="message" />
                    </div>
                    <div class="row">
                        <div class="form-group form-group">
                            <div class="col-md-4">
                                <member-search v-model="agenda.responsible" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
                            </div>
                            <div class="col-md-4">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.start_time') }}</label>
                                    <vue-timepicker v-model="agenda.startTime" hide-clear-button></vue-timepicker>
                                    <error
                                        v-if="validationMessages.start && validationMessages.start.length"
                                        v-for="message in validationMessages.start"
                                        :message="message" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.finish_time') }}</label>
                                    <vue-timepicker v-model="agenda.endTime" hide-clear-button></vue-timepicker>
                                    <error
                                        v-if="validationMessages.end && validationMessages.end.length"
                                        v-for="message in validationMessages.end"
                                        :message="message" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a @click="addAgenda()" class="btn-rounded btn-auto">{{ translateText('message.add_new_topic') }}</a>
                    </div>
                    <!-- /// End Meeting Objectives /// -->

                    <hr class="double">

                    <!-- /// Meeting Documents /// -->
                    <meeting-attachments v-on:input="setMedias" v-bind:editMedias="medias" />
                    <!-- /// End Meeting Documents /// -->

                    <hr class="double">

                    <!-- /// Decisions /// -->
                    <h3>{{ translateText('message.decisions') }}</h3>

                    <div class="entries-wrapper" v-if="meeting.decisions">
                        <!-- /// Decision /// -->
                        <div class="entry" v-for="decision in meeting.decisions">
                            <div class="entry-header flex flex-space-between flex-v-center">
                                <div class="entry-title">
                                    <h4>{{ decision.title }}</h4>  | {{ translateText('message.due_date') }}: <b>{{ decision.dueDate | moment('DD.MM.YYYY') }}</b> | {{ translateText('message.status') }}: <b v-if="decision.status">{{ decision.statusName }}</b><b v-else>-</b>
                                </div>
                                <div class="entry-buttons">
                                    <button @click="initEditDecision(decision)" class="btn btn-rounded second-bg btn-auto btn-md" data-toggle="modal" type="button">edit</button>
                                    <button @click="initDeleteDecision(decision)" type="button" class="btn btn-rounded btn-auto btn-md danger-bg" >{{ translateText('message.delete') }}</button>
                                </div>
                            </div>
                            <div class="entry-responsible flex flex-v-center">
                                <div class="user-avatar">
                                    <img :src="decision.responsibilityAvatar" :alt="decision.responsibilityFullName"/>
                                </div>
                                <div>
                                    {{ translateText('message.responsible') }}:
                                    <b>{{ decision.responsibilityFullName }}</b>
                                </div>
                            </div>
                            <div class="entry-body" v-html="decision.description"></div>
                        </div>
                        <!-- /// End Decision /// -->
                    </div>

                    <input-field type="text" v-bind:label="translateText('placeholder.decision_title')" v-model="decision.title" :content="decision.title" />
                    <error
                        v-if="validationOrigin==DECISION_VALIDATION_ORIGIN && validationMessages.title && validationMessages.title.length"
                        v-for="message in validationMessages.title"
                        :message="message" />
                    <div class="form-group">
                        <editor
                            id="decision-description"
                            height="200px"
                            label="placeholder.decision_description"
                            v-model="decision.description" />
                    </div>
                    <error
                        v-if="validationOrigin==DECISION_VALIDATION_ORIGIN && validationMessages.description && validationMessages.description.length"
                        v-for="message in validationMessages.description"
                        :message="message" />
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <member-search v-model="decision.responsibility" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
                            </div>
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.due_date') }}</label>
                                    <datepicker v-model="decision.dueDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <div class="flex flex-direction-reverse">
                                    <a @click="addDecision()" class="btn-rounded btn-auto">{{ translateText('message.add_new_decision') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Decisions /// -->

                    <hr class="double">

                    <!-- /// ToDos /// -->
                    <h3>{{ translateText('message.todos') }}</h3>

                    <div class="entries-wrapper" v-if="meeting.todos">
                        <!-- /// ToDo /// -->
                        <div class="entry" v-for="todo in meeting.todos">
                            <div class="entry-header flex flex-space-between flex-v-center">
                                <div class="entry-title">
                                    <h4>{{ todo.title }}</h4>  | {{ translateText('message.due_date') }}: <b>{{ todo.dueDate | moment('DD.MM.YYYY') }}</b> | {{ translateText('message.status') }}: <b v-if="todo.status">{{ todo.statusName }}</b><b v-else>-</b>
                                </div>
                                <div class="entry-buttons">
                                    <button @click="initEditTodo(todo)"  class="btn btn-rounded second-bg btn-auto btn-md" data-toggle="modal" type="button">edit</button>
                                    <button @click="initDeleteTodo(todo)"  type="button" class="btn btn-rounded btn-auto btn-md danger-bg" >{{ translateText('message.delete') }}</button>
                                </div>
                            </div>
                            <div class="entry-responsible flex flex-v-center">
                                <div class="user-avatar">
                                    <img :src="todo.responsibilityAvatar" :alt="todo.responsibilityFullName"/>
                                </div>
                                <div>
                                    {{ translateText('message.responsible') }}:
                                    <b>{{ todo.responsibilityFullName }}</b>
                                </div>
                            </div>
                            <div class="entry-body" v-html="todo.description"></div>
                        </div>
                        <!-- /// End ToDo /// -->
                    </div>

                    <input-field type="text" v-bind:label="translateText('placeholder.topic')" v-model="todo.title" v-bind:content="todo.title" />
                    <error
                        v-if="validationOrigin==TODO_VALIDATION_ORIGIN && validationMessages.title && validationMessages.title.length"
                        v-for="message in validationMessages.title"
                        :message="message" />
                    <div class="form-group">
                        <editor
                            id="todo-description"
                            height="200px"
                            label="placeholder.todo_description"
                            v-model="todo.description" />
                    </div>
                    <error
                        v-if="validationOrigin==TODO_VALIDATION_ORIGIN && validationMessages.description && validationMessages.description.length"
                        v-for="message in validationMessages.description"
                        :message="message" />
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <member-search v-model="todo.responsibility" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
                            </div>
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.due_date') }}</label>
                                    <datepicker v-model="todo.dueDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="translateText('label.select_status')"
                                    v-bind:options="todoStatusesForSelect"
                                    v-model="todo.status"
                                    v-bind:currentOption="todo.status" />
                            </div>
                            <div class="col-md-6">
                                <div class="flex flex-direction-reverse">
                                    <a @click="addTodo()" class="btn-rounded btn-auto">{{ translateText('message.add_new_todo') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /// End ToDos /// -->

                    <hr class="double">

                    <!-- /// Infos /// -->
                    <h3>{{ translateText('message.infos') }}</h3>

                    <div class="entries-wrapper" v-if="meeting.infos">
                        <!-- /// Info /// -->
                        <div class="entry" v-for="info in meeting.infos">
                            <div class="entry-header flex flex-space-between flex-v-center">
                                <div class="entry-title">
                                    <h4>{{ info.topic }}</h4> |
                                    {{ translateText('message.due_date') }}: <b>{{ info.dueDate | moment('DD.MM.YYYY') }}</b> |
                                    {{ translateText('message.status') }}: <b v-if="info.infoStatus">{{ translateText(info.infoStatusName) }}</b><b v-else>-</b>
                                    {{ translateText('message.category') }}: <b v-if="info.infoCategory">{{ translateText(info.infoCategoryName) }}</b><b v-else>-</b>
                                </div>
                                <div class="entry-buttons">
                                    <button @click="initEditInfo(info)" class="btn btn-rounded second-bg btn-auto btn-md" data-toggle="modal" type="button">edit</button>
                                    <button @click="initDeleteInfo(info)" type="button" class="btn btn-rounded btn-auto btn-md danger-bg" >{{ translateText('message.delete') }}</button>
                                </div>
                            </div>
                            <div class="entry-responsible flex flex-v-center">
                                <div class="user-avatar">
                                    <img :src="info.responsibilityAvatar ? '/uploads/avatars/' + info.responsibilityAvatar : info.responsibilityGravatar" :alt="info.responsibilityFullName"/>
                                </div>
                                <div>
                                    {{ translateText('message.responsible') }}:
                                    <b>{{ info.responsibilityFullName }}</b>
                                </div>
                            </div>
                            <div class="entry-body" v-html="info.description"></div>
                        </div>
                        <!-- /// End Info /// -->
                    </div>

                    <input-field type="text"
                        v-bind:label="translateText('placeholder.topic')"
                        v-model="info.topic"
                        v-bind:content="info.topic" />
                    <error
                        v-if="validationOrigin==INFO_VALIDATION_ORIGIN && validationMessages.topic && validationMessages.topic.length"
                        v-for="message in validationMessages.topic"
                        :message="message" />
                    <div class="form-group">
                        <editor
                            id="info-description"
                            height="200px"
                            label="placeholder.info_description"
                            v-model="info.description" />
                    </div>
                    <error
                        v-if="validationOrigin==INFO_VALIDATION_ORIGIN && validationMessages.description && validationMessages.description.length"
                        v-for="message in validationMessages.description"
                        :message="message" />
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <member-search v-model="info.responsibility" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
                            </div>
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.due_date') }}</label>
                                    <datepicker v-model="info.dueDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="'label.select_status'"
                                    v-bind:options="infoStatusesForDropdown"
                                    v-model="info.infoStatus"
                                    v-bind:currentOption="info.infoStatus" />
                            </div>
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="'label.category'"
                                    v-bind:options="infoCategoriesForDropdown"
                                    v-model="info.infoCategory"
                                    v-bind:currentOption="info.infoCategory" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <div class="flex flex-direction-reverse">
                                    <a @click="addInfo()" class="btn-rounded btn-auto">{{ translateText('message.add_new_info') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /// End ToDos /// -->

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-meetings'}" class="btn-rounded btn-auto btn-auto disable-bg">{{ translateText('button.cancel') }}</router-link>
                        <a @click="saveMeeting()" class="btn-rounded btn-auto second-bg">{{ translateText('button.save_meeting') }}</a>
                    </div>
                    <!-- /// End Actions /// -->
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="create-meeting page-section">
                <!-- /// Header /// -->
                <div class="margintop20 text-right">
                    <a @click="saveMeeting()" class="btn-rounded btn-auto second-bg">{{ translateText('button.save_meeting') }}</a>
                </div>
                <!-- /// End Header /// -->

                <div class="flex flex-v-center flex-space-between">
                    <div>
                        <h3>{{ translateText('message.participants') }}</h3>
                    </div>
                    <!--<div class="buttons">
                        <router-link :to="{name: 'project-organization-edit'}" class="btn-rounded btn-auto btn-md btn-empty">{{ translateText('button.edit_distribution_list') }}</router-link>
                    </div>-->
                </div>

                <meeting-participants
                    v-bind:meetingParticipants="displayedParticipants"
                    v-bind:participants="participants"
                    v-bind:participantsPages="participantsPages"
                    v-bind:participantsPerPage="participantsPerPage" />
            </div>
        </div>

        <alert-modal v-if="showSaved" @close="showSaved = false" body="message.saved" />
        <alert-modal v-if="showFailed" @close="showFailed = false" body="message.unable_to_save" />
    </div>
</template>

<script>
import InputField from '../../_common/_form-components/InputField';
import SelectField from '../../_common/_form-components/SelectField';
import datepicker from '../../_common/_form-components/Datepicker';
import CalendarIcon from '../../_common/_icons/CalendarIcon';
import MemberSearch from '../../_common/MemberSearch';
import MeetingAttachments from './MeetingAttachments';
import EditIcon from '../../_common/_icons/EditIcon';
import DeleteIcon from '../../_common/_icons/DeleteIcon';
import {mapGetters, mapActions} from 'vuex';
import VueTimepicker from 'vue2-timepicker';
import moment from 'moment';
import {createFormData} from '../../../helpers/meeting';
import MultiSelectField from '../../_common/_form-components/MultiSelectField';
import MeetingModals from './MeetingModals';
import MeetingParticipants from './MeetingParticipants';
import Error from '../../_common/_messages/Error.vue';
import AlertModal from '../../_common/AlertModal.vue';
import router from '../../../router';
import Editor from '../../_common/Editor';

export default {
    components: {
        Editor,
        InputField,
        SelectField,
        datepicker,
        CalendarIcon,
        MemberSearch,
        MeetingAttachments,
        EditIcon,
        DeleteIcon,
        VueTimepicker,
        moment,
        MultiSelectField,
        MeetingModals,
        MeetingParticipants,
        Error,
        AlertModal,
    },
    methods: {
        ...mapActions([
            'getDistributionLists', 'getMeetingCategories', 'getInfoStatuses', 'getProjectMeeting', 'createMeetingObjective', 'getTodoStatuses',
            'createProjectMeeting', 'getMeetingAgendas', 'editProjectMeeting', 'editMeetingObjective', 'deleteMeetingObjective',
            'createMeetingAgenda', 'createMeetingDecision', 'createMeetingTodo', 'createInfo', 'getMeetingParticipants',
            'getInfoCategories',
        ]),
        translateText: function(text) {
            return this.translate(text);
        },
        getDuration: function(startDate, endDate) {
            let end = moment(endDate, 'HH:mm');
            let start = moment(startDate, 'HH:mm');

            return !isNaN(end.diff(start, 'minutes')) ? end.diff(start, 'minutes') : '-';
        },
        setMedias(value) {
            this.medias = value;
        },
        setModals(value) {
            this.showEditObjectiveModal = value;
            this.showDeleteObjectiveModal = value;
            this.showEditAgendaModal = value;
            this.showDeleteAgendaModal = value;
            this.showEditDecisionModal = value;
            this.showDeleteDecisionModal = value;
            this.showEditTodoModal = value;
            this.showDeleteTodoModal = value;
            this.showEditInfoModal = value;
            this.showDeleteInfoModal = value;
        },
        changeAgendasPage: function(page) {
            this.agendasActivePage = page;
            this.getMeetingAgendas({
                meetingId: this.$route.params.meetingId,
                apiParams: {
                    page: this.agendasActivePage,
                },
            });
        },
        saveSchedule: function() {
            this.editProjectMeeting({
                id: this.$route.params.meetingId,
                date: moment(this.schedule.meetingDate).format('DD-MM-YYYY'),
                start: this.schedule.startTime.HH + ':' + this.schedule.startTime.mm,
                end: this.schedule.endTime.HH + ':' + this.schedule.endTime.mm,
            });
        },
        addObjective: function() {
            this.createMeetingObjective({
                id: this.$route.params.meetingId,
                description: this.objectiveDescription,
            });
            this.objectiveDescription = null;
        },
        initEditObjective: function(objective) {
            this.showEditObjectiveModal = true;
            this.editObjectiveObject = {
                id: objective.id,
                description: objective.description,
            };
        },
        initDeleteObjective: function(objective) {
            this.showDeleteObjectiveModal = true;
            this.editObjectiveObject = {id: objective.id};
        },
        addAgenda: function() {
            this.createMeetingAgenda({
                id: this.$route.params.meetingId,
                topic: this.agenda.topic,
                responsibility: this.agenda.responsible.length > 0 ? this.agenda.responsible[0] : null,
                start: this.agenda.startTime.HH + ':' + this.agenda.startTime.mm,
                end: this.agenda.endTime.HH + ':' + this.agenda.endTime.mm,
            });
            this.agenda.responsible = [];
            this.agenda.topic = null;
        },
        initEditAgenda: function(agenda) {
            this.showEditAgendaModal = true;
            this.editAgendaObject = {
                id: agenda.id,
                topic: agenda.topic,
                responsibility: agenda.responsibility,
                start: {
                    HH: moment(agenda.start, 'HH:mm').format('HH'),
                    mm: moment(agenda.start, 'HH:mm').format('mm'),
                },
                end: {
                    HH: moment(agenda.end, 'HH:mm').format('HH'),
                    mm: moment(agenda.end, 'HH:mm').format('mm'),
                },
            };
        },
        initDeleteAgenda: function(agenda) {
            this.showDeleteAgendaModal = true;
            this.editAgendaObject = {id: agenda.id};
        },
        addDecision: function() {
            this.createMeetingDecision({
                id: this.$route.params.meetingId,
                title: this.decision.title,
                description: this.decision.description,
                responsibility: this.decision.responsibility.length > 0 ? this.decision.responsibility[0] : null,
                dueDate: moment(this.decision.dueDate, 'DD-MM-YYYY').format('DD-MM-YYYY'),
                status: this.decision.status ? this.decision.status.key : null,
            });
            this.decision.responsibility = [];
            this.decision.title = null;
        },
        initEditDecision: function(decision) {
            this.showEditDecisionModal = true;
            this.editDecisionObject = {
                id: decision.id,
                title: decision.title,
                description: decision.description,
                responsibility: [decision.responsibility],
                responsibilityFullName: decision.responsibilityFullName,
                dueDate: decision.dueDate ? new Date(decision.dueDate) : new Date(),
                status: {key: decision.status, label: decision.statusName},
                meeting: this.$route.params.meetingId,
            };
        },
        initDeleteDecision: function(decision) {
            this.showDeleteDecisionModal = true;
            this.editDecisionObject = {id: decision.id, meeting: this.$route.params.meetingId};
        },
        addTodo: function() {
            this.createMeetingTodo({
                id: this.$route.params.meetingId,
                title: this.todo.title,
                description: this.todo.description,
                responsibility: this.todo.responsibility.length > 0 ? this.todo.responsibility[0] : null,
                dueDate: moment(this.todo.dueDate, 'DD-MM-YYYY').format('DD-MM-YYYY'),
                status: this.todo.status ? this.todo.status.key : null,
            });
            this.todo.responsibility = [];
            this.todo.title = null;
        },
        initEditTodo: function(todo) {
            this.showEditTodoModal = true;
            this.editTodoObject = {
                id: todo.id,
                title: todo.title,
                description: todo.description,
                responsibility: [todo.responsibility],
                responsibilityFullName: todo.responsibilityFullName,
                dueDate: todo.dueDate ? new Date(todo.dueDate) : new Date(),
                status: {key: todo.status, label: this.translateText(todo.statusName)},
                meeting: this.$route.params.meetingId,
            };
        },
        initDeleteTodo: function(todo) {
            this.showDeleteTodoModal = true;
            this.editTodoObject = {id: todo.id, meeting: this.$route.params.meetingId};
        },
        addInfo: function() {
            const data = {
                // id: this.$route.params.meetingId,
                topic: this.info.topic,
                description: this.info.description,
                responsibility: this.info.responsibility.length ? this.info.responsibility[0] : null,
                dueDate: moment(this.info.dueDate, 'DD-MM-YYYY').format('DD-MM-YYYY'),
                infoStatus: this.info.infoStatus ? this.info.infoStatus.key : null,
                infoCategory: this.info.infoCategory ? this.info.infoCategory.key : null,
                meeting: this.$route.params.meetingId,
            };
            const projectId = this.$route.params.id;
            this.createInfo({projectId, data});
            this.info.topic = null;
            this.info.responsibility = [];
        },
        initEditInfo: function(info) {
            this.showEditInfoModal = true;
            this.editInfoObject = {
                id: info.id,
                topic: info.topic,
                description: info.description,
                responsibility: [info.responsibility],
                responsibilityFullName: info.responsibilityFullName,
                dueDate: info.dueDate ? new Date(info.dueDate) : new Date(),
                infoStatus: {key: info.infoStatus, label: info.infoStatusName},
                infoCategory: {key: info.infoCategory, label: info.infoCategoryName},
                meeting: this.$route.params.meetingId,
            };
        },
        initDeleteInfo: function(info) {
            this.showDeleteInfoModal = true;
            this.editInfoObject = {id: info.id, meeting: this.$route.params.meetingId};
        },
        saveMeeting: function() {
            let data = {
                name: this.name,
                distributionLists: this.details.distributionLists,
                meetingCategory: this.details.category,
                date: this.schedule.meetingDate,
                start: this.schedule.startTime,
                end: this.schedule.endTime,
                location: this.location,
                medias: this.medias,
            };
            this
                .editProjectMeeting({
                    id: this.$route.params.meetingId,
                    data: createFormData(data),
                    withPost: true,
                })
                .then(
                    (response) => {
                        if (response.body && response.body.error && response.body.messages) {
                            this.showFailed = true;
                        } else {
                            this.showSaved = true;
                        }
                    },
                    () => {
                        this.showFailed = true;
                    }
                )
            ;
        },
    },
    computed: {
        ...mapGetters({
            distributionListsForSelect: 'distributionListsForSelect',
            meetingCategoriesForSelect: 'meetingCategoriesForSelect',
            infoStatusesForDropdown: 'infoStatusesForDropdown',
            infoCategoriesForDropdown: 'infoCategoriesForDropdown',
            todoStatusesForSelect: 'todoStatusesForSelect',
            meeting: 'meeting',
            meetingAgendas: 'meetingAgendas',
            distributionLists: 'distributionLists',
            meetingParticipants: 'meetingParticipants',
            validationMessages: 'validationMessages',
            validationOrigin: 'validationOrigin',
        }),
        agendasPerPage: function() {
            return this.meetingAgendas.pageSize;
        },
        agendasPages: function() {
            return Math.ceil(this.meetingAgendas.totalItems / this.agendasPerPage);
        },
    },
    created() {
        this.getDistributionLists({projectId: this.$route.params.id});
        this.getMeetingParticipants({id: this.$route.params.meetingId});
        this.getMeetingCategories();
        this.getTodoStatuses();
        this.getInfoStatuses();
        this.getInfoCategories();
        this.getProjectMeeting(this.$route.params.meetingId);
        this.getMeetingAgendas({
            meetingId: this.$route.params.meetingId,
            apiParams: {
                page: this.agendasActivePage,
            },
        });
    },
    data() {
        return {
            showSaved: false,
            showFailed: false,
            agendasActivePage: 1,
            participantsPerPage: 10,
            participantsPages: 0,
            location: '',
            objectiveDescription: '',
            medias: [],
            name: '',
            schedule: {
                meetingDate: new Date(),
                startTime: null,
                endTime: null,
            },
            details: {
                distributionLists: [],
                category: null,
            },
            agenda: {
                topic: null,
                responsible: [],
                startTime: {
                    HH: null,
                    mm: null,
                },
                endTime: {
                    HH: null,
                    mm: null,
                },
            },
            decision: {
                title: null,
                description: null,
                responsibility: [],
                dueDate: new Date(),
                status: null,
            },
            todo: {
                title: null,
                description: null,
                responsibility: [],
                dueDate: new Date(),
                status: null,
            },
            info: {
                topic: null,
                description: null,
                responsibility: [],
                dueDate: new Date(),
                infoStatus: null,
                infoCategory: null,
            },
            decisionDescription: '',
            editDecisionDescription: '',
            todoDescription: '',
            editTodoDescription: '',
            infoDescription: '',
            editInfoDescription: '',
            showEditObjectiveModal: false,
            showDeleteObjectiveModal: false,
            editObjectiveObject: {},
            showEditAgendaModal: false,
            showDeleteAgendaModal: false,
            editAgendaObject: {},
            showEditDecisionModal: false,
            showDeleteDecisionModal: false,
            editDecisionObject: {},
            showEditTodoModal: false,
            showDeleteTodoModal: false,
            editTodoObject: {},
            showEditInfoModal: false,
            showDeleteInfoModal: false,
            editInfoObject: {},
            participants: [],
            displayedParticipants: [],
            decisionDescriptionEditor: null,
            todoDescriptionEditor: null,
            infoDescriptionEditor: null,
        };
    },
    watch: {
        details: {
            handler: function(value) {
                let users = [];
                this.getMeetingParticipants({id: this.$route.params.meetingId});
                this.meetingParticipants.map(function(item) {
                    users.push({
                        id: item.user,
                        fullName: item.userFullName,
                        avatar: item.userAvatar,
                        departments: item.userDepartmentNames,
                        isPresent: item.isPresent,
                    });
                });

                this.lists = this.distributionLists.filter((item) => {
                    for (let i = 0; i < this.details.distributionLists.length; i++) {
                        if (item.id === this.details.distributionLists[i].key) {
                            return true;
                        }
                    }
                    return false;
                });

                this.lists.map((item) => {
                    let existingUser = users.find((participant) => {
                        return participant.id === item.createdBy;
                    });
                    if (!existingUser) {
                        users.push({
                            id: item.createdBy,
                            fullName: item.createdByFullName,
                            avatar: item.createdByAvatar,
                            departments: item.createdByDepartmentNames,
                        });
                    }
                    item.users.map((user) => {
                        let projectUser = user.projectUsers.filter((item) => {
                            return item.project !== this.$route.params.id;
                        });
                        let existingUser = users.find((participant) => {
                            return participant.id === user.id;
                        });
                        if (!existingUser && projectUser.length > 0) {
                            users.push({
                                id: user.id,
                                fullName: user.firstName + ' ' + user.lastName,
                                avatar: user.avatar ? user.avatar : user.gravatar,
                                departments: projectUser[0].projectDepartmentNames,
                            });
                        }
                    });
                });

                this.participants = users;
                this.displayedParticipants = this.participants.slice(0, this.participantsPerPage);
                this.participantsPages = Math.ceil(this.participants.length / this.participantsPerPage);
            },
            deep: true,
        },
        meeting(value) {
            this.name = this.meeting.name;
            if (this.meeting.distributionLists.length > 0) {
                let selectedList = [];
                this.meeting.distributionLists.map(function(item) {
                    selectedList.push({'key': item.id, 'label': item.name});
                });
                this.details.distributionLists = selectedList;
            };
            this.details.category = this.meeting.meetingCategory
                ? {key: this.meeting.meetingCategory, label: this.meeting.meetingCategoryName}
                : null
            ;
            this.schedule.meetingDate = new Date(this.meeting.date);
            this.schedule.startTime = {
                HH: moment(this.meeting.start, 'HH:mm').format('HH'),
                mm: moment(this.meeting.start, 'HH:mm').format('mm'),
            };
            this.schedule.endTime = {
                HH: moment(this.meeting.end, 'HH:mm').format('HH'),
                mm: moment(this.meeting.end, 'HH:mm').format('mm'),
            };
            this.location = this.meeting.location;
            this.medias = this.meeting.medias.map(item => {
                return {
                    'path': item.path,
                };
            });
        },
        showSaved(value) {
            if (value === false) {
                router.push({
                    name: 'project-meetings',
                    params: {
                        id: this.$route.params.id,
                    },
                });
            }
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_mixins';
    @import '../../../css/_variables';
    @import '../../../css/common';

    .title {
        position: relative;
        top: 15px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 700;
    }

    .action-list {
        margin-bottom: 15px;

        li {
            margin-bottom: 15px;
            position: relative;
            padding-right: 60px;
            padding-bottom: 15px;
            border-bottom: 1px solid $darkerColor;

            .list-item-description {
                position: relative;
                padding-left: 30px;

                &:before {
                    content: '';
                    display: block;
                    position: absolute;
                    @include border-radius(50%);
                    background-color: $lightColor;
                    width: 10px;
                    height: 10px;
                    left: 0;
                    top: 0;
                }
            }

            .list-item-actions {
                position: absolute;
                top: 0;
                right: 0;
                width: 60px;
                text-align: right;

                a {
                    margin-left: 10px;
                }
            }

            &:last-child {
                margin-bottom: 0;
            }
        }
    }

    .actions {
        margin: 30px 0;
    }

    .table-wrapper {
        width: 100%;
        padding-bottom: 40px;
    }

    .avatars {
        > div {
            height: 34px;
            padding: 2px 0;
            display: inline-block;
        }

        span {
            margin-left: 10px;
            line-height: 34px;
        }
    }

    .avatar {
        width: 30px;
        height: 30px;
        @include border-radius(50%);
        background-size: cover;
        display: inline-block;
        margin-right: 5px;

        &:last-child {
            margin-right: 0;
        }
    }

    .topic {
        white-space: normal;
        text-transform: none;
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

    .entry {
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid $darkerColor;

        .entry-header {
            .entry-title {
                text-transform: uppercase;
                letter-spacing: 0.1em;
                font-size: 10px;
                margin-bottom: 10px;

                h4 {
                    display: inline-block;
                    text-transform: none;
                    letter-spacing: 0;
                    margin: 0;
                    font-weight: 700;
                }
            }

            .done {
                color: $secondColor;
            }

            .undone {
                color: $dangerColor;
            }

            .entry-buttons {
                text-align: right;

                .btn {
                    margin: 0 0 10px 10px;
                }
            }
        }

        .entry-responsible {
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 10px;
            line-height: 1.5em;

            b {
                display: block;
                font-size: 12px;
            }
        }

        .entry-body {
            padding: 10px 0 0 0;

            ul {
                list-style-type: disc;
                list-style-position: inside;

                &:last-child {
                    margin-bottom: 0;
                }
            }

            ol {
                list-style-type: decimal;
                list-style-position: inside;
                padding: 0;

                &:last-child {
                    margin-bottom: 0;
                }
            }

            img {
                @include box-shadow(0, 0, 20px, $darkColor);
            }

            .title {
                font-weight: bold;
                margin-bottom: 5px;
            }

            .cost {
                text-transform: uppercase;
                color: $lightColor;
                letter-spacing: 0.1em;

                b {
                    color: $lighterColor;
                }
            }

            p {
                &:last-child {
                    margin-bottom: 0;
                }
            }
        }
    }

    .new-comment {
        .footer-buttons {
            margin-top: 15px;
        }
    }

    .buttons {
      a {
        margin: 5px 0 5px 10px;
      }
    }
</style>
