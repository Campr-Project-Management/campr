<template>
    <div class="row">
        <div class="col-md-6">
            <div class="create-phase page-section">
                <!-- /// Header /// -->
                <div class="header flex-v-center">
                    <div>
                        <router-link :to="{name: 'project-phases-and-milestones'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{message.back_to_phases_and_milestones}}
                        </router-link>
                        <h1>{{message.create_new_phase}}</h1>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <div class="form">
                    <!-- /// Phase Name /// -->
                    <input-field type="text" v-bind:label="label.phase_title" v-model="title" v-bind:content="title" />
                    <!-- /// End Phase Name /// -->

                    <!-- /// Phase Description /// -->
                    <div class="vueditor-holder">
                        <div class="vueditor-header">{{message.phase_description}}</div>
                        <Vueditor ref="description" />
                    </div>
                    <!-- /// End Phase Description /// -->

                    <hr class="double">

                    <!-- /// Phase Schedule /// -->
                    <h3>{{message.schedule}}</h3>
                    <h4>{{ message.automatic_schedule }}</h4>
                    <span class="note">{{message.automatic_phase_schedule_note}}</span>
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <span class="title">
                                    {{message.start_date}}: -</b>
                                </span>
                            </div>
                            <div class="col-md-6">
                                <span class="title">
                                    {{message.finish_date}}: -</b>
                                </span>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h4>{{ message.manual_schedule }}</h4>
                    <span class="note no-margin-bottom">{{message.manual_phase_schedule_note_1}}</span>
                    <span class="note"><b>{{message.note}}:</b> {{message.manual_phase_schedule_note_2}}</span>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{ label.base_start_date }}</label>
                                    <datepicker v-model="schedule.baseStartDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill" stroke="middle-stroke" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{ label.base_end_date }}</label>
                                    <datepicker v-model="schedule.baseEndDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill" stroke="middle-stroke" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{ label.forecast_start_date }}</label>
                                    <datepicker v-model="schedule.forecastStartDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill" stroke="middle-stroke" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{ label.forecast_end_date }}</label>
                                    <datepicker v-model="schedule.forecastEndDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill" stroke="middle-stroke" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Phase Schedule /// -->

                    <hr class="double">

                    <!-- /// Phase Details /// -->
                    <h3>{{message.details}}</h3>

                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="label.responsible"
                                    v-bind:options="responsibleForSelect"
                                    v-model="details.responsible"
                                    v-bind:currentOption="details.responsible" />
                            </div>
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="label.status"
                                    v-bind:options="workPackageStatusesForSelect"
                                    v-model="details.status"
                                    v-bind:currentOption="details.status" />
                            </div>
                        </div>
                    </div>
                    <!-- /// End Phase Details /// -->

                    <hr class="double">
                    
                    <!-- /// Is Subphase /// -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="checkbox-input clearfix">
                                <input id="is-subphase" type="checkbox" v-model="visibleSubphase">
                                <label for="is-subphase" class="no-margin-bottom">{{label.phase_is_subphase}}</label>
                            </div>
                        </div>
                        <div class="col-md-6 margintop20" v-if="visibleSubphase">
                            <div class="form-group last-form-group">
                                <select-field v-bind:title="label.parent_phase" />
                            </div>
                        </div>
                    </div>
                    <!-- /// End Is Subphase /// -->

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-phases-and-milestones'}" class="btn-rounded btn-auto disable-bg">{{ button.cancel }}</router-link>
                        <a href="#" class="btn-rounded btn-auto second-bg">{{button.create_phase}}</a>
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
import datepicker from 'vuejs-datepicker';
import CalendarIcon from '../../_common/_icons/CalendarIcon';

export default {
    components: {
        InputField,
        SelectField,
        datepicker,
        CalendarIcon,
    },
    methods: {
        visibleSubphase: function() {
            this.visibleSubphase = !this.visibleSubphase;
        },
    },
    data() {
        return {
            message: {
                back_to_phases_and_milestones: this.translate('Back to Phases &amp; Milestones'),
                create_new_phase: this.translate('Create new Phase'),
                phase_description: this.translate('Phase Description'),
                schedule: this.translate('message.schedule'),
                manual_schedule: this.translate('message.manual_schedule'),
                manual_phase_schedule_note_1: this.translate('Add manual dates for Base and Forecast schedule.'),
                note: this.translate('Note'),
                manual_phase_schedule_note_2: this.translate('Base Start Date and Base Finish Date are overwritten by the automatically calculated dates.'),
                automatic_schedule: this.translate('message.automatic_schedule'),
                automatic_phase_schedule_note: this.translate(`Calculated based on the Start Date of the first Task/Milestone and
                 the Finish Date of the last Task/Milestone added to this phase.`),
                start_date: this.translate('Start Date'),
                finish_date: this.translate('Finish Date'),
                details: this.translate('Details'),
            },
            label: {
                phase_title: this.translate('Phase Title'),
                base_start_date: this.translate('label.base_start_date'),
                base_end_date: this.translate('label.base_end_date'),
                forecast_start_date: this.translate('label.forecast_start_date'),
                forecast_end_date: this.translate('label.forecast_end_date'),
                responsible: this.translate('label.responsible'),
                status: this.translate('label.status'),
                phase_is_subphase: this.translate('This is a subphase'),
                parent_phase: this.translate('Select parent phase'),
            },
            schedule: {
                baseStartDate: new Date(),
                baseEndDate: new Date(),
                forecastStartDate: new Date(),
                forecastEndDate: new Date(),
            },
            button: {
                cancel: this.translate('button.cancel'),
                create_phase: this.translate('Create Phase'),
            },
            details: {
                status: null,
                responsible: null,
            },
            visibleSubphase: false,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_mixins';
    @import '../../../css/_variables';

    .title {
        position: relative;
        top: 15px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 700;
    }
</style>
