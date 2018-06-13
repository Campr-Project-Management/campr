<template>
    <div>
        <div class="row">
            <div class="create-meeting page-section">
                <!-- /// Header /// -->
                <div class="header flex-v-center">
                    <div>
                        <!--<router-link :to="{name: 'project-meetings'}" class="small-link">-->
                            <!--<i class="fa fa-angle-left"></i>-->
                            <!--{{ translateText('message.back_to_meetings') }}-->
                        <!--</router-link>-->
                        <h1>{{ meeting.name }}</h1>
                        <h3 class="category"><b>{{ meeting.meetingCategoryName }}</b></h3>
                        <h4>
                            {{ translateText('message.starting_on') }} <b>{{ meeting.date | moment('dddd') }}</b>, <b>{{ meeting.date | moment('DD.MM.YYYY') }}</b>
                            {{ translateText('message.from') }} <b>{{ meeting.start }}</b> {{ translateText('message.to') }} <b>{{ meeting.end }}</b> | {{ translateText('message.duration') }}: <b>{{ getDuration(meeting.start, meeting.end) }} {{ translateText('message.min') }}</b>
                        </h4>
                    </div>
                </div>
                <!-- /// End Header /// -->
            </div>

            <hr class="double">

            <div class="create-meeting page-section">
                <div class="flex flex-v-center flex-space-between">
                    <div>
                        <h3>{{ translateText('message.participants') }}</h3>
                    </div>
                </div>

                <table class="table table-striped table-responsive">
                    <thead>
                    <tr>
                        <th>{{ translate('table_header_cell.team_member') }}</th>
                        <th>{{ translate('table_header_cell.department') }}</th>
                        <th>{{ translate('table_header_cell.present') }}</th>
                        <th>{{ translate('table_header_cell.distribution_list') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for='participant in meeting.meetingParticipants' :key="`participant-${participant.user}`">
                        <td>
                            <div class="avatars flex flex-v-center">
                                <div>
                                    <div class="avatar" :style="{ backgroundImage: 'url('+participant.userAvatar+')' }"></div>
                                </div>
                                <span>{{ participant.userFullName }}</span>
                            </div>
                        </td>
                        <td>
                            <span v-for="(department, index) in participant.departments" :key="`participant-department-${index}`">
                                {{ department }}<span v-if="index < participant.departments.length - 1">,</span>
                            </span>
                        </td>
                        <td>
                            <span v-if="participant.isPresent">yes</span>
                            <span v-else>no</span>
                        </td>
                        <td>
                            <span v-if="participant.inDistributionList">yes</span>
                            <span v-else>no</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <hr class="double">

            <!-- /// Meeting Location /// -->
            <h3>{{ translateText('message.location') }}</h3>
            <p>{{ meeting.location }}</p>
            <!-- /// End Meeting Location /// -->

            <hr class="double">

            <!-- /// Meeting Objectives /// -->
            <h3>{{ translateText('message.objectives') }}</h3>
            <ul class="action-list" v-if="meeting.meetingObjectives">
                <li v-for="objective in meeting.meetingObjectives" :key="`objective-${objective.id}`">
                    <div class="list-item-description">
                        {{ objective.description }}
                    </div>
                </li>
            </ul>
            <!-- /// End Meeting Objectives /// -->

            <hr class="double">

            <!-- /// Meeting Agenda /// -->
            <h3>{{ translateText('message.agenda') }}</h3>
            <div class="overflow-hidden">
                <table class="table table-striped table-responsive">
                    <thead>
                        <tr>
                            <th>{{ translateText('table_header_cell.topic') }}</th>
                            <th>{{ translateText('table_header_cell.responsible') }}</th>
                            <th>{{ translateText('table_header_cell.start') }}</th>
                            <th>{{ translateText('table_header_cell.finish') }}</th>
                            <th>{{ translateText('table_header_cell.duration') }}</th>
                        </tr>
                    </thead>
                    <tbody v-if="meetingAgendas">
                        <tr v-for="agenda in meetingAgendas.items" :key="`agenda-${agenda.id}`">
                            <td class="topic">{{ agenda.topic }}</td>
                            <td>
                                <div class="avatars collapse in" id="tp-meeting-20032017-1">
                                    <div>
                                        <div class="avatar" :style="{ backgroundImage: 'url('+agenda.responsibilityAvatar+')' }"></div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ agenda.start }}</td>
                            <td>{{ agenda.end }}</td>
                            <td>{{ getDuration(agenda.start, agenda.end) }} {{ translateText('message.min') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <hr class="double">

            <!-- /// Decisions /// -->
            <h3>{{ translateText('message.decisions') }}</h3>

            <div class="entries-wrapper" v-if="meeting.decisions">
                <!-- /// Decision /// -->
                <div class="entry" v-for="decision in meeting.decisions" :key="`decision-${decision.id}`">
                    <div class="entry-header flex flex-space-between flex-v-center">
                        <div class="entry-title">
                            <h4>{{ decision.title }}</h4>  | {{ translateText('message.due_date') }}: <b>{{ decision.dueDate | moment('DD.MM.YYYY') }}</b>
                        </div>
                    </div>
                    <div class="entry-responsible flex flex-v-center">
                        <div class="user-avatar" v-bind:style="{ backgroundImage: 'url(' + decision.responsibilityAvatar + ')' }"></div>
                        <div>
                            {{ translateText('message.responsible') }}:
                            <b>{{ decision.responsibilityFullName }}</b>
                        </div>
                    </div>
                    <div class="entry-body" v-html="decision.description"></div>
                </div>
                <!-- /// End Decision /// -->
            </div>
            <!-- /// End Decisions /// -->

            <hr class="double">

            <!-- /// ToDos /// -->
            <h3>{{ translateText('message.todos') }}</h3>

            <div class="entries-wrapper" v-if="meeting.todos">
                <!-- /// ToDo /// -->
                <div class="entry" v-for="todo in meeting.todos" :key="`todo-${todo.id}`">
                    <div class="entry-header flex flex-space-between flex-v-center">
                        <div class="entry-title">
                            <h4>{{ todo.title }}</h4>  | {{ translateText('message.due_date') }}: <b>{{ todo.dueDate | moment('DD.MM.YYYY') }}</b> | {{ translateText('message.status') }}: <b v-if="todo.status">{{ translateText(todo.statusName) }}</b><b v-else>-</b>
                        </div>
                    </div>
                    <div class="entry-responsible flex flex-v-center">
                        <div class="user-avatar" v-bind:style="{ backgroundImage: 'url(' + todo.responsibilityAvatar + ')' }"></div>
                        <div>
                            {{ translateText('message.responsible') }}:
                            <b>{{ todo.responsibilityFullName }}</b>
                        </div>
                    </div>
                    <div class="entry-body" v-html="todo.description"></div>
                </div>
                <!-- /// End ToDo /// -->
            </div>
            <!-- /// End ToDos /// -->

            <hr class="double">

            <!-- /// Infos /// -->
            <h3>{{ translateText('message.infos') }}</h3>

            <div class="entries-wrapper" v-if="meeting.infos">
                <!-- /// Info /// -->
                <div class="entry" v-for="info in meeting.infos" :key="`info-${info.id}`">
                    <div class="entry-header flex flex-space-between flex-v-center">
                        <div class="entry-title">
                            <h4>{{ info.topic }}</h4> |
                            <template v-if="info.isExpired">
                                {{ translateText('message.expired_at') }} <b class="middle-color">{{ info.expiresAt | date }}</b>
                            </template>
                            <template v-else>
                                {{ translateText('message.expiry_date') }} <b>{{ info.expiresAt | date }}</b>
                            </template> |

                            {{ translateText('message.category') }}: <b v-if="info.infoCategory">{{ translateText(info.infoCategoryName) }}</b><b v-else>-</b>
                        </div>
                    </div>
                    <div class="entry-responsible flex flex-v-center" v-if="info.responsibility">
                        <div class="user-avatar" v-bind:style="{ backgroundImage: 'url(' + (info.responsibilityAvatar ? '/uploads/avatars/' + info.responsibilityAvatar : info.responsibilityGravatar) + ')' }"></div>
                        <div>
                            {{ translateText('message.responsible') }}:
                            <b>{{ info.responsibilityFullName }}</b>
                        </div>
                    </div>
                    <div class="entry-body" v-html="info.description"></div>
                </div>
                <!-- /// End Info /// -->
            </div>
            <!-- /// End Infos /// -->
        </div>
    </div>
</template>

<script>
import Vue from 'vue';
import moment from 'moment';

export default {
    validate({params}) {
        return /^\d+$/.test(params.id);
    },
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
        getDuration: function(startDate, endDate) {
            let end = moment(endDate, 'HH:mm');
            let start = moment(startDate, 'HH:mm');

            return !isNaN(end.diff(start, 'minutes')) ? end.diff(start, 'minutes') : '-';
        },
    },
    async asyncData({params, query}) {
        let meeting = {};
        let meetingAgendas = [];
        let distributionLists = [];

        if (query.host && query.key) {
            let res = await Vue.doFetch(`http://${query.host}/api/meetings/${params.id}`, query.key);
            meeting = await res.json();

            res = await Vue.doFetch(`http://${query.host}/api/meetings/${params.id}/agendas`, query.key);
            meetingAgendas = await res.json();

            console.log(meeting);
            res = await Vue.doFetch(`http://${query.host}/api/projects/${meeting.project}/distribution-lists`, query.key);
            distributionLists = await res.json();
        }

        console.log(query);
        console.log(params);

        return {
            //
            meeting,
            meetingAgendas,
            distributionLists,
            //
            meetingId: params.id,
            agendasActivePage: 1,
            showPresent: null,
            showEditObjectiveModal: false,
            showDeleteObjectiveModal: false,
            editObjectiveObject: {},
            showEditAgendaModal: false,
            showDeleteAgendaModal: false,
            editAgendaObject: {},
            showEditDecisionModal: false,
            showDeleteDecisionModal: false,
            editDecisionObject: {},
            showEditTodoModal: false,
            showDeleteTodoModal: false,
            editTodoObject: {},
            showEditInfoModal: false,
            showDeleteInfoModal: false,
            editInfoObject: {},
            deleteMeetingModal: false,
            rescheduleModal: false,
            date: new Date(),
            startTime: {},
            endTime: {},
            selectedParticipants: [],
            showNotificationModal: false,
        };
    }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../frontend/src/css/_mixins';
    @import '../../../frontend/src/css/_variables';
    @import '../../../frontend/src/css/common';

    .title {
        position: relative;
        top: 15px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 700;
    }

    .action-list {
        margin-bottom: 15px;

        li {
            margin-bottom: 15px;
            position: relative;
            padding-right: 60px;
            padding-bottom: 15px;
            border-bottom: 1px solid $darkerColor;

            .list-item-description {
                position: relative;
                padding-left: 30px;

                &:before {
                    content: '';
                    display: block;
                    position: absolute;
                    @include border-radius(50%);
                    background-color: $lightColor;
                    width: 10px;
                    height: 10px;
                    left: 0;
                    top: 0;
                }
            }

            .list-item-actions {
                position: absolute;
                top: 0;
                right: 0;
                width: 60px;
                text-align: right;

                a {
                    margin-left: 10px;
                }
            }

            &:last-child {
                margin-bottom: 0;
                padding-bottom: 0;
                border: none;
            }
        }
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
            display: inline-block;
        }

        span {
            margin-left: 10px;
            line-height: 34px;
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

    .topic {
        white-space: normal;
        text-transform: none;
    }

    .user-avatar {
        width: 30px;
        height: 30px;
        display: inline-block;
        margin: 0 10px 0 0;
        position: relative;
        top: -2px;
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        @include border-radius(50%);
    }

    p {
        margin-bottom: 20px;
    }

    .entry {
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid $darkerColor;

        .entry-header {
            .entry-title {
                text-transform: uppercase;
                letter-spacing: 0.1em;
                font-size: 10px;
                margin-bottom: 10px;

                h4 {
                    display: inline-block;
                    text-transform: none;
                    letter-spacing: 0;
                    margin: 0;
                    font-weight: 700;
                }
            }

            .done {
                color: $secondColor;
            }

            .undone {
                color: $dangerColor;
            }

            .entry-buttons {
                text-align: right;

                .btn {
                    margin: 0 0 10px 10px;
                }
            }
        }

        .entry-responsible {
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 10px;
            line-height: 1.5em;

            b {
                display: block;
                font-size: 12px;
            }
        }

        .entry-body {
            padding: 10px 0 0 0;

            ul {
                list-style-type: disc;
                list-style-position: inside;

                &:last-child {
                    margin-bottom: 0;
                }
            }

            ol {
                list-style-type: decimal;
                list-style-position: inside;
                padding: 0;

                &:last-child {
                    margin-bottom: 0;
                }
            }

            img {
                @include box-shadow(0, 0, 20px, $darkColor);
            }

            .title {
                font-weight: bold;
                margin-bottom: 5px;
            }

            .cost {
                text-transform: uppercase;
                color: $lightColor;
                letter-spacing: 0.1em;

                b {
                    color: $lighterColor;
                }
            }

            p {
                &:last-child {
                    margin-bottom: 0;
                }
            }
        }

        &:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border: none;
        }
    }

    .new-comment {
        .footer-buttons {
            margin-top: 15px;
        }
    }

    .buttons {
        a {
            margin: 5px 0 5px 10px;
        }
    }

    .category {
        margin-top: 0;
    }

    ul.attachments {
        display: inline-block;
        margin: 0;
        list-style: none;
        padding: 0;

        li {
            margin-bottom: 15px;

            a {
                color: $secondColor;
                @include transition(color, 0.2s, ease);

                &:hover {
                    color: $secondDarkColor;
                }
            }

            &:last-child {
                margin: 0;
            }
        }
    }

    .footer-buttons {
        margin-top: 60px;
        padding: 30px 0;
        border-top: 1px solid $darkerColor;
    }
</style>
