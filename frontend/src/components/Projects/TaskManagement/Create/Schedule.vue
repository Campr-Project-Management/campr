<template>
    <div>
        <h3 class="with-label">{{ message.task_schedule }}</h3>

        <div class="row">
            <div class="col-md-12">
                <div class="radio-input">
                    <input
                        id="manual-schedule"
                        name="schedule-planning"
                        type="radio"
                        value="manual"
                        v-on:click="toggleManualSchedule"
                        v-bind:checked="visibleManualSchedule">
                    <label for="manual-schedule">{{ message.manual_schedule }}</label>
                    <input
                        id="automatic-schedule"
                        type="radio"
                        name="schedule-planning"
                        v-on:click="toggleAutomaticSchedule"
                        v-bind:checked="visibleAutomaticSchedule">
                    <label for="automatic-schedule">{{ message.automatic_schedule }}</label>
                </div>
            </div>
        </div>

        <div v-show="visibleManualSchedule">
            <span class="note">{{ message.manual_schedule_note }}</span>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6">
                        <div class="input-holder right">
                            <label class="active">{{ label.base_start_date }}</label>
                            <datepicker v-model="schedule.baseStartDate" format="dd-MM-yyyy" />
                            <calendar-icon fill="middle-fill"/>
                        </div>
                        <error
                            v-if="validationMessages.scheduledStartAt && validationMessages.scheduledStartAt.length"
                            v-for="message in validationMessages.scheduledStartAt"
                            :message="message" />
                    </div>
                    <div class="col-md-6">
                        <div class="input-holder right">
                            <label class="active">{{ label.base_end_date }}</label>
                            <datepicker v-model="schedule.baseEndDate" format="dd-MM-yyyy" />
                            <calendar-icon fill="middle-fill"/>
                        </div>
                        <error
                            v-if="validationMessages.scheduledFinishAt && validationMessages.scheduledFinishAt.length"
                            v-for="message in validationMessages.scheduledFinishAt"
                            :message="message" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group last-form-group">
                    <div class="col-md-6">
                        <div class="input-holder right">
                            <label class="active">{{ label.forecast_start_date }}</label>
                            <datepicker v-model="schedule.forecastStartDate" format="dd-MM-yyyy" />
                            <calendar-icon fill="middle-fill"/>
                        </div>
                        <error
                            v-if="validationMessages.forecastStartAt && validationMessages.forecastStartAt.length"
                            v-for="message in validationMessages.forecastStartAt"
                            :message="message" />
                    </div>
                    <div class="col-md-6">
                        <div class="input-holder right">
                            <label class="active">{{ label.forecast_end_date }}</label>
                            <datepicker v-model="schedule.forecastEndDate" format="dd-MM-yyyy" />
                            <calendar-icon fill="middle-fill"/>
                        </div>
                        <error
                            v-if="validationMessages.forecastFinishAt && validationMessages.forecastFinishAt.length"
                            v-for="message in validationMessages.forecastFinishAt"
                            :message="message" />
                    </div>
                </div>
            </div>
        </div>           

        <div v-show="visibleAutomaticSchedule">
            <span class="note">{{ message.automatic_schedule_note }}</span>
            <div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-4">
                            <multi-select-field
                                v-bind:title="label.select_predecessors"
                                v-bind:options="predecessorsForSelect"
                                v-bind:selectedOptions="schedule.predecessors"
                                v-model="schedule.predecessors" />
                        </div>
                        <div class="col-md-4">
                            <multi-select-field
                                v-bind:title="label.select_successors"
                                v-bind:options="successorsForSelect"
                                v-bind:selectedOptions="schedule.successors"
                                v-model="schedule.successors" />
                        </div>
                        <div class="col-md-4">
                            <input-field
                                type="text"
                                v-bind:label="label.duration_in_days"
                                v-bind:content="schedule.durationInDays"
                                v-model="schedule.durationInDays" />
                            <error
                                v-if="validationMessages.duration && validationMessages.duration.length"
                                v-for="message in validationMessages.duration"
                                :message="message" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group last-form-group">
                        <div class="col-md-6">
                            <div class="input-holder right">
                                <label class="active">{{ label.forecast_start_date }}</label>
                                <datepicker v-model="schedule.forecastStartDate" format="dd-MM-yyyy" />
                                <calendar-icon fill="middle-fill"/>
                            </div>
                            <error
                                v-if="validationMessages.forecastStartAt && validationMessages.forecastStartAt.length"
                                v-for="message in validationMessages.forecastStartAt"
                                :message="message" />
                        </div>
                        <div class="col-md-6">
                            <div class="input-holder right">
                                <label class="active">{{ label.forecast_end_date }}</label>
                                <datepicker v-model="schedule.forecastEndDate" format="dd-MM-yyyy" />
                                <calendar-icon fill="middle-fill"/>
                            </div>
                            <error
                                v-if="validationMessages.forecastFinishAt && validationMessages.forecastFinishAt.length"
                                v-for="message in validationMessages.forecastFinishAt"
                                :message="message" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import InputField from '../../../_common/_form-components/InputField';
import MultiSelectField from '../../../_common/_form-components/MultiSelectField';
import CalendarIcon from '../../../_common/_icons/CalendarIcon';
import datepicker from '../../../_common/_form-components/Datepicker';
import Error from '../../../_common/_messages/Error.vue';
import Switches from '../../../3rdparty/vue-switches';
import {mapActions, mapGetters} from 'vuex';

export default {
    props: ['editSchedule'],
    components: {
        InputField,
        MultiSelectField,
        CalendarIcon,
        datepicker,
        Error,
        Switches,
    },
    methods: {
        ...mapActions(['getWorkPackages']),
        toggleManualSchedule: function() {
            this.visibleManualSchedule = !this.visibleManualSchedule;
            this.visibleAutomaticSchedule = !this.visibleManualSchedule;
            this.schedule.automatic = this.visibleAutomaticSchedule;
        },
        toggleAutomaticSchedule: function() {
            this.visibleAutomaticSchedule = !this.visibleAutomaticSchedule;
            this.visibleManualSchedule = !this.visibleAutomaticSchedule;
            this.schedule.automatic = this.visibleAutomaticSchedule;
        },
        togglePredecessor: function() {
            this.visiblePredecessor = !this.visiblePredecessor;
            this.visibleSuccessor = !this.visibleSuccessor;
        },
        toggleSuccessor: function() {
            this.visibleSuccessor = !this.visibleSuccessor;
            this.visiblePredecessor = !this.visiblePredecessor;
        },
    },
    watch: {
        schedule: {
            handler: function(value) {
                this.$emit('input', value);
            },
            deep: true,
        },
        editSchedule(value) {
            this.schedule = this.editSchedule;
            this.visibleAutomaticSchedule = this.schedule.automatic;
            this.visibleManualSchedule = !this.schedule.automatic;
        },
    },
    created() {
        this.getWorkPackages(this.$route.params.id);
        this.schedule = this.editSchedule;
        this.visibleAutomaticSchedule = this.schedule.automatic;
        this.visibleManualSchedule = !this.schedule.automatic;
    },
    computed: {
        ...mapGetters({
            tasks: 'projectTasks',
            tasksForSelect: 'projectTasksForSelect',
            validationMessages: 'validationMessages',
        }),
        predecessorsForSelect: function() {
            if (!this.schedule.successors || !this.schedule.successors.length) {
                return this.tasksForSelect;
            }

            let successors = new Set(this.schedule.successors);
            return [...new Set([...this.tasksForSelect].filter(item => !successors.has(item)))];
        },
        successorsForSelect: function() {
            if (!this.schedule.predecessors || !this.schedule.predecessors.length) {
                return this.tasksForSelect;
            }

            let predecessors = new Set(this.schedule.predecessors);
            return [...new Set([...this.tasksForSelect].filter(item => !predecessors.has(item)))];
        },
    },
    data() {
        return {
            message: {
                task_schedule: this.translate('message.task_schedule'),
                manual_schedule: this.translate('message.manual_schedule'),
                manual_schedule_note: this.translate('message.manual_schedule_note'),
                automatic_schedule: this.translate('message.automatic_schedule'),
                automatic_schedule_note: this.translate('message.automatic_schedule_note'),
                forecast_start_date: this.translate('message.forecast_start_date'),
                forecast_end_date: this.translate('message.forecast_end_date'),
            },
            label: {
                base_start_date: this.translate('label.base_start_date'),
                base_end_date: this.translate('label.base_end_date'),
                forecast_start_date: this.translate('label.forecast_start_date'),
                forecast_end_date: this.translate('label.forecast_end_date'),
                select_successors: this.translate('label.select_successors'),
                select_predecessors: this.translate('label.select_predecessors'),
                duration_in_days: this.translate('label.duration_in_days'),
                has_predecessor: this.translate('label.has_predecessor'),
                has_successor: this.translate('label.has_successor'),
            },
            visibleManualSchedule: true,
            visibleAutomaticSchedule: false,
            visiblePredecessor: true,
            visibleSuccessor: false,
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
        };
    },
};
</script>
