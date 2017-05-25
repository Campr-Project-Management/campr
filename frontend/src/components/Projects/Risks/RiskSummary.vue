<template>
<div class="ro-summary">
                    <div class="text-center flex flex-center">
                        <div class="text-right">
                            <p>{{ translateText('message.total_potential_savings') }}:</p>
                            <p>{{ translateText('message.total_potential_time_savings') }}:</p>
                            <p>{{ translateText('message.total_number_of_measures') }}:</p>
                            <p>{{ translateText('message.total_cost_of_measures') }}:</p>
                        </div>
                        <div class="text-left">
                            <p><b>$323.350</b></p>
                            <p><b>74 days</b></p>
                            <p><b>12</b></p>
                            <p><b>$85.550</b></p>
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