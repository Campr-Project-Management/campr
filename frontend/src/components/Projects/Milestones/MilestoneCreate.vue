<template>
    <div class="row">
        <div class="col-md-6 custom-col-md-6">
            <div class="create-phase page-section">
                <!-- /// Header /// -->
                <div class="header flex-v-center">
                    <div>
                        <router-link :to="{name: 'project-phases-and-milestones'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ translateText('message.back_to_phases_and_milestones') }}
                        </router-link>
                        <h1 v-if="!isEdit">{{ translateText('message.create_new_milestone') }}</h1>
                        <h1 v-else>{{ translateText('message.edit_milestone') }} - {{ name }}</h1>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <div class="form">
                    <!-- /// Milestone Name /// -->
                    <input-field type="text" v-bind:label="translateText('placeholder.milestone_name')" v-model="name" v-bind:content="name" />
                    <error
                        v-if="validationMessages.name && validationMessages.name.length"
                        v-for="message in validationMessages.name"
                        :message="message" />
                    <!-- /// End Milestone Name /// -->

                    <!-- /// Milestone Description /// -->
                    <editor
                        v-model="content"
                        :label="'placeholder.milestone_description'"/>
                    <!-- /// End Milestone Description /// -->

                    <hr class="double">

                    <template v-if="!isEdit">
                        <!-- /// Milestone Schedule /// -->
                        <h3>{{ translateText('message.schedule') }}</h3>
                        <br/>
                        <div class="row">
                            <div class="form-group last-form-group">
                                <div class="col-md-6">
                                    <div class="input-holder right" :class="{disabledpicker: isEdit }">
                                        <label class="active">{{ translateText('label.base_start_date') }}</label>
                                        <datepicker v-model="schedule.scheduledStartAt" format="dd-MM-yyyy" />
                                        <calendar-icon fill="middle-fill"/>
                                    </div>
                                    <error at-path="scheduledStartAt"/>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-holder right">
                                        <label class="active">{{ translateText('label.base_end_date') }}</label>
                                        <datepicker v-model="schedule.scheduledFinishAt" format="dd-MM-yyyy" />
                                        <calendar-icon fill="middle-fill"/>
                                    </div>
                                    <error at-path="scheduledFinishAt"/>
                                </div>
                            </div>
                        </div>
                        <!-- /// End Milestone Schedule /// -->

                        <hr class="double">
                    </template>

                    <!-- /// Milestone Details /// -->
                    <h3>{{ translateText('message.details') }}</h3>
                    <br/>

                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <member-search v-model="details.responsible" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
                                <error at-path="responsibility" />
                            </div>
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="translateText('label.status')"
                                    v-bind:options="workPackageStatusesForMilestone"
                                    v-model="details.status"
                                    v-bind:currentOption="details.status" />
                                <error
                                    v-if="validationMessages.workPackageStatus && validationMessages.workPackageStatus.length"
                                    v-for="message in validationMessages.workPackageStatus"
                                    :message="message" />
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
                                    v-model="details.phase"
                                    v-bind:currentOption="details.phase" />
                            </div>
                            <div class="col-md-6">
                                <div class="checkbox-input clearfix">
                                    <input v-model="isKeyMilestone" id="is_key_milestone" type="checkbox" name="" value="">
                                    <label class="no-margin-bottom" for="is_key_milestone">{{ translateText('label.is_key_milestone') }}</label>
                                </div>
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
import datepicker from '../../_common/_form-components/Datepicker';
import CalendarIcon from '../../_common/_icons/CalendarIcon';
import moment from 'moment';
import Error from '../../_common/_messages/Error.vue';
import Editor from '../../_common/Editor';
import MemberSearch from '../../_common/MemberSearch';

export default {
    components: {
        InputField,
        SelectField,
        datepicker,
        CalendarIcon,
        Error,
        Editor,
        MemberSearch,
    },
    methods: {
        ...mapActions([
            'getProjectUsers', 'getWorkPackageStatuses', 'getProjectPhases',
            'createProjectMilestone', 'getProjectMilestone', 'editProjectMilestone', 'emptyValidationMessages',
        ]),
        translateText: function(text) {
            return this.translate(text);
        },
        createMilestone: function() {
            let data = {
                project: this.$route.params.id,
                name: this.name,
                type: 1,
                content: this.content,
                scheduledStartAt: moment(this.schedule.scheduledStartAt).format('DD-MM-YYYY'),
                scheduledFinishAt: moment(this.schedule.scheduledFinishAt).format('DD-MM-YYYY'),
                responsibility: this.details.responsible.length > 0 ? this.details.responsible[0] : null,
                workPackageStatus: this.details.status ? this.details.status.key: null,
                phase: this.details.phase ? this.details.phase.key : null,
                isKeyMilestone: this.isKeyMilestone,
            };
            this.createProjectMilestone(data);
        },
        editMilestone: function() {
            let data = {
                id: this.$route.params.milestoneId,
                project: this.$route.params.id,
                name: this.name,
                type: 1,
                content: this.content,
                responsibility: this.details.responsible.length > 0 ? this.details.responsible[0] : null,
                workPackageStatus: this.details.status ? this.details.status.key: null,
                phase: this.details.phase ? this.details.phase.key : null,
                isKeyMilestone: this.isKeyMilestone,
            };
            this.editProjectMilestone(data);
        },
    },
    computed: mapGetters({
        projectUsersForSelect: 'projectUsersForSelect',
        workPackageStatusesForMilestone: 'workPackageStatusesForMilestone',
        projectPhasesForSelect: 'projectPhasesForSelect',
        projectPhases: 'projectPhases',
        milestone: 'currentMilestone',
        validationMessages: 'validationMessages',
    }),
    watch: {
        milestone(value) {
            this.name = this.milestone.name;
            this.schedule.scheduledStartAt = new Date(this.milestone.scheduledStartAt);
            this.schedule.scheduledFinishAt = new Date(this.milestone.scheduledFinishAt);
            this.details.status = this.milestone.workPackageStatus
                ? {key: this.milestone.workPackageStatus, label: this.translateText(this.milestone.workPackageStatusName)}
                : null
            ;
            this.details.responsible.push(this.milestone.responsibility);
            this.details.phase = this.milestone.phase
                ? {key: this.milestone.phase, label: this.translateText(this.milestone.phaseName)}
                : null
            ;
            this.isKeyMilestone = this.milestone.isKeyMilestone;
            this.content = this.milestone.content;
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
    beforeDestroy() {
        this.emptyValidationMessages();
    },
    data() {
        return {
            name: '',
            content: '',
            schedule: {
                scheduledStartAt: new Date(),
                scheduledFinishAt: new Date(),
            },
            details: {
                status: null,
                responsible: [],
                phase: null,
            },
            isKeyMilestone: false,
            isEdit: this.$route.params.milestoneId,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_mixins';
    @import '../../../css/_variables';
    @import '../../../css/common';

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
