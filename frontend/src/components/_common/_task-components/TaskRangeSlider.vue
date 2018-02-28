<template>
    <div class="task-slider-holder">
        <div v-show="toTooltip" class="slider-tooltip to-tooltip">
            {{ this.message }} Finish: {{ this.to }}
        </div>
        <div v-show="fromTooltip" class="slider-tooltip from-tooltip">
            {{ this.message }} Start: {{ this.from }}
        </div>
        <input type="text" class="range" :id="'slider' + _uid" ref="slider"/>
    </div>
</template>

<script>
    import 'ion-rangeslider/js/ion.rangeSlider.js';
    import 'ion-rangeslider/css/ion.rangeSlider.css';
    import 'ion-rangeslider/css/ion.rangeSlider.skinHTML5.css';
    import moment from 'moment';

    export default {
        props: ['message', 'min', 'max', 'from', 'to', 'type'],
        mounted() {
            const $this = window.$('#slider' + this._uid);
            $this.ionRangeSlider({
                type: this.type,
                min: +moment(this.min, 'YYYY-MM-DD'),
                max: +moment(this.max, 'YYYY-MM-DD'),
                from: +moment(this.from, 'YYYY-MM-DD'),
                to: +moment(this.to, 'YYYY-MM-DD'),
                from_fixed: true,
                to_fixed: true,
            });
            $this.prev().find('.irs-slider.from').hover(() => {
                this.fromTooltip = !this.fromTooltip;
            });
            $this.prev().find('.irs-slider.to').hover(() => {
                this.toTooltip = !this.toTooltip;
            });
        },
        data() {
            return {
                fromTooltip: false,
                toTooltip: false,
            };
        },
    };
</script>

<style lang="scss">
    @import '../../../css/_variables.scss';

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
            width: 100%;
            height: 20px;
            top: 20px;
            left: 0;
        }

        .slider-tooltip {
            position: absolute;
            text-align: right;
            right: 0;
            top: -22px;
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

        .base {
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

        .forecast {
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

        .actual {
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

        .irs-bar-edge {
            display:none;
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
