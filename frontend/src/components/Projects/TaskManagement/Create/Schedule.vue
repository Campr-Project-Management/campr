<template>
    <div>
        <h3 class="with-label">{{ message.task_schedule }}</h3>

        <div class="row" v-if="editableBase">
            <div class="form-group">
                <div class="col-md-6">
                    <div class="input-holder right">
                        <label class="active">{{ label.base_start_date }}</label>
                        <date-field
                                :value="value.baseStartDate"
                                @input="onInput('baseStartDate', $event)"/>
                    </div>
                    <error at-path="scheduledStartAt"/>
                </div>
                <div class="col-md-6">
                    <div class="input-holder right">
                        <label class="active">{{ label.base_end_date }}</label>
                        <date-field
                                :value="value.baseEndDate"
                                format="dd-MM-yyyy"
                                @input="onInput('baseEndDate', $event)"/>
                    </div>
                    <error at-path="scheduledFinishAt"/>
                </div>
            </div>
        </div>

        <div class="row" v-if="editableForecast">
            <div class="form-group last-form-group">
                <div class="col-md-6">
                    <div class="input-holder right">
                        <label class="active">{{ label.forecast_start_date }}</label>
                        <date-field
                                :value="value.forecastStartDate"
                                format="dd-MM-yyyy"
                                @input="onInput('forecastStartDate', $event)"/>
                    </div>
                    <error at-path="forecastStartAt"/>
                </div>
                <div class="col-md-6">
                    <div class="input-holder right">
                        <label class="active">{{ label.forecast_end_date }}</label>
                        <date-field
                                :value="value.forecastEndDate"
                                format="dd-MM-yyyy"
                                @input="onInput('forecastEndDate', $event)"/>
                    </div>
                    <error at-path="forecastFinishAt"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import InputField from '../../../_common/_form-components/InputField';
    import Error from '../../../_common/_messages/Error.vue';
    import Switches from '../../../3rdparty/vue-switches';
    import {mapActions, mapGetters} from 'vuex';
    import DateField from '../../../_common/_form-components/DateField';

    export default {
        name: 'schedule',
        props: {
            value: {
                type: Object,
                required: false,
            },
            editableBase: {
                type: Boolean,
                required: false,
                default: true,
            },
            editableForecast: {
                type: Boolean,
                required: false,
                default: true,
            },
        },
        components: {
            DateField,
            InputField,
            Error,
            Switches,
        },
        methods: {
            ...mapActions(['getWorkPackages']),
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
        },
        data() {
            return {
                message: {
                    task_schedule: this.translate('message.task_schedule'),
                    manual_schedule: this.translate('message.manual_schedule'),
                    manual_schedule_note: this.translate('message.manual_schedule_note'),
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

