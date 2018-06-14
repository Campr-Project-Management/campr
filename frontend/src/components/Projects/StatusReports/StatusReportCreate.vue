<template>
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
            this.getGeneratedStatusReport(this.$route.params.id);
        },
        methods: {
            ...mapActions([
                'getGeneratedStatusReport',
                'createStatusReport',
            ]),
            onSave() {
                this.createStatusReport({id: this.$route.params.id, data: this.editableData});
            },
        },
        computed: {
            ...mapGetters([
                'generatedStatusReport',
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
            };
        },
    };
</script>
