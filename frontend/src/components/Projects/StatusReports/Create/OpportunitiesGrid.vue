<template>
    <div>
        <h3 class="marginbottom20 margintop0">{{ translate('message.opportunities') }}</h3>
        <div class="ro-grid-wrapper clearfix">
            <risk-grid
                    :grid-data="value.grid"
                    :is-risk="false"
                    :clickable="false"/>

            <h4>{{ translate('message.top_opportunity') }}:</h4>
            <div class="ro-main ro-main-opportunity" v-if="value.top">
                <b>{{ value.top.title }}</b>

                <span class="ro-main-stats">|
                    <template v-if="value.top.priorityName">
                        <b :class="value.top.priorityName">
                            {{ translate('message.priority') }}: {{ translate(`message.${value.top.priorityName}`) }}
                        </b>|
                    </template>
                    {{ translate('message.potential_savings') }}: {{ value.top.potentialCost | money({symbol: currency}) }} |
                    {{ translate('message.potential_time_savings') }}: {{ value.top.potentialTimeHours | humanizeHours({ units: ['d', 'h'] }) }} |
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
                    v-if="value.summary"/>
        </div>
    </div>
</template>

<script>
    import RiskGrid from '../../Risks/RiskGrid';
    import OpportunitySummary from '../../Opportunities/OpportunitySummary';
    import UserAvatar from '../../../_common/UserAvatar';
    import {mapGetters} from 'vuex';

    export default {
        name: 'status-report-opportunities-grid',
        props: {
            value: {
                type: Object,
                required: true,
                default: () => ({
                    top_risk: {
                        title: null,
                        priorityName: null,
                        opportunityStrategyName: null,
                        opportunityriskStatusName: null,
                        potentialTimeHours: null,
                        potentialCost: null,
                        responsibilityFullName: null,
                        responsibilityAvatar: null,
                    },
                    grid: [],
                }),
            },
            currency: {
                type: String,
                required: true,
                default: '',
            },
        },
        components: {
            RiskGrid,
            OpportunitySummary,
            UserAvatar,
        },
        computed: {
            ...mapGetters([
                'projectUserAvatarByUserId',
            ]),
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

            &.ro-main-opportunity {
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
