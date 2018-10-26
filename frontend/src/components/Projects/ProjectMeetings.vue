<template>
    <div class="project-meetings page-section">
        <modal v-if="showDeleteModal" @close="showDeleteModal = false">
            <p class="modal-title">{{ translate('message.delete_meeting') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showDeleteModal = false" class="btn-rounded btn-auto">{{ translate('message.no') }}</a>
                <a href="javascript:void(0)" @click="deleteMeeting()" class="btn-rounded btn-empty btn-auto danger-color danger-border">{{ translate('message.yes') }}</a>
            </div>
        </modal>

        <modal v-if="showRescheduleModal" @close="showRescheduleModal = false" v-bind:hasSpecificClass="true">
            <p class="modal-title">{{ translate('message.reschedule_meeting') }}</p>
            <div class="form-group last-form-group">
                <div class="col-md-4">
                    <div class="input-holder">
                        <label class="active">{{ translate('label.select_date') }}</label>
                        <date-field v-model="date"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-holder">
                        <label class="active">{{ translate('label.start_time') }}</label>
                        <vue-timepicker v-model="startTime" hide-clear-button></vue-timepicker>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-holder">
                        <label class="active">{{ translate('label.finish_time') }}</label>
                        <vue-timepicker v-model="endTime" hide-clear-button></vue-timepicker>
                    </div>
                </div>
            </div>
            <hr class="double">

            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showRescheduleModal = false" class="btn-rounded btn-auto">{{ translate('button.cancel') }}</a>
                <a href="javascript:void(0)" @click="rescheduleMeeting()" class="btn-rounded btn-auto second-bg">{{ translate('button.save') }}</a>
            </div>
        </modal>

        <modal v-if="showNotificationModal" @close="showNotificationModal = false">
            <p class="modal-title">{{ translate('message.send_notifications') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showNotificationModal = false" class="btn-rounded btn-auto">{{ translate('message.no') }}</a>
                <a href="javascript:void(0)" @click="sendNotifications" class="btn-rounded btn-auto second-bg">{{ translate('message.yes') }}</a>
            </div>
        </modal>

        <modal v-if="showMeetingReportModal" @close="showMeetingReportModal = false">
            <p class="modal-title">{{ translate('message.send_meeting_report') }}</p>
            <div class="form-group">
                <editor
                        v-model="lastMeetingReportContent"
                        :label="'placeholder.email_content'"/>
                <error
                        v-if="validationMessages.content && validationMessages.content.length"
                        v-for="message in validationMessages.content"
                        :message="message" />
            </div>

            <hr class="double">
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showMeetingReportModal = false" class="btn-rounded btn-auto">{{ translate('message.no') }}</a>
                <a href="javascript:void(0)" @click="sendReport" class="btn-rounded btn-auto second-bg">{{ translate('message.yes') }}</a>
            </div>
        </modal>

        <div class="header flex flex-space-between">
            <h1>{{ translate('message.project_meetings') }}</h1>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-meetings-create-meeting'}" class="btn-rounded btn-auto second-bg">{{ translate('message.create_new_meeting') }}</router-link>
            </div>
        </div>

        <div class="flex flex-direction-reverse">
            <div class="full-filters">
                <meetings-filters :clearAllFilters="clearMeetingsFilters" :selectEvent="setEventFilter" :selectCategory="setFilterCategory" :selectDate="setFilterDate"></meetings-filters>
            </div>
        </div>

        <div class="meetings-list">
            <scrollbar class="table-wrapper customScrollbar">
                <div class="scroll-wrapper">
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>{{ translate('table_header_cell.event') }}</th>
                                <th>{{ translate('table_header_cell.category') }}</th>
                                <th>{{ translate('table_header_cell.date') }}</th>
                                <th>{{ translate('table_header_cell.schedule') }}</th>
                                <th>{{ translate('table_header_cell.duration') }}</th>
                                <th>{{ translate('table_header_cell.participants') }}</th>
                                <th>{{ translate('table_header_cell.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody v-if="projectMeetings">
                            <tr v-for="(meeting, index) in projectMeetings.items"
                                :key="index"
                                :class="{'inactive': isInactive(meeting)}">
                                <td>{{ translate(meeting.name) }}</td>
                                <td>{{ meeting.meetingCategoryName }}</td>
                                <td>{{ meeting.date | moment('DD.MM.YYYY') }}</td>
                                <td>{{ meeting.start }} - {{ meeting.end }}</td>
                                <td>{{ getDuration(meeting.start, meeting.end) }}</td>
                                <td>
                                    <div class="avatars collapse in" v-if="meetingHasParticipants(meeting)">
                                        <div v-if="meeting.meetingParticipants">
                                            <span v-for="(participant, index) in (showMore[meeting.id] ? participants(meeting) : participants(meeting).slice(0, 3))"
                                                :key="index">

                                                <user-avatar
                                                        size="small"
                                                        :tooltip="participant.userFullName"
                                                        :url="participant.avatarUrl"
                                                        :name="participant.userFullName"/>
                                            </span>
                                            <button v-if="participants(meeting).length > 3" type="button" v-bind:class="[{collapsed: !showMore[meeting.id]}, 'two-state']" @click="setShowMore(meeting.id, !showMore[meeting.id])">
                                                <span v-if="!showMore[meeting.id]" class="more">{{ translate('message.more') }} +</span>
                                                <span v-if="showMore[meeting.id]" class="less">{{ translate('message.less') }} -</span>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <router-link class="btn-icon" :to="{name: 'project-meetings-view-meeting', params:{meetingId: meeting.id}}" v-tooltip.top-center="translate('message.view_meeting')">
                                            <view-icon fill="second-fill"></view-icon>
                                        </router-link>
                                        <router-link class="btn-icon" :to="{name: 'project-meetings-edit-meeting', params:{meetingId: meeting.id}}" v-tooltip.top-center="translate('message.edit_meeting')">
                                            <edit-icon fill="second-fill"></edit-icon>
                                        </router-link>
                                        <a
                                                @click="printMeeting(meeting)"
                                                class="btn-icon"
                                                v-tooltip.top-center="translate('message.print_meeting')"><print-icon fill="second-fill"></print-icon></a>
                                        <a
                                                @click="initSendNotifications(meeting)"
                                                v-if="!isInactive(meeting)"
                                                href="javascript:void(0)"
                                                class="btn-icon"
                                                v-tooltip.top-center="translate('message.send_notifications')"><notification-icon fill="second-fill"></notification-icon></a>
                                        <a
                                                @click="initSendMeetingReport(meeting)"
                                                v-if="isInactive(meeting)"
                                                href="javascript:void(0)"
                                                class="btn-icon"
                                                v-tooltip.top-center="translate('message.send_meeting_report')"><notification-icon fill="second-fill"></notification-icon></a>

                                        <a
                                                @click="initRescheduleModal(meeting)"
                                                v-if="!isInactive(meeting)"
                                                href="javascript:void(0)"
                                                class="btn-icon"
                                                v-tooltip.top-center="translate('message.reschedule_meeting')"><reschedule-icon fill="second-fill"></reschedule-icon></a>
                                        <a
                                                @click="initDeleteModal(meeting)"
                                                v-if="!isInactive(meeting)"
                                                href="javascript:void(0)"
                                                class="btn-icon"
                                                v-tooltip.top-center="translate('message.delete_meeting')"><delete-icon fill="danger-fill"></delete-icon></a>

                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </scrollbar>

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
                    <span class="pagination-info">{{ translate('message.displaying') }} {{ projectMeetings.items.length }} {{ translate('message.results_out_of') }} {{ projectMeetings.totalItems }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import MeetingsFilters from '../_common/_meetings-components/MeetingsFilters';
import ViewIcon from '../_common/_icons/ViewIcon';
import EditIcon from '../_common/_icons/EditIcon';
import PrintIcon from '../_common/_icons/PrintIcon';
import NotificationIcon from '../_common/_icons/NotificationIcon';
import RescheduleIcon from '../_common/_icons/RescheduleIcon';
import DeleteIcon from '../_common/_icons/DeleteIcon';
import moment from 'moment';
import Modal from '../_common/Modal';
import VueTimepicker from 'vue2-timepicker';
import DateField from '../_common/_form-components/DateField';
import UserAvatar from '../_common/UserAvatar';
import Editor from '../_common/Editor';

export default {
    components: {
        UserAvatar,
        DateField,
        MeetingsFilters,
        ViewIcon,
        EditIcon,
        PrintIcon,
        NotificationIcon,
        RescheduleIcon,
        DeleteIcon,
        moment,
        Modal,
        Editor,
        VueTimepicker,
    },
    methods: {
        ...mapActions([
            'getProjectMeetings',
            'setMeetingsFilters',
            'deleteProjectMeeting',
            'editProjectMeeting',
            'sendMeetingNotifications',
            'sendMeetingReport',
            'getLastMeetingReport',
        ]),
        isInactive(meeting) {
            let currentDate = moment();
            let date = meeting.date.split(' ');
            let meetingDate = moment(date[0] + ' ' + meeting.start);

            return meetingDate < currentDate;
        },
        getDuration(startDate, endDate) {
            let end = moment(endDate, 'HH:mm');
            let start = moment(startDate, 'HH:mm');

            return this.$humanizeDuration(end.diff(start, 'miliseconds'));
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
            this.date = meeting.date ? moment(meeting.date).toDate() : null;

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
            this.editProjectMeeting({
                id: this.meetingId,
                data,
            });
            this.showRescheduleModal = false;
        },
        initSendNotifications(meeting) {
            this.showNotificationModal = true;
            this.meetingId = meeting.id;
        },
        initSendMeetingReport(meeting) {
            this.showMeetingReportModal = true;
            this.getLastMeetingReport({meetingId: meeting.id});
            this.meetingId = meeting.id;
        },
        printMeeting(meeting) {
            let pdfURL = Routing.generate('app_meeting_pdf', {id: meeting.id});
            window.location = pdfURL;
        },
        sendNotifications() {
            this.sendMeetingNotifications(this.meetingId)
                .then((response) => {
                    this.showNotificationModal = false;
                });
        },
        sendReport() {
            this
                .sendMeetingReport({
                    id: this.meetingId,
                    content: this.content,
                }).then(
                    (response) => {
                        if (response.body && response.body.error && response.body.messages) {
                            return;
                        }
                        this.showMeetingReportModal = false;
                    },
                    () => {
                        this.showMeetingReportModal = false;
                    },
                )
            ;
        },
        participants: (meeting) => meeting
            .meetingParticipants
            .filter((item) => {
                return item.isPresent === true;
            }),
        setShowMore(meetingId, value) {
            this.showMore[meetingId] = value;
            this.$forceUpdate();
        },
        meetingHasParticipants(meeting) {
            if (meeting.meetingParticipants.length) {
                let participants = meeting.meetingParticipants.filter((participant) => {
                    return participant.isPresent === true;
                });
                if (participants.length) {
                    return true;
                }
            }
            return false;
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
            validationMessages: 'validationMessages',
            lastMeetingReport: 'lastMeetingReport',
        }),
        lastMeetingReportContent: {
            get() {
                if (!this.lastMeetingReport.content) {
                    return '';
                }

                return this.lastMeetingReport.content;
            },
            set(newValue) {
                this.content = newValue;
            },
        },
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
            showMeetingReportModal: false,
            meetingId: null,
            date: moment().toDate(),
            startTime: null,
            endTime: null,
            showMore: {},
            content: '',
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped lang="scss">
    @import '../../css/variables';
    @import '../../css/mixins';
    @import '../../css/common';

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
