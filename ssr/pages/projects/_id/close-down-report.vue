<template>
    <page :team="team" :project="project" :title="projectCloseDown.projectName" :subtitle="translate('message.close_down_report')">
        <div class="page-section">
            <div class="row">
                <!-- /// Header /// -->
                <h3 class="text-center">
                    <span>{{ translate('message.scope') }}: {{ project.projectScopeName || '-' }}</span>
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <span>{{ translate('message.category') }}: {{ project.projectCategoryName || '-' }}</span>
                </h3>
            </div>

            <div class="row" v-if="projectCloseDown.overallImpression">
                <h3>{{ translate('message.overall_impression') }}</h3>
                <div v-html="projectCloseDown.overallImpression"></div>
                <!-- End Overall Impression -->
            </div>

            <div class="row">
                <h3>{{ translate('message.evaluation_objectives') }}</h3>

                <table class="content-table">
                    <tbody>
                    <tr>
                        <th>{{ translate('label.title') }}</th>
                    </tr>
                    <tr
                        v-if="projectCloseDown.evaluationObjectives && projectCloseDown.evaluationObjectives.length > 0"
                        v-for="(evaluationObjective, index) in projectCloseDown.evaluationObjectives"
                        :key="'evaluationObjective'+index"
                    >
                        <td>{{ index + 1 }}. {{ evaluationObjective.title }}</td>
                    </tr>
                    <tr v-else>
                        <td>{{ translate('label.no_data') }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <h3>{{ translate('message.lessons_learned') }}</h3>

                <table class="content-table">
                    <tbody>
                        <tr>
                            <th>{{ translate('label.title') }}</th>
                        </tr>
                        <tr
                            v-if="projectCloseDown.lessons && projectCloseDown.lessons.length > 0"
                            v-for="(lesson, index) in projectCloseDown.lessons"
                            :key="'lesson'+index"
                        >
                            <td>{{ index + 1 }}. {{ lesson.title }}</td>
                        </tr>
                        <tr v-else>
                            <td>{{ translate('label.no_data') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <h3>{{ translate('message.reflection') }}: {{ translate('message.performance_schedule') }}</h3>
                <div v-html="projectCloseDown.performanceSchedule"></div>
            </div>

            <div class="row">
                <h3>{{ translate('message.reflection') }}: {{ translate('message.organization_context') }}</h3>
                <div v-html="projectCloseDown.organizationContext"></div>
            </div>

            <div class="row">
                <h3>{{ translate('message.reflection') }}: {{ translate('message.project_management') }}</h3>
                <div v-html="projectCloseDown.projectManagement"></div>
            </div>

            <div class="row">
                <h3>{{ translate('message.remaining_action') }}</h3>
                <table v-if="closeDownActions.items && closeDownActions.items.length > 0" class="content-table">
                    <tbody>
                        <tr>
                            <th>{{ translate('table_header_cell.due_date') }}</th>
                            <th>{{ translate('table_header_cell.topic') }}</th>
                            <th>{{ translate('table_header_cell.description') }}</th>
                            <th>{{ translate('table_header_cell.responsible') }}</th>
                        </tr>
                        <tr :key="`action-hr-${action.id}`" v-for="action in closeDownActions.items">
                            <td>{{ action.dueDate|date }}</td>
                            <td>{{ action.title }}</td>
                            <td>
                                <p class="action-description" v-html="action.description"></p>
                            </td>
                            <td>
                                {{ action.responsibilityFullName }}
                                &nbsp;
                                <div class="avatar"
                                     v-bind:style="{ backgroundImage: 'url(' + action.responsibilityAvatarUrl + ')' }"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-else>
                    {{ translate('label.no_data') }}
                </div>
            </div>

            <div class="row col-xs-12" style="padding-left: 0; padding-right: 0;">
                <h3>{{ translate('message.project_acceptance') }}</h3>
                <div class="col-xs-6 text-center">
                    <div class="project-acceptance">
                        <div class="job-title">
                            {{ translate('label.project_sponsors') }}
                        </div>
                        <!--<div class="member-name" v-for="sponsor in sponsors" :key="`sponsor-${sponsor.id}`">-->
                        <!--{{ sponsor.userFullName }}-->
                        <!--</div>-->

                        <div class="flex flex-row flex-center members-big">
                            <member-badge v-for="(item, index) in sponsors"
                                          v-bind:item="item" v-bind:key="'sponsor'+index" size="small"/>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 text-center" style="padding-right: 0; margin-left: -10px;">
                    <div class="project-acceptance">
                        <div class="job-title">
                            {{ translate('label.project_managers') }}
                        </div>
                        <div class="flex flex-row flex-center members-big">
                            <member-badge v-for="(item, index) in managers"
                                          v-bind:index="index" v-bind:key="'manager'+index"
                                          v-bind:item="item" size="small"/>
                        </div>
                        <!--<div class="member-name" v-for="manager in managers" :key="`manager-${manager.id}`">-->
                        <!--{{ manager.userFullName }}-->
                        <!--</div>-->
                    </div>
                </div>
            </div>

            <signature />
        </div>
    </page>
</template>

<script>
    import Vue from 'vue';

    import CalendarIcon from '~/components/_icons/CalendarIcon.vue';
    import InputField from '~/components/_form-components/InputField.vue';
    import Signature from '~/components/_common/Signature.vue';
    import MemberBadge from '~/components/MemberBadge.vue';
    import Page from '../../../layouts/Page';

    export default {
        validate({params}) {
            return /^\d+$/.test(params.id);
        },
        components: {
            CalendarIcon,
            InputField,
            MemberBadge,
            Page,
            Signature,
        },
        created() {
            if (this.locale) {
                Translator.locale = this.locale;
            }
        },
        async asyncData({params, query}) {
            let closeDownActions = {items: []};
            let contract = {};
            let managers = [];
            let project = {};
            let team = {};
            let projectCloseDown = {};
            let sponsors = [];
            let locale = query.locale ? query.locale : '';

            if (query.host && query.key) {
                let res = await Vue.doFetch(`http://${query.host}/api/team`, query.key);
                team = await res.json();

                res = await Vue.doFetch(`http://${query.host}/api/projects/${params.id}/close-downs`, query.key);
                const projectCloseDowns = await res.json();
                projectCloseDown = projectCloseDowns && projectCloseDowns.length ? projectCloseDowns[0] : {};

                res = await Vue.doFetch(`http://${query.host}/api/projects/${params.id}`, query.key);
                project = await res.json();

                res = await Vue.doFetch(`http://${query.host}/api/projects/${params.id}/contracts`, query.key);
                const contracts = await res.json();
                contract = contracts && contracts.length ? contracts[0] : {};

                res = await Vue.doFetch(`http://${query.host}/api/projects/${params.id}/project-users`, query.key);
                const users = await res.json();
                sponsors = users && users.items
                    ? users.items.filter(
                        projectUser => projectUser.projectRoleNames.indexOf('roles.project_sponsor') !== -1)
                    : []
                ;
                managers = users && users.items
                    ? users.items.filter(
                        projectUser => projectUser.projectRoleNames.indexOf('roles.project_manager') !== -1)
                    : []
                ;

                if (projectCloseDown.id) {
                    res = await Vue.doFetch(
                        `http://${query.host}/api/project-close-downs/${projectCloseDown.id}/close-down-actions?page=1&pageSize=128`,
                        query.key);
                    closeDownActions = await res.json();
                }
            }

            return {
                contract,
                team,
                closeDownActions,
                managers,
                project,
                projectCloseDown,
                sponsors,
                locale,
            };
        },
    };
</script>

<style lang="scss">
    // ../../css/_common
    // @import '../../../../frontend/src/css/_common.scss';
    // @import '../../../../frontend/src/css/vueditor.css';
</style>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    /*@import '../../../../frontend/src/css/page-section';
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
        height: 100px;
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
    }*/
</style>

