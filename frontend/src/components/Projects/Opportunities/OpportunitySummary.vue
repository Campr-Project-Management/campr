<template>
    <div class="ro-summary">
        <div class="text-center flex flex-center">
            <div class="text-right">
                <p>{{ translateText('message.total_potential_savings') }}:</p>
                <p>{{ translateText('message.total_potential_time_savings') }}:</p>
                <p>{{ translateText('message.total_number_of_measures') }}:</p>
                <p>{{ translateText('message.total_cost_of_measures') }}:</p>
            </div>
            <div v-if="summaryData" class="text-left">
                <p v-if="summaryData.opportunity_data.costSavings && summaryData.opportunity_data.costSavings.length > 0">
                    <b v-for="(item, index) in summaryData.opportunity_data.costSavings">{{ translateText(item.currency) }} {{ item.totalCost }}<span v-if="index < summaryData.opportunity_data.costSavings.length - 1">, </span></b>
                </p>
                <p v-else>-</p>
                <p v-if="summaryData.opportunity_data.timeSavings">
                    <b>{{ summaryData.opportunity_data.timeSavings.days }} {{ translateText('label.days') }}, {{ summaryData.opportunity_data.timeSavings.hours }} {{ translateText('label.hours') }}</b>
                </p>
                <p v-else>-</p>
                <p v-if="summaryData.measure_data.measuresNumber">
                    <b>{{ summaryData.measure_data.measuresNumber }}</b>
                </p>
                <p v-else>-</p>
                <p v-if="summaryData.measure_data.totalCost">
                    <b>${{ summaryData.measure_data.totalCost }}</b>
                </p>
                <p v-else>-</p>
            </div>
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
