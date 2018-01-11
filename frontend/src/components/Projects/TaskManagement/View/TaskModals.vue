<template>
    <div>
        <!-- /// External costs Modal /// -->
        <modal v-if="showEditExternalCostModal" @close="showEditExternalCostModal = false; $emit('input', showEditExternalCostModal);">
            <div>
                <p class="modal-title" v-if="!isEditExternalCost">{{ translateText('message.add_external_costs') }}</p>
                <p class="modal-title" v-if="isEditExternalCost">{{ translateText('message.edit_external_costs') }}</p>
                <div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-10">
                                <input-field
                                    type="text"
                                    v-bind:label="translateText('label.cost_description')"
                                    v-model="editExternalCostObj.name"
                                    v-bind:content="editExternalCostObj.name" />
                            </div>
                            <div class="col-md-2">
                                <input-field
                                    type="text"
                                    v-bind:label="translateText('label.qty')"
                                    v-model="editExternalCostObj.quantity"
                                    v-bind:content="editExternalCostObj.quantity" />
                            </div>
                        </div>
                    </div>
                    <div class="row error-row">
                        <div class="form-group last-form-group">
                            <div class="col-md-10">
                                <error
                                    v-if="validationMessages.name && validationMessages.name.length"
                                    v-for="(message, index) in validationMessages.name"
                                    :key="index"
                                    :message="message"/>
                            </div>
                            <div class="col-md-2">
                                <error
                                    v-if="validationMessages.quantity && validationMessages.quantity.length"
                                    v-for="(message, index) in validationMessages.quantity"
                                    :key="index"
                                    :message="message"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-8">
                                <radio-field
                                    name="units"
                                    v-bind:options="projectUnitsForSelect"
                                    v-bind:currentOption="editExternalCostObj.selectedUnit"
                                    v-model="editExternalCostObj.selectedUnit" />
                            </div>
                            <div class="col-md-2">
                                <input-field
                                    type="text"
                                    v-bind:label="translateText('label.custom')"
                                    v-model="editExternalCostObj.customUnit"
                                    v-bind:content="editExternalCostObj.customUnit"
                                    v-bind:disabled="editExternalCostObj.selectedUnit !== 'custom'" />
                            </div>
                            <div class="col-md-2">
                                <input-field
                                    type="text"
                                    v-bind:label="translateText('label.external_cost_unit_rate')"
                                    v-model="editExternalCostObj.rate"
                                    v-bind:content="editExternalCostObj.rate" />
                            </div>
                        </div>
                        <br>
                    </div>
                    <div class="row error-row">
                        <div class="form-group last-form-group">
                            <div class="col-md-8">
                            </div>
                            <div class="col-md-2">
                                <error
                                    v-if="validationMessages.customUnit && validationMessages.customUnit.length"
                                    v-for="(message, index) in validationMessages.customUnit"
                                    :key="index"
                                    :message="message"/>
                            </div>
                            <div class="col-md-2">
                                <error
                                    v-if="validationMessages.rate && validationMessages.rate.length"
                                    v-for="(message, index) in validationMessages.rate"
                                    :key="index"
                                    :message="message"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-space-between">
                    <a href="javascript:void(0)" @click="showEditExternalCostModal = false; $emit('input', showEditExternalCostModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
                    <a href="javascript:void(0)" @click="saveExternalCost" class="btn-rounded">{{ translateText('message.yes') }} </a>
                </div>
            </div>
        </modal>
        <modal v-if="showDeleteExternalCostModal" @close="showDeleteExternalCostModal = false; $emit('input', showDeleteExternalCostModal);">
            <p class="modal-title">{{ translateText('message.delete_external_cost') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showDeleteExternalCostModal = false; $emit('input', showDeleteExternalCostModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="deleteExternalCost()" class="btn-rounded">{{ translateText('message.yes') }}</a>
            </div>
        </modal>
        <modal v-if="showEditExternalForecastCostModal" @close="showEditExternalForecastCostModal = false; $emit('input', showEditExternalForecastCostModal);">
            <div>
                <p class="modal-title">{{ translateText('message.edit_forecast_total') }}</p>
                <div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-10">
                                <input-field
                                    type="text"
                                    v-bind:label="translateText('label.forecast_total')"
                                    v-model="externalForecastCost"
                                    v-bind:content="externalForecastCost" />
                            </div>
                        </div>
                    </div>
                    <div class="row error-row">
                        <div class="form-group">
                            <div class="col-md-10">
                                <error
                                    v-if="validationMessages.externalForecastCost && validationMessages.externalForecastCost.length"
                                    v-for="(message, index) in validationMessages.externalForecastCost"
                                    :key="index"
                                    :message="message"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-space-between">
                    <a href="javascript:void(0)" @click="showEditExternalForecastCostModal = false; $emit('input', showEditExternalForecastCostModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
                    <a href="javascript:void(0)" @click="saveExternalForecastCost" class="btn-rounded">{{ translateText('message.yes') }} </a>
                </div>
            </div>
        </modal>
        <modal v-if="showEditExternalActualCostModal" @close="showEditExternalActualCostModal = false; $emit('input', showEditExternalActualCostModal);">
            <div>
                <p class="modal-title">{{ translateText('message.edit_actual_total') }}</p>
                <div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-10">
                                <input-field
                                    type="text"
                                    v-bind:label="translateText('label.actual_total')"
                                    v-model="externalActualCost"
                                    v-bind:content="externalActualCost" />
                            </div>
                        </div>
                    </div>
                    <div class="row error-row">
                        <div class="form-group">
                            <div class="col-md-10">
                                <error
                                    v-if="validationMessages.externalActualCost && validationMessages.externalActualCost.length"
                                    v-for="(message, index) in validationMessages.externalActualCost"
                                    :key="index"
                                    :message="message"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-space-between">
                    <a href="javascript:void(0)" @click="showEditExternalActualCostModal = false; $emit('input', showEditExternalActualCostModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
                    <a href="javascript:void(0)" @click="saveExternalActualCost" class="btn-rounded">{{ translateText('message.yes') }} </a>
                </div>
            </div>
        </modal>
        <!-- /// External Cost Modal /// -->
        <!-- /// Internal costs Modal /// -->
        <modal v-if="showEditInternalCostModal" @close="showEditInternalCostModal = false; $emit('input', showEditInternalCostModal);">
            <div>
                <p class="modal-title" v-if="!isEditInternalCost">{{ translateText('message.add_internal_costs') }}</p>
                <p class="modal-title" v-if="isEditInternalCost">{{ translateText('message.edit_internal_costs') }}</p>
                <div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="translateText('label.resource')"
                                    v-bind:options="resourcesForSelect"
                                    v-bind:currentOption="editInternalCostObj.resource"
                                    v-on:input="populateRateField"
                                    v-model="editInternalCostObj.resource" />
                             </div>
                            <div class="col-md-2">
                                <input-field
                                    type="text"
                                    v-bind:label="translateText('label.daily_rate')"
                                    v-bind:content="editInternalCostObj.daily_rate"
                                    v-model="editInternalCostObj.daily_rate" />
                            </div>
                            <div class="col-md-2">
                                <input-field
                                    type="text"
                                    v-bind:label="translateText('label.qty')"
                                    v-bind:content="editInternalCostObj.quantity"
                                    v-model="editInternalCostObj.quantity" />
                            </div>
                            <div class="col-md-2">
                                <input-field
                                    type="text"
                                    v-bind:label="translateText('label.days')"
                                    v-bind:content="editInternalCostObj.duration"
                                    v-model="editInternalCostObj.duration" />
                            </div>
                        </div>
                    </div>
                    <div class="row error-row">
                        <div class="form-group">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-2">
                                <error
                                    v-if="validationMessages.rate && validationMessages.rate.length"
                                    v-for="(message, index) in validationMessages.rate"
                                    :key="index"
                                    :message="message"/>
                            </div>
                            <div class="col-md-2">
                                <error
                                    v-if="validationMessages.quantity && validationMessages.quantity.length"
                                    v-for="(message, index) in validationMessages.quantity"
                                    :key="index"
                                    :message="message"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-space-between">
                    <a href="javascript:void(0)" @click="showEditInternalCostModal = false; $emit('input', showEditInternalCostModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
                    <a href="javascript:void(0)" @click="saveInternalCost" class="btn-rounded">{{ translateText('message.yes') }} </a>
                </div>
            </div>
        </modal>
        <modal v-if="showDeleteInternalCostModal" @close="showDeleteInternalCostModal = false; $emit('input', showDeleteInternalCostModal);">
            <p class="modal-title">{{ translateText('message.delete_internal_cost') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showDeleteInternalCostModal = false; $emit('input', showDeleteInternalCostModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="deleteInternalCost()" class="btn-rounded">{{ translateText('message.yes') }}</a>
            </div>
        </modal>
        <modal v-if="showEditInternalForecastCostModal" @close="showEditInternalForecastCostModal = false; $emit('input', showEditInternalForecastCostModal);">
            <div>
                <p class="modal-title">{{ translateText('message.edit_forecast_total') }}</p>
                <div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-10">
                                <input-field
                                    type="text"
                                    v-bind:label="translateText('label.forecast_total')"
                                    v-model="internalForecastCost"
                                    v-bind:content="internalForecastCost" />
                            </div>
                        </div>
                    </div>
                    <div class="row error-row">
                        <div class="form-group">
                            <div class="col-md-10">
                                <error
                                    v-if="validationMessages.internalForecastCost && validationMessages.internalForecastCost.length"
                                    v-for="(message, index) in validationMessages.internalForecastCost"
                                    :key="index"
                                    :message="message"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-space-between">
                    <a href="javascript:void(0)" @click="showEditInternalForecastCostModal = false; $emit('input', showEditInternalForecastCostModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
                    <a href="javascript:void(0)" @click="saveInternalForecastCost" class="btn-rounded">{{ translateText('message.yes') }} </a>
                </div>
            </div>
        </modal>
        <modal v-if="showEditInternalActualCostModal" @close="showEditInternalActualCostModal = false; $emit('input', showEditInternalActualCostModal);">
            <div>
                <p class="modal-title">{{ translateText('message.edit_actual_total') }}</p>
                <div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-10">
                                <input-field
                                    type="text"
                                    v-bind:label="translateText('label.actual_total')"
                                    v-model="internalActualCost"
                                    v-bind:content="internalActualCost" />
                            </div>
                        </div>
                    </div>
                    <div class="row error-row">
                        <div class="form-group">
                            <div class="col-md-10">
                                <error
                                    v-if="validationMessages.internalActualCost && validationMessages.internalActualCost.length"
                                    v-for="(message, index) in validationMessages.internalActualCost"
                                    :key="index"
                                    :message="message"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-space-between">
                    <a href="javascript:void(0)" @click="showEditInternalActualCostModal = false; $emit('input', showEditInternalActualCostModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
                    <a href="javascript:void(0)" @click="saveInternalActualCost" class="btn-rounded">{{ translateText('message.yes') }} </a>
                </div>
            </div>
        </modal>
        <!-- /// Internal costs Modal /// -->
        <!-- /// Edit Schedule Modal /// -->
        <modal v-if="showEditScheduleModal" @close="showEditScheduleModal = false; $emit('input', showEditScheduleModal);">
            <p class="modal-title">{{ translateText('title.schedule.edit') }}</p>
            <schedule v-model="editScheduleObj" v-bind:editSchedule="editScheduleObj" />
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showEditScheduleModal = false; $emit('input', showEditScheduleModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
                <a href="javascript:void(0)" @click="changeSchedule()" class="btn-rounded">{{ translateText('button.edit_schedule') }} +</a>
            </div>
        </modal>
        <!-- /// Edit Schedule Modal /// -->
        <!-- /// Close task Modal /// -->
        <modal v-if="showCloseTaskModal" @close="showCloseTaskModal = false; $emit('input', showCloseTaskModal);">
            <p class="modal-title">{{ translateText('message.confirm_close_task') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showCloseTaskModal = false; $emit('input', showCloseTaskModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="closeTask()" class="btn-rounded">{{ translateText('message.yes') }}</a>
            </div>
        </modal>
        <!-- /// close task Modal /// -->
        <!-- /// Open task Modal /// -->
        <modal v-if="showOpenTaskModal" @close="showOpenTaskModal = false; $emit('input', showOpenTaskModal);">
            <p class="modal-title">{{ translateText('message.confirm_open_task') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showOpenTaskModal = false; $emit('input', showOpenTaskModal);" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="openTask()" class="btn-rounded">{{ translateText('message.yes') }}</a>
            </div>
        </modal>
        <!-- /// Open task Modal /// -->
    </div>
</template>
<script>
import InputField from '../../../_common/_form-components/InputField';
import RadioField from '../../../_common/_form-components/RadioField';
import SelectField from '../../../_common/_form-components/SelectField';
import Error from '../../../_common/_messages/Error.vue';
import Schedule from '../Create/Schedule';
import Modal from '../../../_common/Modal';
import {mapActions, mapGetters} from 'vuex';
import moment from 'moment';

export default {
    props: [
        'editExternalCostModal',
        'deleteExternalCostModal',
        'externalCostObj',
        'editExternalForecastCostModal',
        'editExternalActualCostModal',
        'editInternalCostModal',
        'deleteInternalCostModal',
        'internalCostObj',
        'editInternalForecastCostModal',
        'editInternalActualCostModal',
        'editScheduleModal',
        'scheduleObj',
        'taskObj',
        'closeTaskModal',
        'openTaskModal',
    ],
    components: {
        InputField,
        RadioField,
        SelectField,
        Schedule,
        Modal,
        Error,
    },
    watch: {
        editExternalCostModal(value) {
            this.showEditExternalCostModal = this.editExternalCostModal;
        },
        deleteExternalCostModal(value) {
            this.showDeleteExternalCostModal = this.deleteExternalCostModal;
        },
        externalCostObj(value) {
            this.editExternalCostObj = this.externalCostObj;
            this.isEditExternalCost = this.editExternalCostObj.id !== 0;
        },
        editExternalForecastCostModal(value) {
            this.showEditExternalForecastCostModal = this.editExternalForecastCostModal;
            this.externalForecastCost = this.taskObj.externalForecastCost;
        },
        editExternalActualCostModal(value) {
            this.showEditExternalActualCostModal = this.editExternalActualCostModal;
            this.externalActualCost = this.taskObj.externalActualCost;
        },
        editInternalCostModal(value) {
            this.showEditInternalCostModal = this.editInternalCostModal;
        },
        deleteInternalCostModal(value) {
            this.showDeleteInternalCostModal = this.deleteInternalCostModal;
        },
        internalCostObj(value) {
            this.editInternalCostObj = this.internalCostObj;
            this.isEditInternalCost = this.editInternalCostObj.id !== 0;
        },
        editInternalForecastCostModal(value) {
            this.showEditInternalForecastCostModal = this.editInternalForecastCostModal;
            this.internalForecastCost = this.taskObj.internalForecastCost;
        },
        editInternalActualCostModal(value) {
            this.showEditInternalActualCostModal = this.editInternalActualCostModal;
            this.internalActualCost = this.taskObj.internalActualCost;
        },
        editScheduleModal(value) {
            this.showEditScheduleModal = this.editScheduleModal;
        },
        scheduleObj(value) {
            this.editScheduleObj = this.scheduleObj;
        },
        closeTaskModal(value) {
            this.showCloseTaskModal = this.closeTaskModal;
        },
        openTaskModal(value) {
            this.showOpenTaskModal = this.openTaskModal;
        },
    },
    methods: {
        ...mapActions([
            'createTaskCost',
            'deleteTaskCost',
            'editTaskCost',
            'getProjectUnits',
            'patchTask',
            'getProjectResources',
        ]),
        translateText(text) {
            return this.translate(text);
        },
        saveExternalCost() {
            let data = {
                project: this.$route.params.id,
                workPackage: this.$route.params.taskId,
                quantity: this.editExternalCostObj.quantity,
                rate: this.editExternalCostObj.rate,
                name: this.editExternalCostObj.name,
                type: 1,
            };
            if (this.editExternalCostObj.customUnit && this.editExternalCostObj.customUnit.length) {
                data.customUnit = this.editExternalCostObj.customUnit;
            } else if (this.editExternalCostObj.selectedUnit && this.editExternalCostObj.selectedUnit.length) {
                data.unit = this.editExternalCostObj.selectedUnit;
            }

            if (this.editExternalCostObj.id === 0) {
                this.createTaskCost(data)
                    .then(response => this.successHandler(this.showEditExternalCostModal, response.body.error));
            } else {
                this.editTaskCost({costId: this.editExternalCostObj.id, data: data})
                    .then(response => this.successHandler(this.showEditExternalCostModal, response.body.error));
            }
        },
        deleteExternalCost() {
            this.deleteTaskCost(this.editExternalCostObj.id)
                .then(response => this.successHandler(this.showDeleteExternalCostModal));
        },
        isEditExternalCost() {
            return this.editExternalCostObj.id != 0;
        },
        saveExternalForecastCost() {
            let data = {
                externalForecastCost: this.externalForecastCost,
            };
            this.patchTask({
                data: data,
                taskId: this.$route.params.taskId,
            })
                .then(response => this.successHandler(this.showEditExternalForecastCostModal, response.body.error));
        },
        saveExternalActualCost() {
            let data = {
                externalActualCost: this.externalActualCost,
            };
            this.patchTask({
                data: data,
                taskId: this.$route.params.taskId,
            })
                .then(response => this.successHandler(this.showEditExternalActualCostModal, response.body.error));
        },
        saveInternalCost() {
            let data = {
                project: this.$route.params.id,
                workPackage: this.$route.params.taskId,
                resource: this.editInternalCostObj.resource ? this.editInternalCostObj.resource.key : null,
                rate: this.editInternalCostObj.daily_rate,
                quantity: this.editInternalCostObj.quantity,
                duration: this.editInternalCostObj.duration,
                type: 0,
            };

            if (this.editInternalCostObj.id === 0) {
                this.createTaskCost(data)
                    .then(response => this.successHandler(this.showEditInternalCostModal, response.body.error));
            } else {
                this.editTaskCost({costId: this.editInternalCostObj.id, data: data})
                    .then(response => this.successHandler(this.showEditInternalCostModal, response.body.error));
            }
        },
        deleteInternalCost() {
            this.deleteTaskCost(this.editInternalCostObj.id)
                .then(response => this.successHandler(this.showDeleteInternalCostModal));
        },
        saveInternalForecastCost() {
            let data = {
                internalForecastCost: this.internalForecastCost,
            };
            this.patchTask({
                data: data,
                taskId: this.$route.params.taskId,
            })
                .then(response => this.successHandler(this.showEditInternalForecastCostModal, response.body.error));
        },
        saveInternalActualCost() {
            let data = {
                internalActualCost: this.internalActualCost,
            };
            this.patchTask({
                data: data,
                taskId: this.$route.params.taskId,
            })
                .then(response => this.successHandler(this.showEditInternalActualCostModal, response.body.error));
        },
        changeSchedule() {
            let data = {
                scheduledStartAt: moment(this.editScheduleObj.baseStartDate, 'DD-MM-YYYY').format('DD-MM-YYYY'),
                scheduledFinishAt: moment(this.editScheduleObj.baseEndDate, 'DD-MM-YYYY').format('DD-MM-YYYY'),
                forecastStartAt: moment(this.editScheduleObj.forecastStartDate, 'DD-MM-YYYY').format('DD-MM-YYYY'),
                forecastFinishAt: moment(this.editScheduleObj.forecastEndDate, 'DD-MM-YYYY').format('DD-MM-YYYY'),
                automaticSchedule: this.editScheduleObj.automatic,
                duration: this.editScheduleObj.durationInDays,
                dependants: this.editScheduleObj.successors.map((item) => {
                    return item.key;
                }),
                dependencies: this.editScheduleObj.predecessors.map((item) => {
                    return item.key;
                }),
            };

            this.patchTask({
                data: data,
                taskId: this.$route.params.taskId,
            })
                .then(response => this.successHandler(this.showEditScheduleModal, response.body.error));
        },
        closeTask() {
            let data = {
                workPackageStatus: 5,
            };
            this.patchTask({
                data: data,
                taskId: this.$route.params.taskId,
            })
                .then(response => this.successHandler(this.showCloseTaskModal, response.body.error));
        },
        openTask() {
            let data = {
                workPackageStatus: 1,
            };
            this.patchTask({
                data: data,
                taskId: this.$route.params.taskId,
            })
                .then(response => this.successHandler(this.showOpenTaskModal, response.body.error));
        },
        populateRateField(value) {
            this.editInternalCostObj.daily_rate = value.rate;
        },
        successHandler(modal, errors) {
            if (errors == undefined) {
                modal = false;
                this.$emit('input', modal);
            }
        },
    },
    created() {
        this.getProjectUnits(this.$route.params.id);
        this.getProjectResources(this.$route.params.id);
    },
    computed: {
        ...mapGetters({
            projectUnitsForSelect: 'projectUnitsForSelect',
            resourcesForSelect: 'projectResourcesForSelect',
            validationMessages: 'validationMessages',
        }),
    },
    data() {
        return {
            showEditExternalCostModal: false,
            showDeleteExternalCostModal: false,
            editExternalCostObj: {},
//            isEditExternalCost: false,
            showEditExternalForecastCostModal: false,
            externalForecastCost: 0,
            showEditExternalActualCostModal: false,
            externalActualCost: 0,
            showEditInternalCostModal: false,
            showDeleteInternalCostModal: false,
            editInternalCostObj: {},
            isEditInternalCost: false,
            showEditInternalForecastCostModal: false,
            internalForecastCost: 0,
            showEditInternalActualCostModal: false,
            internalActualCost: 0,
            showEditScheduleModal: false,
            editScheduleObj: {},
            showCloseTaskModal: false,
            showOpenTaskModal: false,
        };
    },
};
</script>

<style scoped lang="scss">
    .error-row {
        .error {
            position:relative;
            top: -60px;
        }
    }
</style>

