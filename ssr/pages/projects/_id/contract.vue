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
                                <th>{{ translate('label.proposed_start_date') }}</th>
                                <th>{{ translate('label.proposed_end_date') }}</th>
                            </tr>
                            <tr>
                                <td>{{ contract.proposedStartDate || '-' }}</td>
                                <td>{{ contract.proposedEndDate || '-' }}</td>
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
            <div class="row" style="padding-left: 0; padding-right: 0; height: 200px; clear: both; overflow: hidden;">
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
                    <tr v-for="(teamMember, index) in teamMembers" :key="`team-member-${index}`">
                        <td>{{ teamMember[0] }}</td>
                        <td>
                            <span v-for="(role, roleIndex) in teamMember[1]" :key="`team-member-role-${index}-${roleIndex}`">
                                <span v-if="roleIndex > 0">, </span>
                                {{ translate(role) }}
                            </span>
                        </td>
                        <td>
                            <span v-for="(department, departmentIndex) in teamMember[2]" :key="`team-member-department-${index}-${departmentIndex}`">
                                <span v-if="departmentIndex > 0">, </span>
                                {{ translate(department) }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <template v-if="isPhasesAndMilestoneModuleActive && (phases || milestones)">
            <div class="row">
                <h3>{{ translate('message.phases_and_milestones') }}</h3>

                <no-ssr>
                    <status-report-timeline
                        v-if="phases.length > 0 || milestones.length > 0"
                        style="width: 777px"
                        :phases="phases"
                        :milestones="milestones"
                        :locale="forcedLocale"/>
                    <div class="no-results" v-else>{{ translate('message.not_enough_data') }}</div>
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
        teamMembers() {
            return []
                .concat(
                    (this.project && this.project.projectSponsors && this.project.projectSponsors.map && this.project.projectSponsors.map(ps => [
                        ps.userFullName,
                        ['roles.project_sponsor'],
                        [],
                        ps.userEmail
                    ])) || [],
                    (this.project && this.project.projectManagers && this.project.projectManagers.map && this.project.projectManagers.map(pm => [
                        pm.userFullName,
                        ['roles.project_manager'],
                        [],
                        pm.userEmail
                    ])) || [],
                    (this.project && this.project.projectUsers && this.project.projectUsers.map && this.project.projectUsers.map(pu => [
                        pu.userFullName,
                        pu.projectRoleNames,
                        pu.projectDepartmentNames,
                        pu.userEmail
                    ])) || []
                )
                .reduce((accumulator, currentValue) => {
                    if (currentValue[0].replace(' ', '') === currentValue[3]) {
                        return accumulator;
                    }

                    const emails = accumulator.map(i => i[3]);
                    const previousIndex = emails.indexOf(currentValue[3]);

                    if (previousIndex === -1) {
                        return accumulator.concat([currentValue]);
                    }

                    accumulator[previousIndex][1] = accumulator[previousIndex][1]
                        .concat(currentValue[1])
                        .reduce((ac, cv) => {
                            if (ac.indexOf(cv) === -1) {
                                return ac.concat([cv]);
                            }

                            return ac;
                        }, [])
                    ;
                    accumulator[previousIndex][2] = accumulator[previousIndex][2]
                        .concat(currentValue[2])
                        .reduce((ac, cv) => {
                            if (ac.indexOf(cv) === -1) {
                                return ac.concat([cv]);
                            }

                            return ac;
                        }, [])
                    ;

                    return accumulator;
                }, [])
            ;
        }
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
            milestones: milestones && milestones.items ? milestones.items.filter(i => i.isKeyMilestone) : [],
        };
    }
};
</script>

<style lang="scss" scoped>
@import '/../../../../frontend/src/css/_variables';

.no-results {
    text-align: center;
    color: $middleColor;
    min-height: 80%;
    line-height: 20em;
}
</style>
