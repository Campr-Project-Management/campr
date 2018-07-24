<template>
    <div class="project-status-report page-section" v-if="isReady">
        <modal v-if="showEmailModal" @close="showEmailModal = false">
            <p class="modal-title">{{ translate('message.email_report') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showEmailModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translate('message.no') }}</a>
                <a href="javascript:void(0)" @click="emailReport()" class="btn-rounded">{{ translate('message.yes') }}</a>
            </div>
        </modal>
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

            <div class="col-lg-8 col-lg-offset-2" v-if="currentStatusReport && currentStatusReport.id">
                <hr class="double">

                <div class="row">
                    <div class="col-md-12">
                        <div class="flex flex-space-between">
                            <a
                                :href="downloadPdf"
                                class="btn-rounded btn-auto btn-auto second-bg">{{ translate('button.download_pdf') }}<download-icon fill="white-fill"></download-icon></a>
                            <a
                                @click="showEmailModal = true"
                                class="btn-rounded btn-auto btn-auto second-bg">
                                {{ translate('button.email_status_report') }}<at-icon fill="white-fill"></at-icon>
                            </a>
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
            this.getStatusReport(this.$route.params.reportId);
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
