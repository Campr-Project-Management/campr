<template>
    <div class="row">
        <div class="col-md-6">
            <div class="create-phase page-section">
                <!-- /// Header /// -->
                <div class="header flex-v-center">
                    <div>
                        <router-link :to="{name: 'project-phases-and-milestones'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ translateText('message.back_to_phases_and_milestones') }}
                        </router-link>
                        <h1>{{ translateText('message.create_new_milestone')}}</h1>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <div class="form">
                    <!-- /// Milestone Name /// -->
                    <input-field type="text" v-bind:label="translateText('placeholder.milestone_title')" v-model="name" v-bind:content="name" />
                    <!-- /// End Milestone Name /// -->

                    <!-- /// Milestone Description /// -->
                    <div class="vueditor-holder">
                        <div class="vueditor-header">{{ translateText('placeholder.milestone_description') }}</div>
                        <Vueditor ref="content" />
                    </div>
                    <!-- /// End Milestone Description /// -->

                    <hr class="double">

                    <!-- /// Milestone Schedule /// -->
                    <h3>{{ translateText('message.schedule') }}</h3>
                    <span class="note"><b>{{ translateText('message.note') }}:</b> {{ translateText('message.milestone_schedule_note') }}</span>
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <div class="input-holder right" :class="{disabledpicker: isEdit }">
                                    <label class="active">{{ translateText('label.base_due_date') }}</label>
                                    <datepicker v-model="schedule.baseDueDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill" stroke="middle-stroke" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.forecast_due_date') }}</label>
                                    <datepicker v-model="schedule.forecastDueDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill" stroke="middle-stroke" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Milestone Schedule /// -->

                    <hr class="double">

                    <!-- /// Milestone Details /// -->
                    <h3>{{ translateText('message.details') }}</h3>

                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="translateText('label.responsible')"
                                    v-bind:options="projectUsersForSelect"
                                    v-model="details.responsible"
                                    v-bind:currentOption="details.responsible" />
                            </div>
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="translateText('label.status')"
                                    v-bind:options="workPackageStatusesForMilestone"
                                    v-model="details.status"
                                    v-bind:currentOption="details.status" />
                            </div>
                        </div>
                    </div>
                    <!-- /// End Milestone Details /// -->

                    <hr class="double">
                    
                    <!-- /// Milestone Planning /// -->
                    <h3>{{ translateText('message.planning') }}</h3>
                    <div class="row">
                    	<div class="form-group last-form-group">
                        	<div class="col-md-6">                            
                                <select-field
                                    v-bind:title="translateText('label.select_phase')"
                                    v-bind:options="projectPhasesForSelect"
                                    v-if="projectPhases.items && projectPhases.items.length > 0"
                                    v-model="details.phase"
                                    v-bind:currentOption="details.phase" />
                            </div>
                        </div>
                    </div>
                    <!-- /// End Milestone Planning /// -->

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-phases-and-milestones'}" class="btn-rounded btn-auto disable-bg">{{ translateText('button.cancel') }}</router-link>
                        <a v-if="!isEdit" @click="createMilestone()" class="btn-rounded btn-auto second-bg">{{ translateText('button.create_milestone') }}</a>
                        <a v-if="isEdit" @click="editMilestone()" class="btn-rounded btn-auto second-bg">{{ translateText('button.edit_milestone') }}</a>
                    </div>
                    <!-- /// End Actions /// -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import InputField from '../../_common/_form-components/InputField';
import SelectField from '../../_common/_form-components/SelectField';
import datepicker from 'vuejs-datepicker';
import CalendarIcon from '../../_common/_icons/CalendarIcon';
import moment from 'moment';

export default {
    components: {
        InputField,
        SelectField,
        datepicker,
        CalendarIcon,
    },
    methods: {
        ...mapActions([
            'getProjectUsers', 'getWorkPackageStatuses', 'getProjectPhases',
            'createProjectMilestone', 'getProjectMilestone', 'editProjectMilestone',
        ]),
        translateText: function(text) {
            return this.translate(text);
        },
        createMilestone: function() {
            let data = {
                project: this.$route.params.id,
                name: this.name,
                type: 1,
                content: this.$refs.content.getContent(),
                scheduledFinishAt: moment(this.schedule.baseDueDate).format('DD-MM-YYYY'),
                forecastFinishAt: moment(this.schedule.forecastDueDate).format('DD-MM-YYYY'),
                responsibility: this.details.responsible ? this.details.responsible.key : null,
                workPackageStatus: this.details.status ? this.details.status.key: null,
                phase: this.details.phase ? this.details.phase.key : null,
            };
            this.createProjectMilestone(data);
        },
        editMilestone: function() {
            let data = {
                id: this.$route.params.milestoneId,
                project: this.$route.params.id,
                name: this.name,
                type: 1,
                content: this.$refs.content.getContent(),
                scheduledFinishAt: moment(this.schedule.baseDueDate).format('DD-MM-YYYY'),
                forecastFinishAt: moment(this.schedule.forecastDueDate).format('DD-MM-YYYY'),
                responsibility: this.details.responsible ? this.details.responsible.key : null,
                workPackageStatus: this.details.status ? this.details.status.key: null,
                phase: this.details.phase ? this.details.phase.key : null,
            };
            this.editProjectMilestone(data);
        },
    },
    computed: mapGetters({
        projectUsersForSelect: 'projectUsersForSelect',
        workPackageStatusesForMilestone: 'workPackageStatusesForMilestone',
        projectPhasesForSelect: 'projectPhasesForSelect',
        projectPhases: 'projectPhases',
        milestone: 'milestone',
    }),
    watch: {
        milestone(value) {
            this.name = this.milestone.name;
            this.$refs.content.setContent(this.milestone.content);
            this.schedule.baseDueDate = new Date(this.milestone.scheduledFinishAt);
            this.schedule.forecastDueDate = new Date(this.milestone.forecastFinishAt);
            this.details.status = this.milestone.workPackageStatus
                ? {key: this.milestone.workPackageStatus, label: this.translateText(this.milestone.workPackageStatusName)}
                : null
            ;
            this.details.responsible = this.milestone.responsibility
                ? {key: this.milestone.responsibility, label: this.translateText(this.milestone.responsibilityFullName)}
                : null
            ;
            this.details.phase = this.milestone.phase
                ? {key: this.milestone.phase, label: this.translateText(this.milestone.phaseName)}
                : null
            ;
        },
    },
    created() {
        this.getProjectUsers({id: this.$route.params.id});
        this.getWorkPackageStatuses();
        this.getProjectPhases({projectId: this.$route.params.id});
        if (this.$route.params.milestoneId) {
            this.getProjectMilestone(this.$route.params.milestoneId);
        }
    },
    data() {
        return {
            name: '',
            content: '',
            schedule: {
                baseDueDate: new Date(),
                forecastDueDate: new Date(),
            },
            details: {
                status: null,
                responsible: null,
                phase: null,
            },
            isEdit: this.$route.params.milestoneId,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_mixins';
    @import '../../../css/_variables';

    .disabledpicker {
        pointer-events: none;
        opacity: .5;
    }
    .title {
        position: relative;
        top: 15px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 700;
    }
</style>
