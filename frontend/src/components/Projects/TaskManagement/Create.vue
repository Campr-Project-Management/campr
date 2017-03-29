<template>
    <div class="create-task page-section">
        <div class="header flex-v-center">
            <h1>{{ message.create_new_task }}</h1>
            <a href="javascript:void(0)" class="btn-rounded btn-auto btn-empty flex">
                <span>{{ message.import_task }}</span>
                <upload-icon></upload-icon>
            </a>
        </div>
        <div class="form">
            <input-field v-model="title" type="text" label="Task Title"></input-field>
            <Vueditor></Vueditor>
            <hr>
            <h3 class="with-label">{{ message.task_schedule }}</h3>
            <div class="flex flex-space-between dates-top">
                <div class="input-holder right">
                    <label class="active">{{ label.base_start_date }}</label>
                    <datepicker :value="date | moment('DD-MM-YYYY')" format="DD-MM-YYYY"></datepicker>
                    <calendar-icon fill="lighter-fill" stroke="lighter-stroke"></calendar-icon>
                </div>
                <div class="input-holder right">
                    <label class="active">{{ label.base_end_date }}</label>
                    <datepicker :value="date | moment('DD-MM-YYYY')" format="DD-MM-YYYY"></datepicker>
                    <calendar-icon fill="lighter-fill" stroke="lighter-stroke"></calendar-icon>
                </div>
            </div>
            <div class="flex flex-space-between">
                <div class="input-holder right">
                    <label class="active">{{ label.forecast_start_date }}</label>
                    <datepicker :value="date | moment('DD-MM-YYYY')" format="DD-MM-YYYY"></datepicker>
                    <calendar-icon fill="lighter-fill" stroke="lighter-stroke"></calendar-icon>
                </div>
                <div class="input-holder right">
                    <label class="active">{{ label.forecast_end_date }}</label>
                    <datepicker :value="date | moment('DD-MM-YYYY')" format="DD-MM-YYYY"></datepicker>
                    <calendar-icon fill="lighter-fill" stroke="lighter-stroke"></calendar-icon>
                </div>
            </div>
            <hr>
            <h3>{{ message.task_details }}</h3>
            <div class="flex flex-space-between">
                <select-field v-bind:title="label.asignee"></select-field>
                <select-field v-bind:title="label.labels" v-bind:options="labelsForChoice"></select-field>
            </div>
            <hr>
            <h3>{{ message.planning }}</h3>
            <div class="flex flex-space-between">
                <select-field v-bind:title="label.phase"></select-field>
                <select-field v-bind:title="label.milestone"></select-field>
            </div>
            <hr>
            <h3>{{ message.subtasks }}</h3>
            <input-field type="text" v-bind:label="label.subtask_description"></input-field>
            <div class="flex flex-direction-reverse">
                <a href="javascript:void(0)" class="btn-rounded btn-auto add-task">{{ button.add_new_subtask }} +</a>
            </div>
            <hr>
            <h4>{{ label.status }}</h4>
            <div class="status-info">
                <p v-for="status in colorStatuses"><span v-bind:style="{ color: status.color }">{{ status.name }}</span></p>
            </div>
            <div class="status flex">
                <div v-for="status in colorStatuses" v-bind:style="{ background: status.color }" class="status-item"></div>
            </div>
            <hr>
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
import datepicker from 'vue-date-picker';
import {mapActions, mapGetters} from 'vuex';

export default {
    components: {
        InputField,
        SelectField,
        UploadIcon,
        CalendarIcon,
        datepicker,
    },
    methods: {
        ...mapActions(['getColorStatuses', 'getProjectLabels', 'createNewTask']),
        createTask: function() {
            let data = {
                project: this.$route.params.id,
                name: this.title,
                puid: '123',
                progress: 0,
            };
            this.createNewTask(data);
        },
    },
    created() {
        if (!this.$store.state.colorStatus || this.$store.state.colorStatus.items.length == 0) {
            this.getColorStatuses();
        }
        if (!this.$store.state.project.labelsForChoice || this.$store.state.project.labelsForChoice.length == 0) {
            this.getProjectLabels(this.$route.params.id);
        }
    },
    computed: mapGetters({
        colorStatuses: 'colorStatuses',
        labelsForChoice: 'labelsForChoice',
    }),
    data() {
        return {
            message: {
                create_new_task: Translator.trans('message.create_new_task'),
                import_task: Translator.trans('message.import_task'),
                task_schedule: Translator.trans('message.task_schedule'),
                task_details: Translator.trans('message.task_details'),
                planning: Translator.trans('message.planning'),
                subtasks: Translator.trans('message.subtasks'),
            },
            label: {
                base_start_date: Translator.trans('label.base_start_date'),
                base_end_date: Translator.trans('label.base_end_date'),
                forecast_start_date: Translator.trans('label.forecast_start_date'),
                forecast_end_date: Translator.trans('label.forecast_end_date'),
                status: Translator.trans('label.status'),
                asignee: Translator.trans('label.asignee'),
                labels: Translator.trans('label.labels'),
                phase: Translator.trans('label.phase'),
                milestone: Translator.trans('label.milestone'),
                subtask_description: Translator.trans('label.subtask_description'),
            },
            button: {
                add_new_subtask: Translator.trans('button.add_new_subtask'),
                cancel: Translator.trans('button.cancel'),
                create_task: Translator.trans('button.create_task'),
            },
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style lang="scss">
    .vueditor {
        border: none !important;
        background: #191E37;
        margin-top: 20px;
        padding: 0 20px 10px;
    }

    .ve-toolbar {
        border-bottom: 1px solid #646EA0;
    }

    .download-icon {
        line-height: 50px;
        margin-left: 6px;
    }
</style>

<style scoped lang="scss">
    @import '../../../css/page-section';
    @import '../../../css/_common';

    .dropdown, .datetime-picker {
        width: 400px !important;
    }

    .create-task {
        max-width: 820px;
    }

    hr {
        border-top: 1px solid #000;
    }

    h3 {
        font-size: 14px;
        text-transform: uppercase;
        font-weight: 300;
        margin-bottom: 15px;

        &.with-label {
            margin-bottom: 25px;
        }
    }

    .dates-top {
        margin-bottom: 20px;
    }

    .status-item {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin-right: 10px;
    }

    h4 {
        text-transform: uppercase;
        font-size: 12px;
        font-weight: 300;
        letter-spacing: 1.6px;
    }

    .add-task {
        margin-top: 20px;
    }

    .status-info {
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

    .status {
        margin-top: 22px;
    }
</style>
