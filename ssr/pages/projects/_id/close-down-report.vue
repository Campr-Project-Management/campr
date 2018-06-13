<template>
    <div class="project-contract">
        <div class="page-section">
            <div class="row">
                <!-- /// Header /// -->
                <div class="col-md-12">
                    <div class="header">
                        <div class="text-center">
                            <h1>{{ projectCloseDown.projectName }}</h1>
                        </div>
                    </div>

                    <div class="hero-text">
                        {{ translateText('message.close_down_report') }}
                    </div>

                    <div class="project-info">
                        <span>{{ translateText('message.scope') }}: {{ project.projectScopeName || '-' }}</span>
                        <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <span>{{ translateText('message.category') }}: {{ project.projectCategoryName || '-' }}</span>
                    </div>
                </div>
                <!-- /// End Header /// -->
            </div>

            <div class="row">
                <!-- /// Overall Impression /// -->
                <div class="col-md-6 col-md-offset-3">
                    <div class="vueditor-holder">
                        <div class="vueditor-header">{{ translateText('message.overall_impression') }}</div>
                        <div class="vueditor campr-editor">
                            <div class="ve-toolbar">
                                <br>
                                <br>
                            </div>
                            <div class="ve-design" v-html="projectCloseDown.overallImpression"></div>
                        </div>
                    </div>
                </div>
                <!-- End Overall Impression -->
            </div>

            <div class="row margintop40">
                <!-- /// Evaluation Objectives /// -->
                <div class="col-md-6">
                    <h3>{{ translateText('message.evaluation_objectives') }}</h3>

                    <div class="box"
                         v-if="projectCloseDown.evaluationObjectives && projectCloseDown.evaluationObjectives.length > 0"
                         v-for="(evaluationObjective, index) in projectCloseDown.evaluationObjectives"
                         :key="'evaluationObjective'+index"
                    >
                        <header class="flex flex-space-between full">
                            <span>{{index+1}}. <span>{{evaluationObjective.title}}</span></span>
                        </header>
                    </div>
                    <p class="notice" v-else>{{ translateText('label.no_data') }}</p>
                    <div class="hr small"></div>
                </div>
                <!-- /// End Evaluation Objectives /// -->

                <!-- /// Lessons Learned /// -->
                <div class="col-md-6">
                    <h3>{{ translateText('message.lessons_learned') }}</h3>

                    <div class="box"
                         v-if="projectCloseDown.lessons && projectCloseDown.lessons.length > 0"
                         v-for="(lesson, index) in projectCloseDown.lessons"
                         :key="'lesson'+index"
                    >
                        <header class="flex flex-space-between full">
                            <span>{{index+1}}. <span>{{lesson.title}}</span></span>
                        </header>
                    </div>
                    <p class="notice" v-else>{{ translateText('label.no_data') }}</p>
                    <div class="hr small"></div>
                </div>
                <!-- /// End Lessons Learned /// -->
            </div>

            <div class="row margintop40">
                <!-- /// Reflection: Performance / Schedule /// -->
                <div class="col-md-4">
                    <h4>{{ translateText('message.reflection') }}: {{ translateText('message.performance_schedule') }}</h4>
                    <div class="vueditor-holder">
                        <div class="vueditor campr-editor">
                            <br>
                            <div class="ve-design" v-html="projectCloseDown.performanceSchedule"></div>
                        </div>
                    </div>
                </div>
                <!-- /// End Reflection: Performance / Schedule /// -->

                <!-- /// Reflection: Organization / Context /// -->
                <div class="col-md-4">
                    <h4>{{ translateText('message.reflection') }}: {{ translateText('message.organization_context') }}</h4>
                    <div class="vueditor-holder">
                        <div class="vueditor campr-editor">
                            <br>
                            <div class="ve-design" v-html="projectCloseDown.organizationContext"></div>
                        </div>
                    </div>
                </div>
                <!-- /// End Reflection: Organization / Context /// -->

                <!-- /// Reflection: Project Management /// -->
                <div class="col-md-4">
                    <h4>{{ translateText('message.reflection') }}: {{ translateText('message.project_management') }}</h4>
                    <div class="vueditor-holder">
                        <div class="vueditor campr-editor">
                            <br>
                            <div class="ve-design" v-html="projectCloseDown.projectManagement"></div>
                        </div>
                    </div>
                </div>
                <!-- /// End Reflection: Project Management /// -->
            </div>

            <div class="row margintop40">
                <!-- /// Remaining Actions Table /// -->
                <div class="col-md-12">
                    <h3>{{ translateText('message.remaining_action') }}</h3>
                    <div class="table-wrapper" v-if="closeDownActions">
                        <table class="table table-striped" v-if="closeDownActions.items && closeDownActions.items.length > 0">
                            <tbody>
                                <template v-for="action in closeDownActions.items">
                                    <tr :key="`action-hr-${action.id}`">
                                        <td colspan="2">
                                            <hr>
                                        </td>
                                    </tr>
                                    <tr :key="`action-due-date-${action.id}`">
                                        <th>{{ translateText('table_header_cell.due_date') }}</th>
                                        <td>{{ action.dueDate|moment('DD.MM.YYYY') }}</td>
                                    </tr>
                                    <tr :key="`action-empty-0-${action.id}`"></tr>
                                    <tr :key="`action-title-${action.id}`">
                                        <th>{{ translateText('table_header_cell.topic') }}</th>
                                        <td>{{ action.title }}</td>
                                    </tr>
                                    <tr :key="`action-empty-1-${action.id}`"></tr>
                                    <tr :key="`action-action-description-${action.id}`">
                                        <th>{{ translateText('table_header_cell.description') }}</th>
                                        <td>
                                            <p class="action-description" v-html="action.description"></p>
                                        </td>
                                    </tr>
                                    <tr :key="`action-empty-2-${action.id}`"></tr>
                                    <tr :key="`action-responsibility-full-name-${action.id}`">
                                        <th>{{ translateText('table_header_cell.responsible') }}</th>
                                        <td>
                                            {{ action.responsibilityFullName }}
                                            &nbsp;
                                            <div class="avatar" v-bind:style="{ backgroundImage: 'url(' + action.responsibilityAvatar + ')' }"></div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        <div v-else>{{ translateText('label.no_data') }}</div>
                    </div>
                    <div v-else>{{ translateText('label.no_data') }}</div>
                </div>
            </div>

            <!-- /// Project Acceptance /// -->
            <div class="row margintop40">
                <div class="col-md-6 col-md-offset-3">
                    <h3 class="text-center">{{ translateText('message.project_acceptance') }}</h3>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="project-acceptance">
                                <div class="job-title">
                                    {{ translateText('label.project_sponsors') }}
                                </div>
                                <!--<div class="member-name" v-for="sponsor in sponsors" :key="`sponsor-${sponsor.id}`">-->
                                    <!--{{ sponsor.userFullName }}-->
                                <!--</div>-->

                                <div class="flex flex-row flex-center members-big">
                                    <member-badge v-for="(item, index) in sponsors"
                                        v-bind:item="item" v-bind:key="'sponsor'+index" size="small" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="project-acceptance">
                                <div class="job-title">
                                    {{ translateText('label.project_managers') }}
                                </div>
                                <div class="flex flex-row flex-center members-big">
                                    <member-badge v-for="(item, index) in managers"
                                        v-bind:index="index" v-bind:key="'manager'+index" v-bind:item="item" size="small" />
                                </div>
                                <!--<div class="member-name" v-for="manager in managers" :key="`manager-${manager.id}`">-->
                                    <!--{{ manager.userFullName }}-->
                                <!--</div>-->
                            </div>
                        </div>
                    </div>

                    <div class="hr small"></div>

                    <div class="row margintop40">
                        <div class="half half-left">
                            <h3>{{ translateText('message.project_manager_signature') }}</h3>
                        </div>
                        <div class="half half-right">
                            <h3>{{ translateText('message.project_sponsor_signature') }}</h3>
                        </div>

                        <div class="clear-fix"></div>
                    </div>
                </div>
            </div>
            <!-- /// End Project Acceptance /// -->
        </div>
    </div>
</template>

<script>
import Vue from 'vue';

import CalendarIcon from '~/components/_icons/CalendarIcon.vue';
import Datepicker from '~/components/_form-components/Datepicker';
import InputField from '~/components/_form-components/InputField.vue';
import MemberBadge from '~/components/MemberBadge.vue';

export default {
    validate({params}) {
        return /^\d+$/.test(params.id);
    },
    components: {
        CalendarIcon,
        Datepicker,
        InputField,
        MemberBadge,
    },
    methods: {
        translateText(str) {
            return this.translate(str);
        }
    },
    async asyncData({params, query}) {
        let closeDownActions = {items: []};
        let contract = {};
        let managers = [];
        let project = {};
        let projectCloseDown = {};
        let sponsors = [];

        if (query.host && query.key) {
            let res = await Vue.doFetch(`http://${query.host}/api/projects/${params.id}`, query.key);
            project = await res.json();

            res = await Vue.doFetch(`http://${query.host}/api/projects/${params.id}/contracts`, query.key);
            const contracts = await res.json();
            contract = contracts && contracts.length ? contracts[0] : {};

            res = await Vue.doFetch(`http://${query.host}/api/projects/${params.id}/close-downs`, query.key);
            const projectCloseDowns = await res.json();
            projectCloseDown = projectCloseDowns && projectCloseDowns.length ? projectCloseDowns[0] : {};

            res = await Vue.doFetch(`http://${query.host}/api/projects/${params.id}/project-users`, query.key);
            const users = await res.json();
            sponsors = users && users.items
                ? users.items.filter(projectUser => projectUser.projectRoleNames.indexOf('roles.project_sponsor') !== -1)
                : []
            ;
            managers = users && users.items
                ? users.items.filter(projectUser => projectUser.projectRoleNames.indexOf('roles.project_manager') !== -1)
                : []
            ;

            if (projectCloseDown.id) {
                res = await Vue.doFetch(`http://${query.host}/api/project-close-downs/${projectCloseDown.id}/close-down-actions?page=1&pageSize=128`, query.key);
                closeDownActions = await res.json();
            }
        }

        return {
            contract,
            closeDownActions,
            managers,
            project,
            projectCloseDown,
            sponsors,
        }
    }
};
</script>

<style lang="scss">
    // ../../css/_common
    @import '../../../../frontend/src/css/_common.scss';
    @import '../../../../frontend/src/css/vueditor.css';
</style>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../../frontend/src/css/page-section';
    @import '../../../../frontend/src/css/_variables';
    @import '../../../../frontend/src/css/_mixins';

    @media print {
        h3, h4 {
            border-bottom: 1px solid #646EA0;
            padding-bottom: 10px;
            font-size: 11px;
        }
    }

    .half-left {
        float: left;
    }
    .half-right {
        float: right;
    }
    .half {
        width: 45%;
        text-align: center;
        height: 160px;
        line-height: 40px;
        border-bottom: 1px solid #191E37;

        h3 {
            height: 40px;
            line-height: 40px;
            margin-top: 0;
            margin-bottom: 0;
            border-bottom: 1px solid #191E37;
        }
    }

    .members-big {
        margin: 1.9em 0 0;
    }

    .member-badge {
        &:before {
            display: none;
        }
    }

    th {
        border-top: none !important;
        text-align: right;
    }

    .page-section {
        .header {
            justify-content: center;
            text-align: center;

            h1 {
                padding-bottom: 20px;

                span {
                    font-size: 0.75em;
                    display: block;
                    margin-top: 10px;
                }
            }
        }
    }

    .hero-text {
        font-size: 3em;
        font-weight: 700;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 30px;
    }

    .project-info {
        text-align: center;
        margin-bottom: 2em;

        span {
            display: inline-block;
            font-size: 1.5em;
        }
    }

    .header .btn-md {
        margin-top: 24px;
    }

    .hr {
        margin: 30px 0;

        &.small {
            margin: 20px 0;
        }
    }

    .buttons {
        a {
            margin: 0 20px 10px 0;

            &:last-child {
                margin: 0 0 10px 0;
            }
        }
    }

    .long-cell {
        width: 50%;
    }

    .table-wrapper {
        overflow: auto;
    }

    .avatar {
        width: 30px;
        height: 30px;
        @include border-radius(50%);
        background-size: cover;
        display: inline-block;
    }

    .table-responsive {
        th, td {
            white-space: nowrap;
        }
    }

    .action-description {
        width: 300px;
        margin: 0;
        white-space: normal;
        text-transform: none;
    }

    .project-acceptance {
        text-align: center;

        .job-title {
            color: $lightColor;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.2em;
        }

        .member-name {
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .signature-holder {
            height: 100px;
            border-bottom: 3px solid $darkColor;
        }
    }

    .disabledpicker {
        pointer-events: none;
        opacity: .5;
    }
</style>

