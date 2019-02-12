<template>
    <div class="row">
        <div class="col-md-6 custom-col-md-6">
            <div class="create-phase page-section">
                <!-- /// Header /// -->
                <div class="header flex-v-center">
                    <div>
                        <router-link :to="{name: 'project-phases-and-milestones'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ translate('message.back_to_phases_and_milestones') }}
                        </router-link>
                        <h1 v-if="!isEdit">{{ translate('message.create_new_phase') }}</h1>
                        <h1 v-else>{{ translate('message.edit_phase') }} - {{ phase.name }}</h1>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <div class="form">
                    <!-- /// Phase Name /// -->
                    <input-field type="text" v-bind:label="translate('placeholder.phase_name')" v-model="name"
                                 v-bind:content="name"/>
                    <error at-path="name"/>
                    <!-- /// End Phase Name /// -->

                    <!-- /// Phase Description /// -->
                    <editor
                            v-model="content"
                            :label="'placeholder.phase_description'"/>
                    <error at-path="content"/>
                    <!-- /// End Phase Description /// -->

                    <hr class="double">

                    <template v-if="!isEdit">
                        <!-- /// Phase Schedule /// -->
                        <h3>{{ translate('message.schedule') }}</h3>
                        <br/>

                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <div class="input-holder right">
                                        <label class="active">{{ translate('label.base_start_date') }}</label>
                                        <date-field v-model="schedule.baseStartDate"/>
                                    </div>
                                    <error at-path="scheduledStartAt"/>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-holder right">
                                        <label class="active">{{ translate('label.base_end_date') }}</label>
                                        <date-field v-model="schedule.baseEndDate"/>
                                    </div>
                                    <error at-path="scheduledFinishAt"/>
                                </div>
                            </div>
                        </div>
                        <!-- /// End Phase Schedule /// -->

                        <hr class="double">
                    </template>

                    <!-- /// Phase Details /// -->
                    <h3>{{ translate('message.details') }}</h3>
                    <br/>

                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-12">
                                <select-field
                                        :allow-clear="true"
                                        :title="translate('label.responsible')"
                                        :options="responsibilityOptions"
                                        v-model="details.responsibility"/>

                                <error at-path="responsibility"/>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Phase Details /// -->

                    <hr class="double">

                    <template v-if="projectPhasesForSelect">
                        <!-- /// Is Subphase /// -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="checkbox-input clearfix">
                                    <input id="is-subphase" type="checkbox" v-model="visibleSubphase">
                                    <label for="is-subphase" class="no-margin-bottom">{{ translate('label.phase_is_subphase') }}</label>
                                </div>
                            </div>
                        </div>
                        <br/>

                        <div class="row">
                            <div class="col-md-12" v-if="visibleSubphase">
                                <div class="form-group last-form-group">
                                    <select-field
                                            :title="translate('label.parent_phase')"
                                            :options="projectPhasesForSelect"
                                            v-model="details.parent"/>
                                </div>
                            </div>
                        </div>
                        <!-- /// End Is Subphase /// -->

                        <hr class="double">
                    </template>

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-phases-and-milestones'}"
                                     class="btn-rounded btn-auto disable-bg">{{ translate('button.cancel') }}
                        </router-link>
                        <a v-if="!isEdit" @click="createNewPhase" class="btn-rounded btn-auto second-bg">{{ translate('button.create_phase') }}</a>
                        <a v-if="isEdit" @click="editPhase()" class="btn-rounded btn-auto">{{ translate('button.edit_phase') }}</a>

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
    import moment from 'moment';
    import Error from '../../_common/_messages/Error.vue';
    import Editor from '../../_common/Editor';
    import MemberSearch from '../../_common/MemberSearch';
    import DateField from '../../_common/_form-components/DateField';

    export default {
        name: 'project-phase-create',
        components: {
            DateField,
            InputField,
            SelectField,
            Error,
            Editor,
            MemberSearch,
        },
        methods: {
            ...mapActions([
                'getProjectUsers', 'getWorkPackageStatuses', 'getProjectPhases',
                'createProjectPhase', 'getProjectPhase', 'editProjectPhase', 'emptyValidationMessages',
            ]),
            createNewPhase: function() {
                if (!this.isSaving) {
                    let data = {
                        project: this.$route.params.id,
                        name: this.name,
                        type: 0,
                        content: this.content,
                        scheduledStartAt: moment(this.schedule.baseStartDate).format('DD-MM-YYYY'),
                        scheduledFinishAt: moment(this.schedule.baseEndDate).format('DD-MM-YYYY'),
                        responsibility: this.details.responsibility ? this.details.responsibility.key : null,
                        workPackageStatus: this.details.status ? this.details.status.key : null,
                        parent: this.visibleSubphase ? this.details.parent ? this.details.parent.key : null : null,
                    };

                    this.isSaving = true;
                    this.createProjectPhase(data).then(
                        (response) => {
                            this.isSaving = false;
                            if (response.body && response.body.error && response.body.messages) {
                                this.$flashError('message.unable_to_save');
                            } else {
                                this.$flashSuccess('message.saved');
                            }
                        },
                        () => {
                            this.isSaving = false;
                            this.$flashError('message.unable_to_save');
                        },
                    );
                }
            },
            editPhase: function() {
                let data = {
                    project: this.$route.params.id,
                    id: this.$route.params.phaseId,
                    name: this.name,
                    type: 0,
                    content: this.content,
                    responsibility: this.details.responsibility ? this.details.responsibility.key : null,
                    workPackageStatus: this.details.status ? this.details.status.key : null,
                    parent: !this.visibleSubphase ? this.details.parent ? this.details.parent.key : null : null,
                };
                this.editProjectPhase(data);
            },
        },
        computed: {
            ...mapGetters([
                'rasciProjectUsersForSelect',
                'workPackageStatusesForSelect',
                'projectPhases',
                'projectPhasesForSelect',
            ]),
            ...mapGetters({
                phase: 'currentPhase',
            }),
            responsibilityOptions() {
                return this.rasciProjectUsersForSelect;
            },
        },
        watch: {
            phase(value) {
                this.name = this.phase.name;
                this.content = this.phase.content;
                this.schedule.baseStartDate = new Date(this.phase.scheduledStartAt);
                this.schedule.baseEndDate = new Date(this.phase.scheduledFinishAt);
                this.schedule.forecastStartDate = new Date(this.phase.forecastStartAt);
                this.schedule.forecastEndDate = new Date(this.phase.forecastFinishAt);
                this.details.status = this.phase.workPackageStatus
                    ? {key: this.phase.workPackageStatus, label: this.translate(this.phase.workPackageStatusName)}
                    : null
                ;
                this.details.responsibility = {key: this.phase.responsibility};
                if (this.phase.parent) {
                    this.visibleSubphase = true;
                    this.details.parent = {key: this.phase.parent, label: this.translate(this.phase.parentName)};
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
                    responsibility: null,
                    parent: null,
                },
                visibleSubphase: false,
                isEdit: this.$route.params.phaseId,
                isSaving: false,
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
