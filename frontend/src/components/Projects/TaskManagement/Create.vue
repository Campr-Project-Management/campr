<template>
    <div class="row">
        <div class="col-md-6">
            <div class="create-task page-section">
                <!-- /// Header /// -->
                <div class="header flex-v-center">
                    <div>
                        <router-link :to="{name: 'project-task-management-list'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            Back to Task Management
                        </router-link>
                        <h1 v-if="!isEdit">{{ message.create_new_task }}</h1>
                        <h1 v-if="isEdit">{{ message.edit_task }} - {{ task.name }}</h1>
                    </div>
                    <a class="btn-rounded btn-auto btn-empty flex">
                        <span>{{ message.import_task }}</span>
                        <upload-icon></upload-icon>
                    </a>
                </div>
                <!-- /// End Header /// -->

                <div class="form">
                    <!-- /// Task Name /// -->
                    <input-field type="text" v-bind:label="label.task_title" v-model="title" v-bind:content="title" />
                    <error
                        v-if="validationMessages && validationMessages.name && validationMessages.name.length"
                        v-for="message in validationMessages.name"
                        :message="message" />
                    <!-- /// End Task Name /// -->

                    <!-- /// Task Description /// -->
                    <div class="vueditor-holder">
                        <div class="vueditor-header">{{ label.task_description }}</div>
                        <Vueditor ref="description" />
                        <div cass="vueditor-footer clearfix">
                            <div class="pull-right"></div>
                        </div>
                    </div>
                    <!-- /// End Task Description /// -->

                    <hr class="double">

                    <!-- /// Task Planning /// -->
                    <planning v-model="planning" v-bind:editPlanning="planning" />
                    <!-- /// End Task Planning /// -->

                    <hr>

                    <!-- /// Task Schedule /// -->
                    <schedule v-model="schedule" v-bind:editSchedule="schedule" />
                    <!-- /// End Task Schedule /// -->

                    <hr class="double">

                    <!-- /// Task Internal Costs /// -->
                    <internal-costs
                        v-model="internalCosts"
                        v-on:input="setInternalCosts"
                        :validationMessages="internalValidationMessages" />
                    <!-- /// End Task Internal Costs /// -->

                    <hr class="double">

                    <!-- /// Task External Costs /// -->
                    <external-costs
                        v-model="externalCosts"
                        v-on:input="setExternalCosts"
                        :validationMessages="externalValidationMessages" />
                    <!-- /// End Task External Costs /// -->

                    <hr class="double">

                    <!-- /// Task Details /// -->
                    <task-details v-model="details" v-bind:editDetails="details" />
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
                    <!--<attachments v-model="medias" v-bind:editMedias="medias" />-->
                    <attachments v-on:input="setMedias" v-bind:editMedias="medias" />
                    <!-- /// End Task Attachments /// -->

                    <hr class="double">

                    <!-- /// Task Condition /// -->
                    <condition v-model="statusColor" v-bind:selectedStatusColor="statusColor" />
                    <error
                        v-if="validationMessages.colorStatus && validationMessages.colorStatus.length"
                        v-for="message in validationMessages.colorStatus"
                        :message="message" />
                    <!-- /// End Task Condition /// -->

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-task-management-list'}" class="btn-rounded btn-auto disable-bg">{{ button.cancel }}</router-link>
                        <a v-if="!isEdit" @click="createTask" class="btn-rounded btn-auto second-bg">{{ button.create_task }}</a>
                        <a v-if="isEdit" @click="editExistingTask" class="btn-rounded btn-auto second-bg">{{ button.edit_task }}</a>
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
import Attachments from './Create/Attachments';
import datepicker from 'vuejs-datepicker';
import Switches from '../../3rdparty/vue-switches';
import Error from '../../_common/_messages/Error.vue';
import AlertModal from '../../_common/AlertModal.vue';
import {mapGetters, mapActions} from 'vuex';
import {createFormData} from '../../../helpers/task';
import router from '../../../router';
import _ from 'lodash';

export default {
    components: {
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
        Error,
        AlertModal,
    },
    methods: {
        ...mapActions(['createNewTask', 'getTaskById', 'editTask']),
        createTask: function() {
            let data = {
                project: this.$route.params.id,
                name: this.title,
                type: 2,
                description: this.$refs.description.getContent(),
                schedule: this.schedule,
                internalCosts: this.internalCosts,
                externalCosts: this.externalCosts,
                subtasks: this.subtasks,
                planning: this.planning,
                medias: this.medias,
                details: this.details,
                statusColor: this.statusColor,
            };

            this
                .createNewTask({
                    data: createFormData(data),
                    projectId: this.$route.params.id,
                })
                .then(
                    () => {
                        if (this.task.id) {
                            this.showSaved = true;
                        } else {
                            this.showFailed = true;
                        }
                    },
                    () => {
                        this.showFailed = true;
                    }
                )
            ;
        },
        editExistingTask: function() {
            let data = {
                project: this.$route.params.id,
                name: this.title,
                type: 2,
                description: this.$refs.description.getContent(),
                schedule: this.schedule,
                internalCosts: this.internalCosts,
                externalCosts: this.externalCosts,
                subtasks: this.subtasks,
                planning: this.planning,
                medias: this.medias,
                details: this.details,
                statusColor: this.statusColor,
            };
            this
                .editTask({
                    data: createFormData(data),
                    taskId: this.$route.params.taskId,
                })
                .then(
                    () => {
                        if (this.task.id) {
                            this.showSaved = true;
                        } else {
                            this.showFailed = true;
                        }
                    },
                    () => {
                        this.showFailed = true;
                    }
                )
            ;
        },
        // event methods
        setInternalCosts(value) {
            this.internalCosts = value;
        },
        setExternalCosts(value) {
            this.externalCosts = value;
        },
        setMedias(value) {
            this.medias = value;
        },
    },
    created() {
        if (this.$route.params.taskId) {
            this.getTaskById(this.$route.params.taskId);
        }
    },
    computed: {
        ...mapGetters({
            task: 'currentTask',
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
    },
    watch: {
        showSaved(value) {
            if (!this.isEdit && value === false) {
                router.push({
                    name: 'project-task-management-list',
//                    name: 'project-task-management-edit',
//                    params: {
//                        id: this.$route.params.id,
//                        taskId: this.task.id,
//                    },
                });
            }
        },
        task(value) {
            this.title = this.task.name;
            this.$refs.description.setContent(this.task.content);
            this.schedule = {
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
                durationInDays: this.task.duration,
            };
            this.statusColor = {
                id: this.task.colorStatus,
                name: this.task.colorStatusName,
            };
            this.details = {
                assignee: this.task.responsibility
                    ? {key: this.task.responsibility, label: this.task.responsibilityFullName}
                    : null,
                status: this.task.workPackageStatus
                    ? {key: this.task.workPackageStatus, label: this.translate(this.task.workPackageStatusName)}
                    : null,
                label: this.task.label
                    ? {key: this.task.label, label: this.task.labelName}
                    : null,
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

            let internal = [];
            let external = [];
            this.task.costs.map((cost) => {
                if (cost.type === 0) {
                    internal.push(_.merge(
                        {},
                        cost,
                        {
                            resource: cost.resource
                                ? {
                                    label: cost.resourceName,
                                    key: cost.resource.toString(),
                                }
                                : null,
                            selectedResource: cost.resource
                                ? {
                                    label: cost.resourceName,
                                    key: cost.resource.toString(),
                                }
                                : null,
                            project: this.$route.params.id ? this.$route.params.id: null,
                            workPackage: this.$route.params.taskId ? this.$route.params.taskId : null,
                        }
                    ));
                } else {
                    external.push(_.merge(
                        {},
                        cost,
                        {
                            selectedUnit: cost.unit && cost.unit.id ? cost.unit.id.toString() : null,
                            capex: cost.expenseType === 0 ? 1 : 0,
                            opex: cost.expenseType === 1 ? 1 : 0,
                            customUnit: '',
                            project: this.$route.params.id ? this.$route.params.id: null,
                            workPackage: this.$route.params.taskId ? this.$route.params.taskId : null,
                        }
                    ));
                }
            });
            this.internalCosts.items = internal;
            this.externalCosts.items = external;

            this.medias = this.task.medias;
        },
    },
    data() {
        return {
            showSaved: false,
            showFailed: false,
            isEdit: this.$route.params.taskId,
            message: {
                create_new_task: this.translate('message.create_new_task'),
                edit_task: this.translate('message.edit_task'),
                import_task: this.translate('message.import_task'),
                task_details: this.translate('message.task_details'),
            },
            label: {
                task_title: this.translate('label.task_title'),
                task_description: this.translate('label.task_description'),
            },
            button: {
                cancel: this.translate('button.cancel'),
                create_task: this.translate('button.create_task'),
                edit_task: this.translate('button.edit_task'),
            },
            schedule: {
                baseStartDate: new Date(),
                baseEndDate: new Date(),
                forecastStartDate: new Date(),
                forecastEndDate: new Date(),
                automatic: false,
                successors: [],
                predecessors: [],
                durationInDays: 0,
            },
            title: '',
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
            },
            medias: [],
            details: {
                status: null,
                assignee: null,
                label: null,
            },
            statusColor: {},
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_variables';
    @import '../../../css/page-section';

    .download-icon {
        line-height: 50px;
        margin-left: 6px;
    }
</style>
