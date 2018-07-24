<template>
    <div class="project-status-report page-section" v-if="isReady">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2" id="statusReportPrint">
                <status-report
                        ref="statusReport"
                        :report="currentStatusReport"
                        v-model="editableData"
                        :editable="false"/>
                <br>
                <br>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex';
    import StatusReport from './Create/StatusReportPrint';
    import DownloadIcon from '../../_common/_icons/DownloadIcon';
    import AtIcon from '../../_common/_icons/AtIcon';
    import Modal from '../../_common/Modal';

    export default {
        name: 'status-report-view',
        components: {
            StatusReport,
            DownloadIcon,
            AtIcon,
            Modal,
        },
        created() {
            window.pdfRenderDone = false;
            this.getStatusReport(this.$route.params.reportId).then(() => {
                window.pdfRenderDone = true;
            });
        },
        computed: {
            ...mapGetters([
                'currentStatusReport',
            ]),
            isStatusReportLoaded() {
                return this.currentStatusReport.project;
            },
            isReady() {
                return this.isStatusReportLoaded;
            },
            downloadPdf() {
                return Routing.generate('app_status_report_pdf', {id: this.currentStatusReport.id});
            },
        },
        methods: {
            ...mapActions([
                'getStatusReport',
                'emailStatusReport',
            ]),
            emailReport() {
                this.emailStatusReport(this.currentStatusReport.id).then(
                    (response) => {
                        this.showSaved = true;
                        this.showEmailModal = false;
                    },
                    () => {
                        this.showFailed = true;
                        this.showEmailModal = false;
                    },
                );
            },
        },
        data() {
            return {
                showEmailModal: false,
            };
        },
    };
</script>
