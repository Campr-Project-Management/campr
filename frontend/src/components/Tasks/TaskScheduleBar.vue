<template>
    <div class="task-range-slider">
        <div class="task-range-slider-title" v-if="this.title">{{ translate(this.title) }}</div>

        <div class="task-slider-holder base" v-if="showBase">
            <input type="text" class="range" ref="base"/>
        </div>
        <div class="task-slider-holder forecast" v-if="showForecast">
            <input type="text" class="range" ref="forecast"/>
        </div>
        <div class="task-slider-holder actual" v-if="showActual">
            <input type="text" class="range" ref="actual"/>
        </div>

        <div ref="tooltipOverlay" class="task-slider-holder tooltip-overlay"></div>

        <div ref="tooltip" v-show="false">
            <table class="table table-small">
                <thead>
                <tr>
                    <th>{{ translate('table_header_cell.schedule') }}</th>
                    <th>{{ translate('table_header_cell.start') }}</th>
                    <th>{{ translate('table_header_cell.finish') }}</th>
                    <th>{{ translate('table_header_cell.duration') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{ translate('label.base') }}</td>
                    <td>{{ task.scheduledStartAt ? formatDate(task.scheduledStartAt) : '-' }}</td>
                    <td>{{ task.scheduledFinishAt ? formatDate(task.scheduledFinishAt) : '-' }}</td>
                    <td>{{ task.scheduledDurationDays > 0 ? task.scheduledDurationDays : 0 }}</td>
                </tr>
                <tr class="column-warning">
                    <td>{{ translate('label.forecast') }}</td>
                    <td>{{ task.forecastStartAt ? formatDate(task.forecastStartAt) : '-' }}</td>
                    <td>{{ task.forecastFinishAt ? formatDate(task.forecastFinishAt) : '-' }}</td>
                    <td>{{ task.forecastDurationDays ? task.forecastDurationDays : '' }}</td>
                </tr>
                <tr>
                    <td>{{ translate('label.actual') }}</td>
                    <td>{{ task.actualStartAt ? formatDate(task.actualStartAt) : '-' }}</td>
                    <td>{{ task.actualFinishAt ? formatDate(task.actualFinishAt) : '-' }}</td>
                    <td>{{ task.actualDurationDays > 0 ? task.actualDurationDays : '-' }}</td>
                </tr>
                </tbody>
            </table>
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

    export default {
        name: 'task-schedule-bar',
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
            showActual() {
                return this.actualStartAt > 0 &&
                    this.actualStartAt <= this.actualFinishAt;
            },
            showBase() {
                return this.baseStartAt > 0
                    && this.baseFinishAt > 0
                    && this.baseStartAt <= this.baseFinishAt;
            },
            showForecast() {
                return this.forecastStartAt > 0 &&
                    this.forecastFinishAt > 0 &&
                    this.forecastStartDate <= this.forecastFinishAt;
            },
            baseStartAt() {
                return moment(this.task.scheduledStartAt).unix();
            },
            baseFinishAt() {
                let date = moment(this.task.scheduledFinishAt).unix();
                if (date === this.baseStartAt) {
                    date += 1;
                }

                return date;
            },
            actualStartAt() {
                return moment(this.task.actualStartAt).unix();
            },
            actualFinishAt() {
                if (this.task.actualFinishAt) {
                    return moment(this.task.actualFinishAt).unix();
                }

                return moment().unix();
            },
            forecastStartAt() {
                return moment(this.task.forecastStartAt).unix();
            },
            forecastFinishAt() {
                let date = moment(this.task.forecastFinishAt).unix();
                if (this.forecastStartAt === date) {
                    date += 1;
                }

                return date;
            },
            minDate() {
                let dates = [];

                if (this.showActual) {
                    dates.push(this.actualStartAt);
                }

                if (this.showForecast) {
                    dates.push(this.forecastStartAt);
                }

                if (this.showBase) {
                    dates.push(this.baseStartAt);
                }

                if (dates.length === 0) {
                    return;
                }

                return Math.min(...dates);
            },
            maxDate() {
                let dates = [];

                if (this.showActual) {
                    dates.push(this.actualFinishAt);
                }

                if (this.showForecast) {
                    dates.push(this.forecastFinishAt);
                }

                if (this.showBase) {
                    dates.push(this.baseFinishAt);
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

                this.createOverallTooltip(this.$refs.tooltipOverlay);
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
                    from: this.baseStartAt,
                    to: this.baseFinishAt,
                    from_fixed: true,
                    to_fixed: true,
                };

                $el.ionRangeSlider(options);

                let $parent = $el.parent();
                let fromHandle = $parent.find('.irs-slider.from')[0];
                let toHandle = $parent.find('.irs-slider.to')[0];

                this.createFromTooltip(fromHandle, this.baseStartAt * 1000, 'Base');
                this.createToTooltip(toHandle, this.baseFinishAt * 1000, 'Base');
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
                    from: this.forecastStartAt,
                    to: this.forecastFinishAt,
                    from_fixed: true,
                    to_fixed: true,
                };

                $el.ionRangeSlider(options);

                let $parent = $el.parent();
                let fromHandle = $parent.find('.irs-slider.from')[0];
                let toHandle = $parent.find('.irs-slider.to')[0];

                this.createFromTooltip(fromHandle, this.forecastStartAt * 1000, 'Forecast');
                this.createToTooltip(toHandle, this.forecastFinishAt * 1000, 'Forecast');
            },
            createActual() {
                console.log(this.showActual);
                if (!this.showActual) {
                    return;
                }

                let $el = $(this.$refs.actual);
                let options = {
                    type: 'double',
                    min: this.min,
                    max: this.max,
                    from: this.actualStartAt,
                    to: this.actualFinishAt,
                    from_fixed: true,
                    to_fixed: true,
                };

                $el.ionRangeSlider(options);

                let $parent = $el.parent();
                let fromHandle = $parent.find('.irs-slider.from')[0];
                let toHandle = $parent.find('.irs-slider.to')[0];
                this.createFromTooltip(fromHandle, this.actualStartAt * 1000, 'Actual');
                this.createToTooltip(toHandle, this.actualFinishAt * 1000, 'Actual');
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
                let fromValue = this.formatDate(date) || 'N/A';
                let text = `${prefix} ${this.translate('message.start')}: ${fromValue}`;

                return this.createTooltip(el, text);
            },
            createToTooltip(el, date, prefix) {
                let toValue = this.formatDate(date) || 'N/A';
                if (!this.task.actualFinishAt && prefix === 'Actual') {
                    toValue = 'N/A';
                }
                if (!this.task.baseFinishAt && prefix === 'Base') {
                    toValue = 'N/A';
                }
                if (!this.task.forecastFinishAt && prefix === 'Forecast') {
                    toValue = 'N/A';
                }
                let text = `${prefix} ${this.translate('message.finish')}: ${toValue}`;
                return this.createTooltip(el, text);
            },
            createOverallTooltip(el) {
                return this.createTooltip(el, this.$refs.tooltip.innerHTML, 'task-schedule-bar overall-tooltip');
            },
            formatDate(date) {
                if (!date) {
                    return;
                }

                return moment(date).format('DD.MM.YYYY');
            },
        },
    };
</script>
