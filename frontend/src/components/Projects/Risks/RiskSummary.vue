<template>
    <div class="ro-summary">
        <div class="text-center" v-if="summaryData">
            <p>
                <span class="text-right">{{ translateText('message.total_potential_cost') }}:</span>
                <span class="text-left">
                    <span v-if="summaryData.risk_data.costs && summaryData.risk_data.costs.length > 0">
                        <b v-for="(item, index) in summaryData.risk_data.costs">{{ translateText(item.currency) }} {{ item.totalCost }}<span v-if="index < summaryData.risk_data.costs.length - 1">, </span></b>
                    </span>
                    <span v-else>-</span>
                </span>
            </p>
            <p>                
                <span class="text-right">{{ translateText('message.total_potential_delay') }}:</span>
                <span class="text-left">
                    <span v-if="summaryData.risk_data.delay">
                        <b>{{ summaryData.risk_data.delay | humanizeHours({ units: ['d', 'h']}) }}</b>
                    </span>
                    <span v-else>-</span>
                </span>
            </p>
            <p>
                <span class="text-right">{{ translateText('message.total_number_of_measures') }}:</span>
                <span class="text-left">
                    <span v-if="summaryData.measure_data.measuresNumber"><b>{{ summaryData.measure_data.measuresNumber }}</b></span>
                    <span v-else>-</span>
                </span>
            </p>
            <p>
                <span class="text-right">{{ translateText('message.total_cost_of_measures') }}:</span>
                <span class="text-left">
                    <span v-if="summaryData.measure_data.totalCost"><b>${{ summaryData.measure_data.totalCost }}</b></span>
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
                width: 49%;
                display: inline-block;
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
