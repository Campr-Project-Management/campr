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
                        v-on:change="uploadImportTaskFile" />
                    <a class="btn-rounded btn-auto btn-empty flex" v-on:click="openFileSelection">
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
                    <planning v-model="planning" :editPlanning="planning" />
                    <!-- /// End Task Planning /// -->

                    <hr>
                    <!-- /// Task Schedule /// -->
                    <schedule
                            v-model="schedule"
                            :editable-base="!isEdit"
                            :editable-forecast="isEdit"/>
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
                        :validationMessages="validationMessages.children" />
                    <!-- /// End SubTasks /// -->

                    <hr class="double">

                    <!-- /// Task Attachments /// -->
                    <attachments v-model="medias"/>
                    <error
                            v-if="validationMessages.medias && validationMessages.medias.length"
                            v-for="message in validationMessages.medias"
                            :message="message"/>
                    <!-- /// End Task Attachments /// -->

                    <hr class="double">

                    <!-- /// Task Condition /// -->
                    <condition v-model="statusColor" :selectedStatusColor="statusColor" />
                    <error
                        v-if="validationMessages.colorStatus && validationMessages.colorStatus.length"
                        v-for="message in validationMessages.colorStatus"
                        :message="message" />
                    <!-- /// End Task Condition /// -->

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-task-management-list'}" class="btn-rounded btn-auto disable-bg">{{ translate('button.cancel') }}</router-link>
                        <a v-if="!isEdit" @click="createTask" class="btn-rounded btn-auto second-bg">{{ translate('button.create_task') }}</a>
                        <a v-if="isEdit" @click="editExistingTask" class="btn-rounded btn-auto second-bg">{{ translate('button.edit_task') }}</a>
                    </div>
                    <!-- /// End Actions /// -->
                </div>
            </div>
        </div>

        <alert-modal v-if="showSaved" @close="showSaved = false" body="message.saved" />
        <alert-modal v-if="showFailed" @close="showFailed = false" body="message.unable_to_save" />
    </div>
</template>

<script>
import InputField from '../../_common/_form-components/InputField';
import SelectField from '../../_common/_form-components/SelectField';
import UploadIcon from '../../_common/_icons/UploadIcon';
import CalendarIcon from '../../_common/_icons/CalendarIcon';
import Schedule from './Create/Schedule';
import InternalCosts from './Create/InternalCosts';
import ExternalCosts from './Create/ExternalCosts';
import Subtasks from './Create/Subtasks';
import Planning from './Create/Planning';
import Condition from './Create/Condition';
import TaskDetails from './Create/Details';
import TaskAssignments from './Create/Assignments';
import Attachments from './Create/Attachments';
import datepicker from '../../_common/_form-components/Datepicker';
import Switches from '../../3rdparty/vue-switches';
import Editor from '../../_common/Editor.vue';
import Error from '../../_common/_messages/Error.vue';
import AlertModal from '../../_common/AlertModal.vue';
import {mapGetters, mapActions} from 'vuex';
import {createFormData} from '../../../helpers/task';
import router from '../../../router';
import _ from 'lodash';
import moment from 'moment';

export default {
    components: {
        TaskAssignments,
        InputField,
        SelectField,
        UploadIcon,
        CalendarIcon,
        datepicker,
        Switches,
        Schedule,
        InternalCosts,
        ExternalCosts,
        Subtasks,
        Planning,
        Condition,
        TaskDetails,
        Attachments,
        Editor,
        Error,
        AlertModal,
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
                'getGreenColorStatus',
            ]
        ),
        createTask: function() {
            this
                .createNewTask({
                    data: createFormData(this.formData),
                    projectId: this.$route.params.id,
                })
                .then(
                    (response) => {
                        if (response.body && response.body.error && response.body.messages) {
                            this.showFailed = true;
                            return;
                        }

                        this.showSaved = true;
                    },
                    () => {
                        this.showFailed = true;
                    }
                )
            ;
        },
        editExistingTask: function() {
            let customUnitAdded = this.wasCustomUnitAdded(this.externalCosts);

            let formData = createFormData(this.formData);
            formData.append('scheduledStartAt', moment(this.task.scheduledStartAt).format('DD-MM-YYYY'));
            formData.append('scheduledFinishAt', moment(this.task.scheduledFinishAt).format('DD-MM-YYYY'));

            this
                .editTask({
                    data: formData,
                    taskId: this.$route.params.taskId,
                })
                .then(
                    (response) => {
                        if (response.body && response.body.error && response.body.messages) {
                            this.showFailed = true;
                            return;
                        }

                        if (customUnitAdded) {
                            this.getProjectUnits(this.$route.params.id);
                        }

                        this.showSaved = true;
                    },
                    () => {
                        this.showFailed = true;
                    }
                )
            ;
        },
        openFileSelection: function() {
            document.getElementById('importXmlFile').click();
        },
        uploadImportTaskFile: function(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length) {
                return;
            }
            let formData = new FormData();
            formData.append('file', files[0]);

            this.importTask({
                data: formData,
                projectId: this.$route.params.id,
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
            );
        },
        setMedias(value) {
            this.medias = value;
        },
        onInternalCostAdded() {
            this.internalCosts.items.push({
                resource: '',
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
    },
    created() {
        if (this.$route.params.taskId) {
            this.getTaskById(this.$route.params.taskId);
        }

        if (!this.$store.state.greenColorStatus || this.$store.state.greenColorStatus.length == 0) {
            this.getGreenColorStatus();
        }
    },
    beforeDestroy() {
        this.emptyValidationMessages();
    },
    computed: {
        ...mapGetters({
            task: 'currentTask',
            greenColorStatus: 'greenColorStatus',
            validationMessages: 'validationMessages',
        }),
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
                statusColor: this.statusColor,
            };

            if (this.isEdit) {
                data.schedule.forecastStartDate = this.schedule.forecastStartDate;
                data.schedule.forecastEndDate = this.schedule.forecastEndDate;
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
            if (!this.isEdit && value === false) {
                router.push({
                    name: 'project-task-management-list',
                });
            }
        },
        greenColorStatus(value) {
            if(!this.isEdit) {
                this.statusColor = {
                    id: this.greenColorStatus.id,
                    name: this.greenColorStatus.name,
                };
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

            this.statusColor = {
                id: this.task.colorStatus,
                name: this.task.colorStatusName,
            };

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
                supportUsers: this.task.supportUsers.map(user => ({key: user.id})),
                consultedUsers: this.task.consultedUsers.map(user => ({key: user.id})),
                informedUsers: this.task.informedUsers.map(user => ({key: user.id})),
            };

            let children = [];
            this.task.children.map(function(child) {
                children.push({
                    description: child.name,
                });
            });
            this.subtasks = children;

            if (!this.planning) {
                this.planning = {};
            }
            if (this.task.milestone) {
                this.planning.milestone = {
                    key: this.task.milestone.toString(),
                    label: this.task.milestoneName,
                };
            }

            if (this.task.phase) {
                this.planning.phase = {
                    key: this.task.phase.toString(),
                    label: this.task.phaseName,
                };
            }

            if (this.task.parent) {
                this.planning.parent = {
                    key: this.task.parent.toString(),
                    label: this.task.parentName,
                };
            }

            let internal = [];
            let external = [];
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
                            project: this.$route.params.id ? this.$route.params.id: null,
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
            this.internalCosts.items = internal;
            this.internalCosts.forecast = this.task.internalForecastCost;
            this.internalCosts.actual = this.task.internalActualCost;

            this.externalCosts.items = external;
            this.externalCosts.forecast = this.task.externalForecastCost;
            this.externalCosts.actual = this.task.externalActualCost;

            this.medias = this.task.medias;
        },
    },
    data() {
        return {
            showSaved: false,
            showFailed: false,
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
            statusColor: {},
        };
    },
};
</script>
