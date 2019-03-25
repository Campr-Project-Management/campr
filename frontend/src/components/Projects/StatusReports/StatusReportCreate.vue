<template>
    <can role="roles.project_manager|roles.project_sponsor" :subject="project">
        <div class="project-status-report page-section" v-if="isReady">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <status-report
                            :report="generatedStatusReport"
                            v-model="editableData"/>

                    <hr class="double">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="flex flex-space-between">
                                <a @click="onSave()" class="btn-rounded btn-auto btn-auto second-bg">{{ translate('button.save') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </can>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex';
    import StatusReport from './Create/StatusReport';
    import tl from '../../../util/traffic-light';

    export default {
        name: 'status-report-create',
        components: {
            StatusReport,
        },
        created() {
            this.getProjectById(this.$route.params.id);
            this.getGeneratedStatusReport(this.$route.params.id);
        },
        methods: {
            ...mapActions([
                'getGeneratedStatusReport',
                'createStatusReport',
                'getProjectById',
            ]),
            onSave() {
                if (!this.isSaving) {
                    this.isSaving = true;
                    this.createStatusReport({id: this.$route.params.id, data: this.editableData})
                        .then(
                            (response) => {
                                this.isSaving = false;
                                if (response.body && response.body.error && response.body.messages) {
                                    this.$flashError('message.unable_to_save');
                                    return;
                                }

                                this.$flashSuccess('message.saved');
                            },
                            () => {
                                this.$flashError('message.unable_to_save');
                                this.isSaving = false;
                            }
                        )
                    ;
                }
            },
        },
        computed: {
            ...mapGetters([
                'generatedStatusReport',
                'project',
            ]),
            isStatusReportLoaded() {
                return this.generatedStatusReport.project;
            },
            isReady() {
                return this.isStatusReportLoaded;
            },
        },
        watch: {
            generatedStatusReport() {
                this.editableData.projectActionNeeded = this.generatedStatusReport.projectActionNeeded;
                this.editableData.comment = this.generatedStatusReport.comment;
                this.editableData.projectTrafficLight = this.generatedStatusReport.projectTrafficLight;
            },
        },
        data() {
            return {
                projectId: this.$route.params.id,
                editableData: {
                    projectActionNeeded: false,
                    comment: null,
                    projectTrafficLight: tl.TrafficLight.GREEN,
                },
                isSaving: false,
            };
        },
    };
</script>
