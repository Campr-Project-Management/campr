<template>
    <div class="create-task page-section">
        <div class="header flex-v-center">
            <h1>{{ message.create_new_task }}</h1>
            <a class="btn-rounded btn-auto btn-empty flex">
                <span>{{ message.import_task }}</span>
                <upload-icon></upload-icon>
            </a>
        </div>
        <div class="form">
            <input-field type="text" v-bind:label="label.task_title" v-model="title" v-bind:content="title" />

            <div class="vueditor-holder">
                <div class="vueditor-header">{{ label.task_description }}</div>
                <Vueditor ref="description" />
                <div cass="vueditor-footer clearfix">
                    <div class="pull-right"></div>
                </div>
            </div>

            <hr class="double">

            <planning v-model="planning" />

            <hr>

            <schedule v-model="schedule" />

            <hr class="double">

            <internal-costs v-model="internalCosts" />

            <hr class="double">

            <external-costs v-model="externalCosts" />

            <hr class="double">

            <task-details v-model="details" />

            <hr class="double">

            <subtasks v-model="subtasks" />

            <hr class="double">

            <attachments v-model="attachments" />

            <hr class="double">

            <condition v-model="statusColor" v-bind:selectedStatusColor="statusColor" />

            <hr class="double">

            <div class="flex flex-space-between">
                <router-link :to="{name: 'project-task-management-list'}" class="btn-rounded btn-auto disable-bg">{{ button.cancel }}</router-link>
                <a v-on:click="createTask" class="btn-rounded btn-auto second-bg">{{ button.create_task }}</a>
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
            schedule: {},
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
<style lang="scss">
    @import '../../../css/_variables';
    @import '../../../css/page-section';

    .download-icon {
        line-height: 50px;
        margin-left: 6px;
    }

    .title {
        line-height: 41px;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1.6px;
    }

    .create-task {
        max-width: 820px;
    }

    h3 {
        font-size: 16px;
        text-transform: uppercase;
        font-weight: 300;
        letter-spacing: 1.6px;
        margin-bottom: 15px;

        &.with-label {
            margin-bottom: 25px;
        }
    }

    p {
        margin-bottom: 20px;
    }

    .dates-top {
        margin-bottom: 20px;
    }

    .condition-item {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin-right: 10px;
    }

    h4 {
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1.6px;
    }

    .add-task {
        margin-top: 20px;
    }

    .condition-info {
        font-weight: 300;
        font-size: 10px;

        span {
            text-transform: uppercase;
            letter-spacing: 1.6px;
        }

        p {
            letter-spacing: 0.3px;
            margin-bottom: 8px;
        }
    }

    .condition {
        margin-top: 22px;
    }

    .note {
        display: block;
        font-size: 0.875em;
        margin: 10px 0 20px;

        &.blue-note {
            margin: 0 0 20px;
            color: $middleColor;
        }

        &.no-margin-bottom {
            margin: 10px 0 0;
        }
    }

    .radio-list {
        label {
            margin: 10px 0 10px 10px;
        }
    }

    .vue-switcher {
        margin: 3px 10px 0 0;
    }

    .status-boxes {
        .status-box {
            width: 30px;
            height: 30px;
            margin-right: 5px;
        }
    }
</style>
