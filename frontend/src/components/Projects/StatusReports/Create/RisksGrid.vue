<template>
    <div>
        <h3 class="marginbottom20 margintop0">{{ translate('message.risks') }}</h3>
        <div class="ro-grid-wrapper clearfix">
            <risk-grid
                    :grid-data="value.grid"
                    :is-risk="true"
                    :clickable="false"></risk-grid>
            <h4>{{ translate('message.top_risk') }}:</h4>
            <div class="ro-main ro-main-risk" v-if="value && value.top_risk">
                <b>{{ value.top_risk.title }}</b>
                <span class="ro-main-stats">|
                    <b
                            v-if="value.top_risk.priorityName"
                            :class="value.top_risk.priorityName">
                        {{ translate('message.priority') }}: {{ translate(`message.${value.top_risk.priorityName}`) }}
                    </b>|
                    {{ translate('message.potential_savings') }}: {{ value.top_risk.potentialCost | money({symbol: currency}) }} |
                    {{ translate('message.potential_time_savings') }}: {{ value.top_risk.potentialDelayHours | humanizeHours({ units: ['d', 'h'] }) }} |
                    {{ translate('message.strategy') }}: {{ translate(value.top_risk.riskStrategyName) }} |
                    {{ translate('message.status') }}: {{ value.top_risk.riskStatusName | defaultValue('-') }}
                </span>
                <div class="entry-responsible flex flex-v-center">
                    <div class="user-avatar">
                        <img :src="value.top_risk.responsibilityAvatar" :alt="value.top_risk.responsibilityFullName"/>
                    </div>
                    <div>
                        {{ translate('message.responsible') }}:
                        <b>{{ value.top_risk.responsibilityFullName }}</b>
                    </div>
                </div>
            </div>
            <risk-summary :summary="value"></risk-summary>
        </div>
    </div>
</template>

<script>
    import RiskGrid from '../../Risks/RiskGrid';
    import RiskSummary from '../../Risks/RiskSummary';

    export default {
        name: 'status-report-risks-grid',
        props: {
            value: {
                type: Object,
                required: true,
                default: () => ({
                    grid: [],
                }),
            },
            currency: {
                type: String,
                required: true,
            },
        },
        components: {
            RiskGrid,
            RiskSummary,
        },
    };
</script>

<style lang="scss" scoped>
    @import '../../../../css/_variables';
    @import '../../../../css/_mixins';

    .ro-grid-wrapper {
        .ro-grid {
            width: 100%;
            float: none;
        }

        .ro-list {
            width: 100%;
            float: none;
        }

        .ro-summary {
            font-size: 0.875em;
            margin-top: 5px;
            padding-top: 5px;
            padding-bottom: 0;
            border-top: 1px solid $darkColor;
        }

        .ro-reprezentative {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid $darkColor;
        }

        .ro-main {
            margin-top: 10px;

            .ro-main-stats {
                text-transform: uppercase;
            }

            &.ro-main-risk {
                .ro-main-stats {
                    .very_high {
                        color: $secondDarkColor;
                    }
                    .high {
                        color: $secondColor;
                    }
                    .medium {
                        color: $warningColor;
                    }
                    .low {
                        color: $dangerColor;
                    }
                    .very_low {
                        color: $dangerDarkColor;
                    }
                }
            }
        }
    }

    .entry-responsible {
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 10px;
        line-height: 1.5em;
        margin: 20px 0;

        b {
            display: block;
            font-size: 12px;
        }
    }
</style>


