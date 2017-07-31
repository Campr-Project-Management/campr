<template>
    <div :data-percentage="percentage" class="chart relative" :id="'chart-' + _uid">
        <svg viewBox="0 0 100 100">
            <circle cx="50" cy="50" r="49" class="empty" stroke-width="1" fill="transparent"/>
            <circle cx="50" cy="50" r="49" class="full" stroke-width="1" fill="transparent"/>
        </svg>
        <div class="text">
            <div class="title">{{ title }}</div>
            <div class="value">
                <div class="percentage">0</div>
                <div class="percentage-sign">%</div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['percentage', 'title'],
    mounted() {
        const $this = window.$('#chart-' + this._uid);
        let speed = 1000;

        const $percentageNumber = $this.find('.percentage');
        const animatedCircle = $this.find('.full');
        const percentage = $this.data('percentage');
        const c = Math.PI*(animatedCircle.attr('r')*2);

        let strokeDasharray = 0;
        let pct = percentage/100*c;
        let animation = setInterval(function() {
            strokeDasharray++;

            animatedCircle[0]
            .style.strokeDasharray = strokeDasharray + ', 10000';
            if (strokeDasharray >= pct) {
                clearInterval(animation);
            };
        }, 1);

        window.$({Counter: 0})
        .animate({Counter: $this.data('percentage')}, {
            duration: speed,
            step: function() {
                $percentageNumber.text(this.Counter.toFixed(2));
            },
            complete: function() {
                $percentageNumber.text(this.Counter.toFixed(2));
            },
        });
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
@import '../../../css/_common';
@import '../../../css/_variables.scss';

    .chart {
        font-size: 22px;

        svg {
            display: block;
            width: 100%;
            transform: rotate(-90deg);
        }

        .empty {
            stroke: $mainColor;
        }

        .full {
            stroke: $secondColor;
            stroke-dasharray: 0, 10000;
        }

        .text {
            position: absolute;
            left: 50%;
            top: 50%;
            padding: 0;
            margin: 0;
            transform: translate(-50%, -50%);
            color: rgb(216, 218, 229);
            line-height: 1em;

            .title {
                text-transform: uppercase;
                font-size: 9px;
                text-align: center;
            }

            .value {
                text-align: center;
                white-space: nowrap;
            }

            .percentage {
                display: inline-block;
                font-weight: 700;
                font-size: 34px;
                line-height: 34px;
            }

            .percentage-sign {
                display: inline-block;
                color: $middleColor;
                font-size: 12px;
                line-height: 22px;
                margin-left: -5px;
            }
        }

        &.dark-chart {
            .empty {
                stroke: $darkerColor;
            }
        }

        &.warning {
            .full {
                stroke: $warningColor;
            }
        }

        &.danger {
            .full {
                stroke: $dangerColor;
            }
        }
    }

    .widget.task-status-widget {
        @media (min-width:1024px) {
            .chart {
                .text {
                    .title {
                        font-size: 16px;
                    }

                    .percentage {
                        font-size: 85px;
                        line-height: 85px;
                    }

                    .percentage-sign {
                        font-size: 16px;
                        line-height: 50px;
                    }
                }
            }
        }
    }
</style>
