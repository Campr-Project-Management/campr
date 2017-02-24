<template>
    <div class="create-task page-section">
        <div class="header flex-v-center">
            <h1>Create New Task</h1>
            <a href="javascript:void(0)" class="btn-rounded btn-auto btn-empty flex">
                <span>Import Task</span>
                <upload-icon></upload-icon>
            </a>
        </div>
        <div class="form">
            <input-field type="text" label="Task Title"></input-field>
            <Vueditor></Vueditor>
            <hr>
            <h3 class="with-label"> Task Schedule</h3>
            <div class="flex flex-space-between dates-top">
                <div class="input-holder right">
                    <label class="active">Base Start Date</label>
                    <datepicker :value="date | moment('DD / MM / YYYY')" format="DD / MM / YYYY"></datepicker>
                    <calendar-icon fill="lighter-fill" stroke="lighter-stroke"></calendar-icon>
                </div>
                <div class="input-holder right">
                    <label class="active">Base Finish Date</label>
                    <datepicker :value="date | moment('DD / MM / YYYY')" format="DD / MM / YYYY"></datepicker>
                    <calendar-icon fill="lighter-fill" stroke="lighter-stroke"></calendar-icon>
                </div>
            </div>
            <div class="flex flex-space-between">
                <div class="input-holder right">
                    <label class="active">Forecast Start Date</label>
                    <datepicker :value="date | moment('DD / MM / YYYY')" format="DD / MM / YYYY"></datepicker>
                    <calendar-icon fill="lighter-fill" stroke="lighter-stroke"></calendar-icon>
                </div>
                <div class="input-holder right">
                    <label class="active">Forecast Finish Date</label>
                    <datepicker :value="date | moment('DD / MM / YYYY')" format="DD / MM / YYYY"></datepicker>
                    <calendar-icon fill="lighter-fill" stroke="lighter-stroke"></calendar-icon>
                </div>
            </div>
            <hr>
            <h3>Task Details</h3>
            <div class="flex flex-space-between">
                <select-field title="Asignee"></select-field>
                <select-field title="Labels"></select-field>
            </div>
            <hr>
            <h3>Planning</h3>
            <div class="flex flex-space-between">
                <select-field title="Phase"></select-field>
                <select-field title="Milestone"></select-field>
            </div>
            <hr>
            <h3>Subtasks</h3>
            <input-field type="text" label="Subtask Description"></input-field>
            <div class="flex flex-direction-reverse">
                <a href="javascript:void(0)" class="btn-rounded btn-auto add-task">Add new subtask +</a>
            </div>
            <hr>
            <h4>Status</h4>
            <div class="status-info">
                <p><span class="second-color">Green: </span>Quisque maximus quam id nunc congue ultricies. Aliquam quis quam lectus. Suspendisse porta ipsum non justo gravida, commodo rhoncus dolor congue.
                </p>
                <p><span class="warning-color">Yellow: </span>Quisque maximus quam id nunc congue ultricies. Aliquam quis quam lectus. Suspendisse porta ipsum non justo gravida, commodo rhoncus dolor congue.
                </p>
                <p><span class="danger-color">Red: </span>Quisque maximus quam id nunc congue ultricies. Aliquam quis quam lectus. Suspendisse porta ipsum non justo gravida, commodo rhoncus dolor congue.
                </p>
            </div>
            <div class="status flex">
                <div class="status-item second-bg"></div>
                <div class="status-item warning-bg"></div>
                <div class="status-item danger-bg"></div>
            </div>
            <hr>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" class="btn-rounded btn-auto disable-bg">Cancel</a>
                <a href="javascript:void(0)" class="btn-rounded btn-auto second-bg">Create Task</a>
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

export default {
    components: {
        InputField,
        SelectField,
        UploadIcon,
        CalendarIcon,
        datepicker,
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
