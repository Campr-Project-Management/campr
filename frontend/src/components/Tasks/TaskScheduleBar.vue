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
                return this.actualStartAt > 0;
            },
            showBase() {
                return this.baseStartAt > 0 && this.baseFinishAt > 0;
            },
            showForecast() {
                return this.forecastStartAt > 0 && this.forecastFinishAt > 0;
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
                    return this.task.actualFinishAt;
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
                this.createBase();
                this.createForecast();
                this.createActual();

                this.createOverallTooltip(this.$refs.tooltipOverlay);
            });
        },
        methods: {
            createBase() {
                if (!this.showBase) {
                    return;
                }

                let $el = window.$(this.$refs.base);
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

                let $el = window.$(this.$refs.forecast);
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
                if (!this.showActual) {
                    return;
                }

                let $el = window.$(this.$refs.actual);
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

<style lang="scss" scoped>
    @import '../../css/_variables.scss';

    .task-range-slider {
        margin-bottom: 20px;
        height: 45px;
        position: relative;

        .task-range-slider-title {
            text-transform: uppercase;
            color: $lightColor;
            position: relative;
            font-size: 9px;
            letter-spacing: 1.9px;
        }

        .task-slider-holder {
            position: absolute;
            width: 101.5%;
            height: 20px;
            top: 20px;
            left: -1.5% !important;

            &.tooltip-overlay {
                z-index: 40;
                height: 8px;
            }

            &.base {
                z-index: 10;

                .irs-bar {
                    background: $mainColor !important;
                }

                .irs-slider {
                    background-color: $mainColor !important;
                }

                &.dark-range-slider {
                    .irs-bar {
                        background: $darkerColor !important;
                    }

                    .irs-slider {
                        background-color: $darkerColor !important;
                    }
                }
            }

            &.forecast {
                z-index: 20;

                .irs-bar {
                    background: $middleColor !important;
                }

                .irs-slider {
                    background-color: $middleColor !important;
                }

                &.warning {
                    .irs-bar {
                        background: $warningColor !important;
                    }

                    .irs-slider {
                        background-color: $warningColor !important;
                    }
                }

                &.danger {
                    .irs-bar {
                        background: $dangerColor !important;
                    }

                    .irs-slider {
                        background-color: $dangerColor !important;
                    }
                }
            }

            &.actual {
                z-index: 30;

                .irs-bar {
                    background: $secondColor !important;
                }

                .irs-slider {
                    background-color: $secondColor !important;
                }

                &.warning {
                    .irs-bar {
                        background: $warningColor !important;
                    }

                    .irs-slider {
                        background-color: $warningColor !important;
                    }
                }

                &.danger {
                    .irs-bar {
                        background: $dangerColor !important;
                    }

                    .irs-slider {
                        background-color: $dangerColor !important;
                    }
                }
            }
        }

        .irs {
            height: 20px;
        }

        .irs-line {
            display: none;
        }

        .irs-bar {
            border: none !important;
            height: 8px;
            top: 0;
        }

        .irs-slider {
            border: none !important;
            font-size: 0 !important;
            width: 10px;
            height: 12px;
            top: 12px;
            -webkit-mask-image: url(data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHZpZXdCb3g9IjAgMCAxMCAxMiI+ICAgIDxwYXRoIGQ9Ik01LDEyYzIuNywwLDQuNy0yLjIsNC43LTVDOS43LDMuNCw1LDAsNSwwUzAuMywzLjQsMC4zLDdDMC4zLDkuOCwyLjMsMTIsNSwxMnogTTUsNC43IGMxLjQsMCwyLjYsMS4yLDIuNiwyLjZjMCwxLjQtMS4yLDIuNi0yLjYsMi42Yy0xLjQsMC0yLjYtMS4yLTIuNi0yLjZDMi40LDUuOSwzLjYsNC43LDUsNC43eiIvPjwvc3ZnPg==);
            mask-image: url(data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHZpZXdCb3g9IjAgMCAxMCAxMiI+ICAgIDxwYXRoIGQ9Ik01LDEyYzIuNywwLDQuNy0yLjIsNC43LTVDOS43LDMuNCw1LDAsNSwwUzAuMywzLjQsMC4zLDdDMC4zLDkuOCwyLjMsMTIsNSwxMnogTTUsNC43IGMxLjQsMCwyLjYsMS4yLDIuNiwyLjZjMCwxLjQtMS4yLDIuNi0yLjYsMi42Yy0xLjQsMC0yLjYtMS4yLTIuNi0yLjZDMi40LDUuOSwzLjYsNC43LDUsNC43eiIvPjwvc3ZnPg==);
        }

        .irs-bar-edge {
            display: none;
        }

        &.big-range-slider {
            margin-left: -5px;
            margin-right: -5px;

            .irs-bar {
                height: 16px;
            }

            .irs-slider {
                display: none;
            }

            .irs-bar-edge {
                display: block;
            }
        }
    }
</style>

<style lang="scss">
    @import '../../css/_variables.scss';

    .task-schedule-bar {
        &.overall-tooltip {
            max-width: 440px;
            padding: 0;

            .tooltip-content {
                background-color: $mainColor;
                box-shadow: 0 0 8px -2px #000;
                color: $lighterColor;
                padding: 5px 0;
                text-align: left;
            }

            &.tooltip-target-attached-top {
                &:after {
                    border-color: transparent transparent $mainColor transparent;
                    top: -5px;
                }
            }

            &.tooltip-target-attached-bottom {
                &:after {
                    border-color: transparent transparent $mainColor transparent;
                    top: -5px;
                }
            }
        }
    }
</style>
