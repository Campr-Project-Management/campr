<template>
    <div class="progress-bar-chart">
        <div
                v-for="(data, index) in series"
                :key="index"
                :style="{width: getPercent(data) + '%', backgroundColor: data.color}"
                class="bar">

            <span v-if="options.labels.enabled">{{ translate(data.name) }}: </span>
            {{ data.value | formatNumber }}
        </div>
    </div>
</template>

<script>
    import _ from 'lodash';

    export default {
        name: 'progress-bar-chart',
        props: {
            series: {
                type: Array,
                required: true,
                validator(value) {
                    let valid = true;
                    value.forEach((data) => {
                        valid = valid && (data.name && data.color && _.isNumber(data.value));
                    });

                    return valid;
                },
            },
            options: {
                type: Object,
                required: false,
                default() {
                    return {
                        labels: {
                            enabled: true,
                        },
                    };
                },
            },
        },
        computed: {
            total() {
                let sum = 0;

                this.series.forEach((data) => {
                    sum += data.value;
                });

                return sum;
            },
        },
        methods: {
            getPercent(data) {
                return (data.value / this.total) * 100;
            },
        },
    };
</script>

<style scoped lang="scss">
    @import '../../../../frontend/src/css/_variables';

    .progress-bar-chart {
        width: 100%;
        margin: 1em 0;
        max-width: 100%;
        display: table;

        .bar {
            -webkit-print-color-adjust: exact;

            height: 30px;
            line-height: 30px;
            text-align: center;
            color: $whiteColor;
            font-weight: 500;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            min-width: 7em;
            display: table-cell;
        }
    }
</style>
