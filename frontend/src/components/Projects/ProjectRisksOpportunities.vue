<template>
    <div class="page-section">
        <div class="row">
            <div class="col-lg-6 dark-border-right">
                <!-- /// Project Opportunities Header /// -->
                <div class="header flex flex-space-between">
                    <div class="flex">
                        <h1>{{ translateText('message.project_opportunities') }}</h1>
                    </div>
                    <div class="flex flex-v-center">
                        <router-link :to="{name: 'project-opportunities-create-opportunity'}" class="btn-rounded btn-auto second-bg">{{ translateText('message.new_opportunity') }}</router-link>
                    </div>
                </div>
                <!-- /// End Project Opportunities Header /// -->

                <!-- /// Project Opportunities /// -->
                <div class="ro-grid-wrapper clearfix">
                    <!-- /// Project Opportunities Grid /// -->
                    <risk-grid :gridData="gridData"></risk-grid>
                    <!-- /// End Project Opportunities Grid /// -->

                    <!-- /// Project Opportunities List /// -->
                    <opportunity-list :listData="opportunities"></opportunity-list>
                    <!-- /// End Project Opportunities List /// -->
                </div>
                <!-- /// End Project Opportunities /// -->

                <!-- /// Project Opportunities Summary /// -->
                <risk-summary :summaryData="summaryData"></risk-summary>
                <!-- /// End Project Opportunities Summary /// -->
            </div>
            <div class="col-lg-6">
                <!-- /// Project Risks Header /// -->
                <div class="header flex flex-space-between paddingright15">
                    <div class="flex">
                        <h1>{{ translateText('message.project_risks') }}</h1>
                    </div>
                    <div class="flex flex-v-center">
                        <router-link :to="{name: 'project-risks-create-risk'}" class="btn-rounded btn-auto second-bg">{{ translateText('message.new_risk') }}</router-link>
                    </div>
                </div>
                <!-- /// End Project Risks Header /// -->

                <!-- /// Project Risks /// -->
                <div class="ro-grid-wrapper clearfix">
                    <!-- /// Project Risks Grid /// -->
                    <risk-grid :gridData="gridData"></risk-grid>
                    <!-- /// End Project Risks Grid /// -->

                    <!-- /// Project Risks List /// -->
                    <risk-list :listData="risks"></risk-list>
                    <!-- /// End Project Risks List /// -->
                </div>
                <!-- /// End Project Risks /// -->

                <!-- /// Project Risks Summary /// -->
                <risk-summary :summaryData="summaryData"></risk-summary>
                <!-- /// End Project Risks Summary /// -->
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import RiskGrid from './Risks/RiskGrid';
import RiskList from './Risks/RiskList';
import OpportunityList from './Opportunities/OpportunityList';
import RiskSummary from './Risks/RiskSummary';

export default {
    components: {
        RiskGrid,
        RiskList,
        RiskSummary,
        OpportunityList,
    },
    computed: {
        ...mapGetters({
            opportunities: 'opportunities',
            risks: 'risks',
        }),
    },
    methods: {
        ...mapActions(['getProjectOpportunities', 'getProjectRisks']),
        translateText: function(text) {
            return this.translate(text);
        },
    },
    created() {
        this.getProjectOpportunities({projectId: this.$route.params.id});
        this.getProjectRisks({projectId: this.$route.params.id});
    },
    data: function() {
        return {
            gridData: [[{number: 1, type: 'medium'}, {number: 2, type: 'high'}, {number: null, type: 'low'}, {number: null, type: 'very-low'}],
            [{number: 1, type: 'medium'}, {number: 2, type: 'high'}, {number: null, type: 'low'}, {number: null, type: 'very-low'}],
            [{number: 1, type: 'medium'}, {number: 2, type: 'high'}, {number: null, type: 'low'}, {number: null, type: 'very-low'}],
            [{number: 1, type: 'medium'}, {number: 2, type: 'high'}, {number: null, type: 'low'}, {number: null, type: 'very-low'}]],
            summaryData: {
                total_potential_savings: '$323.350',
                total_potential_time_savings: '74 days',
                total_number_of_measures: 12,
                total_cost_of_measures: '$85.550',
            },
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

    .ro-grid-header {
        text-transform: uppercase;
        letter-spacing: 0.1em;
        text-align: center;
        position: absolute;
        width: 100%;
        left: 0;

        .big-header {
            color: $lightColor;
            font-size: 0.875em;
        }

        .small-header {
            width: 25%;
            font-size: 0.75em;
            float: left;
            white-space: nowrap;
        }

        &.vertical-axis-header {
            @include rotate(-90);
            @include transform-origin(left top);
            top: calc(100% - 67px);
            width: calc(100% - 97px);

            .big-header {
                padding-bottom: 10px;
                margin-bottom: 10px;
                border-bottom: 1px solid $darkerColor;
            }
        }

        &.horizontal-axis-header {
            bottom: 0;
            padding: 0 30px 0 67px;

            .big-header {
                padding-top: 10px;
                margin-top: 10px;
                border-top: 1px solid $darkerColor;
            }
        }
    }

    .ro-grid {
        width: 65%;
        padding: 0 30px 67px 67px;
        float: left;
        position: relative;
    }

    .ro-list {
        width: 35%;
        float: left;
    }

    .ro-grid-item {
        width: 24%;
        height: 0;
        padding-bottom: 24%;
        @include border-radius(50%);
        margin: 0.5%;
        float: left;
        font-size: 3em;
        font-weight: bold;
        color: $mainColor;
        text-align: center;
        white-space: nowrap;
        position: relative;
        cursor: pointer;
        @include transition(background-color, 0.2s, ease);

        span {
            display: inline-block;
            vertical-align: middle;
            width: 100%;
            height: 30px;
            line-height: 35px;
            position: absolute;
            top: 50%;
            left: 0;
            margin-top: -15px;
        }

        &.medium {
            background-color: $warningColor;
        }

        &.high {
            background-color: $dangerColor;
        }

        &.very-high {
            background-color: $dangerDarkColor;
        }

        &.low {
            background-color: $secondColor;
        }

        &.very-low {
            background-color: $secondDarkColor;
        }

        &.active {
            background-color: $lightColor;
        }

        &:hover{
            background-color: $lightColor;
        }
    }

    .ro-list-item {
        text-transform: uppercase;
        letter-spacing: 0.1em;
        position: relative;
        padding-bottom: 20px;
        margin-bottom: 20px;
        border-bottom: 1px solid $darkerColor;

        .avatar {
            height: 20px;
            width: 20px;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            position: absolute;
            right: 0;
            top: 0;
            @include border-radius(50%);
        }

        a {
            font-size: 1.166em;
            color: $secondColor;
            font-weight: 600;
            padding-right: 25px;
            display: block;
        }

        p {
            font-size: 0.833em;
            color: $lightColor;

            b {
                color: $lighterColor;
            }
        }

        &:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }
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
