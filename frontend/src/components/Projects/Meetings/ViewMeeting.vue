<template>
    <div>
        <div class="row">
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
                    v-bind:editNoteModal="showEditNoteModal"
                    v-bind:deleteNoteModal="showDeleteNoteModal"
                    v-bind:noteObject="editNoteObject"
                    v-on:input="setModals"
            >
            </meeting-modals>
            <modal v-if="deleteMeetingModal" @close="deleteMeetingModal = false">
                <p class="modal-title">{{ translateText('message.delete_meeting') }}</p>
                <div class="flex flex-space-between">
                    <a href="javascript:void(0)" @click="deleteMeetingModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                    <a href="javascript:void(0)" @click="deleteMeeting()" class="btn-rounded">{{ translateText('message.yes') }}</a>
                </div>
            </modal>
            <modal v-if="rescheduleModal" @close="rescheduleModal = false">
                <p class="modal-title">{{ translateText('message.reschedule_meeting') }}</p>
                <div class="form-group last-form-group">
                    <div class="col-md-4">
                        <div class="input-holder">
                            <label class="active">{{ translateText('label.select_date') }}</label>
                            <datepicker :clear-button="false" v-model="date" format="dd-MM-yyyy" :value="date"></datepicker>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-holder">
                            <label class="active">{{ translateText('label.start_time') }}</label>
                            <vue-timepicker v-model="startTime" hide-clear-button></vue-timepicker>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-holder">
                            <label class="active">{{ translateText('label.finish_time') }}</label>
                            <vue-timepicker v-model="endTime" hide-clear-button></vue-timepicker>
                        </div>
                    </div>
                </div>
                <hr class="double">

                <div class="flex flex-space-between">
                    <a href="javascript:void(0)" @click="rescheduleModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
                    <a href="javascript:void(0)" @click="rescheduleMeeting()" class="btn-rounded">{{ translateText('button.save') }}</a>
                </div>
            </modal>

            <modal v-if="showNotificationModal" @close="showNotificationModal = false">
                <p class="modal-title">{{ translateText('message.send_notifications') }}</p>
                <div class="flex flex-space-between">
                    <a href="javascript:void(0)" @click="showNotificationModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                    <a href="javascript:void(0)" @click="sendNotifications()" class="btn-rounded">{{ translateText('message.yes') }}</a>
                </div>
            </modal>

            <div class="col-md-6">
                <div class="create-meeting page-section">
                    <!-- /// Header /// -->
                    <div class="header flex-v-center">
                        <div>
                            <router-link :to="{name: 'project-meetings'}" class="small-link">
                                <i class="fa fa-angle-left"></i>
                                {{ translateText('message.back_to_meetings') }}
                            </router-link>
                            <h1>{{ meeting.name }}</h1>
                            <h3 class="category"><b>{{ meeting.meetingCategoryName }}</b></h3>
                            <h4>
                                {{ translateText('message.starting_on') }} <b>{{ meeting.date | moment('dddd') }}</b>, <b>{{ meeting.date | moment('DD.MM.YYYY') }}</b>
                                {{ translateText('message.from') }} <b>{{ meeting.start }}</b> {{ translateText('message.to') }} <b>{{ meeting.end }}</b> | {{ translateText('message.duration') }}: <b>{{ getDuration(meeting.start, meeting.end) }} {{ translateText('message.min') }}</b>
                            </h4>
                            <a @click="rescheduleModal = true;" class="btn-rounded btn-auto btn-md btn-empty">{{ translateText('button.reschedule') }} <reschedule-icon></reschedule-icon></a>
                        </div>
                    </div>
                    <!-- /// End Header /// -->
                </div>
                
        
                <!-- /// Meeting Location /// -->
                <h3>{{ translateText('message.location') }}</h3>
                <p>{{ meeting.location }}</p>
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
                <!-- /// End Meeting Objectives /// -->
        
                <hr class="double">
        
                <!-- /// Meeting Agenda /// -->
                <h3>{{ translateText('message.agenda') }}</h3>
                <div class="overflow-hidden">
                    <vue-scrollbar class="table-wrapper">
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
                    </vue-scrollbar>
                </div>
                <div v-if="meetingAgendas && meetingAgendas.items" class="flex flex-direction-reverse flex-v-center">
                    <div class="pagination flex flex-center" v-if="meetingAgendas && meetingAgendas.totalItems > 0">
                        <span v-if="agendasPages > 1" v-for="page in agendasPages" v-bind:class="{'active': page == agendasActivePage}" @click="changeAgendasPage(page)">{{ page }}</span>
                    </div>
                    <div>
                        <span class="pagination-info">{{ translateText('message.displaying') }} {{ meetingAgendas.items.length }} {{ translateText('message.results_out_of') }} {{ meetingAgendas.totalItems }}</span>
                    </div>
                </div>
                <!-- /// End Meeting Agenda /// -->
        
                <hr class="double">
        
                <!-- /// Meeting Documents /// -->
                <h3>{{ translateText('message.documents') }}</h3>
                <ul class="attachments" v-if="meeting.medias">
                    <li v-for="media in meeting.medias">
                        <a :href="downloadMedia(media)" :title="translateText('message.download_attachment')">{{ media.path }} <downloadbutton-icon fill="second-fill"></downloadbutton-icon></a>
                    </li>
                </ul>
                <!-- /// End Meeting Documents /// -->
        
                <hr class="double">
                
                <!-- /// Decisions /// -->
                <h3>{{ translateText('message.decisions') }}</h3>

                <div class="entries-wrapper" v-if="meeting.decisions">
                    <!-- /// Decision /// -->
                    <div class="entry" v-for="decision in meeting.decisions">
                        <div class="entry-header flex flex-space-between flex-v-center">
                            <div class="entry-title">
                                <h4>{{ decision.title }}</h4>  | {{ translateText('message.due_date') }}: <b>{{ decision.dueDate | moment('DD.MM.YYYY') }}</b>
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
                <!-- /// End Decisions /// -->
        
                <hr class="double">
        
                <!-- /// ToDos /// -->
                <h3>{{ translateText('message.todos') }}</h3>

                <div class="entries-wrapper" v-if="meeting.todos">
                    <!-- /// ToDo /// -->
                    <div class="entry" v-for="todo in meeting.todos">
                        <div class="entry-header flex flex-space-between flex-v-center">
                            <div class="entry-title">
                                <h4>{{ todo.title }}</h4>  | {{ translateText('message.due_date') }}: <b>{{ todo.dueDate | moment('DD.MM.YYYY') }}</b> | {{ translateText('message.status') }}: <b v-if="todo.status">{{ translateText(todo.statusName) }}</b><b v-else>-</b>
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
                <!-- /// End ToDos /// -->
        
                <hr class="double">
        
                <!-- /// Infos /// -->
                <h3>{{ translateText('message.infos') }}</h3>

                <div class="entries-wrapper" v-if="meeting.notes">
                    <!-- /// Info /// -->
                    <div class="entry" v-for="note in meeting.notes">
                        <div class="entry-header flex flex-space-between flex-v-center">
                            <div class="entry-title">
                                <h4>{{ note.title }}</h4> | {{ translateText('message.due_date') }}: <b>{{ note.dueDate | moment('DD.MM.YYYY') }}</b> | {{ translateText('message.status') }}: <b v-if="note.status">{{ note.statusName }}</b><b v-else>-</b>
                            </div>
                            <div class="entry-buttons">
                                <button @click="initEditNote(note)" class="btn btn-rounded second-bg btn-auto btn-md" data-toggle="modal" type="button">edit</button>
                                <button @click="initDeleteNote(note)" type="button" class="btn btn-rounded btn-auto btn-md danger-bg" >{{ translateText('message.delete') }}</button>
                            </div>
                        </div>
                        <div class="entry-responsible flex flex-v-center">
                            <div class="user-avatar">
                                <img :src="note.responsibilityAvatar" :alt="note.responsibilityFullName"/>
                            </div>
                            <div>
                                {{ translateText('message.responsible') }}:
                                <b>{{ note.responsibilityFullName }}</b>
                            </div>
                        </div>
                        <div class="entry-body" v-html="note.description"></div>
                    </div>
                    <!-- /// End Info /// -->
                </div>
                <!-- /// End Infos /// -->
            </div>
            <div class="col-md-6">
                <div class="create-meeting page-section">
                    <!-- /// Header /// -->
                    <div class="margintop20 text-right">
                        <div class="buttons">
                            <router-link :to="{name: 'project-meetings-edit-meeting', params: {id: projectId, meetingId: meetingId}}" class="btn-rounded btn-auto">
                                {{ translateText('button.edit_meeting') }}
                            </router-link>
                            <router-link :to="{name: 'project-meetings-create-meeting', params: {id: projectId}}" class="btn-rounded btn-auto second-bg">
                                {{ translateText('button.new_meeting') }}
                            </router-link>
                            <a @click="deleteMeetingModal = true;" class="btn-rounded btn-auto danger-bg">{{ translateText('button.delete_meeting') }}</a>
                        </div>
                    </div>
                    <!-- /// End Header /// -->
        
                    <div class="flex flex-v-center flex-space-between">
                        <div>
                            <h3>{{ translateText('message.participants') }}</h3>
                        </div>
                        <div class="buttons">
                            <!--<router-link :to="{name: 'project-organization-edit'}" class="btn-rounded btn-auto btn-md btn-empty">{{ translateText('button.edit_distribution_list') }}</router-link>-->
                            <a @click="showNotificationModal = true" class="btn-rounded btn-auto btn-md btn-empty">{{ translateText('button.send_notifications') }}</a>
                        </div>
                    </div>

                    <meeting-participants
                            v-bind:meetingParticipants="displayedParticipants"
                            v-bind:participants="participants"
                            v-bind:participantsPages="participantsPages"
                            v-bind:participantsPerPage="participantsPerPage"
                    >
                    </meeting-participants>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="text-right footer-buttons">
                    <div class="buttons">
                        <router-link :to="{name: 'project-meetings-edit-meeting', params: {id: projectId, meetingId: meetingId}}" class="btn-rounded btn-auto">
                            {{ translateText('button.edit_meeting') }}
                        </router-link>
                        <router-link :to="{name: 'project-meetings-create-meeting', params: {id: projectId}}" class="btn-rounded btn-auto second-bg">
                            {{ translateText('button.new_meeting') }}
                        </router-link>
                        <a @click="deleteMeetingModal = true;" class="btn-rounded btn-auto danger-bg">{{ translateText('button.delete_meeting') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import EditIcon from '../../_common/_icons/EditIcon';
import DeleteIcon from '../../_common/_icons/DeleteIcon';
import VueScrollbar from 'vue2-scrollbar';
import Switches from '../../3rdparty/vue-switches';
import RescheduleIcon from '../../_common/_icons/RescheduleIcon';
import DownloadbuttonIcon from '../../_common/_icons/DownloadbuttonIcon';
import {mapGetters, mapActions} from 'vuex';
import moment from 'moment';
import MeetingModals from './MeetingModals';
import MeetingParticipants from './MeetingParticipants';
import Modal from '../../_common/Modal';
import VueTimepicker from 'vue2-timepicker';
import datepicker from '../../_common/_form-components/Datepicker';
import {createEditor} from 'vueditor';
import vueditorConfig from '../../_common/vueditorConfig';

export default {
    components: {
        EditIcon,
        DeleteIcon,
        VueScrollbar,
        Switches,
        RescheduleIcon,
        DownloadbuttonIcon,
        MeetingModals,
        MeetingParticipants,
        Modal,
        VueTimepicker,
        datepicker,
    },
    methods: {
        ...mapActions([
            'getProjectMeeting', 'getMeetingAgendas', 'getMeetingParticipants',
            'getDistributionLists', 'deleteProjectMeeting', 'editProjectMeeting', 'sendMeetingNotifications',
        ]),
        translateText: function(text) {
            return this.translate(text);
        },
        getDuration: function(startDate, endDate) {
            let end = moment(endDate, 'HH:mm');
            let start = moment(startDate, 'HH:mm');

            return !isNaN(end.diff(start, 'minutes')) ? end.diff(start, 'minutes') : '-';
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
            this.showEditNoteModal = value;
            this.showDeleteNoteModal = value;
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
        initEditDecision: function(decision) {
            this.showEditDecisionModal = true;
            this.editDecisionObject = {
                id: decision.id,
                title: decision.title,
                responsibility: [decision.responsibility],
                responsibilityFullName: decision.responsibilityFullName,
                dueDate: decision.dueDate ? new Date(decision.dueDate) : new Date(),
                meeting: this.$route.params.meetingId,
            };
            setTimeout(() => {
                this.$refs.meetingmodal.$refs.editDecisionDescription.setContent(decision.description);
            }, 100);
        },
        initDeleteDecision: function(decision) {
            this.showDeleteDecisionModal = true;
            this.editDecisionObject = {id: decision.id, meeting: this.$route.params.meetingId};
        },
        initEditTodo: function(todo) {
            this.showEditTodoModal = true;
            this.editTodoObject = {
                id: todo.id,
                title: todo.title,
                responsibility: [todo.responsibility],
                responsibilityFullName: todo.responsibilityFullName,
                dueDate: todo.dueDate ? new Date(todo.dueDate) : new Date(),
                status: {key: todo.status, label: todo.statusName},
                meeting: this.$route.params.meetingId,
            };
            setTimeout(() => {
                this.$refs.meetingmodal.$refs.editTodoDescription.setContent(todo.description);
            }, 100);
        },
        initDeleteTodo: function(todo) {
            this.showDeleteTodoModal = true;
            this.editTodoObject = {id: todo.id, meeting: this.$route.params.meetingId};
        },
        initEditNote: function(note) {
            this.showEditNoteModal = true;
            this.editNoteObject = {
                id: note.id,
                title: note.title,
                responsibility: [note.responsibility],
                responsibilityFullName: note.responsibilityFullName,
                dueDate: note.dueDate ? new Date(note.dueDate) : new Date(),
                status: {key: note.status, label: note.statusName},
                meeting: this.$route.params.meetingId,
            };
            setTimeout(() => {
                this.$refs.meetingmodal.$refs.editNoteDescription.setContent(note.description);
            }, 100);
        },
        initDeleteNote: function(note) {
            this.showDeleteNoteModal = true;
            this.editNoteObject = {id: note.id, meeting: this.$route.params.meetingId};
        },
        deleteMeeting: function(note) {
            this.deleteMeetingModal = false;
            this.deleteProjectMeeting(this.$route.params.meetingId);
        },
        rescheduleMeeting: function() {
            this.rescheduleModal = false;
            let data = {
                id: this.$route.params.meetingId,
                date: moment(this.date).format('DD-MM-YYYY'),
                start: this.startTime.HH + ':' + this.startTime.mm,
                end: this.endTime.HH + ':' + this.endTime.mm,
            };
            this.editProjectMeeting(data);
        },
        sendNotifications: function() {
            this.sendMeetingNotifications(this.$route.params.id);
            this.showNotificationModal = false;
        },
        downloadMedia: function(media) {
            return Routing.generate('app_media_download', {id: media.id});
        },
        initVueEditors: function() {
            setTimeout(() => {
                this.decisionDescriptionEditor = createEditor(document.getElementById('decisionDescription'), {...vueditorConfig, id: 'decisionDescription'});
                this.todoDescriptionEditor = createEditor(document.getElementById('todoDescription'), {...vueditorConfig, id: 'todoDescription'});
                this.noteDescriptionEditor = createEditor(document.getElementById('noteDescription'), {...vueditorConfig, id: 'noteDescription'});
            }, 100);
        },
    },
    computed: {
        ...mapGetters({
            meeting: 'meeting',
            meetingAgendas: 'meetingAgendas',
            distributionLists: 'distributionLists',
            meetingParticipants: 'meetingParticipants',
        }),
    },
    created() {
        this.getDistributionLists({projectId: this.$route.params.id});
        this.getProjectMeeting(this.$route.params.meetingId);
        this.getMeetingAgendas({
            meetingId: this.$route.params.meetingId,
            apiParams: {
                page: this.agendasActivePage,
            },
        });
        this.getMeetingParticipants({id: this.$route.params.meetingId});
    },
    mounted() {
        this.initVueEditors();
    },
    data() {
        return {
            projectId: this.$route.params.id,
            meetingId: this.$route.params.meetingId,
            agendasActivePage: 1,
            participantsPages: 0,
            participantsPerPage: 10,
            showPresent: null,
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
            showEditNoteModal: false,
            showDeleteNoteModal: false,
            editNoteObject: {},
            deleteMeetingModal: false,
            rescheduleModal: false,
            date: new Date(),
            startTime: {},
            endTime: {},
            participants: [],
            displayedParticipants: [],
            showNotificationModal: false,
        };
    },
    watch: {
        meeting(value) {
            let users = [];
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
                for (let i = 0; i < this.meeting.distributionLists.length; i++) {
                    if (item.id === this.meeting.distributionLists[i].id) {
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
            this.date = new Date(this.meeting.date);
            this.startTime = {
                HH: moment(this.meeting.start, 'HH:mm').format('HH'),
                mm: moment(this.meeting.start, 'HH:mm').format('mm'),
            };
            this.endTime = {
                HH: moment(this.meeting.end, 'HH:mm').format('HH'),
                mm: moment(this.meeting.end, 'HH:mm').format('mm'),
            };
        },
    },
};
</script>

<style lang="scss">
    @import '../../../css/_mixins';
    @import '../../../css/_variables';

    ul.attachments {
        li {
            a {
                .icon {
                    svg {
                        width: 12px;
                        height: 12px;
                        @include transition(color, 0.2s, ease);
                    }
                }

                &:hover {
                    .icon {
                        svg {
                            fill: $secondDarkColor;
                        }
                    }                    
                }
            }
        }
    }
</style>

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
                padding-bottom: 0;
                border: none;
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

        &:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border: none;
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

    .category {
        margin-top: 0;
    }

    ul.attachments {
        display: inline-block;
        margin: 0;
        list-style: none;
        padding: 0;

        li {
            margin-bottom: 15px;

            a {
                color: $secondColor;
                @include transition(color, 0.2s, ease);

                &:hover {
                    color: $secondDarkColor;
                }
            }

            &:last-child {
                margin: 0;
            }
        }
    }

    .footer-buttons {
        margin-top: 60px;
        padding: 30px 0;
        border-top: 1px solid $darkerColor; 
    }
</style>
