<template>
    <div class="project-meetings page-section">
        <modal v-if="showDeleteModal" @close="showDeleteModal = false">
            <p class="modal-title">{{ translateText('message.delete_meeting') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showDeleteModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="deleteMeeting()" class="btn-rounded">{{ translateText('message.yes') }}</a>
            </div>
        </modal>

        <modal v-if="showRescheduleModal" @close="showRescheduleModal = false">
            <p class="modal-title">{{ translateText('message.reschedule_meeting') }}</p>
            <div class="form-group last-form-group">
                <div class="col-md-4">
                    <div class="input-holder">
                        <label class="active">{{ translateText('label.select_date') }}</label>
                        <datepicker :clear-button="false" v-model="date" format="dd-MM-yyyy" :value="date"></datepicker>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-holder">
                        <label class="active">{{ translateText('label.start_time') }}</label>
                        <vue-timepicker v-model="startTime" hide-clear-button></vue-timepicker>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-holder">
                        <label class="active">{{ translateText('label.finish_time') }}</label>
                        <vue-timepicker v-model="endTime" hide-clear-button></vue-timepicker>
                    </div>
                </div>
            </div>
            <hr class="double">

            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showRescheduleModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
                <a href="javascript:void(0)" @click="rescheduleMeeting()" class="btn-rounded">{{ translateText('button.save') }}</a>
            </div>
        </modal>

        <modal v-if="showNotificationModal" @close="showNotificationModal = false">
            <p class="modal-title">{{ translateText('message.send_notifications') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showNotificationModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="sendNotifications()" class="btn-rounded">{{ translateText('message.yes') }}</a>
            </div>
        </modal>

        <div class="header flex flex-space-between">
            <h1>{{ translateText('message.project_meetings') }}</h1>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-meetings-create-meeting'}" class="btn-rounded btn-auto second-bg">{{ translateText('message.create_new_meeting') }}</router-link>
            </div>
        </div>

        <div class="flex flex-direction-reverse">
            <div class="full-filters">
                <meetings-filters :clearAllFilters="clearMeetingsFilters" :selectEvent="setEventFilter" :selectCategory="setFilterCategory" :selectDate="setFilterDate"></meetings-filters>
            </div>
        </div>

        <div class="meetings-list">
            <vue-scrollbar class="table-wrapper">
                <div class="scroll-wrapper">
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>{{ translateText('table_header_cell.event') }}</th>
                                <th>{{ translateText('table_header_cell.category') }}</th>
                                <th>{{ translateText('table_header_cell.date') }}</th>
                                <th>{{ translateText('table_header_cell.schedule') }}</th>
                                <th>{{ translateText('table_header_cell.duration') }}</th>
                                <th>{{ translateText('table_header_cell.participants') }}</th>
                                <th>{{ translateText('table_header_cell.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody v-if="projectMeetings">
                            <tr v-for="(meeting, index) in projectMeetings.items"
                                :key="index"
                                :class="{'inactive': isInactive(meeting)}">
                                <td>{{ meeting.name }}</td>
                                <td>{{ meeting.meetingCategoryName }}</td>
                                <td>{{ meeting.date | moment('DD.MM.YYYY') }}</td>
                                <td>{{ meeting.start }} - {{ meeting.end }}</td>
                                <td>{{ getDuration(meeting.start, meeting.end) }} {{ translateText('message.min') }}</td>
                                <td>
                                    <div class="avatars collapse in" id="m1" v-if="meeting.meetingParticipants.length > 0">
                                        <div>
                                            <span v-for="(participant, index) in meeting.meetingParticipants"
                                                :key="index">
                                                <div class="avatar" v-tooltip.top-center="participant.userFullName" :style="{ backgroundImage: 'url('+participant.userAvatar+')' }"></div>
                                            </span>
                                            <button type="button" data-toggle="collapse" data-target="#m1" class="two-state collapsed"><span class="more">{{ translateText('message.more') }} +</span><span class="less">{{ translateText('message.less') }} -</span></button>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <router-link class="btn-icon" :to="{name: 'project-meetings-view-meeting', params:{meetingId: meeting.id}}" v-tooltip.top-center="translateText('message.view_meeting')">
                                            <view-icon fill="second-fill"></view-icon>
                                        </router-link>
                                        <router-link class="btn-icon" :to="{name: 'project-meetings-edit-meeting', params:{meetingId: meeting.id}}" v-tooltip.top-center="translateText('message.edit_meeting')">
                                            <edit-icon fill="second-fill"></edit-icon>
                                        </router-link>
                                        <a @click="printMeeting(meeting)" class="btn-icon" v-tooltip.top-center="translateText('message.print_meeting')"><print-icon fill="second-fill"></print-icon></a>
                                        <a @click="initSendNotifications(meeting)" v-if="!isInactive(meeting)" href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.send_notifications')"><notification-icon fill="second-fill"></notification-icon></a>
                                        <a @click="initRescheduleModal(meeting)" v-if="!isInactive(meeting)" href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.reschedule_meeting')"><reschedule-icon fill="second-fill"></reschedule-icon></a>
                                        <a @click="initDeleteModal(meeting)" v-if="!isInactive(meeting)" href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_meeting')"><delete-icon fill="danger-fill"></delete-icon></a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </vue-scrollbar>

            <div v-if="projectMeetings && projectMeetings.items" class="flex flex-direction-reverse flex-v-center">
                <div class="pagination flex flex-center" v-if="projectMeetings && projectMeetings.totalItems > 0">
                    <span v-if="pages > 1"
                        v-for="(page, index) in pages"
                        :key="index"
                        v-bind:class="{'active': page == activePage}"
                        @click="changePage(page)">
                            {{ page }}
                    </span>
                </div>
                <div>
                    <span class="pagination-info">{{ translateText('message.displaying') }} {{ projectMeetings.items.length }} {{ translateText('message.results_out_of') }} {{ projectMeetings.totalItems }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import MeetingsFilters from '../_common/_meetings-components/MeetingsFilters';
import VueScrollbar from 'vue2-scrollbar';
import ViewIcon from '../_common/_icons/ViewIcon';
import EditIcon from '../_common/_icons/EditIcon';
import PrintIcon from '../_common/_icons/PrintIcon';
import NotificationIcon from '../_common/_icons/NotificationIcon';
import RescheduleIcon from '../_common/_icons/RescheduleIcon';
import DeleteIcon from '../_common/_icons/DeleteIcon';
import moment from 'moment';
import Modal from '../_common/Modal';
import datepicker from '../_common/_form-components/Datepicker';
import VueTimepicker from 'vue2-timepicker';

export default {
    components: {
        MeetingsFilters,
        VueScrollbar,
        ViewIcon,
        EditIcon,
        PrintIcon,
        NotificationIcon,
        RescheduleIcon,
        DeleteIcon,
        moment,
        Modal,
        datepicker,
        VueTimepicker,
    },
    methods: {
        ...mapActions([
            'getProjectMeetings',
            'setMeetingsFilters',
            'deleteProjectMeeting',
            'editProjectMeeting',
            'sendMeetingNotifications',
        ]),
        translateText(text) {
            return this.translate(text);
        },
        isInactive(meeting) {
            let currentDate = new Date();
            let meetingDate = new Date(meeting.date);

            return meetingDate < currentDate;
        },
        getDuration(startDate, endDate) {
            let end = moment(endDate, 'HH:mm');
            let start = moment(startDate, 'HH:mm');

            return !isNaN(end.diff(start, 'minutes')) ? end.diff(start, 'minutes') : '-';
        },
        changePage(page) {
            this.activePage = page;
            this.refreshData();
        },
        refreshData() {
            this.getProjectMeetings({
                projectId: this.$route.params.id,
                apiParams: {
                    page: this.activePage,
                },
            });
        },
        setFilterCategory(value) {
            this.setMeetingsFilters({category: value});
            this.refreshData();
        },
        setFilterDate(value) {
            this.setMeetingsFilters({date: value ? moment(value).format('YYYY-MM-DD') : null});
            this.refreshData();
        },
        setEventFilter(value) {
            this.setMeetingsFilters({event: value});
            this.refreshData();
        },
        clearMeetingsFilters(value) {
            this.setMeetingsFilters({clear: value});
            this.refreshData();
        },
        initDeleteModal(meeting) {
            this.showDeleteModal = true;
            this.meetingId = meeting.id;
        },
        deleteMeeting() {
            if (this.meetingId) {
                this.deleteProjectMeeting(this.meetingId);
                this.showDeleteModal = false;
            }
        },
        initRescheduleModal(meeting) {
            this.showRescheduleModal = true;
            this.meetingId = meeting.id;
            this.date = new Date(meeting.date);

            this.startTime = {
                HH: moment(meeting.start, 'HH:mm').format('HH'),
                mm: moment(meeting.start, 'HH:mm').format('mm'),
            };
            this.endTime = {
                HH: moment(meeting.end, 'HH:mm').format('HH'),
                mm: moment(meeting.end, 'HH:mm').format('mm'),
            };
        },
        rescheduleMeeting() {
            let data = {
                id: this.meetingId,
                date: moment(this.date).format('DD-MM-YYYY'),
                start: this.startTime.HH + ':' + this.startTime.mm,
                end: this.endTime.HH + ':' + this.endTime.mm,
            };
            this.editProjectMeeting(data);
            this.showRescheduleModal = false;
        },
        initSendNotifications(meeting) {
            this.showNotificationModal = true;
            this.meetingId = meeting.id;
        },
        printMeeting(meeting) {
            let pdfURL = Routing.generate('app_meeting_pdf', {id: meeting.id});
            window.location = pdfURL;
        },
        sendNotifications() {
            this.sendMeetingNotifications(this.meetingId);
            this.showNotificationModal = false;
        },
    },
    created() {
        this.getProjectMeetings({
            projectId: this.$route.params.id,
            apiParams: {
                page: this.activePage,
            },
        });
    },
    computed: {
        ...mapGetters({
            projectMeetings: 'projectMeetings',
        }),
        pages() {
            return Math.ceil(this.projectMeetings.totalItems / this.perPage);
        },
        perPage() {
            return this.projectMeetings.pageSize;
        },
    },
    data() {
        return {
            activePage: 1,
            showDeleteModal: false,
            showRescheduleModal: false,
            showNotificationModal: false,
            meetingId: null,
            date: null,
            startTime: null,
            endTime: null,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped lang="scss">
    @import '../../css/_variables';
    @import '../../css/_mixins';

    .full-filters {
        margin: 20px 0;
    }

    .meetings-list {
        overflow: hidden;
    }

    .actions {
        margin: 30px 0;
    }

    .table-wrapper {
        width: 100%;
        padding-bottom: 40px;
    }

    .avatars {
        > div {
            height: 34px;
            padding: 2px 0;
        }
    }

    .avatar {
        width: 30px;
        height: 30px;
        @include border-radius(50%);
        background-size: cover;
        display: inline-block;
        margin-right: 5px;

        &:last-child {
            margin-right: 0;
        }
    }

    .two-state {
        position: relative;
        top: -12px;
        background-color: transparent;
        border: none;
        color: $secondColor;
        padding: 5px 0;

        &:hover {
            color: $secondDarkColor;
        }
    }

    .btn-icon {
        cursor: pointer;
    }
</style>
