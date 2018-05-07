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

            <div class="col-lg-8 col-lg-offset-2">
                <hr class="double">

                <div class="row">
                    <div class="col-md-12">
                        <div class="flex flex-space-between">
                            <a
                                    @click="downloadPDF()"
                                    class="btn-rounded btn-auto btn-auto second-bg">{{ translate('button.download_pdf') }}<download-icon fill="white-fill"></download-icon></a>
                            <!--<a-->
                                    <!--@click="showEmailModal = true"-->
                                    <!--class="btn-rounded btn-auto btn-auto second-bg"> {{ translate('button.email_status_report') }} <at-icon fill="white-fill"></at-icon></a>-->
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
    import html2canvas from 'html2canvas';
    import jsPDF from 'jspdf';

    export default {
        name: 'status-report-view',
        components: {
            StatusReport,
            DownloadIcon,
            AtIcon,
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
        },
        methods: {
            ...mapActions([
                'getStatusReport',
            ]),
            emailReport() {
                this.emailStatusReport(this.$route.params.reportId).then(
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
            downloadPDF() {
                html2canvas(document.getElementById('statusReportPrint'), this.html2canvasOptions).then((canvas) => {
                    const ratio = canvas.height / canvas.width;
                    const width = 400;
                    const height = width * ratio;
                    const Pdf = jsPDF;
                    let pdf = new Pdf('p', 'pt', [width, height]);

                    pdf.addImage(canvas, 'JPEG', 0, 0, width, height);
                    pdf.save(`status-report-${this.currentStatusReport.id}.pdf`);
                });
            },
        },
        data() {
            return {
                html2canvasOptions: {
                    backgroundColor: '#232D4B',
                    logging: false,
                    allowTaint: true,
                },
            };
        },
    };
</script>
