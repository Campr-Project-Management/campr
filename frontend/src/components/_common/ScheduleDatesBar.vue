<template>
    <div class="task-range-slider">
        <div class="task-range-slider-title" v-if="title">{{ translate(title) }}</div>

        <div
                class="task-slider-holder base"
                v-if="showBase">
            <input type="text" class="range" ref="base"/>
        </div>
        <div
                class="task-slider-holder forecast"
                v-if="showForecast"
                :class="{[forecastClass]: forecastClass, 'no-pins': !showPins}">
            <input type="text" class="range" ref="forecast"/>
        </div>
        <div
                class="task-slider-holder actual"
                v-if="showActual"
                :class="{[actualClass]: actualClass, 'no-pins': !showPins}">
            <input type="text" class="range" ref="actual"/>
        </div>

        <div
                v-if="showScheduleTooltip"
                ref="tooltipOverlay"
                class="task-slider-holder tooltip-overlay"></div>

        <div ref="tooltip" v-show="false">
            <schedule-dates-table
                    :base-start-at="baseStartAt"
                    :base-finish-at="baseFinishAt"
                    :base-duration-days="baseDurationDays"
                    :forecast-start-at="forecastStartAt"
                    :forecast-finish-at="forecastFinishAt"
                    :forecast-duration-days="forecastDurationDays"
                    :actual-start-at="actualStartAt"
                    :actual-finish-at="actualFinishAt"
                    :actual-duration-days="actualDurationDays"
                    :activity-completed="activityCompleted"/>
        </div>
    </div>
</template>

<script>
    import $ from 'jquery';
    import 'ion-rangeslider/js/ion.rangeSlider.js';
    import 'ion-rangeslider/css/ion.rangeSlider.css';
    import 'ion-rangeslider/css/ion.rangeSlider.skinHTML5.css';
    import moment from 'moment';
    import Tooltip from 'tether-tooltip';
    import ScheduleDatesTable from './ScheduleDatesTable';
    import {getScheduleForecastTrafficLight, getScheduleActualTrafficLight} from '../../util/traffic-light';

    export default {
        name: 'schedule-dates-bar',
        components: {ScheduleDatesTable},
        props: {
            title: {
                type: String,
                required: false,
            },
            baseStartAt: {
                type: [String, Date],
            },
            baseFinishAt: {
                type: [String, Date],
            },
            baseDurationDays: {
                type: Number,
            },
            forecastStartAt: {
                type: [String, Date],
            },
            forecastFinishAt: {
                type: [String, Date],
            },
            forecastDurationDays: {
                type: Number,
            },
            actualStartAt: {
                type: [String, Date],
            },
            actualFinishAt: {
                type: [String, Date],
            },
            actualDurationDays: {
                type: Number,
            },
            activityCompleted: {
                type: Boolean,
                required: false,
                default: false,
            },
            showScheduleTooltip: {
                type: Boolean,
                default: true,
            },
            showPins: {
                type: Boolean,
                default: true,
            },
        },
        computed: {
            showActual() {
                return this._actualStartAt > 0 &&
                    this._actualStartAt <= this._actualFinishAt;
            },
            showBase() {
                return this._baseStartAt > 0
                    && this._baseFinishAt > 0
                    && this._baseStartAt <= this._baseFinishAt;
            },
            showForecast() {
                return this._forecastStartAt > 0 &&
                    this._forecastFinishAt > 0 &&
                    this._forecastStartAt <= this._forecastFinishAt;
            },
            _baseStartAt() {
                return moment(this.baseStartAt).unix();
            },
            _baseFinishAt() {
                let date = moment(this.baseFinishAt).unix();
                if (date === this._baseStartAt) {
                    date += 1;
                }

                return date;
            },
            _actualStartAt() {
                return moment(this.actualStartAt).unix();
            },
            _actualFinishAt() {
                if (this.actualFinishAt) {
                    return moment(this.actualFinishAt).unix();
                }

                return moment().unix();
            },
            _forecastStartAt() {
                return moment(this.forecastStartAt).unix();
            },
            _forecastFinishAt() {
                let date = moment(this.forecastFinishAt).unix();
                if (this._forecastStartAt === date) {
                    date += 1;
                }

                return date;
            },
            minDate() {
                let dates = [];

                if (this.showActual) {
                    dates.push(this._actualStartAt);
                }

                if (this.showForecast) {
                    dates.push(this._forecastStartAt);
                }

                if (this.showBase) {
                    dates.push(this._baseStartAt);
                }

                if (dates.length === 0) {
                    return;
                }

                return Math.min(...dates);
            },
            maxDate() {
                let dates = [];

                if (this.showActual) {
                    dates.push(this._actualFinishAt);
                }

                if (this.showForecast) {
                    dates.push(this._forecastFinishAt);
                }

                if (this.showBase) {
                    dates.push(this._baseFinishAt);
                }

                if (dates.length === 0) {
                    return;
                }

                return Math.max(...dates);
            },
            min() {
                return this.minDate;
            },
            max() {
                if (this.maxDate === this.minDate) {
                    return this.maxDate + 1;
                }

                return this.maxDate;
            },
            forecastClass() {
                let tl = getScheduleForecastTrafficLight({
                    startAt: this.baseStartAt,
                    finishAt: this.baseFinishAt,
                }, {
                    startAt: this.forecastStartAt,
                    finishAt: this.forecastFinishAt,
                });

                if (tl.isYellow()) {
                    return 'warning';
                }

                if (tl.isRed()) {
                    return 'danger';
                }
            },
            actualClass() {
                let tl = getScheduleActualTrafficLight({
                    startAt: this.forecastStartAt,
                    finishAt: this.forecastFinishAt,
                }, {
                    startAt: this.actualStartAt,
                    finishAt: this.actualFinishAt,
                }, this.activityCompleted);

                if (tl.isYellow()) {
                    return 'warning';
                }

                if (tl.isRed()) {
                    return 'danger';
                }
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

                if (this.showScheduleTooltip) {
                    this.createOverallTooltip(this.$refs.tooltipOverlay);
                }
            },
            createBase() {
                if (!this.showBase) {
                    return;
                }

                let $el = $(this.$refs.base);
                let options = {
                    type: 'double',
                    min: this.min,
                    max: this.max,
                    from: this._baseStartAt,
                    to: this._baseFinishAt,
                    from_fixed: true,
                    to_fixed: true,
                };

                $el.ionRangeSlider(options);

                let $parent = $el.parent();
                let fromHandle = $parent.find('.irs-slider.from')[0];
                let toHandle = $parent.find('.irs-slider.to')[0];

                this.createFromTooltip(fromHandle, this._baseStartAt * 1000, 'Base');
                this.createToTooltip(toHandle, this._baseFinishAt * 1000, 'Base');
            },
            createForecast() {
                if (!this.showForecast) {
                    return;
                }

                let $el = $(this.$refs.forecast);

                let options = {
                    type: 'double',
                    min: this.min,
                    max: this.max,
                    from: this._forecastStartAt,
                    to: this._forecastFinishAt,
                    from_fixed: true,
                    to_fixed: true,
                    extra_classes: (this._forecastStartAt > this._baseStartAt) ? 'irs-warning' : '',
                };

                $el.ionRangeSlider(options);

                let $parent = $el.parent();
                let fromHandle = $parent.find('.irs-slider.from')[0];
                let toHandle = $parent.find('.irs-slider.to')[0];

                this.createFromTooltip(fromHandle, this._forecastStartAt * 1000, 'Forecast');
                this.createToTooltip(toHandle, this._forecastFinishAt * 1000, 'Forecast');
            },
            createActual() {
                if (!this.showActual) {
                    return;
                }

                let $el = $(this.$refs.actual);

                let options = {
                    type: 'double',
                    min: this.min,
                    max: this.max,
                    from: this._actualStartAt,
                    to: this._actualFinishAt,
                    from_fixed: true,
                    to_fixed: true,
                };

                $el.ionRangeSlider(options);

                let $parent = $el.parent();
                let fromHandle = $parent.find('.irs-slider.from')[0];
                let toHandle = $parent.find('.irs-slider.to')[0];
                this.createFromTooltip(fromHandle, this._actualStartAt * 1000, 'Actual');
                this.createToTooltip(toHandle, this._actualFinishAt * 1000, 'Actual');
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
            createFromTooltip(el, date, prefix) {
                let fromValue = this.$formatDate(date) || 'N/A';
                let text = `${prefix} ${this.translate('message.start')}: ${fromValue}`;

                return this.createTooltip(el, text);
            },
            createToTooltip(el, date, prefix) {
                let toValue = this.$formatDate(date) || 'N/A';
                if (!this.actualFinishAt && prefix === 'Actual') {
                    toValue = 'N/A';
                }

                if (!this.forecastFinishAt && prefix === 'Forecast') {
                    toValue = 'N/A';
                }

                let text = `${prefix} ${this.translate('message.finish')}: ${toValue}`;

                return this.createTooltip(el, text);
            },
            createOverallTooltip(el) {
                return this.createTooltip(el, this.$refs.tooltip.innerHTML, 'task-schedule-bar overall-tooltip');
            },
        },
    };
</script>

<style lang="scss">
    @import '../../css/_variables';

    .task-range-slider {
        .no-pins {
            .irs-slider {
                display: none;
            }
        }
    }
</style>
