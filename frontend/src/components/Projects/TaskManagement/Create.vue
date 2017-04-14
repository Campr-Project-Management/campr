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
                        <h1>{{ message.create_new_task }}</h1>
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
                    <planning v-model="planning" />
                    <!-- /// End Task Planning /// -->

                    <hr>

                    <!-- /// Task Schedule /// -->
                    <schedule v-model="schedule" />
                    <!-- /// End Task Schedule /// -->

                    <hr class="double">

                    <!-- /// Task Internal Costs /// -->
                    <internal-costs v-model="internalCosts" />
                    <!-- /// End Task Internal Costs /// -->

                    <hr class="double">

                    <!-- /// Task External Costs /// -->
                    <external-costs v-model="externalCosts" />
                    <!-- /// End Task External Costs /// -->

                    <hr class="double">

                    <!-- /// Task Details /// -->
                    <task-details v-model="details" />
                    <!-- /// End Task Details /// -->

                    <hr class="double">

                    <!-- /// SubTasks /// -->
                    <subtasks v-model="subtasks" />
                    <!-- /// End SubTasks /// -->

                    <hr class="double">

                    <!-- /// Task Attachments /// -->
                    <attachments v-model="attachments" />
                    <!-- /// End Task Attachments /// -->

                    <hr class="double">

                    <!-- /// Task Condition /// -->
                    <condition v-model="statusColor" v-bind:selectedStatusColor="statusColor" />
                    <!-- /// End Task Condition /// -->

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-task-management-list'}" class="btn-rounded btn-auto disable-bg">{{ button.cancel }}</router-link>
                        <a v-on:click="createTask" class="btn-rounded btn-auto second-bg">{{ button.create_task }}</a>
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
import {mapActions} from 'vuex';
import {createFormData} from '../../../helpers/task';

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
    },
    methods: {
        ...mapActions(['createNewTask']),
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
                attachments: this.attachments,
                details: this.details,
                statusColor: this.statusColor,
            };

            this.createNewTask({
                data: createFormData(data),
                projectId: this.$route.params.id,
            });
        },
    },
    data() {
        return {
            message: {
                create_new_task: this.translate('message.create_new_task'),
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
            internalCosts: [],
            externalCosts: {},
            subtasks: [],
            planning: {},
            attachments: [],
            details: {},
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
