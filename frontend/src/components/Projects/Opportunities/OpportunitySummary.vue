<template>
    <div class="ro-summary">
        <div class="text-center" v-if="summaryData">
            <p class="clearfix">
                <span class="text-right">{{ translateText('message.total_potential_savings') }}:</span>
                <span class="text-left">
                    <span v-if="summaryData.opportunity_data.costSavings && summaryData.opportunity_data.costSavings.length > 0">
                        <b v-for="(item, index) in summaryData.opportunity_data.costSavings">{{ translateText(item.currency) }} {{ item.totalCost }}
                            <span v-if="index < summaryData.opportunity_data.costSavings.length - 1">, </span>
                        </b>
                    </span>
                    <span v-else>-</span>
                </span>
            </p>
            <p class="clearfix">                
                <span class="text-right">{{ translateText('message.total_potential_time_savings') }}:</span>
                <span class="text-left">
                    <span v-if="summaryData.opportunity_data.timeSavings">
                        <b>{{ summaryData.opportunity_data.timeSavings.days }} {{ translateText('label.days') }}, {{ summaryData.opportunity_data.timeSavings.hours }} {{ translateText('label.hours') }}</b>
                    </span>
                    <span v-else>-</span>
                </span>
            </p>
            <p class="clearfix">
                <span class="text-right">{{ translateText('message.total_number_of_measures') }}:</span>
                <span class="text-left">
                    <span v-if="summaryData.measure_data.measuresNumber">
                        <b>{{ summaryData.measure_data.measuresNumber }}</b>
                    </span>
                    <span v-else>-</span>
                </span>
            </p>
            <p class="clearfix">
                <span class="text-right">{{ translateText('message.total_cost_of_measures') }}:</span>
                <span class="text-left">
                    <span v-if="summaryData.measure_data.totalCost">
                        <b>${{ summaryData.measure_data.totalCost }}</b>
                    </span>
                    <span v-else>-</span>
                </span>
            </p>
        </div>
    </div>
</template>

<script>
export default {
    props: ['summaryData'],
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
    },
};
</script>

<style scoped lang="scss">
    @import '../../../css/page-section';
    @import '../../../css/_mixins';
    @import '../../../css/_variables';

    .ro-summary {
        margin-top: 60px;
        padding-bottom: 30px;
        font-size: 1.333em;
        text-transform: uppercase;
        letter-spacing: 0.1em;

        p {
            line-height: 1.5em;
            color: $lightColor;
            display: block;
            border-bottom: 1px solid $darkColor;
            padding-bottom: 5px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;

            .text-right {
                width: 50%;
                float: left;
            }

            .text-left {
                width: 48%;
                float: left;
                padding-left: 2%;
            }

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
