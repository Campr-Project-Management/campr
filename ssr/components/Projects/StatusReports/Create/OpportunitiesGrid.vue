<template>
    <div class="opportunities-grid-wrapper">
        <h3 class="marginbottom20 margintop0">{{ translate('message.opportunities') }}</h3>
        <div class="ro-grid-wrapper clearfix">
            <opportunity-matrix
                    :labels="matrixLabels"
                    :show-summary="false"/>

            <h4>{{ translate('message.top_opportunity') }}:</h4>
            <div class="uppercase" v-if="value.top">
                <b>{{ value.top.title }}</b>

                <span class="ro-main-stats">|
                    <template v-if="value.top.priorityName">
                        <b :style="{color: topPriorityColor}">
                            {{ translate('message.priority') }}: {{ translate(`message.${value.top.priorityName}`) }}
                        </b>|
                    </template>
                    {{ translate('message.potential_savings') }}: {{ value.top.potentialCost | money({symbol: currency}) }} |
                    {{ translate('message.potential_time_savings') }}: {{ value.top.potentialTimeHours | humanizeHours({ units: ['d', 'h'], language: locale }) }} |
                    {{ translate('message.strategy') }}: {{ translate(value.top.strategyName) }} |
                    {{ translate('message.status') }}: {{ translate(value.top.statusName) | defaultValue('-') }}
                </span>
                <div class="entry-responsible flex flex-v-center" v-if="value.top.responsibilityId">
                    <user-avatar
                            size="small"
                            :url="projectUserAvatarByUserId(value.top.responsibilityId)"
                            :name="value.top.responsibilityFullName"/>

                    <div>
                        {{ translate('message.responsible') }}:
                        <b>{{ value.top.responsibilityFullName }}</b>
                    </div>
                </div>
            </div>

            <opportunity-summary
                    :potential-cost="value.summary.potentialCost"
                    :potential-time="value.summary.potentialTime"
                    :measures-cost="value.summary.measuresCost"
                    :measures-count="value.summary.measuresCount"
                    :currency="currency"
                    :locale="locale"
                    v-if="value.summary"/>
        </div>
    </div>
</template>

<script>
    import OpportunitySummary from '../../../../../frontend/src/components/Projects/Opportunities/OpportunitySummary';
    import UserAvatar from '../../../_common/UserAvatar';
    import OpportunityMatrix from '../../../../../frontend/src/components/Projects/RiskManagement/OpportunityMatrix';
    import {riskManagement} from '../../../../../frontend/src/util/colors';

    export default {
        name: 'status-report-opportunities-grid',
        props: {
            value: {
                type: Object,
                required: true,
                default: () => ({
                    top: {
                        title: null,
                        priorityName: null,
                        opportunityStrategyName: null,
                        opportunityriskStatusName: null,
                        potentialTimeHours: null,
                        potentialCost: null,
                        responsibilityFullName: null,
                        responsibilityAvatarUrl: null,
                    },
                    items: [],
                    summary: {},
                }),
            },
            currency: {
                type: String,
                required: true,
                default: '',
            },
            locale: {
                type: String,
                required: false,
                default: 'en',
            },
        },
        components: {
            OpportunityMatrix,
            OpportunitySummary,
            UserAvatar,
        },
        methods: {
            projectUserAvatarByUserId(user) {
                return null;
            },
        },
        computed: {
            matrixLabels() {
                if (!this.value.items) {
                    return [];
                }

                let labels = {};
                this.value.items.forEach((value) => {
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
            topPriorityColor() {
                if (!this.value.top) {
                    return;
                }

                return riskManagement.opportunity.getColorByPriority(this.value.top.priority);
            },
        },
    };
</script>

<style lang="scss" scoped>
    @import '../../../../../frontend/src/css/_variables';
    @import '../../../../../frontend/src/css/_mixins';

    @media print {
        .opportunities-grid-wrapper {
            page-break-inside: avoid !important;
        }
    }

    .ro-grid-wrapper {
        .ro-summary {
            font-size: 0.875em;
            margin-top: 5px;
            padding-top: 5px;
            padding-bottom: 0;
            border-top: 1px solid $darkColor;
        }
    }
</style>
