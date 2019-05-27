<template>
    <page :team="team" :project="project">
        <div class="project-contract">
            <div class="page-section">
                <div class="row">
                    <!-- /// Header /// -->
                    <div class="col-md-12">
                        <div class="header">
                            <div class="text-center">
                                <h1>{{ contract.projectName }}</h1>
                            </div>
                        </div>

                        <div class="hero-text">
                            {{ translate('message.project_contract') }}
                        </div>

                        <div class="project-info">
                            <span>{{ translate('message.scope') }}: {{ project.projectScopeName || '-' }}</span>
                            <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                            <span>{{ translate('message.category') }}: {{ project.projectCategoryName || '-' }}</span>
                            <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                            <span>{{ translate('message.approved_on') }}: {{ contract.approvedAt || '-' }}</span>
                        </div>
                    </div>
                    <!-- /// End Header /// -->
                </div>

                <div class="row">
                    <!-- /// Project Description /// -->
                    <div class="col-md-6">
                        <div class="vueditor-holder">
                            <div class="vueditor-header">{{ translate('message.project_description') }}</div>
                            <div class="vueditor campr-editor">
                                <div class="ve-toolbar">
                                    <br>
                                    <br>
                                </div>
                                <div class="ve-design" v-html="contract.description"></div>
                            </div>
                        </div>
                    </div>
                    <!-- End Project Description -->

                    <!-- /// Project Start Event /// -->
                    <div class="col-md-6">
                        <div class="vueditor-holder">
                            <div class="vueditor-header">{{ translate('label.project_start_event') }}</div>
                            <div class="vueditor campr-editor">
                                <div class="ve-toolbar">
                                    <br>
                                    <br>
                                </div>
                                <div class="ve-design" v-html="contract.projectStartEvent"></div>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Project Start Event /// -->
                </div>

                <div class="row margintop40">
                    <!-- /// Project Schedule /// -->
                    <div class="col-md-6 col-md-offset-3">
                        <h3 class="text-center">{{ translate('message.schedule') }}</h3>
                        <div class="flex flex-space-between dates">
                            <div class="input-holder left">
                                <label class="active">{{ translate('label.proposed_start_date') }}</label>
                                <span>{{ proposedStartDate | date }}</span>
                            </div>
                            <div class="input-holder right">
                                <label class="active">{{ translate('label.proposed_end_date') }}</label>
                                <span>{{ proposedEndDate | date }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Project Schedule /// -->
                </div>

                <div class="row">
                    <!-- /// Project Sponsors & Managers /// -->
                    <div class="col-md-12">
                        <div class="header">
                            <h3 class="clickable" @click="toggleSponsorsManagers()">{{ translate('message.sponsors_managers') }}
                                <i class="fa fa-angle-down" v-if="!showSponsorsManagers"></i>
                                <i class="fa fa-angle-up" v-if="showSponsorsManagers"></i>
                            </h3>
                        </div>
                        <div class="flex flex-row flex-center members-big" v-if="showSponsorsManagers">
                            <member-badge v-for="(item, index) in project.projectSponsors"
                                          v-bind:item="item" v-bind:key="'projectSponsors'+index" size="small"></member-badge>
                            <member-badge v-for="(item, index) in project.projectManagers"
                                          v-bind:index="index" v-bind:key="'projectManagers'+index" v-bind:item="item" size="small"></member-badge>
                            <p v-if="project.projectSponsors.length === 0 && project.projectManagers.length === 0">{{ translate('label.no_data') }}</p>
                        </div>
                    </div>
                    <!-- /// End Project Sponsors & Managers /// -->
                </div>

                <div class="row margintop40">
                    <!-- /// Project Objectives /// -->
                    <div class="col-md-4">
                        <h3>{{ translate('message.project_objectives') }}</h3>

                        <div class="box"
                             v-if="project.projectObjectives && project.projectObjectives.length > 0"
                             v-for="(projectObjective, index) in project.projectObjectives"
                             :key="'projectObjective'+index"
                        >
                            <header class="flex flex-space-between full">
                                <span>{{index+1}}. <span>{{projectObjective.title}}</span></span>
                            </header>
                        </div>
                        <p class="notice" v-else>{{ translate('label.no_data') }}</p>
                        <div class="hr small"></div>
                    </div>
                    <!-- /// End Project Objectives /// -->

                    <!-- /// Project Limitations /// -->
                    <div class="col-md-4">
                        <h3>{{ translate('message.project_limitations') }}</h3>

                        <div class="box"
                             v-if="project.projectLimitations && project.projectLimitations.length > 0"
                             v-for="(projectLimitation, index) in project.projectLimitations"
                             :key="'projectLimitation'+index"
                        >
                            <header class="flex flex-space-between full">
                                <span>{{index+1}}. <span>{{projectLimitation.description}}</span></span>
                            </header>
                        </div>
                        <p class="notice" v-else>{{ translate('label.no_data') }}</p>
                        <div class="hr small"></div>
                    </div>
                    <!-- /// End Project Limitations /// -->

                    <!-- /// Project Deliverables /// -->
                    <div class="col-md-4">
                        <h3>{{ translate('message.project_deliverables') }}</h3>

                        <div class="box"
                             v-if="project.projectDeliverables && project.projectDeliverables.length > 0"
                             v-for="(projectLimitation, index) in project.projectDeliverables"
                             :key="'projectLimitation'+index"
                        >
                            <header class="flex flex-space-between full">
                                <span>{{index+1}}. <span>{{projectLimitation.description}}</span></span>
                            </header>
                        </div>
                        <p class="notice" v-else>{{ translate('label.no_data') }}</p>
                        <div class="hr small"></div>
                    </div>
                    <!-- /// End Project Deliverables /// -->
                </div>

                <div class="row margintop40 resources">
                    <!-- /// Project Internal Costs /// -->
                    <div class="col-md-6">
                        <h3>{{ translate('message.internal_costs') }}</h3>
                        <no-ssr>
                            <chart :data="internalCostsGraphData.byPhase | graphData" theme="print" />
                        </no-ssr>
                    </div>
                    <!-- /// End Project Internal Costs /// -->

                    <!-- /// Project External Costs /// -->
                    <div class="col-md-6">
                        <h3>{{ translate('message.external_costs') }}</h3>
                        <no-ssr>
                            <chart :data="externalCostsGraphData.byPhase | graphData" theme="print" />
                        </no-ssr>
                    </div>
                    <!-- /// End Project External Costs /// -->
                </div>

                <div class="hr small"></div>

                <div class="row margintop40">
                    <div class="half half-left">
                        <h3>{{ translate('message.project_manager_signature') }}</h3>
                    </div>
                    <div class="half half-right">
                        <h3>{{ translate('message.project_sponsor_signature') }}</h3>
                    </div>

                    <div class="clear-fix"></div>
                </div>
            </div>
        </div>
    </page>
</template>

<script>
import CalendarIcon from '~/components/_icons/CalendarIcon.vue';
import Chart from '~/components/Charts/CostsChart.vue';
import DownloadbuttonIcon from '~/components/_icons/DownloadbuttonIcon.vue';
import EyeIcon from '~/components/_icons/EyeIcon.vue';
import InputField from '~/components/_form-components/InputField.vue';
import MemberBadge from '~/components/MemberBadge.vue';
import Vue from 'vue';
import Page from '../../../layouts/Page';

export default {
    validate({params}) {
        return /^\d+$/.test(params.id);
    },
    components: {
        Page,
        CalendarIcon,
        Chart,
        DownloadbuttonIcon,
        EyeIcon,
        InputField,
        MemberBadge,
    },
    computed: {
        downloadPdf() {
            return ''; //Routing.generate('app_contract_pdf', {id: this.contract.id});
        },
        isApproved: {
            get() {
                return (this.contract.approvedAt !== '');
            },
        },

    },
    filters: {
        graphData(value) {
            if (!value) {
                return {};
            }

            let data = {};
            value.forEach((row) => {
                data[row.name] = row.values;
            });

            return data;
        },
    },
    created() {
        Translator.locale = this.locale;
    },
    async asyncData({params, query}) {
        let project = {};
        let contracts = [];
        let team = {};
        let externalCostsGraphData = {};
        let internalCostsGraphData = {};
        let projectSponsors = [];
        let projectManagers = [];
        let locale = query.locale ? query.locale : 'en';

        if (query.host && query.key) {
            // project
            let res = await Vue.doFetch(`http://${query.host}/api/projects/${params.id}`, query.key);
            project = await res.json();

            res = await Vue.doFetch(`http://${query.host}/api/team`, query.key);
            team = await res.json();

            // contract
            res = await Vue.doFetch(`http://${query.host}/api/projects/${params.id}/contracts`, query.key);
            contracts = await res.json();

            // external cost data
            res = await Vue.doFetch(`http://${query.host}/api/projects/${params.id}/external-costs-graph-data`, query.key);
            externalCostsGraphData = await res.json();

            // internal cost data
            res = await Vue.doFetch(`http://${query.host}/api/projects/${params.id}/internal-costs-graph-data`, query.key);
            internalCostsGraphData = await res.json();
        }

        const contract = contracts && contracts.length ? contracts[0] : {};

        return {
            project,
            team,
            contract,
            externalCostsGraphData,
            internalCostsGraphData,
            validationMessages: {},

            objectiveTitle: '-',
            deliverableDescription: '-',
            colOne: false,

            descriptionEditor: null,
            eventEditor: null,
            showSaved: false,
            showFailed: false,
            showSavedComponent: false,
            showFailedComponent: false,
            showSponsorsManagers: true,
            objectiveDescription: null,
            limitationDescription: null,
            frozen: false,
            proposedStartDate: contract.proposedStartDate,
            proposedEndDate: contract.proposedEndDate,
            locale,
        };
    }
};
</script>

<style lang="scss">
    // ../../css/_common
    @import '../../../../frontend/src/css/_common.scss';
    @import '../../../../frontend/src/css/vueditor.css';
</style>

<style scoped lang="scss">
    @import '../../../../frontend/src/css/page-section';

    @media print {
        .resources {
            > div {
                > div {
                    width: 100% !important;
                    transform: translate(-60px);
                }
            }
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

    .page-section {
        .header {
            justify-content: center;
            text-align: center;

            h1 {
                padding-bottom: 1.25em;

                span {
                    font-size: 0.75em;
                    display: block;
                    margin-top: 0.6em;
                }
            }
        }
    }

    .project-info {
        text-align: center;
        margin-bottom: 2em;

        span {
            display: inline-block;
            font-size: 1.5em;
        }
    }

    .hero-text {
        font-size: 3em;
        font-weight: 700;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 1.9em;
    }

    .header .btn-md {
        margin-top: 1.5em;
    }

    .members-big {
        margin: 1.9em 0 0;
    }

    .member-badge {
        &:before {
            display: none;
        }
    }

    .dates {
        .input-holder {
            width: 50%;

            &.left {
                margin-right: 15px;
            }

            &.right {
                margin-left: 15px;
            }
        }
    }

    .page-side {
        width: 50%;

        &.left {
            padding-right: 15px;
        }

        &.right {
            padding-top: 20px;
            padding-left: 15px;
        }
    }

    .btn-rounded {
        width: 220px;

        &.btn-empty {
            width: auto;
            font-size: 9px;
            padding: 0 26px;
        }
    }

    .st0 {
        fill:none;
        stroke:#65BEA3;
        stroke-linecap:round;
        stroke-linejoin:round;
        stroke-miterlimit:10;
    }

    .pdf {
        margin: 1.9em 13px;
    }

    .download-pdf {
        padding-top: 2px;
    }

    .hr {
        margin: 1.9em 0;

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

    .input-holder {
        margin-bottom: 20px;
    }

    .disabledpicker {
        pointer-events: none;
        opacity: .5;
    }
</style>
