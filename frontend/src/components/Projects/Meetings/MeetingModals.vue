<template>
    <div>
        <!-- /// Modals /// -->
        <!-- /// Objective /// -->
        <modal v-if="showEditObjectiveModal" @close="showEditObjectiveModal = false; $emit('input', showEditObjectiveModal);">
            <p class="modal-title">{{ translateText('message.edit_objective') }}</p>
            <div class="row">
                <div class="form-group">
                    <input-field type="text" v-bind:label="translateText('placeholder.objective')" v-model="editObjectiveObject.description" v-bind:content="editObjectiveObject.description" />
                </div>
            </div>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showEditObjectiveModal = false; $emit('input', showEditObjectiveModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
                <a href="javascript:void(0)" @click="saveObjective()" class="btn-rounded">{{ translateText('button.save') }}</a>
            </div>
        </modal>
        <modal v-if="showDeleteObjectiveModal" @close="showDeleteObjectiveModal = false; $emit('input', showDeleteObjectiveModal);">
            <p class="modal-title">{{ translateText('message.delete_objective') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showDeleteObjectiveModal = false; $emit('input', showDeleteObjectiveModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="deleteObjective()" class="btn-rounded">{{ translateText('message.yes') }}</a>
            </div>
        </modal>

        <!-- /// Agenda /// -->
        <modal v-if="showEditAgendaModal" @close="showEditAgendaModal = false; $emit('input', showEditAgendaModal);">
            <p class="modal-title">{{ translateText('message.edit_agenda') }}</p>
            <div class="row">
                <div class="form-group">
                    <input-field type="text" v-bind:label="translateText('placeholder.topic')" v-model="editAgendaObject.topic" v-bind:content="editAgendaObject.topic" />
                </div>
                <div class="row">
                    <div class="form-group form-group">
                        <div class="col-md-4">
                            <member-search v-model="editAgendaObject.responsible" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
                        </div>
                        <div class="col-md-4">
                            <div class="input-holder right">
                                <label class="active">{{ translateText('label.start_time') }}</label>
                                <vue-timepicker v-model="editAgendaObject.start" hide-clear-button></vue-timepicker>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-holder right">
                                <label class="active">{{ translateText('label.finish_time') }}</label>
                                <vue-timepicker v-model="editAgendaObject.end" hide-clear-button></vue-timepicker>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showEditAgendaModal = false; $emit('input', showEditAgendaModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
                <a href="javascript:void(0)" @click="saveAgenda()" class="btn-rounded">{{ translateText('button.save') }}</a>
            </div>
        </modal>
        <modal v-if="showDeleteAgendaModal" @close="showDeleteAgendaModal = false; $emit('input', showDeleteAgendaModal);">
            <p class="modal-title">{{ translateText('message.delete_agenda') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showDeleteAgendaModal = false; $emit('input', showDeleteAgendaModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="deleteAgenda()" class="btn-rounded">{{ translateText('message.yes') }}</a>
            </div>
        </modal>

        <!-- /// Decisions /// -->
        <modal v-if="showEditDecisionModal" @close="showEditDecisionModal = false; $emit('input', showEditDecisionModal);">
            <p class="modal-title">{{ translateText('message.edit_decision') }}</p>
            <input-field type="text" v-bind:label="translateText('placeholder.decision_title')" v-model="editDecisionObject.title" v-bind:content="editDecisionObject.title" />
            <div class="form-group">
                <div class="vueditor-holder">
                    <div class="vueditor-header">{{ translateText('placeholder.decision_description') }}</div>
                    <Vueditor ref="editDecisionDescription" />
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6">
                        <member-search v-bind:selectedUser="editDecisionObject.responsibilityFullName" v-model="editDecisionObject.responsibility" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
                    </div>
                    <div class="col-md-6">
                        <div class="input-holder right">
                            <label class="active">{{ translateText('label.due_date') }}</label>
                            <datepicker v-model="editDecisionObject.dueDate" format="dd-MM-yyyy" />
                            <calendar-icon fill="middle-fill" stroke="middle-stroke" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showEditDecisionModal = false; $emit('input', showEditDecisionModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
                <a href="javascript:void(0)" @click="saveDecision()" class="btn-rounded">{{ translateText('button.save') }}</a>
            </div>
        </modal>
        <modal v-if="showDeleteDecisionModal" @close="showDeleteDecisionModal = false; $emit('input', showDeleteDecisionModal);">
            <p class="modal-title">{{ translateText('message.delete_decision') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showDeleteDecisionModal = false; $emit('input', showDeleteDecisionModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="deleteMeetingDecision()" class="btn-rounded">{{ translateText('message.yes') }}</a>
            </div>
        </modal>

        <!-- /// TODOS /// -->
        <modal v-if="showEditTodoModal" @close="showEditTodoModal = false; $emit('input', showEditTodoModal);">
            <p class="modal-title">{{ translateText('message.edit_todo') }}</p>
            <input-field type="text" v-bind:label="translateText('placeholder.todo_title')" v-model="editTodoObject.title" v-bind:content="editTodoObject.title" />
            <div class="form-group">
                <div class="vueditor-holder">
                    <div class="vueditor-header">{{ translateText('placeholder.todo_description') }}</div>
                    <Vueditor ref="editTodoDescription" />
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6">
                        <member-search  v-bind:selectedUser="editTodoObject.responsibilityFullName" v-model="editTodoObject.responsibility" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
                    </div>
                    <div class="col-md-6">
                        <div class="input-holder right">
                            <label class="active">{{ translateText('label.due_date') }}</label>
                            <datepicker v-model="editTodoObject.dueDate" format="dd-MM-yyyy" />
                            <calendar-icon fill="middle-fill" stroke="middle-stroke" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6">
                        <select-field
                                v-bind:title="translateText('label.select_status')"
                                v-bind:options="todoStatusesForSelect"
                                v-model="editTodoObject.status"
                                v-bind:currentOption="editTodoObject.status" />
                    </div>
                </div>
            </div>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showEditTodoModal = false; $emit('input', showEditTodoModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
                <a href="javascript:void(0)" @click="saveTodo()" class="btn-rounded">{{ translateText('button.save') }}</a>
            </div>
        </modal>
        <modal v-if="showDeleteTodoModal" @close="showDeleteTodoModal = false; $emit('input', showDeleteTodoModal);">
            <p class="modal-title">{{ translateText('message.delete_todo') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showDeleteTodoModal = false; $emit('input', showDeleteTodoModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="deleteMeetingTodo()" class="btn-rounded">{{ translateText('message.yes') }}</a>
            </div>
        </modal>

        <!-- /// INFOS /// -->
        <modal v-if="showEditNoteModal" @close="showEditNoteModal = false; $emit('input', showEditNoteModal);">
            <p class="modal-title">{{ translateText('message.edit_info') }}</p>
            <input-field type="text" v-bind:label="translateText('placeholder.info_title')" v-model="editNoteObject.title" v-bind:content="editNoteObject.title" />
            <div class="form-group">
                <div class="vueditor-holder">
                    <div class="vueditor-header">{{ translateText('placeholder.info_description') }}</div>
                    <Vueditor ref="editNoteDescription" />
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6">
                        <member-search v-bind:selectedUser="editNoteObject.responsibilityFullName" v-model="editNoteObject.responsibility" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
                    </div>
                    <div class="col-md-6">
                        <div class="input-holder right">
                            <label class="active">{{ translateText('label.due_date') }}</label>
                            <datepicker v-model="editNoteObject.dueDate" format="dd-MM-yyyy" />
                            <calendar-icon fill="middle-fill" stroke="middle-stroke" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6">
                        <select-field
                                v-bind:title="translateText('label.select_status')"
                                v-bind:options="noteStatusesForSelect"
                                v-model="editNoteObject.status"
                                v-bind:currentOption="editNoteObject.status" />
                    </div>
                </div>
            </div>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showEditNoteModal = false; $emit('input', showEditNoteModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
                <a href="javascript:void(0)" @click="saveNote()" class="btn-rounded">{{ translateText('button.save') }}</a>
            </div>
        </modal>
        <modal v-if="showDeleteNoteModal" @close="showDeleteNoteModal = false; $emit('input', showDeleteNoteModal);">
            <p class="modal-title">{{ translateText('message.delete_info') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showDeleteNoteModal = false; $emit('input', showDeleteNoteModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="deleteMeetingNote()" class="btn-rounded">{{ translateText('message.yes') }}</a>
            </div>
        </modal>
    </div>

    <!-- /// End Modals /// -->
</template>
<script>
import InputField from '../../_common/_form-components/InputField';
import SelectField from '../../_common/_form-components/SelectField';
import datepicker from 'vuejs-datepicker';
import CalendarIcon from '../../_common/_icons/CalendarIcon';
import MemberSearch from '../../_common/MemberSearch';
import VueScrollbar from 'vue2-scrollbar';
import {mapGetters, mapActions} from 'vuex';
import VueTimepicker from 'vue2-timepicker';
import moment from 'moment';
import Modal from '../../_common/Modal';
import MultiSelectField from '../../_common/_form-components/MultiSelectField';

export default {
    props: [
        'editObjectiveModal', 'deleteObjectiveModal', 'objectiveObject',
        'editAgendaModal', 'deleteAgendaModal', 'agendaObject',
        'editDecisionModal', 'deleteDecisionModal', 'decisionObject',
        'editTodoModal', 'deleteTodoModal', 'todoObject',
        'editNoteModal', 'deleteNoteModal', 'noteObject',
    ],
    components: {
        InputField,
        SelectField,
        datepicker,
        CalendarIcon,
        MemberSearch,
        VueScrollbar,
        VueTimepicker,
        moment,
        Modal,
        MultiSelectField,
    },
    watch: {
        editObjectiveModal(value) {
            this.showEditObjectiveModal = this.editObjectiveModal;
        },
        deleteObjectiveModal(value) {
            this.showDeleteObjectiveModal = this.deleteObjectiveModal;
        },
        objectiveObject(value) {
            this.editObjectiveObject = this.objectiveObject;
        },
        editAgendaModal(value) {
            this.showEditAgendaModal = this.editAgendaModal;
        },
        deleteAgendaModal(value) {
            this.showDeleteAgendaModal = this.deleteAgendaModal;
        },
        agendaObject(value) {
            this.editAgendaObject = this.agendaObject;
        },
        editDecisionModal(value) {
            this.showEditDecisionModal = this.editDecisionModal;
        },
        deleteDecisionModal(value) {
            this.showDeleteDecisionModal = this.deleteDecisionModal;
        },
        decisionObject(value) {
            this.editDecisionObject = this.decisionObject;
        },
        editTodoModal(value) {
            this.showEditTodoModal = this.editTodoModal;
        },
        deleteTodoModal(value) {
            this.showDeleteTodoModal = this.deleteTodoModal;
        },
        todoObject(value) {
            this.editTodoObject = this.todoObject;
        },
        editNoteModal(value) {
            this.showEditNoteModal = this.editNoteModal;
        },
        deleteNoteModal(value) {
            this.showDeleteNoteModal = this.deleteNoteModal;
        },
        noteObject(value) {
            this.editNoteObject = this.noteObject;
        },
    },
    methods: {
        ...mapActions([
            'getNoteStatuses', 'getTodoStatuses', 'editMeetingObjective', 'deleteMeetingObjective',
            'editMeetingAgenda', 'deleteMeetingAgenda', 'editDecision', 'deleteDecision', 'editTodo', 'deleteTodo',
            'editNote', 'deleteNote',
        ]),
        translateText: function(text) {
            return this.translate(text);
        },
        saveObjective: function() {
            this.editMeetingObjective(this.editObjectiveObject);
            this.showEditObjectiveModal = false;
            this.$emit('input', this.showEditObjectiveModal);
        },
        deleteObjective: function() {
            this.deleteMeetingObjective(this.editObjectiveObject);
            this.showDeleteObjectiveModal = false;
            this.$emit('input', this.showDeleteObjectiveModal);
        },
        saveAgenda: function() {
            this.editAgendaObject.start = this.editAgendaObject.start.HH + ':' + this.editAgendaObject.start.mm,
            this.editAgendaObject.end = this.editAgendaObject.end.HH + ':' + this.editAgendaObject.end.mm,
            this.editMeetingAgenda(this.editAgendaObject);
            this.showEditAgendaModal = false;
            this.$emit('input', this.showEditAgendaModal);
        },
        deleteAgenda: function() {
            this.deleteMeetingAgenda(this.editAgendaObject);
            this.showDeleteAgendaModal = false;
            this.$emit('input', this.showDeleteAgendaModal);
        },
        saveDecision: function() {
            this.editDecisionObject.description = this.$refs.editDecisionDescription.getContent();
            this.editDecisionObject.dueDate = moment(this.editDecisionObject.dueDate, 'DD-MM-YYYY').format('DD-MM-YYYY');
            this.editDecisionObject.status = this.editDecisionObject.status.key;
            this.editDecisionObject.responsibility = this.editDecisionObject.responsibility.length > 0 ? this.editDecisionObject.responsibility[0] : null,
            this.editDecision(this.editDecisionObject);
            this.showEditDecisionModal = false;
            this.$emit('input', this.showEditDecisionModal);
        },
        deleteMeetingDecision: function() {
            this.deleteDecision(this.editDecisionObject);
            this.showDeleteDecisionModal = false;
            this.$emit('input', this.showDeleteDecisionModal);
        },
        saveTodo: function() {
            this.editTodoObject.description = this.$refs.editTodoDescription.getContent();
            this.editTodoObject.dueDate = moment(this.editTodoObject.dueDate, 'DD-MM-YYYY').format('DD-MM-YYYY');
            this.editTodoObject.status = this.editTodoObject.status.key;
            this.editTodoObject.responsibility = this.editTodoObject.responsibility.length > 0 ? this.editTodoObject.responsibility[0] : null,
            this.editTodo(this.editTodoObject);
            this.showEditTodoModal = false;
            this.$emit('input', this.showEditTodoModal);
        },
        deleteMeetingTodo: function() {
            this.deleteTodo(this.editTodoObject);
            this.showDeleteTodoModal = false;
            this.$emit('input', this.showDeleteTodoModal);
        },
        saveNote: function() {
            this.editNoteObject.description = this.$refs.editNoteDescription.getContent();
            this.editNoteObject.dueDate = moment(this.editNoteObject.dueDate, 'DD-MM-YYYY').format('DD-MM-YYYY');
            this.editNoteObject.status = this.editNoteObject.status.key;
            this.editNoteObject.responsibility = this.editNoteObject.responsibility.length > 0 ? this.editNoteObject.responsibility[0] : null,
            this.editNote(this.editNoteObject);
            this.showEditNoteModal = false;
            this.$emit('input', this.showEditNoteModal);
        },
        deleteMeetingNote: function() {
            this.deleteNote(this.editNoteObject);
            this.showDeleteNoteModal = false;
            this.$emit('input', this.showDeleteNoteModal);
        },
    },
    computed: {
        ...mapGetters({
            noteStatusesForSelect: 'noteStatusesForSelect',
            todoStatusesForSelect: 'todoStatusesForSelect',
        }),
    },
    created() {
        this.getTodoStatuses();
        this.getNoteStatuses();
    },
    data() {
        return {
            decisionDescription: '',
            editDecisionDescription: '',
            todoDescription: '',
            editTodoDescription: '',
            noteDescription: '',
            editNoteDescription: '',
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
        };
    },
};
</script>
<style scoped lang="scss">
    @import '../../../css/_mixins';
    @import '../../../css/_variables';
</style>
