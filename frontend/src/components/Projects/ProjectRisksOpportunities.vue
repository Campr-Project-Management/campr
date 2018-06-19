<template>
    <div class="page-section">
        <div class="row">
            <div class="col-lg-6 dark-border-right">
                <!-- /// Project Opportunities Header /// -->
                <div class="header flex flex-space-between">
                    <div class="flex">
                        <h1>{{ translate('message.project_opportunities') }}</h1>
                    </div>
                    <div class="flex flex-v-center">
                        <router-link :to="{name: 'project-opportunities-create-opportunity'}" class="btn-rounded btn-auto second-bg">{{ translate('message.new_opportunity') }}</router-link>
                    </div>
                </div>
                <!-- /// End Project Opportunities Header /// -->

                <!-- /// Project Opportunities /// -->
                <div class="ro-grid-wrapper clearfix">
                    <div class="row">
                        <div class="col-md-8">
                            <opportunity-matrix
                                    :clickable="true"
                                    :show-summary="false"
                                    @priorityClick="onOpportunityPriorityClick"
                                    :labels="opportunitiesMatrixLabels" />
                        </div>
                        <div class="col-md-4">
                            <opportunity-list :list="selectedOpportunities"/>
                        </div>
                    </div>
                </div>

                <opportunity-summary
                        :potential-cost="opportunitiesSummary.potentialCost"
                        :potential-time="opportunitiesSummary.potentialTime"
                        :measures-cost="opportunitiesSummary.measuresCost"
                        :measures-count="opportunitiesSummary.measuresCount"
                        :currency="currency"/>
            </div>
            <div class="col-lg-6">
                <!-- /// Project Risks Header /// -->
                <div class="header flex flex-space-between paddingright15">
                    <div class="flex">
                        <h1>{{ translate('message.project_risks') }}</h1>
                    </div>
                    <div class="flex flex-v-center">
                        <router-link :to="{name: 'project-risks-create-risk'}" class="btn-rounded btn-auto second-bg">{{ translate('message.new_risk') }}</router-link>
                    </div>
                </div>
                <!-- /// End Project Risks Header /// -->

                <div class="ro-grid-wrapper clearfix">
                    <div class="row">
                        <div class="col-md-8">
                            <risk-matrix
                                    :clickable="true"
                                    :show-summary="false"
                                    :labels="risksMatrixLabels"
                                    @priorityClick="onRiskPriorityClick"/>
                        </div>
                        <div class="col-md-4">
                            <risk-list :list="selectedRisks"/>
                        </div>
                    </div>
                </div>

                <risk-summary
                        :potential-cost="risksSummary.potentialCost"
                        :potential-delay="risksSummary.potentialDelay"
                        :measures-count="risksSummary.measuresCount"
                        :measures-cost="risksSummary.measuresCost"
                        :currency="currency"/>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex';
    import RiskList from './Risks/RiskList';
    import OpportunityList from './Opportunities/OpportunityList';
    import RiskSummary from './Risks/RiskSummary';
    import OpportunitySummary from './Opportunities/OpportunitySummary';
    import OpportunityMatrix from './RiskManagement/OpportunityMatrix';
    import RiskMatrix from './RiskManagement/RiskMatrix';
    import _ from 'lodash';

    export default {
        components: {
            OpportunityMatrix,
            RiskMatrix,
            RiskList,
            RiskSummary,
            OpportunitySummary,
            OpportunityList,
        },
        computed: {
            ...mapGetters([
                'risks',
                'opportunities',
                'risksOpportunitiesStats',
                'projectCurrencySymbol',
            ]),
            selectedOpportunities() {
                if (!this.selectedOpportunityPriority) {
                    return [];
                }

                return this.getFilteredByImpactAndProbabilityIndex(this.opportunities,
                    this.selectedOpportunityPriority.impactIndex, this.selectedOpportunityPriority.probabilityIndex);
            },
            selectedRisks() {
                if (!this.selectedRiskPriority) {
                    return [];
                }

                return this.getFilteredByImpactAndProbabilityIndex(this.risks,
                    this.selectedRiskPriority.impactIndex, this.selectedRiskPriority.probabilityIndex);
            },
            risksStats() {
                return this.risksOpportunitiesStats.risks;
            },
            opportunitiesStats() {
                return this.risksOpportunitiesStats.opportunities;
            },
            opportunitiesMatrixLabels() {
                return this.getMatrixLabels(this.opportunities);
            },
            risksMatrixLabels() {
                return this.getMatrixLabels(this.risks);
            },
            opportunitiesSummary() {
                if (!this.opportunitiesStats) {
                    return {};
                }

                return {
                    potentialCost: this.opportunitiesStats.opportunity_data.costSavings,
                    potentialTime: this.opportunitiesStats.opportunity_data.timeSaving,
                    measuresCount: this.opportunitiesStats.measure_data.measuresNumber,
                    measuresCost: this.opportunitiesStats.measure_data.totalCost,
                };
            },
            risksSummary() {
                if (!this.risksStats) {
                    return {};
                }

                return {
                    potentialCost: this.risksStats.risk_data.costs,
                    potentialDelay: this.risksStats.risk_data.delay,
                    measuresCount: this.risksStats.measure_data.measuresNumber,
                    measuresCost: this.risksStats.measure_data.totalCost,
                };
            },
            currency() {
                if (!this.projectCurrencySymbol) {
                    return '';
                }

                return this.projectCurrencySymbol;
            },
        },
        methods: {
            ...mapActions([
                'getProjectOpportunities',
                'getProjectRisks',
                'getProjectRiskAndOpportunitiesStats',
            ]),
            getMatrixLabels(values) {
                if (!values) {
                    return [];
                }

                let labels = {};
                values.forEach((value) => {
                    let key = `${value.impactIndex}-${value.probabilityIndex}`;
                    if (!labels[key]) {
                        labels[key] = {
                            position: [value.impactIndex, value.probabilityIndex],
                            text: 0,
                        };
                    }

                    labels[key].text++;
                });

                return Object.values(labels);
            },
            onRiskPriorityClick(data) {
                this.selectedRiskPriority = data;
            },
            onOpportunityPriorityClick(data) {
                this.selectedOpportunityPriority = data;
            },
            getFilteredByImpactAndProbabilityIndex(values, impactIndex, probabilityIndex) {
                return _.filter(values, (value) => {
                    return value.impactIndex === impactIndex && value.probabilityIndex === probabilityIndex;
                });
            },
        },
        created() {
            this.getProjectOpportunities({projectId: this.$route.params.id});
            this.getProjectRisks({projectId: this.$route.params.id});
            this.getProjectRiskAndOpportunitiesStats(this.$route.params.id);
        },
        data() {
            return {
                selectedRiskPriority: null,
                selectedOpportunityPriority: null,
            };
        },
    };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/page-section';
    @import '../../css/_mixins';
    @import '../../css/_variables';

    .dark-border-right {
        border-right: 1px solid $darkerColor;
    }

    .ro-summary {
        margin-top: 60px;
        padding-bottom: 30px;
        font-size: 1.333em;
        text-transform: uppercase;
        letter-spacing: 0.1em;

        p {
            line-height: 1.5em;
            color: $lightColor;

            b {
                color: $lighterColor;
            }
        }

        .text-left {
            p {
                padding-left: 15px;
            }
        }
    }
</style>
