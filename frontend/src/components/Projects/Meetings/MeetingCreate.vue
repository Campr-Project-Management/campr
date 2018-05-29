<template>
    <div class="row">
        <edit-distribution-list-modal
            v-if="editDistributionListModal"
            @close="editDistributionListModal = null"
            v-on:distributionListUpdated="distributionListUpdated"
            :distribution-id="editDistributionListModal" />

        <div class="col-md-6">
            <div class="create-meeting page-section">
                <!-- /// Header /// -->
                <div class="header flex-v-center">
                    <div>
                        <router-link :to="{name: 'project-meetings'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ translate('message.back_to_meetings') }}
                        </router-link>
                        <h1>{{ translate('message.create_new_meeting') }}</h1>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <div class="form">
                    <!-- /// Meeting Distribution List (Event Name) and Category /// -->
                    <hr class="double">

                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="translate('placeholder.distribution_list')"
                                    v-bind:options="distributionListsForSelect"
                                    v-model="details.distributionList"/>
                                <error at-path="distributionLists"/>
                            </div>
                            <div class="col-md-6">
                                <select-field
                                        :title="translate('placeholder.category')"
                                        :options="meetingCategoriesForSelect"
                                        v-model="details.category"/>
                                <error at-path="meetingCategory"/>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Meeting Distribution List (Event Name) and Category /// -->

                    <hr class="double">

                    <!-- /// Meeting Schedule /// -->
                    <h3>{{ translate('message.schedule') }}</h3>
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-4">
                                <div class="input-holder right">
                                    <label class="active">{{ translate('label.select_date') }}</label>
                                    <datepicker v-model="schedule.meetingDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill"/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-holder right">
                                    <label class="active">{{ translate('label.start_time') }}</label>
                                    <timepicker v-model="schedule.startTime" hide-clear-button />
                                    <error
                                        v-if="validationMessages.start && validationMessages.start.length"
                                        v-for="(message, index) in validationMessages.start"
                                        :key="`schedule-schedule-${index}`"
                                        :message="message" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-holder right">
                                    <label class="active">{{ translate('label.finish_time') }}</label>
                                    <timepicker v-model="schedule.endTime" hide-clear-button />
                                    <error
                                        v-if="validationMessages.end && validationMessages.end.length"
                                        v-for="(message, index) in validationMessages.end"
                                        :key="`schedule-end-${index}`"
                                        :message="message" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Meeting Schedule /// -->

                    <hr class="double">

                    <!-- /// Meeting Location /// -->
                    <h3>{{ translate('message.location') }}</h3>
                    <input-field type="text" :label="translate('placeholder.location')" v-model="location" :content="location" />
                    <error
                        v-if="validationMessages.location && validationMessages.location.length"
                        v-for="(message, index) in validationMessages.location"
                        :key="`location-${index}`"
                        :message="message" />
                    <!-- /// End Meeting Location /// -->

                    <hr class="double">

                    <!-- /// Meeting Objectives /// -->
                    <h3>{{ translate('message.objectives') }}</h3>
                    <div class="form-group"
                        v-for="(objective, index) in objectives"
                        :key="index">
                        <input-field type="text" :label="translate('placeholder.objective')" v-model="objective.description" :content="objective.description" />
                        <div v-if="validationMessages.meetingObjectives && validationMessages.meetingObjectives[index.toString()]">
                            <error
                                v-if="validationMessages.meetingObjectives[index.toString()].description && validationMessages.meetingObjectives[index.toString()].description.length"
                                v-for="(message, index) in validationMessages.meetingObjectives[index.toString()].description"
                                :key="`objective-description-${index}`"
                                :message="message" />
                        </div>
                        <hr>
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a @click="addObjective()" class="btn-rounded btn-auto">{{ translate('message.add_new_objective') }} +</a>
                    </div>
                    <!-- /// End Meeting Objectives /// -->

                    <hr class="double">

                    <!-- /// Meeting Agenda /// -->
                    <h3>{{ translate('message.agenda') }}</h3>
                    <div v-for="(agenda, index) in agendas" :key="`agenda-${index}`">
                        <div class="form-group">
                            <input-field type="text" :label="translate('placeholder.topic')" v-model="agenda.topic" :content="agenda.topic" />
                            <div v-if="validationMessages.meetingAgendas && validationMessages.meetingAgendas[index.toString()]">
                                <error
                                    v-if="validationMessages.meetingAgendas[index.toString()].topic && validationMessages.meetingAgendas[index.toString()].topic.length"
                                    v-for="(message, index) in validationMessages.meetingAgendas[index.toString()].topic"
                                    :key="`agenda-topic-${index}`"
                                    :message="message" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group form-group">
                                <div class="col-md-4">
                                    <member-search singleSelect="false" v-model="agenda.responsible" :placeholder="translate('placeholder.search_members')"></member-search>
                                    <div v-if="validationMessages.meetingAgendas && validationMessages.meetingAgendas[index.toString()]">
                                        <error
                                            v-if="validationMessages.meetingAgendas[index.toString()].responsibility && validationMessages.meetingAgendas[index.toString()].responsibility.length"
                                            v-for="(message, index) in validationMessages.meetingAgendas[index.toString()].responsibility"
                                            :key="`agenda-responsible-${index}`"
                                            :message="message" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-holder right">
                                        <label class="active">{{ translate('label.start_time') }}</label>
                                        <timepicker v-model="agenda.startTime" hide-clear-button />
                                        <div v-if="validationMessages.meetingAgendas && validationMessages.meetingAgendas[index.toString()]">
                                        <error
                                            v-if="validationMessages.meetingAgendas[index.toString()].start && validationMessages.meetingAgendas[index.toString()].start.length"
                                            v-for="(message, index) in validationMessages.meetingAgendas[index.toString()].start"
                                            :key="`agenda-startTime-${index}`"
                                            :message="message" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-holder right">
                                        <label class="active">{{ translate('label.finish_time') }}</label>
                                        <timepicker v-model="agenda.endTime" hide-clear-button />
                                        <div v-if="validationMessages.meetingAgendas && validationMessages.meetingAgendas[index.toString()]">
                                        <error
                                            v-if="validationMessages.meetingAgendas[index.toString()].end && validationMessages.meetingAgendas[index.toString()].end.length"
                                            v-for="(message, index) in validationMessages.meetingAgendas[index.toString()].end"
                                            :key="`agenda-endTime-${index}`"
                                            :message="message" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a @click="addAgenda()" class="btn-rounded btn-auto">{{ translate('message.add_new_topic') }} +</a>
                    </div>
                    <!-- /// End Meeting Objectives /// -->

                    <hr class="double">

                    <!-- /// Meeting Documents /// -->
                    <meeting-attachments v-on:input="setMedias" :editMedias="medias" />
                    <!-- /// End Meeting Documents /// -->

                    <hr class="double">

                    <!-- /// Decisions /// -->
                    <h3>{{ translate('message.decisions') }}</h3>
                    <div v-for="(decision, index) in decisions" :key="`decision-${index}`">
                        <input-field type="text" :label="translate('placeholder.decision_title')" v-model="decision.title" :content="decision.title" />
                        <div v-if="validationMessages.decisions && validationMessages.decisions[index.toString()]">
                        <error
                            v-if="validationMessages.decisions[index.toString()].title && validationMessages.decisions[index.toString()].title.length"
                            v-for="(message, index) in validationMessages.decisions[index.toString()].title"
                            :key="`decision-title-${index}`"
                            :message="message" />
                        </div>
                        <div class="form-group">
                            <editor
                                :id="`decision-description-${index}`"
                                height="200px"
                                label="placeholder.decision_description"
                                v-model="decision.description" />
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <member-search singleSelect="false" v-model="decision.responsible" :placeholder="translate('placeholder.search_members')"></member-search>
                                    <div v-if="validationMessages.decisions && validationMessages.decisions[index.toString()]">
                                    <error
                                        v-if="validationMessages.decisions[index.toString()].responsibility && validationMessages.decisions[index.toString()].responsibility.length"
                                        v-for="(message, index) in validationMessages.decisions[index.toString()].responsibility"
                                        :key="`decision-responsible-${index}`"
                                        :message="message" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-holder right">
                                        <label class="active">{{ translate('label.due_date') }}</label>
                                        <datepicker v-model="decision.dueDate" format="dd-MM-yyyy" />
                                        <calendar-icon fill="middle-fill"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a @click="addDecision()" class="btn-rounded btn-auto">{{ translate('message.add_new_decision') }} +</a>
                    </div>
                    <!-- /// End Decisions /// -->

                    <hr class="double">

                    <!-- /// ToDos /// -->
                    <h3>{{ translate('message.todos') }}</h3>
                    <div v-for="(todo, index) in todos" :key="`todo-${index}`">
                        <input-field type="text" :label="translate('placeholder.topic')" v-model="todo.title" :content="todo.title" />
                        <div v-if="validationMessages.todos && validationMessages.todos[index.toString()]">
                        <error
                            v-if="validationMessages.todos[index.toString()].title && validationMessages.todos[index.toString()].title.length"
                            v-for="(message, index) in validationMessages.todos[index.toString()].title"
                            :key="`todo-title-${index}`"
                            :message="message" />
                        </div>
                        <div class="form-group">
                            <editor
                                :id="`todo-description-${index}`"
                                height="200px"
                                label="placeholder.todo_description"
                                v-model="todo.description" />
                            <div v-if="validationMessages.todos && validationMessages.todos[index.toString()]">
                            <error
                                v-if="validationMessages.todos[index.toString()].description && validationMessages.todos[index.toString()].description.length"
                                v-for="(message, index) in validationMessages.todos[index.toString()].description"
                                :key="`todo-description-${index}`"
                                :message="message" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <member-search singleSelect="false" v-model="todo.responsible" :placeholder="translate('placeholder.search_members')"></member-search>
                                    <div v-if="validationMessages.todos && validationMessages.todos[index.toString()]">
                                    <error
                                        v-if="validationMessages.todos[index.toString()].responsibility && validationMessages.todos[index.toString()].responsibility.length"
                                        v-for="(message, index) in validationMessages.todos[index.toString()].responsibility"
                                        :key="`todo-responsible-${index}`"
                                        :message="message" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-holder right">
                                        <label class="active">{{ translate('label.due_date') }}</label>
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
                                        :title="translate('label.select_status')"
                                        :options="todoStatusesForSelect"
                                        v-model="todo.status"
                                        :currentOption="todo.status" />
                                    <div v-if="validationMessages.todos && validationMessages.todos[index.toString()]">
                                    <error
                                        v-if="validationMessages.todos[index.toString()].status && validationMessages.todos[index.toString()].status.length"
                                        v-for="(message, index) in validationMessages.todos[index.toString()].status"
                                        :key="`todo-status-${index}`"
                                        :message="message" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a @click="addTodo()" class="btn-rounded btn-auto">{{ translate('message.add_new_todo') }} +</a>
                    </div>
                    <!-- /// End ToDos /// -->

                    <hr class="double">

                    <!-- /// Infos /// -->
                    <h3>{{ translate('message.infos') }}</h3>
                    <div v-for="(info, index) in infos" :key="`info-${index}`">
                        <input-field type="text" :label="translate('placeholder.topic')" v-model="info.topic" :content="info.topic" />
                        <div v-if="validationMessages.infos && validationMessages.infos[index.toString()]">
                        <error
                            v-if="validationMessages.infos[index.toString()].topic && validationMessages.infos[index.toString()].topic.length"
                            v-for="(message, index) in validationMessages.infos[index.toString()].topic"
                            :key="`info-topic-${index}`"
                            :message="message" />
                        </div>
                        <div class="form-group">
                            <editor
                                :id="`info-description-${index}`"
                                height="200px"
                                label="placeholder.info_description"
                                v-model="info.description" />
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <member-search singleSelect="false" v-model="info.responsible" :placeholder="translate('placeholder.search_members')"></member-search>
                                    <div v-if="validationMessages.infos && validationMessages.infos[index.toString()]">
                                    <error
                                        v-if="validationMessages.infos[index.toString()].responsibility && validationMessages.infos[index.toString()].responsibility.length"
                                        v-for="(message, index) in validationMessages.infos[index.toString()].responsibility"
                                        :key="`info-responsible-${index}`"
                                        :message="message" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-holder right">
                                        <label class="active">{{ translate('label.due_date') }}</label>
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
                                        :title="translate('label.select_status')"
                                        :options="infoStatusesForDropdown"
                                        v-model="info.infoStatus"
                                        v-bind:currentOption="info.infoStatus" />
                                    <div v-if="validationMessages.infos && validationMessages.infos[index.toString()]">
                                        <error
                                            v-if="validationMessages.infos[index.toString()].infoStatus && validationMessages.infos[index.toString()].infoStatus.length"
                                            v-for="(message, index) in validationMessages.infos[index.toString()].infoStatus"
                                            :key="`info-infoStatus-${index}`"
                                            :message="message" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <select-field
                                        :title="'label.category'"
                                        :options="infoCategoriesForDropdown"
                                        v-model="info.infoCategory"
                                        v-bind:currentOption="info.infoCategory" />
                                    <div v-if="validationMessages.infos && validationMessages.infos[index.toString()]">
                                        <error
                                            v-if="validationMessages.infos[index.toString()].infoCategory && validationMessages.infos[index.toString()].infoCategory.length"
                                            v-for="(message, index) in validationMessages.infos[index.toString()].infoCategory"
                                            :key="`info-infoCategory-${index}`"
                                            :message="message" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a @click="addInfo()" class="btn-rounded btn-auto">{{ translate('message.add_new_info') }} +</a>
                    </div>
                    <!-- /// End ToDos /// -->

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-meetings'}" class="btn-rounded btn-auto btn-auto disable-bg">{{ translate('button.cancel') }}</router-link>
                        <a @click="saveMeeting()" class="btn-rounded btn-auto btn-auto second-bg">{{ translate('button.save_meeting') }}</a>
                    </div>
                    <!-- /// End Actions /// -->
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="create-meeting page-section">
                <!-- /// Header /// -->
                <div class="margintop20 text-right">
                    <a @click="saveMeeting()" class="btn-rounded btn-auto second-bg">{{ translate('button.save_meeting') }}</a>
                </div>
                <div class="margintop20 text-right">
                    <a @click="editDistributionListModal = (details.distributionList && details.distributionList.key)" class="btn-rounded btn-auto btn-md btn-empty">{{ translate('button.edit_distribution_list') }}</a>
                </div>
                <!-- /// End Header /// -->

                <div class="flex flex-v-center flex-space-between">
                    <div>
                        <h3>{{ translate('message.participants') }}</h3>
                    </div>
                    <!--<div class="buttons">
                        <router-link :to="{name: 'project-organization-edit'}" class="btn-rounded btn-auto btn-md btn-empty">{{ translate('button.edit_distribution_list') }}</router-link>
                    </div>-->
                </div>

                <meeting-participants v-model="selectedParticipants" />
            </div>
        </div>

        <alert-modal v-if="showSaved" @close="showSaved = false" body="message.saved" />
        <alert-modal v-if="showFailed" @close="showFailed = false" body="message.unable_to_save" />
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import InputField from '../../_common/_form-components/InputField';
import SelectField from '../../_common/_form-components/SelectField';
import datepicker from '../../_common/_form-components/Datepicker';
import CalendarIcon from '../../_common/_icons/CalendarIcon';
import MemberSearch from '../../_common/MemberSearch';
import MeetingAttachments from './MeetingAttachments';
import Timepicker from '../../_common/_form-components/Timepicker';
import {createFormData} from '../../../helpers/meeting';
import MultiSelectField from '../../_common/_form-components/MultiSelectField';
import AlertModal from '../../_common/AlertModal.vue';
import Error from '../../_common/_messages/Error.vue';
import Editor from '../../_common/Editor';
import router from '../../../router';
import MeetingParticipants from './MeetingParticipants';
import EditDistributionListModal from '../../_common/EditDistributionListModal';

export default {
    components: {
        InputField,
        SelectField,
        datepicker,
        CalendarIcon,
        MemberSearch,
        MeetingAttachments,
        Timepicker,
        MultiSelectField,
        AlertModal,
        Error,
        Editor,
        MeetingParticipants,
        EditDistributionListModal,
    },
    methods: {
        ...mapActions([
            'getDistributionLists',
            'getMeetingCategories',
            'getInfoStatuses',
            'getInfoCategories',
            'getTodoStatuses',
            'createProjectMeeting',
            'emptyValidationMessages',
            'updateParticipantsPresent',
        ]),
        setMedias(value) {
            this.medias = value;
        },
        addObjective() {
            this.objectives.push({description: ''});
        },
        addAgenda() {
            this.agendas.push({
                topic: '',
                responsible: [],
                startTime: {
                    HH: null,
                    mm: null,
                },
                endTime: {
                    HH: null,
                    mm: null,
                },
            });
        },
        addDecision() {
            this.decisions.push({
                title: '',
                description: '',
                responsible: [],
                dueDate: new Date(),
            });
        },
        addTodo() {
            this.todos.push({
                title: '',
                description: '',
                responsible: [],
                dueDate: new Date(),
                status: {label: this.translate('label.select_status')},
            });
        },
        addInfo() {
            this.infos.push({
                topic: '',
                description: '',
                responsible: [],
                dueDate: new Date(),
                infoStatus: {label: this.translate('label.select_status')},
                infoCategory: {label: this.translate('label.category')},
            });
        },
        saveMeeting() {
            let data = {
                distributionLists: this.details.distributionList
                    ? [this.details.distributionList]
                    : null,
                meetingCategory: this.details.category,
                date: this.schedule.meetingDate,
                start: this.schedule.startTime,
                end: this.schedule.endTime,
                location: this.location,
                objectives: this.objectives,
                agendas: this.agendas,
                medias: this.medias,
                decisions: this.decisions,
                todos: this.todos,
                infos: this.infos,
                meetingParticipants: this.selectedParticipants.map(participant => {
                    return {
                        user: participant.user,
                        isPresent: participant.isPresent,
                        inDistributionList: participant.inDistributionList,
                    };
                }),
            };

            if (data.distributionLists && data.distributionLists.length > 0) {
                data.name = '';
                const length = data.distributionLists.length;
                data.distributionLists.map((item, index) => {
                    data.name += index !== length - 1 ? item.label + '|' : item.label;
                });
            }

            this
                .createProjectMeeting({
                    data: createFormData(data),
                    projectId: this.$route.params.id,
                })
                .then((response) => {
                    if (response.body && response.body.error && response.body.messages) {
                        this.showFailed = true;
                    } else {
                        this.showSaved = true;
                    }
                },
                () => {
                    this.showFailed = true;
                })
            ;
        },
        distributionListUpdated(distributionList) {
            this.details.distributionList = {key: distributionList.id, label: distributionList.name};
        },
    },
    computed: {
        ...mapGetters([
            'distributionListsForSelect',
            'meetingCategoriesForSelect',
            'infoCategoriesForDropdown',
            'todoStatusesForSelect',
            'distributionLists',
            'validationMessages',
            'infoStatusesForDropdown',
        ]),
    },
    watch: {
        'details.distributionList': {
            handler: function(value) {
                this.selectedParticipants = this
                    .selectedParticipants
                    .filter(participant => participant.isPresent || participant.inDistributionList);

                if (!value || !value.key) {
                    return;
                }

                this.distributionLists.forEach(distributionList => {
                    if (value.key !== distributionList.id) {
                        return;
                    }

                    distributionList
                        .users
                        .filter(u => this.selectedParticipants.map(sp => sp.id).indexOf(u.id) === -1)
                        .forEach(user => {
                            let projectUser = user.projectUsers.filter((item) => {
                                return item.project !== this.$route.params.id;
                            });

                            this.selectedParticipants.push({
                                id: user.id,
                                user: user.id,
                                userFullName: user.firstName + ' ' + user.lastName,
                                userAvatar: user.avatarUrl,
                                departments: projectUser.length ? projectUser[0].projectDepartmentNames : [],
                                isPresent: false,
                                inDistributionList: false,
                            });
                        });
                });
            },
            deep: true,
        },
        meeting(value) {
            this.name = this.meeting.name;
            if (this.meeting.distributionLists.length > 0) {
                let item = this.meeting.distributionLists[0];
                this.details.distributionList = {'key': item.id, 'label': item.name};
            }
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
    created() {
        this.getDistributionLists({projectId: this.$route.params.id});
        this.getMeetingCategories();
        this.getTodoStatuses();
        this.getInfoCategories();
        this.getInfoStatuses();
    },
    mounted() {
        this.addObjective();
    },
    beforeDestroy() {
        this.emptyValidationMessages();
    },
    data() {
        return {
            showSaved: false,
            showFailed: false,
            selectedParticipants: [],
            participantsPerPage: 10,
            participantsPages: 0,
            location: '',
            objectives: [],
            agendas: [],
            decisions: [],
            todos: [],
            infos: [],
            medias: [],
            schedule: {
                meetingDate: new Date(),
                startTime: {
                    HH: null,
                    mm: null,
                },
                endTime: {
                    HH: null,
                    mm: null,
                },
            },
            details: {
                distributionList: null,
                category: null,
            },
            editDistributionListModal: null,
        };
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
    input.display-time {
        height: 3.2em;
    }
</style>
