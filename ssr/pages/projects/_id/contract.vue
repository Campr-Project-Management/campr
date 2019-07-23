<template>
    <page :team="team" :project="project" :title="contract.projectName" :subtitle="translate('message.project_contract')">
        <div class="col-xs-12">
            <div class="col-xs-6">
                <table class="gray-table">
                    <tbody>
                        <tr>
                            <th>{{ translate('label.project_sponsors') }}</th>
                            <td>
                                <span v-for="(sponsor, index) in project.projectSponsors"
                                    :key="'projectSponsors'+index">
                                    <span v-if="index > 0">, </span>
                                    {{ sponsor.userFullName }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ translate('label.project_managers') }}</th>
                            <td>
                                <span v-for="(manager, index) in project.projectManagers"
                                    :key="'projectManagers'+index">
                                    <span v-if="index > 0">, </span>
                                    {{ manager.userFullName }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ translate('message.project_number') }}</th>
                            <td>{{ project.number || '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-6">
                <table class="gray-table">
                    <tbody>
                        <tr>
                            <th>{{ translate('message.scope') }}</th>
                            <td>{{ translate.projectScopeName || '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('message.category') }}</th>
                            <td>{{ translate.projectCategoryName || '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ translate('message.approved_on') }}</th>
                            <td>{{ contract.approvedAt || '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <h3>{{ translate('message.project_description') }}</h3>
            <div class="col-xs-12" v-html="contract.description"></div>
        </div>

        <div class="row">
            <h3>{{ translate('label.project_start_event') }}</h3>
            <div class="col-xs-12">
                <div class="col-xs-9" v-html="contract.projectStartEvent"></div>
                <div class="col-xs-3">
                    <table class="content-table">
                        <tbody>
                            <tr>
                                <th>{{ translate('label.forecast_start_date') }}</th>
                                <th>{{ translate('label.forecast_end_date') }}</th>
                            </tr>
                            <tr>
                                <td>{{ contract.forecastStartDate || '-' }}</td>
                                <td>{{ contract.forecastEndDate || '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <h3>{{ translate('message.project_objectives') }}</h3>
            <div class="col-xs-12">
                <table class="content-table">
                    <tbody>
                        <tr>
                            <th>{{ translate('label.description') }}</th>
                        </tr>
                        <tr
                            v-if="project.projectObjectives && project.projectObjectives.length > 0"
                            v-for="(projectObjective, index) in project.projectObjectives"
                            :key="'projectObjective'+index"
                        >
                            <td>{{ index + 1 }}. {{ projectObjective.title }}</td>
                        </tr>
                        <tr v-else>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <h3>{{ translate('message.project_limitations') }}</h3>
            <div class="col-xs-12">
                <table class="content-table">
                    <tbody>
                        <tr>
                            <th>{{ translate('label.description') }}</th>
                        </tr>
                        <tr
                            v-if="project.projectLimitations && project.projectLimitations.length > 0"
                            v-for="(projectLimitation, index) in project.projectLimitations"
                            :key="'projectLimitation'+index"
                        >
                            <td>{{ index + 1 }}. {{ projectLimitation.description }}</td>
                        </tr>
                        <tr v-else>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <h3>{{ translate('message.project_deliverables') }}</h3>
            <div class="col-xs-12">
                <table class="content-table">
                    <tbody>
                        <tr>
                            <th>{{ translate('label.description') }}</th>
                        </tr>
                        <tr
                            v-if="project.projectDeliverables !== undefined && project.projectDeliverables.length > 0"
                            v-for="(projectDeliverable, index) in project.projectDeliverables"
                            :key="'projectDeliverable'+index"
                        >
                            <td>{{ index + 1 }}. {{ projectDeliverable.description }}</td>
                        </tr>
                        <tr v-else>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <template v-if="isInternalCostsModuleActive && internalCostsGraphData && isExternalCostsModuleActive && externalCostsGraphData">
            <div class="row" style="padding-left: 0; padding-right: 0; height: 200px; clear: both">
                <div class="col-xs-6" style="padding-left: 0;">
                    <h3>{{ translate('message.internal_costs') }}</h3>
                    <div class="resources-half">
                        <no-ssr>
                            <chart :data="internalCostsGraphData.byPhase | graphData" theme="print" />
                        </no-ssr>
                    </div>
                </div>
                <div class="col-xs-6" style="padding-left: 0;">
                    <h3>{{ translate('message.external_costs') }}</h3>
                    <div class="resources-half">
                        <no-ssr>
                            <chart :data="externalCostsGraphData.byPhase | graphData" theme="print" />
                        </no-ssr>
                    </div>
                </div>
            </div>
        </template>

        <template v-else>
            <div class="row" v-if="isInternalCostsModuleActive && internalCostsGraphData">
                <h3>{{ translate('message.internal_costs') }}</h3>
                <div class="resources">
                    <no-ssr>
                        <chart :data="internalCostsGraphData.byPhase | graphData" theme="print" />
                    </no-ssr>
                </div>
            </div>

            <div class="row" v-if="isExternalCostsModuleActive && externalCostsGraphData">
                <h3>{{ translate('message.external_costs') }}</h3>
                <div class="resources">
                    <no-ssr>
                        <chart :data="externalCostsGraphData.byPhase | graphData" theme="print" />
                    </no-ssr>
                </div>
            </div>
        </template>

        <template v-if="isRiskAndOpportunitiesModuleActive">
            <div class="row ro-columns">
                <div class="col-md-6 col-xs-6 dark-border-right">
                    <no-ssr>
                        <opportunities-grid :value="opportunitiesGrid" :currency="currency"
                            :locale="forcedLocale"/>
                    </no-ssr>
                </div>

                <div class="col-md-6 col-xs-6">
                    <no-ssr>
                        <risks-grid :value="risksGrid" :currency="currency"
                            :locale="forcedLocale"/>
                    </no-ssr>
                </div>
            </div>
        </template>

        <div class="row">
            <h3>{{ translate('message.team_members') }}</h3>
            <table class="content-table">
                <tbody>
                    <tr>
                        <th>{{ translate('label.name') }}</th>
                        <th>{{ translate('label.project_role') }}</th>
                        <th>{{ translate('label.project_department') }}</th>
                    </tr>
                    <tr
                        v-for="(sponsor, index) in project.projectSponsors"
                        :key="'clientProjectSponsors'+index"
                    >
                        <td>{{ sponsor.userFullName }}</td>
                        <td>{{ translate('message.project_sponsor') }}</td>
                        <td></td>
                    </tr>
                    <tr
                        v-for="(manager, index) in project.projectManagers"
                        :key="'clientProjectManagers'+index"
                    >
                        <td>{{ manager.userFullName }}</td>
                        <td>{{ translate('message.project_manager') }}</td>
                        <td></td>
                    </tr>
                    <tr
                        v-if="project.projectUsers !== undefined && project.projectUsers.length > 0"
                        v-for="(projectUser, index) in project.projectUsers"
                        :key="'projectUser'+index"
                    >
                        <td>{{ projectUser.userFullName }}</td>
                        <td>
                            <span
                                v-for="(projectRoleName, index) in projectUser.projectRoleNames"
                                :v-key="'projectRoleName' + index"
                            >
                                <span v-if="index > 0">, </span>
                                {{ translate(projectRoleName) }}
                            </span>
                        </td>
                        <td>
                            <span
                                v-for="(projectDepartmentName, index) in projectUser.projectDepartmentNames"
                                :v-key="'projectDepartmentName' + index"
                            >
                                <span v-if="index > 0">, </span>
                                {{ projectDepartmentName }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <template v-if="isPhasesAndMilestoneModuleActive && (phases || milestones)">
            <div class="row">
                <h3>{{ translate('message.phases_and_milestones') }}</h3>
                <div class="flex flex-center" style="text-align: center">
                    <traffic-light :value="projectTrafficLight"/>
                </div>

                <br/>

                <no-ssr>
                    <status-report-timeline
                        style="width: 777px"
                        :phases="phases"
                        :milestones="milestones"
                        :locale="forcedLocale"/>
                </no-ssr>
            </div>

            <hr class="double">
        </template>

        <signature />
    </page>
</template>

<script>
import CalendarIcon from '~/components/_icons/CalendarIcon.vue';
import Chart from '~/components/Charts/CostsChart.vue';
import DownloadbuttonIcon from '~/components/_icons/DownloadbuttonIcon.vue';
import EyeIcon from '~/components/_icons/EyeIcon.vue';
import InputField from '~/components/_form-components/InputField.vue';
import Signature from '~/components/_common/Signature.vue';
import MemberBadge from '~/components/MemberBadge.vue';
import TrafficLight from '~/components/_common/TrafficLight.vue';
import StatusReportTimeline from '~/components/Projects/StatusReports/Create/Timeline';
import RisksGrid from '~/components/Projects/StatusReports/Create/RisksGrid';
import OpportunitiesGrid from '~/components/Projects/StatusReports/Create/OpportunitiesGrid';
import Vue from 'vue';
import Page from '../../../layouts/Page';
import module from '../../../components/mixins/module';

export default {
    validate({params}) {
        return /^\d+$/.test(params.id);
    },
    mixins: [module],
    components: {
        Page,
        CalendarIcon,
        Chart,
        DownloadbuttonIcon,
        EyeIcon,
        InputField,
        MemberBadge,
        TrafficLight,
        StatusReportTimeline,
        RisksGrid,
        OpportunitiesGrid,
        Signature,
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
        forcedLocale() {
            return this.locale ? this.locale : '';
        },
        projectTrafficLight() {
            return this.project ? this.project.trafficLight : 1;
        },
        opportunitiesGrid() {
            return {
                top: this.contract.opportunities.topItem,
                items: this.contract.opportunities.items,
                summary: {
                    potentialCost: this.contract.opportunities.total.potentialCost,
                    potentialTime: this.contract.opportunities.total.potentialTime,
                    measuresCount: this.contract.opportunities.total.measuresCount,
                    measuresCost: this.contract.opportunities.total.measuresCost,
                },
            };
        },
        risksGrid() {
            return {
                top: this.contract.risks.topItem,
                items: this.contract.risks.items,
                summary: {
                    potentialCost: this.contract.risks.total.potentialCost,
                    potentialDelay: this.contract.risks.total.potentialDelay,
                    measuresCount: this.contract.risks.total.measuresCount,
                    measuresCost: this.contract.risks.total.measuresCost,
                },
            };
        },
        currency() {
            return this.project && this.project.currency
                ? this.project.currency.symbol
                : '';
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
        let phases = [];
        let milestones = [];
        let risksAndOpportunities = [];

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

            // internal cost data
            res = await Vue.doFetch(`http://${query.host}/api/projects/${params.id}/phases`, query.key);
            phases = await res.json();

            // internal cost data
            res = await Vue.doFetch(`http://${query.host}/api/projects/${params.id}/milestones`, query.key);
            milestones = await res.json();
        }

        const contract = contracts && contracts.length ? contracts[0] : {};

        return {
            activeModules: project.projectModules,
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
            phases: phases && phases.items ? phases.items : [],
            milestones: milestones && milestones.items ? milestones.items : [],
        };
    }
};
</script>
