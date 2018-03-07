<template>
    <div class="project-status-reports page-section">
        <div class="header flex flex-space-between">
            <h1>{{ translateText('message.project_status_reports') }}</h1>
            <div class="flex flex-v-center">
                <router-link v-if="!statusReportAvailability" :to="{name: 'project-status-reports-create-status-report'}" class="btn-rounded btn-auto second-bg">{{ translateText('message.create_new_status_report') }}</router-link>
                <span v-else>{{ translateText(statusReportAvailability) }}</span>
            </div>
        </div>

        <modal v-if="showEmailModal" @close="showEmailModal = false">
            <p class="modal-title">{{ translateText('message.email_report') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showEmailModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="emailReport()" class="btn-rounded">{{ translateText('message.yes') }}</a>
            </div>
        </modal>

        <status-filters :updateFilters="applyFilters"></status-filters>
        <div class="status-reports-list margintop20">
            <table class="table table-striped table-responsive">
                <thead>
                    <tr>
                        <th class="date-cell">{{ translateText('table_header_cell.date') }}</th>
                        <th>{{ translateText('table_header_cell.created_by') }}</th>
                        <th>{{ translateText('table_header_cell.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="report in statusReports.items">
                        <td class="date-cell">{{ report.createdAt | moment('DD.MM.YYYY') }} @ {{ report.createdAt | moment('H:m') }}</td>
                        <td>
                            <div class="avatar" v-tooltip.top-center="report.createdByFullName" :style="{ backgroundImage: 'url('+report.createdByAvatar+')' }"></div>
                        </td>
                        <td>
                            <div class="text-right">
                                <router-link :to="{name: 'project-status-reports-view-status-report', params:{reportId: report.id}}" class="btn-icon" v-tooltip.top-center="translateText('label.view_status_report')"><view-icon fill="second-fill"></view-icon>
                                </router-link>
                                <!--<a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.print_status_report')"><print-icon fill="second-fill"></print-icon></a>-->
                                <a @click="initEmailModal(report)" class="btn-icon" v-tooltip.top-center="translateText('label.email_status_report')"><notification-icon fill="second-fill"></notification-icon></a>
                                <a :href="downloadPdf(report)" class="btn-icon" v-tooltip.top-center="translateText('label.download_status_report')"><download-icon fill="second-fill"></download-icon></a>
                            </div>
                        </td>                                
                    </tr>
                </tbody>
            </table>

            <div class="flex flex-direction-reverse flex-v-center" v-if="pages > 1">
                <div class="pagination">
                    <span v-if="pages > 1" v-for="page in pages" v-bind:class="{'active': page == activePage}" @click="changePage(page)" >{{ page }}</span>
                </div>
                <div>
                    <span class="pagination-info">{{ translateText('message.displaying') }} {{ statusReports.items.length }} {{ translateText('message.results_out_of') }} {{ statusReports.totalItems }}</span>
                </div>
            </div>
        </div>

        <alert-modal v-if="showSaved" @close="showSaved = false" body="message.saved" />
        <alert-modal v-if="showFailed" @close="showFailed = false" body="message.unable_to_save" />
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import StatusFilters from '../_common/_status-reports-components/StatusFilters';
import ViewIcon from '../_common/_icons/ViewIcon';
import PrintIcon from '../_common/_icons/PrintIcon';
import NotificationIcon from '../_common/_icons/NotificationIcon';
import DownloadIcon from '../_common/_icons/DownloadIcon';
import Modal from '../_common/Modal';
import AlertModal from '../_common/AlertModal.vue';

export default {
    components: {
        StatusFilters,
        ViewIcon,
        PrintIcon,
        NotificationIcon,
        DownloadIcon,
        Modal,
        AlertModal,
    },
    methods: {
        ...mapActions(['getProjectStatusReports', 'setStatusReportFilters', 'emailStatusReport', 'checkReportAvailability']),
        translateText: function(text) {
            return this.translate(text);
        },
        getData: function() {
            this.getProjectStatusReports({
                projectId: this.$route.params.id,
                queryParams: {
                    page: this.activePage,
                },
            });
        },
        applyFilters: function(key, value) {
            let filterObj = {};
            filterObj[key] = value;
            this.setStatusReportFilters(filterObj);
            this.activePage = 1;
            this.getData();
        },
        changePage: function(page) {
            this.activePage = page;
            this.getData();
        },
        downloadPdf: function(report) {
            return Routing.generate('app_status_report_pdf', {id: report.id});
        },
        initEmailModal: function(report) {
            this.emailReportId = report.id;
            this.showEmailModal = true;
        },
        emailReport: function() {
            this
                .emailStatusReport(this.emailReportId)
                .then(
                    (response) => {
                        this.showSaved = true;
                        this.showEmailModal = false;
                    },
                    () => {
                        this.showFailed = true;
                        this.showEmailModal = false;
                    }
                );
        },
    },
    created() {
        this.getData();
        this.checkReportAvailability(this.$route.params.id);
    },
    computed: {
        ...mapGetters({
            statusReports: 'statusReports',
            statusReportAvailability: 'statusReportAvailability',
        }),
        pages: function() {
            return Math.ceil(this.statusReports.totalItems / this.perPage);
        },
        perPage: function() {
            return this.statusReports.pageSize;
        },
    },
    data: function() {
        return {
            activePage: 1,
            showEmailModal: false,
            emailReportId: null,
            showSaved: false,
            showFailed: false,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/variables';
    @import '../../css/mixins';
    @import '../../css/common';

    .date-cell {
        width: 5%;
    }

    .avatar {
        width: 30px;
        height: 30px;
        @include border-radius(50%);
        background-size: cover;
    }  
</style>
