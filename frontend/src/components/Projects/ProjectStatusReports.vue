<template>
    <div class="project-status-reports page-section">
        <div class="header flex flex-space-between">
            <h1>{{ translateText('message.project_status_reports') }}</h1>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-status-reports-create-status-report'}" class="btn-rounded btn-auto second-bg">{{ translateText('message.create_new_status_report') }}</router-link>
            </div>
        </div>

        <div class="flex flex-space-between">
            <member-search v-model="gridList" v-bind:placeholder="translateText('placeholder.search_members')"></member-search>
            <div class="full-filters">
                <status-filters></status-filters>
            </div>
        </div>

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
                    <tr>
                        <td class="date-cell">20.08.2017 @ 08:00</td>
                        <td>Automatic</td>
                        <td>
                            <div class="text-right">
                                <router-link :to="{name: 'project-status-reports-view-status-report'}" class="btn-icon" v-tooltip.top-center="translateText('message.view_status_report')"><view-icon fill="second-fill"></view-icon>
                                </router-link>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.print_status_report')"><print-icon fill="second-fill"></print-icon></a>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.email_status_report')"><notification-icon fill="second-fill"></notification-icon></a>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.download_status_report')"><download-icon fill="second-fill"></download-icon></a>
                            </div>
                        </td>                                
                    </tr>
                    <tr>
                        <td class="date-cell">15.08.2017 @ 09:22</td>
                        <td>
                            <div class="avatar" v-tooltip.top-center="'David Gilmore'" style="background-image:url(http://dev.campr.biz/uploads/avatars/49.jpg"></div>
                        </td>
                        <td>
                            <div class="text-right">
                                <router-link :to="{name: 'project-status-reports-view-status-report'}" class="btn-icon" v-tooltip.top-center="translateText('message.view_status_report')"><view-icon fill="second-fill"></view-icon>
                                </router-link>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.print_status_report')"><print-icon fill="second-fill"></print-icon></a>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.email_status_report')"><notification-icon fill="second-fill"></notification-icon></a>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.download_status_report')"><download-icon fill="second-fill"></download-icon></a>
                            </div>
                        </td>                                
                    </tr>
                    <tr>
                        <td class="date-cell">20.07.2017 @ 19:33</td>
                        <td>
                            <div class="avatar" v-tooltip.top-center="'Ema Watson'" style="background-image:url(http://dev.campr.biz/uploads/avatars/10.jpg"></div>
                        </td>
                        <td>
                            <div class="text-right">
                                <router-link :to="{name: 'project-status-reports-view-status-report'}" class="btn-icon" v-tooltip.top-center="translateText('message.view_status_report')"><view-icon fill="second-fill"></view-icon>
                                </router-link>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.print_status_report')"><print-icon fill="second-fill"></print-icon></a>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.email_status_report')"><notification-icon fill="second-fill"></notification-icon></a>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.download_status_report')"><download-icon fill="second-fill"></download-icon></a>
                            </div>
                        </td>                                
                    </tr>
                    <tr>
                        <td class="date-cell">20.08.2017 @ 08:00</td>
                        <td>Automatic</td>
                        <td>
                            <div class="text-right">
                                <router-link :to="{name: 'project-status-reports-view-status-report'}" class="btn-icon" v-tooltip.top-center="translateText('message.view_status_report')"><view-icon fill="second-fill"></view-icon>
                                </router-link>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.print_status_report')"><print-icon fill="second-fill"></print-icon></a>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.email_status_report')"><notification-icon fill="second-fill"></notification-icon></a>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.download_status_report')"><download-icon fill="second-fill"></download-icon></a>
                            </div>
                        </td>                                
                    </tr>
                    <tr>
                        <td class="date-cell">15.08.2017 @ 09:22</td>
                        <td>
                            <div class="avatar" v-tooltip.top-center="'David Gilmore'" style="background-image:url(http://dev.campr.biz/uploads/avatars/20.jpg"></div>
                        </td>
                        <td>
                            <div class="text-right">
                                <router-link :to="{name: 'project-status-reports-view-status-report'}" class="btn-icon" v-tooltip.top-center="translateText('message.view_status_report')"><view-icon fill="second-fill"></view-icon>
                                </router-link>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.print_status_report')"><print-icon fill="second-fill"></print-icon></a>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.email_status_report')"><notification-icon fill="second-fill"></notification-icon></a>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.download_status_report')"><download-icon fill="second-fill"></download-icon></a>
                            </div>
                        </td>                                
                    </tr>
                    <tr>
                        <td class="date-cell">20.07.2017 @ 19:33</td>
                        <td>
                            <div class="avatar" v-tooltip.top-center="'Ema Watson'" style="background-image:url(http://dev.campr.biz/uploads/avatars/40.jpg"></div>
                        </td>
                        <td>
                            <div class="text-right">
                                <router-link :to="{name: 'project-status-reports-view-status-report'}" class="btn-icon" v-tooltip.top-center="translateText('message.view_status_report')"><view-icon fill="second-fill"></view-icon>
                                </router-link>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.print_status_report')"><print-icon fill="second-fill"></print-icon></a>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.email_status_report')"><notification-icon fill="second-fill"></notification-icon></a>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.download_status_report')"><download-icon fill="second-fill"></download-icon></a>
                            </div>
                        </td>                                
                    </tr>
                    <tr>
                        <td class="date-cell">20.07.2017 @ 19:33</td>
                        <td>
                            <div class="avatar" v-tooltip.top-center="'Ema Watson'" style="background-image:url(http://dev.campr.biz/uploads/avatars/41.jpg"></div>
                        </td>
                        <td>
                            <div class="text-right">
                                <router-link :to="{name: 'project-status-reports-view-status-report'}" class="btn-icon" v-tooltip.top-center="translateText('message.view_status_report')"><view-icon fill="second-fill"></view-icon>
                                </router-link>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.print_status_report')"><print-icon fill="second-fill"></print-icon></a>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.email_status_report')"><notification-icon fill="second-fill"></notification-icon></a>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.download_status_report')"><download-icon fill="second-fill"></download-icon></a>
                            </div>
                        </td>                                
                    </tr>
                    <tr>
                        <td class="date-cell">20.07.2017 @ 19:33</td>
                        <td>
                            <div class="avatar" v-tooltip.top-center="'Ema Watson'" style="background-image:url(http://dev.campr.biz/uploads/avatars/44.jpg"></div>
                        </td>
                        <td>
                            <div class="text-right">
                                <router-link :to="{name: 'project-status-reports-view-status-report'}" class="btn-icon" v-tooltip.top-center="translateText('message.view_status_report')"><view-icon fill="second-fill"></view-icon>
                                </router-link>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.print_status_report')"><print-icon fill="second-fill"></print-icon></a>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.email_status_report')"><notification-icon fill="second-fill"></notification-icon></a>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.download_status_report')"><download-icon fill="second-fill"></download-icon></a>
                            </div>
                        </td>                                
                    </tr>
                    <tr>
                        <td class="date-cell">20.08.2017 @ 08:00</td>
                        <td>Automatic</td>
                        <td>
                            <div class="text-right">
                                <router-link :to="{name: 'project-status-reports-view-status-report'}" class="btn-icon" v-tooltip.top-center="translateText('message.view_status_report')"><view-icon fill="second-fill"></view-icon>
                                </router-link>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.print_status_report')"><print-icon fill="second-fill"></print-icon></a>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.email_status_report')"><notification-icon fill="second-fill"></notification-icon></a>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.download_status_report')"><download-icon fill="second-fill"></download-icon></a>
                            </div>
                        </td>                                
                    </tr>
                    <tr>
                        <td class="date-cell">20.07.2017 @ 19:33</td>
                        <td>
                            <div class="avatar" v-tooltip.top-center="'Ema Watson'" style="background-image:url(http://dev.campr.biz/uploads/avatars/64.jpg"></div>
                        </td>
                        <td>
                            <div class="text-right">
                                <router-link :to="{name: 'project-status-reports-view-status-report'}" class="btn-icon" v-tooltip.top-center="translateText('message.view_status_report')"><view-icon fill="second-fill"></view-icon>
                                </router-link>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.print_status_report')"><print-icon fill="second-fill"></print-icon></a>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.email_status_report')"><notification-icon fill="second-fill"></notification-icon></a>
                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.download_status_report')"><download-icon fill="second-fill"></download-icon></a>
                            </div>
                        </td>                                
                    </tr>
                </tbody>
            </table>

            <div class="flex flex-direction-reverse flex-v-center">
                <div class="pagination flex flex-center">
                    <span >1</span>
                    <span >2</span>
                    <span >3</span>
                </div>
                <div>
                    <span class="pagination-info">{{ translateText('message.displaying') }} 10 {{ translateText('message.results_out_of') }} 32</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import MemberSearch from '../_common/MemberSearch';
import StatusFilters from '../_common/_status-reports-components/StatusFilters';
import ViewIcon from '../_common/_icons/ViewIcon';
import PrintIcon from '../_common/_icons/PrintIcon';
import NotificationIcon from '../_common/_icons/NotificationIcon';
import DownloadIcon from '../_common/_icons/DownloadIcon';

export default {
    components: {
        MemberSearch,
        StatusFilters,
        ViewIcon,
        PrintIcon,
        NotificationIcon,
        DownloadIcon,
    },
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/_variables';
    @import '../../css/_mixins';

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
