<template>
    <div class="risk-grid-wrapper">
        <h3 class="marginbottom20 margintop0">{{ translate('message.risks') }}</h3>
        <div class="ro-grid-wrapper clearfix">
            <risk-matrix
                    :labels="matrixLabels"
                    :show-summary="false"/>

            <h4>{{ translate('message.top_risk') }}:</h4>
            <div class="uppercase" v-if="value && value.top">
                <b>{{ value.top.title }}</b>
                <span class="ro-main-stats">|
                    <template v-if="value.top.priorityName">
                        <b :style="{color: topPriorityColor}">
                            {{ translate('message.priority') }}: {{ translate(`message.${value.top.priorityName}`) }}
                        </b>|
                    </template>
                    {{ translate('message.potential_cost') }}: {{ value.top.potentialCost | money({symbol: currency}) }} |
                    {{ translate('message.potential_time_delay') }}: {{ value.top.potentialDelayHours | humanizeHours({ units: ['d', 'h'], language: locale }) }} |
                    {{ translate('message.strategy') }}: {{ translate(value.top.strategyName) }} |
                    {{ translate('message.status') }}: {{ translate(value.top.statusName) | defaultValue('-') }}
                </span>
                <div class="entry-responsible flex flex-v-center" v-if="value.top.responsibilityId">
                    <user-avatar
                            size="small"
                            :url="getAvatarUrl(value.top.responsibilityId)"
                            :name="value.top.responsibilityFullName"/>
                    <div>
                        {{ translate('message.responsible') }}:
                        <b>{{ value.top.responsibilityFullName }}</b>
                    </div>
                </div>
            </div>

            <risk-summary
                    :potential-cost="value.summary.potentialCost"
                    :potential-delay="value.summary.potentialDelay"
                    :measures-cost="value.summary.measuresCost"
                    :measures-count="value.summary.measuresCount"
                    :currency="currency"
                    :locale="locale"
                    v-if="value.summary"/>
        </div>
    </div>
</template>

<script>
    import RiskSummary from '../../../../../frontend/src/components/Projects/Risks/RiskSummary';
    import UserAvatar from '../../../_common/UserAvatar';
    import RiskMatrix from '../../../../../frontend/src/components/Projects/RiskManagement/RiskMatrix';
    import {riskManagement} from '../../../../../frontend/src/util/colors';

    export default {
        name: 'status-report-risks-grid',
        props: {
            value: {
                type: Object,
                required: true,
                default: () => ({
                    top: {
                        title: null,
                        priorityName: null,
                        riskStrategyName: null,
                        riskStatusName: null,
                        potentialDelayHours: null,
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
            },
            locale: {
                type: String,
                required: false,
                default: 'en',
            },
        },
        components: {
            RiskMatrix,
            RiskSummary,
            UserAvatar,
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

                return riskManagement.risk.getColorByPriority(this.value.top.priority);
            },
        },
        methods: {
            projectUserByUserId(user) {
                return null;
            },
            getAvatarUrl(id) {
                let projectUser = this.projectUserByUserId(id);
                if (!projectUser) {
                    return null;
                }

                return projectUser.userAvatarUrl;
            },
        },
    };
</script>

<style lang="scss" scoped>
    @import '../../../../../frontend/src/css/_variables';

    @media print {
        .risk-grid-wrapper {
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


