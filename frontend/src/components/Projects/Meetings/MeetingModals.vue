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
                <editor
                    height="200px"
                    :id="`editDecisionObject-${editDecisionObject.id}`"
                    label="placeholder.decision_description"
                    v-model="editDecisionObject.description" />
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
                            <calendar-icon fill="middle-fill"/>
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
            <input-field type="text" v-bind:label="translateText('placeholder.todo_topic')" v-model="editTodoObject.title" v-bind:content="editTodoObject.title" />
            <div class="form-group">
                <editor
                    height="200px"
                    :id="`editTodoObject-${editTodoObject.id}`"
                    label="placeholder.todo_description"
                    v-model="editTodoObject.description" />
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
                            <calendar-icon fill="middle-fill"/>
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
        <modal v-if="showEditInfoModal" @close="showEditInfoModal = false; $emit('input', showEditInfoModal);">
            <p class="modal-title">{{ translateText('message.edit_info') }}</p>
            <input-field
                type="text" v-bind:label="translateText('placeholder.info_topic')"
                v-model="editInfoObject.topic"
                v-bind:content="editInfoObject.topic" />
            <div class="form-group">
                <editor
                    height="200px"
                    :id="`editInfoObject-${editInfoObject.id}`"
                    label="placeholder.info_description"
                    v-model="editInfoObject.description" />
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6">
                        <member-search
                            v-bind:selectedUser="editInfoObject.responsibilityFullName"
                            v-model="editInfoObject.responsibility"
                            :value="editInfoObject.responsibility"
                            v-bind:placeholder="translateText('placeholder.responsible')"
                            v-bind:singleSelect="true" />
                    </div>
                    <div class="col-md-6">
                        <div class="input-holder right">
                            <label class="active">{{ translateText('label.due_date') }}</label>
                            <datepicker v-model="editInfoObject.dueDate" format="dd-MM-yyyy" />
                            <calendar-icon fill="middle-fill"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6">
                        <select-field
                            v-bind:title="translateText('label.select_status')"
                            v-bind:options="infoStatusesForDropdown"
                            v-model="editInfoObject.infoStatus"
                            v-bind:currentOption="editInfoObject.infoStatus" />
                    </div>
                    <div class="col-md-6">
                        <select-field
                            v-bind:title="translateText('label.category')"
                            v-bind:options="infoCategoriesForDropdown"
                            v-model="editInfoObject.infoCategory"
                            v-bind:currentOption="editInfoObject.infoCategory" />
                    </div>
                </div>
            </div>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showEditInfoModal = false; $emit('input', showEditInfoModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
                <a href="javascript:void(0)" @click="saveInfo()" class="btn-rounded">{{ translateText('button.save') }}</a>
            </div>
        </modal>
        <modal v-if="showDeleteInfoModal" @close="showDeleteInfoModal = false; $emit('input', showDeleteInfoModal);">
            <p class="modal-title">{{ translateText('message.delete_info') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showDeleteInfoModal = false; $emit('input', showDeleteInfoModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="deleteMeetingInfo()" class="btn-rounded">{{ translateText('message.yes') }}</a>
            </div>
        </modal>
    </div>

    <!-- /// End Modals /// -->
</template>
<script>
import InputField from '../../_common/_form-components/InputField';
import SelectField from '../../_common/_form-components/SelectField';
import datepicker from '../../_common/_form-components/Datepicker';
import CalendarIcon from '../../_common/_icons/CalendarIcon';
import MemberSearch from '../../_common/MemberSearch';
import {mapGetters, mapActions} from 'vuex';
import VueTimepicker from 'vue2-timepicker';
import moment from 'moment';
import Modal from '../../_common/Modal';
import Editor from '../../_common/Editor';
import MultiSelectField from '../../_common/_form-components/MultiSelectField';

export default {
    props: [
        'editObjectiveModal', 'deleteObjectiveModal', 'objectiveObject',
        'editAgendaModal', 'deleteAgendaModal', 'agendaObject',
        'editDecisionModal', 'deleteDecisionModal', 'decisionObject',
        'editTodoModal', 'deleteTodoModal', 'todoObject',
        'editInfoModal', 'deleteInfoModal', 'infoObject',
    ],
    components: {
        InputField,
        SelectField,
        datepicker,
        CalendarIcon,
        MemberSearch,
        VueTimepicker,
        moment,
        Modal,
        Editor,
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
        editInfoModal(value) {
            this.showEditInfoModal = this.editInfoModal;
        },
        deleteInfoModal(value) {
            this.showDeleteInfoModal = this.deleteInfoModal;
        },
        infoObject(value) {
            this.editInfoObject = this.infoObject;
        },
    },
    methods: {
        ...mapActions([
            'getInfoStatuses', 'getTodoStatuses', 'editMeetingObjective', 'deleteMeetingObjective',
            'editMeetingAgenda', 'deleteMeetingAgenda', 'editDecision', 'deleteDecision', 'editTodo', 'deleteTodo',
            'editInfo', 'deleteInfo', 'getInfoStatuses', 'getInfoCategories',
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
            // this.editDecisionObject.description = this.$refs.editDecisionDescription.getContent();
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
            // this.editTodoObject.description = this.$refs.editTodoDescription.getContent();
            this.editTodoObject.dueDate = moment(this.editTodoObject.dueDate, 'DD-MM-YYYY').format('DD-MM-YYYY');
            this.editTodoObject.status = this.editTodoObject.status.key;
            this.editTodoObject.responsibility = this.editTodoObject.responsibility.length > 0 ? this.editTodoObject.responsibility[0] : null;
            this.editTodo(this.editTodoObject);
            this.showEditTodoModal = false;
            this.$emit('input', this.showEditTodoModal);
        },
        deleteMeetingTodo: function() {
            this.deleteTodo(this.editTodoObject);
            this.showDeleteTodoModal = false;
            this.$emit('input', this.showDeleteTodoModal);
        },
        saveInfo: function() {
            // this.editInfoObject.description = this.$refs.editInfoDescription.getContent();
            this.editInfoObject.dueDate = moment(this.editInfoObject.dueDate, 'DD-MM-YYYY').format('DD-MM-YYYY');
            this.editInfoObject.infoStatus = this.editInfoObject.infoStatus
                ? this.editInfoObject.infoStatus.key
                : null
            ;
            this.editInfoObject.infoCategory = this.editInfoObject.infoCategory
                ? this.editInfoObject.infoCategory.key
                : null
            ;
            this.editInfoObject.responsibility = this.editInfoObject.responsibility.length > 0 ? this.editInfoObject.responsibility[0] : null;

            const data = this.editInfoObject;
            const id = this.editInfoObject.id;

            this.editInfo({id, data});
            this.showEditInfoModal = false;
            this.$emit('input', this.showEditInfoModal);
        },
        deleteMeetingInfo: function() {
            this.deleteInfo(this.editInfoObject);
            this.showDeleteInfoModal = false;
            this.$emit('input', this.showDeleteInfoModal);
        },
    },
    computed: {
        ...mapGetters({
            infoStatusesForSelect: 'infoStatusesForSelect',
            todoStatusesForSelect: 'todoStatusesForSelect',
            infoStatusesForDropdown: 'infoStatusesForDropdown',
            infoCategoriesForDropdown: 'infoCategoriesForDropdown',
        }),
    },
    created() {
        this.getTodoStatuses();
        this.getInfoStatuses();
        this.getInfoCategories();
    },
    data() {
        return {
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
        };
    },
};
</script>
<style scoped lang="scss">
    @import '../../../css/_mixins';
    @import '../../../css/_variables';
</style>
