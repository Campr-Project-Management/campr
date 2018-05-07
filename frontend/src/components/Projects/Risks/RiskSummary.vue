<template>
    <div class="ro-summary">
        <div class="text-center">
            <p>
                <span class="text-right">{{ translate('message.total_potential_cost') }}:</span>
                <span class="text-left">
                    <b>{{ potentialCost | money({symbol: currency}) }}</b>
                </span>
            </p>
            <p>                
                <span class="text-right">{{ translate('message.total_potential_delay') }}:</span>
                <span class="text-left">
                        <b>{{ potentialDelay | humanizeHours({ units: ['d', 'h']}) }}</b>
                </span>
            </p>
            <p>
                <span class="text-right">{{ translate('message.total_number_of_measures') }}:</span>
                <span class="text-left">
                    <b>{{ measuresCount }}</b>
                </span>
            </p>
            <p>
                <span class="text-right">{{ translate('message.total_cost_of_measures') }}:</span>
                <span class="text-left">
                    <b>{{ measuresCost | money({symbol: currency}) }}</b>
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
            potentialDelay: {
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
