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
                        <h1 v-if="!isEdit">{{ translateText('message.create_new_phase') }}</h1>
                        <h1 v-else>{{ translateText('message.edit_phase') }} - {{ phase.name }}</h1>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <div class="form">
                    <!-- /// Phase Name /// -->
                    <input-field type="text" v-bind:label="translateText('placeholder.phase_name')" v-model="name" v-bind:content="name" />
                    <error
                        v-if="validationMessages.name && validationMessages.name.length"
                        v-for="message in validationMessages.name"
                        :message="message" />
                    <!-- /// End Phase Name /// -->

                    <!-- /// Phase Description /// -->
                    <editor
                        v-model="content"
                        :label="'placeholder.phase_description'"/>
                    <!-- /// End Phase Description /// -->

                    <hr class="double">

                    <template v-if="!isEdit">
                        <!-- /// Phase Schedule /// -->
                        <h3>{{ translateText('message.schedule') }}</h3>
                        <br/>

                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <div class="input-holder right">
                                        <label class="active">{{ translateText('label.base_start_date') }}</label>
                                        <datepicker v-model="schedule.baseStartDate" format="dd-MM-yyyy" />
                                        <calendar-icon fill="middle-fill"/>
                                    </div>
                                    <error at-path="scheduledStartAt" />
                                </div>
                                <div class="col-md-6">
                                    <div class="input-holder right">
                                        <label class="active">{{ translateText('label.base_end_date') }}</label>
                                        <datepicker v-model="schedule.baseEndDate" format="dd-MM-yyyy" />
                                        <calendar-icon fill="middle-fill"/>
                                    </div>
                                    <error at-path="scheduledFinishAt" />
                                </div>
                            </div>
                        </div>
                        <!-- /// End Phase Schedule /// -->

                        <hr class="double">
                    </template>

                    <!-- /// Phase Details /// -->
                    <h3>{{ translateText('message.details') }}</h3>
                    <br/>

                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <member-search
                                        v-model="details.responsible"
                                        v-bind:placeholder="translateText('placeholder.responsible')"
                                        v-bind:singleSelect="true"/>

                                <error at-path="responsibility" />
                            </div>
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="translateText('label.status')"
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
                                <label for="is-subphase" class="no-margin-bottom">{{ translateText('label.phase_is_subphase') }}</label>
                            </div>
                        </div>
                        <div class="col-md-6 margintop20" v-if="visibleSubphase">
                            <div class="form-group last-form-group">
                                <select-field
                                    v-bind:title="translateText('label.parent_phase')"
                                    v-if="projectPhases.items && projectPhases.items.length > 0"
                                    v-bind:options="projectPhasesForSelect"
                                    v-model="details.parent"
                                    v-bind:currentOption="details.parent"/>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Is Subphase /// -->

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-phases-and-milestones'}" class="btn-rounded btn-auto disable-bg">{{ translateText('button.cancel') }}</router-link>
                        <a v-if="!isEdit" @click="createNewPhase()" class="btn-rounded btn-auto second-bg">{{ translateText('button.create_phase') }}</a>
                        <a v-if="isEdit" @click="editPhase()" class="btn-rounded btn-auto">{{ translateText('button.edit_phase') }}</a>

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
    name: 'project-phase-create',
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
            'createProjectPhase', 'getProjectPhase', 'editProjectPhase', 'emptyValidationMessages',
        ]),
        translateText: function(text) {
            return this.translate(text);
        },
        createNewPhase: function() {
            let data = {
                project: this.$route.params.id,
                name: this.name,
                type: 0,
                content: this.content,
                scheduledStartAt: moment(this.schedule.baseStartDate).format('DD-MM-YYYY'),
                scheduledFinishAt: moment(this.schedule.baseEndDate).format('DD-MM-YYYY'),
                responsibility: this.details.responsible.length > 0 ? this.details.responsible[0] : null,
                workPackageStatus: this.details.status ? this.details.status.key: null,
                parent: this.visibleSubphase ? this.details.parent ? this.details.parent.key : null : null,
            };
            this.createProjectPhase(data);
        },
        editPhase: function() {
            let data = {
                id: this.$route.params.phaseId,
                name: this.name,
                type: 0,
                content: this.content,
                responsibility: this.details.responsible.length > 0 ? this.details.responsible[0] : null,
                workPackageStatus: this.details.status ? this.details.status.key: null,
                parent: !this.visibleSubphase ? this.details.parent ? this.details.parent.key : null : null,
            };
            this.editProjectPhase(data);
        },
    },
    computed: mapGetters({
        projectUsersForSelect: 'projectUsersForSelect',
        workPackageStatusesForSelect: 'workPackageStatusesForSelect',
        projectPhases: 'projectPhases',
        projectPhasesForSelect: 'projectPhasesForSelect',
        phase: 'currentPhase',
        validationMessages: 'validationMessages',
    }),
    watch: {
        phase(value) {
            this.name = this.phase.name;
            this.content = this.phase.content;
            this.schedule.baseStartDate = new Date(this.phase.scheduledStartAt);
            this.schedule.baseEndDate = new Date(this.phase.scheduledFinishAt);
            this.schedule.forecastStartDate = new Date(this.phase.forecastStartAt);
            this.schedule.forecastEndDate = new Date(this.phase.forecastFinishAt);
            this.details.status = this.phase.workPackageStatus
                ? {key: this.phase.workPackageStatus, label: this.translateText(this.phase.workPackageStatusName)}
                : null
            ;
            this.details.responsible.push(this.phase.responsibility);
            if (this.phase.parent) {
                this.visibleSubphase = true;
                this.details.parent = {key: this.phase.parent, label: this.translateText(this.phase.parentName)};
            }
        },
    },
    created() {
        this.getProjectUsers({id: this.$route.params.id});
        this.getWorkPackageStatuses();
        this.getProjectPhases({projectId: this.$route.params.id});
        if (this.$route.params.phaseId) {
            this.getProjectPhase(this.$route.params.phaseId);
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
                baseStartDate: new Date(),
                baseEndDate: new Date(),
                forecastStartDate: new Date(),
                forecastEndDate: new Date(),
            },
            details: {
                status: null,
                responsible: [],
                parent: null,
            },
            visibleSubphase: false,
            isEdit: this.$route.params.phaseId,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    .title {
        position: relative;
        top: 15px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 700;
    }
</style>
