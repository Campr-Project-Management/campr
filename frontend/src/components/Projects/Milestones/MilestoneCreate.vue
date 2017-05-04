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
                        <h1>{{message.create_new_milestone}}</h1>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <div class="form">
                    <!-- /// Milestone Name /// -->
                    <input-field type="text" v-bind:label="label.milestone_title" v-model="title" v-bind:content="title" />
                    <!-- /// End Milestone Name /// -->

                    <!-- /// Milestone Description /// -->
                    <div class="vueditor-holder">
                        <div class="vueditor-header">{{message.milestone_description}}</div>
                        <Vueditor ref="description" />
                    </div>
                    <!-- /// End Milestone Description /// -->

                    <hr class="double">

                    <!-- /// Milestone Schedule /// -->
                    <h3>{{message.schedule}}</h3>
                    <span class="note"><b>{{message.note}}:</b> {{message.milestone_schedule_note}}</span>
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{label.base_due_date}}</label>
                                    <datepicker v-model="schedule.baseDueDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill" stroke="middle-stroke" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{label.forecast_due_date}}</label>
                                    <datepicker v-model="schedule.forecastDueDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill" stroke="middle-stroke" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Milestone Schedule /// -->

                    <hr class="double">

                    <!-- /// Milestone Details /// -->
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
                    <!-- /// End Milestone Details /// -->

                    <hr class="double">
                    
                    <!-- /// Milestone Planning /// -->
                    <h3>{{message.planning}}</h3>
                    <div class="row">
                    	<div class="form-group last-form-group">
                        	<div class="col-md-6">                            
                                <select-field v-bind:title="label.select_phase" />
                            </div>
                        </div>
                    </div>
                    <!-- /// End Milestone Planning /// -->

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-phases-and-milestones'}" class="btn-rounded btn-auto disable-bg">{{ button.cancel }}</router-link>
                        <a href="#" class="btn-rounded btn-auto second-bg">{{button.create_milestone}}</a>
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
    data() {
        return {
            message: {
                back_to_phases_and_milestones: this.translate('Back to Phases &amp; Milestones'),
                create_new_milestone: this.translate('Create new Milestone'),
                milestone_description: this.translate('Milestone Description'),
                schedule: this.translate('message.schedule'),
                note: this.translate('Note'),
                milestone_schedule_note: this.translate('Base Due Date will be recalculated based on the Tasks dedicated to this Milestone.'),
                details: this.translate('Details'),
            },
            label: {
                milestone_title: this.translate('Milestone Title'),
                base_due_date: this.translate('Base Due Date'),
                forecast_due_date: this.translate('Forecast Due Date'),
                responsible: this.translate('label.responsible'),
                status: this.translate('label.status'),
                select_phase: this.translate('Select Phase'),
            },
            schedule: {
                baseDueDate: new Date(),
                forecastDueDate: new Date(),
            },
            button: {
                cancel: this.translate('button.cancel'),
                create_milestone: this.translate('Create Milestone'),
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
