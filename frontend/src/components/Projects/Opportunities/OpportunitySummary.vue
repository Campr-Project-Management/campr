<template>
    <div class="ro-summary">
        <div class="text-center">
            <p class="clearfix">
                <span class="text-right">{{ translate('message.total_potential_savings') }}:</span>
                <span class="text-left">
                    <b>{{ potentialCost | money({symbol: currency}) }}</b>
                </span>
            </p>
            <p class="clearfix">                
                <span class="text-right">{{ translate('message.total_potential_time_savings') }}:</span>
                <span class="text-left">
                    <span v-if="potentialTime">
                        <b>{{ potentialTime | humanizeHours({ units: ['d', 'h'] }) }}</b>
                    </span>
                    <span v-else>-</span>
                </span>
            </p>
            <p class="clearfix">
                <span class="text-right">{{ translate('message.total_number_of_measures') }}:</span>
                <span class="text-left">
                    <span v-if="measuresCount">
                        <b>{{ measuresCount }}</b>
                    </span>
                    <span v-else>-</span>
                </span>
            </p>
            <p class="clearfix">
                <span class="text-right">{{ translate('message.total_cost_of_measures') }}:</span>
                <span class="text-left">
                    <span v-if="measuresCost">
                        <b>{{ measuresCost | money({symbol: currency}) }}</b>
                    </span>
                    <span v-else>-</span>
                </span>
            </p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        potentialCost: {
            type: Number,
            required: false,
            default: null,
        },
        potentialTime: {
            type: Number,
            required: false,
            default: null,
        },
        measuresCount: {
            type: Number,
            required: false,
            default: null,
        },
        measuresCost: {
            type: Number,
            required: false,
            default: null,
        },
        currency: {
            type: String,
            required: true,
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
