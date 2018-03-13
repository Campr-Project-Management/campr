<template>
    <div class="task-range-slider" v-if="show">
        <div class="task-range-slider-title" v-if="this.title">{{ translate(this.title) }}</div>

        <div class="task-slider-holder base" v-if="showBase">
            <input type="text" class="range" ref="base"/>
        </div>
        <div class="task-slider-holder forecast" v-if="showForecast">
            <input type="text" class="range" ref="forecast"/>
        </div>
        <div class="task-slider-holder actual" :class="actualClass" v-if="showActual">
            <input type="text" class="range" ref="actual"/>
        </div>
    </div>
</template>

<script>
    import $ from 'jquery';
    import 'ion-rangeslider/js/ion.rangeSlider.js';
    import 'ion-rangeslider/css/ion.rangeSlider.css';
    import 'ion-rangeslider/css/ion.rangeSlider.skinHTML5.css';
    import Tooltip from 'tether-tooltip';

    export default {
        name: 'task-cost-bar',
        props: {
            task: {
                type: Object,
                required: true,
            },
            title: {
                type: String,
                required: false,
            },
        },
        computed: {
            show() {
                return this.showActual || this.showForecast || this.showBase;
            },
            showActual() {
                return this.actualCost > 0;
            },
            showBase() {
                return this.baseCost > 0;
            },
            showForecast() {
                return this.forecastCost > 0;
            },
            baseCost() {
                return Math.ceil(this.task.totalCosts * 100);
            },
            actualCost() {
                return Math.ceil(this.task.totalActualCosts * 100);
            },
            forecastCost() {
                return Math.ceil(this.task.totalForecastCosts * 100);
            },
            max() {
                let costs = [this.baseCost, this.forecastCost, this.actualCost];

                return Math.max(...costs);
            },
            actualClass() {
                let classes = {
                    'yellow': 'warning',
                    'red': 'danger',
                };

                if (!classes[this.task.actualCostColor]) {
                    return;
                }

                return classes[this.task.actualCostColor];
            },
        },
        mounted() {
            this.$nextTick(() => {
                this.init();
            });
        },
        updated() {
            this.$nextTick(() => {
                this.init();
            });
        },
        methods: {
            init() {
                this.createBase();
                this.createForecast();
                this.createActual();
            },
            createBase() {
                if (!this.showBase) {
                    return;
                }

                let $el = $(this.$refs.base);
                let options = {
                    type: 'single',
                    min: 0,
                    max: this.max,
                    from: this.baseCost,
                    to: this.baseCost,
                    from_fixed: true,
                    to_fixed: true,
                };

                $el.ionRangeSlider(options);

                let $parent = $el.parent();
                let toHandle = $parent.find('.irs-slider.single')[0];

                this.createToTooltip(toHandle, this.baseCost / 100, 'label.base');
            },
            createForecast() {
                if (!this.showForecast) {
                    return;
                }

                let $el = $(this.$refs.forecast);
                let options = {
                    type: 'single',
                    min: 0,
                    max: this.max,
                    from: this.forecastCost,
                    to: this.forecastCost,
                    from_fixed: true,
                    to_fixed: true,
                };

                $el.ionRangeSlider(options);

                let $parent = $el.parent();
                let toHandle = $parent.find('.irs-slider.single')[0];

                this.createToTooltip(toHandle, this.forecastCost / 100, 'label.forecast');
            },
            createActual() {
                if (!this.showActual) {
                    return;
                }

                let $el = $(this.$refs.actual);
                let options = {
                    type: 'single',
                    min: 0,
                    max: this.max,
                    from: this.actualCost,
                    to: this.actualCost,
                    from_fixed: true,
                    to_fixed: true,
                };

                $el.ionRangeSlider(options);

                let $parent = $el.parent();
                let toHandle = $parent.find('.irs-slider.single')[0];

                this.createToTooltip(toHandle, this.actualCost / 100, 'label.actual');
            },
            createTooltip(el, text, classes) {
                if (!el) {
                    return;
                }

                return new Tooltip({
                    target: el,
                    openOn: 'hover',
                    content: text,
                    classes: classes,
                    position: 'bottom center',
                });
            },
            createToTooltip(el, value, prefix, classes) {
                let text = `${this.translate(prefix)} ${this.translate('message.cost')}: ${this.$formatMoney(value)}`;

                return this.createTooltip(el, text, classes);
            },
        },
    };
</script>
