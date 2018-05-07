<template>
    <div>
        <h3 class="marginbottom20 margintop0">{{ translate('message.risks') }}</h3>
        <div class="ro-grid-wrapper clearfix">
            <risk-grid
                    :grid-data="value.grid"
                    :is-risk="true"
                    :clickable="false"/>

            <h4>{{ translate('message.top_risk') }}:</h4>
            <div class="ro-main ro-main-risk" v-if="value && value.top">
                <b>{{ value.top.title }}</b>
                <span class="ro-main-stats">|
                    <template v-if="value.top.priorityName">
                        <b :class="value.top.priorityName">
                            {{ translate('message.priority') }}: {{ translate(`message.${value.top.priorityName}`) }}
                        </b>|
                    </template>
                    {{ translate('message.potential_savings') }}: {{ value.top.potentialCost | money({symbol: currency}) }} |
                    {{ translate('message.potential_time_savings') }}: {{ value.top.potentialDelayHours | humanizeHours({ units: ['d', 'h'] }) }} |
                    {{ translate('message.strategy') }}: {{ translate(value.top.strategyName) }} |
                    {{ translate('message.status') }}: {{ translate(value.top.statusName) | defaultValue('-') }}
                </span>
                <div class="entry-responsible flex flex-v-center" v-if="value.top.responsibilityFullName">
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
                    v-if="value.summary"/>
        </div>
    </div>
</template>

<script>
    import RiskGrid from '../../Risks/RiskGrid';
    import RiskSummary from '../../Risks/RiskSummary';
    import UserAvatar from '../../../_common/UserAvatar';
    import {mapGetters} from 'vuex';

    export default {
        name: 'status-report-risks-grid',
        props: {
            value: {
                type: Object,
                required: true,
                default: () => ({
                    top_risk: {
                        title: null,
                        priorityName: null,
                        riskStrategyName: null,
                        riskStatusName: null,
                        potentialDelayHours: null,
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
            },
        },
        components: {
            RiskGrid,
            RiskSummary,
            UserAvatar,
        },
        computed: {
            ...mapGetters([
                'projectUserByUserId',
            ]),
        },
        methods: {
            getAvatarUrl(id) {
                let projectUser = this.projectUserByUserId(id);
                if (!projectUser) {
                    return null;
                }

                return projectUser.userAvatar;
            },
        },
    };
</script>

<style lang="scss" scoped>
    @import '../../../../css/_variables';

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


