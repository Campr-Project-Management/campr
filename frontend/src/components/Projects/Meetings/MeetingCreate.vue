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
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{ translate('label.select_date') }}</label>
                                    <date-field v-model="schedule.meetingDate"/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-holder right">
                                    <label class="active">{{ translate('label.start_time') }}</label>
                                    <DataPicker v-model="schedule.startTime"
                                                format="HH:mm"
                                                type="time"
                                                placeholder="HH:mm"
                                                :minute-step="15"
                                                lang="en"
                                    />
                                    <error at-path="start"/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-holder right">
                                    <label class="active">{{ translate('label.finish_time') }}</label>
                                    <DataPicker v-model="schedule.endTime"
                                                  type="time"
                                                  format="HH:mm"
                                                  :minute-step="15"
                                                  placeholder="HH:mm"
                                                  lang="en"
                                      />
                                    <error at-path="end"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Meeting Schedule /// -->

                    <hr class="double">

                    <!-- /// Meeting Location /// -->
                    <h3>{{ translate('message.location') }}</h3>
                    <input-field type="text" :label="translate('placeholder.location')" v-model="location" :content="location" />
                    <error at-path="location"/>
                    <!-- /// End Meeting Location /// -->

                    <hr class="double">

                    <!-- /// Meeting Objectives /// -->
                    <h3>{{ translate('message.objectives') }}</h3>
                    <div v-for="(objective, index) in objectives" :key="`objective-${index}`">
                        <div class="row" v-if="index > 0">
                            <div class="col-xs-offset-10 col-md-2">
                                <div class="flex flex-direction-reverse">
                                    <button
                                            @click="onDeleteObjective(index)"
                                            type="button"
                                            class="btn-icon">
                                        <delete-icon fill="danger-fill"/>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input-field
                                    type="text"
                                    :label="translate('placeholder.objective')"
                                    v-model="objective.description"
                                    :content="objective.description"/>
                            <error
                                    at-path="meetingObjectives[$context.index].description"
                                    :context="{index: index}"/>
                            <hr>
                        </div>
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a @click="addObjective()" class="btn-rounded btn-auto">{{ translate('message.add_new_objective') }} +</a>
                    </div>
                    <!-- /// End Meeting Objectives /// -->

                    <hr class="double">

                    <!-- /// Meeting Agenda /// -->
                    <h3>{{ translate('message.agenda') }}</h3>
                    <div v-for="(agenda, index) in agendas" :key="`agenda-${index}`">
                        <div class="row">
                            <div class="col-xs-offset-10 col-md-2">
                                <div class="flex flex-direction-reverse">
                                    <button
                                            @click="onDeleteAgenda(index)"
                                            type="button"
                                            class="btn-icon">
                                        <delete-icon fill="danger-fill"/>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input-field type="text" :label="translate('placeholder.topic')" v-model="agenda.topic" :content="agenda.topic" />
                            <error
                                    at-path="meetingAgendas[$context.index].topic"
                                    :context="{index: index}"/>
                        </div>
                        <div class="row">
                            <div class="form-group form-group">
                                <div class="col-md-6">
                                    <member-search singleSelect="false" v-model="agenda.responsible" :placeholder="translate('placeholder.responsible')"></member-search>
                                    <error
                                            at-path="meetingAgendas[$context.index].responsibility"
                                            :context="{index: index}"/>
                                </div>
                                <div class="col-md-6">
                                    <input-field type="number" v-bind:label="`${translate('placeholder.duration')} (${translate('placeholder.minutes')})`" v-model="agenda.duration" v-bind:content="agenda.duration" />
                                    <error
                                            at-path="meetingAgendas[$context.index].duration"
                                            :context="{index: index}"/>
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
                    <h3>{{ translate('message.documents') }}</h3>
                    <attachments
                            v-model="medias"
                            label="button.add_document"
                            :max-file-size="projectMaxUploadFileSize"
                            @uploading="onUploading"/>
                    <!-- /// End Meeting Documents /// -->

                    <hr class="double">

                    <!-- /// Decisions /// -->
                    <h3>{{ translate('message.decisions') }}</h3>

                    <div class="entries-wrapper" v-if="openDecisions">
                        <div class="entry" v-for="decision in openDecisions">
                            <div class="entry-header flex flex-space-between flex-v-center">
                                <div class="entry-title">
                                    <h4>{{ decision.title }}</h4>
                                    | {{ translate('message.due_date') }}: <b>{{ decision.dueDate | moment('DD.MM.YYYY') }}</b>
                                    | {{ translate('message.status') }}:
                                    <b v-if="decision.isDone" class="success-color">{{ translate('choices.done') }}</b>
                                    <b v-else class="danger-color">{{ translate('choices.undone') }}</b>
                                </div>
                            </div>
                            <div class="entry-responsible flex flex-v-center" v-if="decision.responsibility">
                                <user-avatar
                                    :name="decision.responsibilityFullName"
                                    :url="decision.responsibilityAvatar"/>
                                <div>
                                    {{ translate('message.responsible') }}:
                                    <b>{{ decision.responsibilityFullName }}</b>
                                </div>
                            </div>
                            <div class="entry-body" v-html="decision.description"></div>
                            <div class="attachments">
                                <template v-for="(media, index) in decision.medias">
                                    <div
                                        class="attachment"
                                        v-if="media"
                                        :key="index">
                                        <view-icon/>
                                        <span class="attachment-name">
                                            <a @click="getMediaFile(media)" v-if="media.id">{{ media.name }}</a>
                                            <span v-else>{{ media.name }}</span>
                                        </span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div v-for="(decision, index) in decisions" :key="`decision-${index}`">
                        <div class="row">
                            <div class="col-xs-offset-10 col-md-2">
                                <div class="flex flex-direction-reverse">
                                    <button
                                            @click="onDeleteDecision(index)"
                                            type="button"
                                            class="btn-icon">
                                        <delete-icon fill="danger-fill"/>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <meeting-decision-form
                            :key="index"
                            v-model="decisions[index]"
                            @uploading="onDecisionUploading"
                            :error-messages="decisionErrors(index)"/>
                        <hr>
                    </div>

                    <div class="flex flex-direction-reverse">
                        <a @click="addDecision()" class="btn-rounded btn-auto">{{ translate('message.add_new_decision') }} +</a>
                    </div>
                    <!-- /// End Decisions /// -->

                    <hr class="double">

                    <!-- /// ToDos /// -->
                    <h3>{{ translate('message.todos') }}</h3>

                    <div class="entries-wrapper" v-if="openTodos">
                        <div class="entry" v-for="todo in openTodos">
                            <div class="entry-header flex flex-space-between flex-v-center">
                                <div class="entry-title">
                                    <h4>{{ todo.title }}</h4>  | {{ translate('message.due_date') }}: <b>{{ todo.dueDate | moment('DD.MM.YYYY') }}</b> | {{ translate('message.status') }}: <b v-if="todo.status">{{ translate(todo.statusName) }}</b><b v-else>-</b>
                                </div>
                            </div>
                            <div class="entry-responsible flex flex-v-center">
                                <user-avatar
                                    size="small"
                                    :url="todo.responsibilityAvatar"
                                    :name="todo.responsibilityFullName"/>
                                <div>
                                    {{ translate('message.responsible') }}:
                                    <b>{{ todo.responsibilityFullName }}</b>
                                </div>
                            </div>
                            <div class="entry-body" v-html="todo.description"></div>
                        </div>
                    </div>

                    <div v-for="(todo, index) in todos" :key="`todo-${index}`">
                        <div class="row">
                            <div class="col-xs-offset-10 col-md-2">
                                <div class="flex flex-direction-reverse">
                                    <button
                                            @click="onDeleteTodo(index)"
                                            type="button"
                                            class="btn-icon">
                                        <delete-icon fill="danger-fill"/>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input-field type="text" :label="translate('placeholder.topic')" v-model="todo.title" :content="todo.title" />
                            <error
                                    v-if="validationMessages.todos && validationMessages.todos[index] && validationMessages.todos[index].title"
                                    :key="`todo-title-${index}`"
                                    :message="validationMessages.todos[index].title"/>
                        <div class="form-group">
                            <editor
                                :id="`todo-description-${index}`"
                                height="200px"
                                label="placeholder.todo_description"
                                v-model="todo.description" />
                            <error
                                    at-path="todos[$context.index].description"
                                    :context="{index: index}"/>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <member-search singleSelect="false" v-model="todo.responsible" :placeholder="translate('placeholder.responsible')"></member-search>
                                    <error
                                            at-path="todos[$context.index].responsibility"
                                            :context="{index: index}"/>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-holder right">
                                        <label class="active">{{ translate('label.due_date') }}</label>
                                        <date-field v-model="todo.dueDate"/>
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
                                    <error
                                            at-path="todos[$context.index].status"
                                            :context="{index: index}"/>
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

                    <div class="entries-wrapper" v-if="openInfos">
                        <div class="entry" v-for="info in openInfos">
                            <div class="entry-header flex flex-space-between flex-v-center">
                                <div class="entry-title">
                                    <h4>{{ info.topic }}</h4> |
                                    {{ translate('message.expiry_date') }}: <b :class="{'danger-color': info.isExpired}">{{ info.expiresAt | date }}</b> |
                                    {{ translate('message.category') }}: <b v-if="info.infoCategory">{{ translate(info.infoCategoryName) }}</b><b v-else>-</b>
                                </div>
                            </div>
                            <div class="entry-responsible flex flex-v-center">
                                <user-avatar
                                    size="small"
                                    :name="info.responsibilityFullName"
                                    :url="info.responsibilityAvatar"/>
                                <div>
                                    {{ translate('message.responsible') }}:
                                    <b>{{ info.responsibilityFullName }}</b>
                                </div>
                            </div>
                            <div class="entry-body" v-html="info.description"></div>
                        </div>
                    </div>

                    <div v-for="(info, index) in infos" :key="`info-${index}`">
                        <div class="row">
                            <div class="col-xs-offset-10 col-md-2">
                                <div class="flex flex-direction-reverse">
                                    <button
                                            @click="onDeleteInfo(index)"
                                            type="button"
                                            class="btn-icon">
                                        <delete-icon fill="danger-fill"/>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input-field type="text" :label="translate('placeholder.topic')" v-model="info.topic" :content="info.topic" />
                        <error
                                at-path="infos[$context.index].topic"
                                :context="{index: index}"/>
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
                                    <member-search singleSelect="false" v-model="info.responsible" :placeholder="translate('placeholder.responsible')"></member-search>
                                    <error
                                            at-path="infos[$context.index].responsibility"
                                            :context="{index: index}"/>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-holder right">
                                        <label class="active">{{ translate('label.expiry_date') }}</label>
                                        <date-field v-model="info.expiresAt"/>
                                    </div>
                                    <error
                                            at-path="infos[$context.index].expiresAt"
                                            :context="{index: index}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group last-form-group">
                                <div class="col-md-6">
                                    <select-field
                                        :title="'label.category'"
                                        :options="infoCategoriesForDropdown"
                                        v-model="info.infoCategory"
                                        v-bind:currentOption="info.infoCategory" />
                                    <error
                                            at-path="infos[$context.index].infoCategory"
                                            :context="{index: index}"/>
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
                        <a
                                v-if="canSave"
                                @click="saveMeeting()"
                                class="btn-rounded btn-auto btn-auto second-bg">{{ translate('button.save_meeting') }}</a>
                    </div>
                    <!-- /// End Actions /// -->
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="create-meeting page-section">
                <!-- /// Header /// -->
                <div class="margintop20 text-right">
                    <a
                            v-if="canSave"
                            @click="saveMeeting()"
                            class="btn-rounded btn-auto second-bg">{{ translate('button.save_meeting') }}</a>
                </div>
                <div class="margintop20 text-right">
                    <a @click="editDistributionListModal = (details.distributionList && details.distributionList.key)" class="btn-rounded btn-auto btn-md btn-empty" v-if="showEditDistributionListBtn">{{ translate('button.edit_distribution_list') }}</a>
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
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import InputField from '../../_common/_form-components/InputField';
import SelectField from '../../_common/_form-components/SelectField';
import DeleteIcon from '../../_common/_icons/DeleteIcon';
import MemberSearch from '../../_common/MemberSearch';
import {createFormData} from '../../../helpers/meeting';
import MultiSelectField from '../../_common/_form-components/MultiSelectField';
import Error from '../../_common/_messages/Error.vue';
import Editor from '../../_common/Editor';
import router from '../../../router';
import MeetingParticipants from './MeetingParticipants';
import EditDistributionListModal from '../../_common/EditDistributionListModal';
import MeetingDecisionForm from './Form/DecisionForm';
import DateField from '../../_common/_form-components/DateField';
import Attachments from '../../_common/Attachments';
import UserAvatar from '../../_common/UserAvatar';
import ViewIcon from '../../_common/_icons/ViewIcon';
import DataPicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import '../../../css/vue-dat-time-picker-custom.css';
import moment from 'moment';
import {replaceBadInputs} from '../../../util/functions';

export default {
    components: {
        Attachments,
        DateField,
        MeetingDecisionForm,
        DeleteIcon,
        InputField,
        SelectField,
        MemberSearch,
        MultiSelectField,
        Error,
        Editor,
        MeetingParticipants,
        EditDistributionListModal,
        UserAvatar,
        ViewIcon,
        DataPicker,
    },
    methods: {
        getMediaFile(media) {
            if (!media.id) {
                return;
            }

            const url = Routing.generate('app_api_media_download', {id: media.id});
            Vue.http.get(url, {responseType: 'blob'})
                .then((response) => {
                    if (response.status !== 200) {
                        return;
                    }

                    let options = {};
                    if (response.headers && response.headers.map && response.headers.map['content-type']) {
                        options.type = response.headers.map['content-type'][0];
                    }

                    let blob = new Blob([response.body], options);
                    let a = document.createElement('a');
                    a.href = window.URL.createObjectURL(blob);
                    a.download = media.originalName;
                    document.body.appendChild(a);
                    a.click();

                    setTimeout(() => {
                        document.body.removeChild(a);
                        window.URL.revokeObjectURL(url);
                    }, 100);
                });
        },
        ...mapActions([
            'getDistributionLists',
            'getMeetingCategories',
            'getInfoCategories',
            'getTodoStatuses',
            'createProjectMeeting',
            'emptyValidationMessages',
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
                duration: 0,
            });
        },
        onDeleteAgenda(index) {
            this.agendas = [
                ...this.agendas.slice(0, index),
                ...this.agendas.slice(index + 1),
            ];
        },
        onDeleteObjective(index) {
            this.$delete(this.objectives, index);
        },
        addDecision() {
            this.decisions.push({
                title: '',
                description: '',
                responsibility: null,
                done: false,
                dueDate: new Date(),
                medias: [],
            });
        },
        onDeleteDecision(index) {
            this.decisions = [
                ...this.decisions.slice(0, index),
                ...this.decisions.slice(index + 1),
            ];
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
        onDeleteTodo(index) {
            this.todos = [
                ...this.todos.slice(0, index),
                ...this.todos.slice(index + 1),
            ];
        },
        addInfo() {
            this.infos.push({
                topic: '',
                description: '',
                responsible: [],
                expiresAt: new Date(),
                infoCategory: {label: this.translate('label.category')},
            });
        },
        onDeleteInfo(index) {
            this.infos = [
                ...this.infos.slice(0, index),
                ...this.infos.slice(index + 1),
            ];
        },
        saveMeeting() {
            if (!this.isSaving) {
                let data = {
                    distributionLists: this.details.distributionList
                        ? [this.details.distributionList]
                        : null,
                    meetingCategory: this.details.category,
                    date: this.schedule.meetingDate,
                    start: {
                        HH: moment(this.schedule.startTime).format('HH'),
                        mm: moment(this.schedule.startTime).format('mm'),
                    },
                    end: {
                        HH: moment(this.schedule.endTime).format('HH'),
                        mm: moment(this.schedule.endTime).format('mm'),
                    },
                    location: this.location,
                    objectives: this.objectives,
                    agendas: this.agendas,
                    medias: this.medias,
                    decisions: this.decisions.map(decision => {
                        return Object.assign({}, decision, {
                            distributionList: this.details.distributionList,
                        });
                    }),
                    todos: this.todos.map(todo => {
                        return Object.assign({}, todo, {
                            distributionList: this.details.distributionList,
                        });
                    }),
                    infos: this.infos.map((info) => {
                        return Object.assign({}, info, {
                            expiresAt: this.$formatToSQLDate(info.expiresAt),
                            distributionList: this.details.distributionList,
                        });
                    }),
                    meetingParticipants: this.selectedParticipants.map(participant => {
                        return {
                            user: participant.user,
                            isPresent: participant.isPresent,
                            inDistributionList: participant.inDistributionList,
                        };
                    }),
                };

                this.isSaving = true;

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
                        this.isSaving = false;
                        if (response.body && response.body.error && response.body.messages) {
                            this.$flashError('message.unable_to_save');
                        } else {
                            this.showSaved = true;
                            this.$flashSuccess('message.saved');
                        }
                    },
                    () => {
                        this.isSaving = false;
                        this.$flashError('message.unable_to_save');
                    })
                ;
            }
        },
        distributionListUpdated(distributionList) {
            this.details.distributionList = {key: distributionList.id, label: distributionList.name};
        },
        onUploading(uploading) {
            this.isUploading = uploading;
        },
        onDecisionUploading(uploading) {
            this.isDecisionUploading = uploading;
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
            'validationMessagesFor',
            'decisionsStatusesForSelect',
            'projectMaxUploadFileSize',
            'project',
        ]),
        canSave() {
            return !this.isUploading && !this.isDecisionUploading;
        },
        mediasValidationMessages() {
            let messages = this.validationMessagesFor('medias');
            let out = [];

            Object.keys(messages).forEach((index) => {
                out[index] = messages[index].file;
            });

            return out;
        },
        decisionsStatusesOptions() {
            return this.decisionsStatusesForSelect.map((option) => {
                return Object.assign({}, option, {label: this.translate(option.label)});
            });
        },
        decisionErrors() {
            return (index) => {
                let messages = this.validationMessagesFor('decisions');
                if (!messages) {
                    return {};
                }

                return messages[index] ? messages[index] : {};
            };
        },
        openInfos() {
            const dl = this
                .distributionLists
                .filter(dl => {
                    return dl.id === (this.details.distributionList && this.details.distributionList.key);
                })
            ;

            return dl && dl.length
                ? dl[0].meetings.map(m => m.openInfos ? m.openInfos : null).flat()
                : []
            ;
        },
        openTodos() {
            const dl = this
                .distributionLists
                .filter(dl => {
                    return dl.id === (this.details.distributionList && this.details.distributionList.key);
                })
            ;

            return dl && dl.length
                ? dl[0].meetings.map(m => m.openTodos ? m.openTodos : null).flat()
                : []
            ;
        },
        openDecisions() {
            const dl = this
                .distributionLists
                .filter(dl => {
                    return dl.id === (this.details.distributionList && this.details.distributionList.key);
                })
            ;

            return dl && dl.length
                ? dl[0].meetings.map(m => m.openDecisions ? m.openDecisions : null).flat()
                : []
            ;
        },
    },
    watch: {
        'details.distributionList': {
            handler: function(value) {
                this.selectedParticipants = [];
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

                this.showEditDistributionListBtn = true;
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
            if (value === true) {
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
    },
    mounted() {
        this.addObjective();
        $(document).ready(
            function() {
                 // Apply input rules as the user types or pastes input
                $('.mx-input').keyup(function() {
                    let val = this.value;
                    let lastLength;
                    do {
                        // Loop over the input to apply rules repeately to pasted inputs
                        lastLength = val.length;
                        val = replaceBadInputs(val);
                    } while(val.length > 0 && lastLength !== val.length);
                    this.value = val;
                    if(this.value.length == 2) {
                        this.value += ':';
                    };
                });

                // Check the final result when the input has lost focus
                $('.mx-input').blur(function() {
                    let val = this.value;
                    val = (/^(([01][0-9]|2[0-3])h)|(([01][0-9]|2[0-3]):[0-5][0-9])$/.test(val) ? val : '');
                    this.value = val;
                });
            }
        );
    },

    beforeDestroy() {
        this.emptyValidationMessages();
    },
    data() {
        return {
            showSaved: false,
            selectedParticipants: [],
            participantsPerPage: 10,
            participantsPages: 0,
            showEditDistributionListBtn: false,
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
            isSaving: false,
            isUploading: false,
            isDecisionUploading: false,
            // openTodos: [],
            // openInfos: [],
            // openDecisions: [],
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
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
