<template>
    <div class="row">
        <div class="col-md-6 custom-col-md-6">
            <div class="create-task page-section">
                <!-- /// Header /// -->
                <div class="header flex-v-center">
                    <div>
                        <router-link :to="{name: 'project-task-management-list'}" class="small-link" v-if="!isEdit">
                            <i class="fa fa-angle-left"></i>
                            {{ translate('message.back_to_task_management') }}
                        </router-link>
                        <router-link
                            v-if="task.id && isEdit"
                            class="small-link"
                            :to="{name: 'project-task-management-view', params: {id: task.project, taskId: task.id}}">
                            <i class="fa fa-angle-left"></i>
                            {{ translate('message.back_to_task_view') }}
                        </router-link>
                        <h1 v-if="!isEdit">{{ translate('message.create_new_task') }}</h1>
                        <h1 v-if="isEdit">{{ translate('message.edit_task') }} - {{ task.name }}</h1>
                    </div>
                    <input
                        id="importXmlFile"
                        type="file"
                        name="importXmlFile"
                        style="display: none;"
                        v-if="showImport"
                        v-on:change="uploadImportTaskFile" />
                    <a
                        class="btn-rounded btn-auto btn-empty flex"
                        v-if="showImport"
                        v-on:click="openFileSelection">
                        <span>{{ translate('message.import_task') }}</span>
                        <upload-icon></upload-icon>
                    </a>
                </div>
                <error
                    v-if="validationMessages && validationMessages.file && validationMessages.file.length"
                    v-for="message in validationMessages.file"
                    :message="message" />
                <!-- /// End Header /// -->

                <div class="form">
                    <!-- /// Task Name /// -->
                    <input-field type="text" :label="translate('label.task_title')" v-model="title" :content="title" />
                    <error
                        v-if="validationMessages && validationMessages.name && validationMessages.name.length"
                        v-for="message in validationMessages.name"
                        :message="message" />
                    <!-- /// End Task Name /// -->

                    <!-- /// Task Description /// -->
                    <editor
                        height="200px"
                        id="task_description"
                        v-model="description"
                        :label="translate('label.task_description')"/>
                    <!-- /// End Task Description /// -->

                    <hr class="double">

                    <!-- /// Task Planning /// -->
                    <planning v-model="planning"/>
                    <!-- /// End Task Planning /// -->

                    <hr>
                    <!-- /// Task Schedule /// -->
                    <schedule
                        v-model="schedule"
                        :editable-base="!isEditBase"
                        :editable-forecast="isEdit"
                        v-on:input="updateSchedule"/>
                    <!-- /// End Task Schedule /// -->

                    <hr class="double">

                    <!-- /// Task Internal Costs /// -->
                    <internal-costs
                        v-model="internalCosts"
                        :validationMessages="internalValidationMessages"
                        @add="onInternalCostAdded"/>
                    <!-- /// End Task Internal Costs /// -->

                    <hr class="double">

                    <!--&lt;!&ndash; /// Task External Costs /// &ndash;&gt;-->
                    <external-costs
                        v-model="externalCosts"
                        :validationMessages="externalValidationMessages"
                        @input="onExternalCostChanged"
                        @add="onExternalCostAdded"/>
                    <!--&lt;!&ndash; /// End Task External Costs /// &ndash;&gt;-->

                    <hr class="double">

                    <!-- /// Task Details /// -->
                    <task-assignments
                            :value="assignments"
                            @input="onAssignmentsUpdate"/>
                    <!-- /// End Task Details /// -->

                    <hr class="double">

                    <!-- /// Task Details /// -->
                    <task-details v-model="details" />
                    <!-- /// End Task Details /// -->

                    <hr class="double">

                    <!-- /// SubTasks /// -->
                    <subtasks
                            v-model="subtasks"
                            :editSubtasks="subtasks"
                            :validationMessages="validationMessages.children"/>
                    <!-- /// End SubTasks /// -->

                    <hr class="double">

                    <!-- /// Task Attachments /// -->
                    <h3>{{ translate('message.attachments') }}</h3>
                    <attachments
                            v-model="medias"
                            :max-file-size="projectMaxUploadFileSize"
                            @uploading="onUploading"/>
                    <!-- /// End Task Attachments /// -->

                    <hr class="double">

                    <!-- /// Task Condition /// -->
                    <h3>{{ 'message.task_condition'|trans }}</h3>
                    <traffic-light
                            size="small"
                            v-model="trafficLight"
                            :editable="true"/>
                    <error at-path="trafficLight"/>

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-task-management-list'}" class="btn-rounded btn-auto disable-bg">{{ translate('button.cancel') }}</router-link>
                        <template v-if="!isUploading">
                            <a v-if="!isEdit" @click="createTask" class="btn-rounded btn-auto second-bg">{{ translate('button.create_task') }}</a>
                            <a v-if="isEdit" @click="updateTask" class="btn-rounded btn-auto second-bg">{{ translate('button.save_task') }}</a>
                        </template>
                    </div>
                    <!-- /// End Actions /// -->
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import InputField from '../../_common/_form-components/InputField';
import SelectField from '../../_common/_form-components/SelectField';
import UploadIcon from '../../_common/_icons/UploadIcon';
import Schedule from './Create/Schedule';
import InternalCosts from './Create/InternalCosts';
import ExternalCosts from './Create/ExternalCosts';
import Subtasks from './Create/Subtasks';
import Planning from './Create/Planning';
import TaskDetails from './Create/Details';
import TaskAssignments from './Create/Assignments';
import Attachments from '../../_common/Attachments';
import Switches from '../../3rdparty/vue-switches';
import Editor from '../../_common/Editor.vue';
import Error from '../../_common/_messages/Error.vue';
import {mapGetters, mapActions} from 'vuex';
import {createFormData} from '../../../helpers/task';
import router from '../../../router';
import _ from 'lodash';
import moment from 'moment';
import TrafficLight from '../../_common/TrafficLight';

export default {
    components: {
        TrafficLight,
        TaskAssignments,
        InputField,
        SelectField,
        UploadIcon,
        Switches,
        Schedule,
        InternalCosts,
        ExternalCosts,
        Subtasks,
        Planning,
        TaskDetails,
        Attachments,
        Editor,
        Error,
    },
    methods: {
        ...mapActions(
            [
                'createNewTask',
                'getTaskById',
                'editTask',
                'importTask',
                'emptyValidationMessages',
                'getProjectUnits',
                'importXMLTask',
            ]
        ),
        createTask: function() {
            if (this.isSaving) {
                return;
            }

            this.isSaving = true;

            this
                .createNewTask({
                    data: createFormData(this.formData),
                    projectId: this.$route.params.id,
                })
                .then(
                    (response) => {
                        this.isSaving = false;
                        if (response.body && response.body.error && response.body.messages) {
                            this.$flashError('message.unable_to_save');
                            return;
                        }

                        this.showSaved = true;
                        this.$flashSuccess('message.saved');
                    },
                    () => {
                        this.isSaving = false;
                        this.$flashError('message.unable_to_save');
                    }
                )
            ;
        },
        updateTask: function() {
            if (this.isSaving) {
                return;
            }

            this.isSaving = true;
            let customUnitAdded = this.wasCustomUnitAdded(this.externalCosts);

            let formData = createFormData(this.formData);
            if (this.task.scheduledStartAt && this.task.scheduledFinishAt) {
                formData.append('scheduledStartAt', moment(this.task.scheduledStartAt).format('DD-MM-YYYY'));
                formData.append('scheduledFinishAt', moment(this.task.scheduledFinishAt).format('DD-MM-YYYY'));
            }

            this
                .editTask({
                    data: formData,
                    taskId: this.$route.params.taskId,
                })
                .then(
                    (response) => {
                        this.isSaving = false;
                        if (response.body && response.body.error && response.body.messages) {
                            this.$flashError('message.unable_to_save');
                            return;
                        }

                        if (customUnitAdded) {
                            this.getProjectUnits(this.$route.params.id);
                        }

                        this.showSaved = true;
                        this.$flashSuccess('message.saved');
                    },
                    () => {
                        this.isSaving = false;
                        this.$flashError('message.unable_to_save');
                    }
                )
            ;
        },
        openFileSelection: function() {
            document.getElementById('importXmlFile').click();
        },
        uploadImportTaskFile: function(e) {
            const files = e.target.files || e.dataTransfer.files;
            if (!files.length) {
                return;
            }

            const file = files[0];

            if (!file.name.match(/^.+\.xml$/i)) {
                alert('Please select an XML file.');
                return;
            }

            const reader = new FileReader();
            const importXMLTask = this.importXMLTask;

            reader.addEventListener('load', function() {
                importXMLTask(this.result);
            });

            reader.addEventListener('error', () => {
                alert('Something went wrong when reading the file. Please make sure it\'s a valid task XML exported from MS Project.');
            });

            reader.readAsText(file);
        },
        setMedias(value) {
            this.medias = value;
        },
        onInternalCostAdded() {
            this.internalCosts.items.push({
                resource: {label: this.translate('label.cost_item')},
                quantity: 1,
                duration: 1,
                rate: 0,
            });
        },
        onExternalCostAdded() {
            this.externalCosts.items.push({
                description: '',
                quantity: 0,
                rate: 0,
                capex: false,
                opex: true,
                customUnit: '',
                expenseType: 1,
                unit: null,
            });
        },
        wasCustomUnitAdded(costs) {
            return !!_.find(costs.items, (item) => {
                return item.customUnit && item.customUnit.length > 0;
            });
        },
        onAssignmentsUpdate(value) {
            this.assignments = Object.assign({}, this.assignments, value);
        },
        updateSchedule(value) {
            this.schedule = value;
        },
        onUploading(uploading) {
            this.isUploading = uploading;
        },
    },
    created() {
        this.trafficLight = this.defaultTrafficLightValue;
        if (this.$route.params.taskId) {
            this.getTaskById(this.$route.params.taskId);
        }
    },
    beforeDestroy() {
        this.emptyValidationMessages();
    },
    computed: {
        ...mapGetters({
            task: 'currentTask',
        }),
        ...mapGetters([
            'validationMessages',
            'defaultTrafficLightValue',
            'projectMaxUploadFileSize',
            'validationMessagesFor',
            'project',
        ]),
        mediasValidationMessages() {
            let messages = this.validationMessagesFor('medias');
            let out = [];

            Object.keys(messages).forEach((index) => {
                out[index] = messages[index].file;
            });

            return out;
        },
        internalValidationMessages() {
            if (_.isPlainObject(this.validationMessages.costs)) {
                const out = {};
                Object
                    .keys(this.validationMessages.costs)
                    .filter(key => key >= this.externalCosts.items.length)
                    .forEach(key => {
                        out[key - this.externalCosts.items.length] = this.validationMessages.costs[key];
                    })
                ;
                return out;
            } else if (_.isArray(this.validationMessages.costs)) {
                return this
                    .validationMessages
                    .costs
                    .slice(
                        this.externalCosts.items.length,
                        this.validationMessages.costs.length
                    )
                ;
            }
            return {};
        },
        // this is actually sent first in the costs array
        externalValidationMessages() {
            if (this.validationMessages && this.validationMessages.costs) {
                if (_.isPlainObject(this.validationMessages.costs)) {
                    const out = {};
                    Object
                        .keys(this.validationMessages.costs)
                        .filter(key => key < this.externalCosts.items.length)
                        .forEach(key => {
                            out[key] = this.validationMessages.costs[key];
                        })
                    ;
                    return out;
                } else if (_.isArray(this.validationMessages.costs)) {
                    return this
                        .validationMessages
                        .costs
                        .slice(0, this.externalCosts.items.length)
                    ;
                }
            }
            return {};
        },
        isEdit() {
            return !!this.$route.params.taskId;
        },
        showImport() {
            return !this.isEdit
                && typeof File !== 'undefined'
                && typeof FileReader !== 'undefined'
            ;
        },
        isEditBase() {
            return this.task.scheduledStartAt && this.task.scheduledFinishAt && !!this.$route.params.taskId;
        },
        formData() {
            let data = {
                project: this.$route.params.id,
                name: this.title,
                type: 2,
                description: this.description,
                schedule: {},
                internalCosts: this.internalCosts,
                externalCosts: this.externalCosts,
                subtasks: this.subtasks,
                planning: this.planning,
                medias: this.medias,
                details: this.details,
                assignments: this.assignments,
                trafficLight: this.trafficLight,
            };

            if (this.isEdit) {
                data.progress = this.task.progress;
                data.schedule.forecastStartDate = this.schedule.forecastStartDate;
                data.schedule.forecastEndDate = this.schedule.forecastEndDate;
                if (!this.isEditBase) {
                    data.schedule.baseStartDate = this.schedule.baseStartDate;
                    data.schedule.baseEndDate = this.schedule.baseEndDate;
                }
            } else {
                data.schedule.baseStartDate = this.schedule.baseStartDate;
                data.schedule.baseEndDate = this.schedule.baseEndDate;
                data.schedule.forecastStartDate = this.schedule.baseStartDate;
                data.schedule.forecastEndDate = this.schedule.baseEndDate;
            }

            return data;
        },
    },
    watch: {
        showSaved(value) {
            if (value === true) {
                router.push({
                    name: 'project-task-management-list',
                });
            }
        },
        task(value) {
            this.title = this.task.name;
            this.description = this.task.content;
            this.schedule = {
                baseStartDate: this.task.scheduledStartAt ? moment(this.task.scheduledStartAt).toDate() : null,
                baseEndDate: this.task.scheduledFinishAt ? moment(this.task.scheduledFinishAt).toDate() : null,
                forecastStartDate: this.task.forecastStartAt ? moment(this.task.forecastStartAt).toDate() : null,
                forecastEndDate: this.task.forecastFinishAt ? moment(this.task.forecastFinishAt).toDate() : null,
                duration: this.task.duration,
            };

            this.trafficLight = this.task.trafficLight;

            this.details = {
                status: this.task.workPackageStatus
                    ? {key: this.task.workPackageStatus, label: this.translate(this.task.workPackageStatusName)}
                    : null,
                label: this.task.label
                    ? {key: this.task.label, label: this.task.labelName}
                    : null,
            };

            this.assignments = {
                responsibility: this.task.responsibility && {key: this.task.responsibility},
                accountability: this.task.accountability && {key: this.task.accountability},
                supportUsers: this.task.supportUsers
                    ? this.task.supportUsers.map(user => ({key: user.id}))
                    : [],
                consultedUsers: this.task.consultedUsers
                    ? this.task.consultedUsers.map(user => ({key: user.id}))
                    : [],
                informedUsers: this.task.informedUsers
                    ? this.task.informedUsers.map(user => ({key: user.id}))
                    : [],
            };

            let children = [];
            if (this.task.children) {
                this.task.children.map((child) => {
                    children.push({
                        description: child.name,
                    });
                });
            }
            this.subtasks = children;

            this.planning.milestone = this.task.milestone;
            this.planning.phase = this.task.phase;
            this.planning.parent = this.task.parent;

            let internal = [];
            let external = [];
            if (this.task.costs) {
                this.task.costs.map((cost) => {
                    if (cost.isInternal) {
                        cost = _.merge(
                            {},
                            cost,
                            {
                                resource: cost.resource
                                    ? {
                                        label: cost.resourceName,
                                        key: cost.resource,
                                    }
                                    : null,
                                project: this.$route.params.id ? this.$route.params.id : null,
                                workPackage: this.$route.params.taskId ? this.$route.params.taskId : null,
                            }
                        );

                        internal.push(cost);
                        return;
                    }

                    external.push(_.merge(
                        {},
                        cost,
                        {
                            selectedUnit: cost.unit && cost.unit.id ? cost.unit.id.toString() : null,
                            customUnit: '',
                            project: this.$route.params.id ? this.$route.params.id : null,
                            workPackage: this.$route.params.taskId ? this.$route.params.taskId : null,
                        },
                    ));
                });
            }

            this.internalCosts.items = internal;
            this.internalCosts.forecast = this.task.internalForecastCost;
            this.internalCosts.actual = this.task.internalActualCost;

            this.externalCosts.items = external;
            this.externalCosts.forecast = this.task.externalForecastCost;
            this.externalCosts.actual = this.task.externalActualCost;

            this.medias = this.task.medias ? this.task.medias : [];
        },
    },
    data() {
        return {
            showSaved: false,
            schedule: {
                baseStartDate: null,
                baseEndDate: null,
                forecastStartDate: null,
                forecastEndDate: null,
                duration: 0,
            },
            title: '',
            description: '',
            internalCosts: {
                items: [],
                forecast: 0,
                actual: 0,
            },
            externalCosts: {
                items: [],
                forecast: 0,
                actual: 0,
            },
            subtasks: [],
            planning: {
                phase: null,
                milestone: null,
                parent: null,
            },
            medias: [],
            details: {
                status: null,
                label: null,
            },
            assignments: {
                responsibility: null,
                accountability: null,
                supportUsers: [],
                consultedUsers: [],
                informedUsers: [],
            },
            trafficLight: null,
            isSaving: false,
            isUploading: false,
        };
    },
};
</script>
