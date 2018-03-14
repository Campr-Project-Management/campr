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
                        @click="toggleAutomaticSchedule"
                        :checked="!value.automatic">
                    <label for="manual-schedule">{{ message.manual_schedule }}</label>

                    <input
                        id="automatic-schedule"
                        name="schedule-planning"
                        type="radio"
                        @click="toggleAutomaticSchedule"
                        :checked="value.automatic">
                    <label for="automatic-schedule">{{ message.automatic_schedule }}</label>
                </div>
            </div>
        </div>

        <div v-if="!value.automatic">
            <span class="note">{{ message.manual_schedule_note }}</span>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6">
                        <div class="input-holder right">
                            <label class="active">{{ label.base_start_date }}</label>
                            <datepicker
                                    :value="value.baseStartDate"
                                    format="dd-MM-yyyy"
                                    @input="onInput('baseStartDate', $event)"/>
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
                            <datepicker
                                    :value="value.baseEndDate"
                                    format="dd-MM-yyyy"
                                    @input="onInput('baseEndDate', $event)"/>
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
                            <datepicker
                                    :value="value.forecastStartDate"
                                    format="dd-MM-yyyy"
                                    @input="onInput('forecastStartDate', $event)"/>
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
                            <datepicker
                                    :value="value.forecastEndDate"
                                    format="dd-MM-yyyy"
                                    @input="onInput('forecastEndDate', $event)"/>
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

        <div v-if="value.automatic">
            <span class="note">{{ message.automatic_schedule_note }}</span>
            <div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-4">
                            <multi-select-field
                                :title="label.select_predecessors"
                                :options="predecessorsForSelect"
                                :selectedOptions="value.predecessors"
                                :value="value.predecessors"
                                @input="onInput('predecessors', $event)"/>
                        </div>
                        <div class="col-md-4">
                            <multi-select-field
                                :title="label.select_successors"
                                :options="successorsForSelect"
                                :selectedOptions="value.successors"
                                :value="value.successors"
                                @input="onInput('successors', $event)"/>
                        </div>
                        <div class="col-md-4">
                            <input-field
                                type="number"
                                :label="label.duration_in_days"
                                :value="value.duration"
                                @input="onInput('duration', $event)"/>
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
                                <datepicker
                                        :value="value.forecastStartDate"
                                        format="dd-MM-yyyy"
                                        @input="onInput('forecastStartDate', $event)"/>
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
                                <datepicker
                                        :value="value.forecastEndDate"
                                        format="dd-MM-yyyy"
                                        @input="onInput('forecastEndDate', $event)"/>
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
    props: {
        value: {
            type: Object,
            required: true,
        },
    },
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
        toggleAutomaticSchedule() {
            this.$emit('input', Object.assign(this.value, {automatic: !this.value.automatic}));
        },
        onInput(field, value) {
            this.$emit('input', Object.assign(this.value, {[field]: value}));
        },
    },
    created() {
        this.getWorkPackages(this.$route.params.id);
    },
    computed: {
        ...mapGetters({
            tasks: 'projectTasks',
            tasksForSelect: 'projectTasksForSelect',
            validationMessages: 'validationMessages',
        }),
        predecessorsForSelect: function() {
            if (!this.value.successors || !this.value.successors.length) {
                return this.tasksForSelect;
            }

            let successors = new Set(this.value.successors);
            return [...new Set([...this.tasksForSelect].filter(item => !successors.has(item)))];
        },
        successorsForSelect: function() {
            if (!this.value.predecessors || !this.value.predecessors.length) {
                return this.tasksForSelect;
            }

            let predecessors = new Set(this.value.predecessors);
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
        };
    },
};
</script>

<style scoped lang="scss">
    @import '../../../../css/_common';
</style>
