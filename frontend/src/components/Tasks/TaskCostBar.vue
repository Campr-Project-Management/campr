<template>
    <div class="task-range-slider" v-if="show">
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
            createToTooltip(el, value, prefix) {
                let text = `${this.translate(prefix)} ${this.translate('message.cost')}: ${this.$formatMoney(value)}`;

                return this.createTooltip(el, text);
            },
        },
    };
</script>

<style lang="scss" scoped>
    @import '../../css/_variables.scss';

    .task-range-slider {
        margin: 1em 0;
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
        .range {
            display: none;
        }
    }
</style>
