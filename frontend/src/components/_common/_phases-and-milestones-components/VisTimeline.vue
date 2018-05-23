<template>
    <div>
        <div id="vis" ref="timeline"></div>

        <template>
            <div v-for="item in items" :key="item.id" :class="'tooltip' + item.id" v-show="false">
                <tooltip :item="item.data" :type="getType(item)"/>
            </div>
        </template>
    </div>
</template>

<script>
    import vis from 'vis';
    import Tooltip from '../../Projects/PhasesAndMilestones/Tooltip.vue';
    import _ from 'lodash';
    import moment from 'moment';

    const TYPE_PHASE = 0;

    export default {
        props: {
            items: {
                type: Array,
                required: true,
            },
            withPhases: {
                type: Boolean,
                required: false,
                default: false,
            },
            startTime: {
                type: String,
                required: false,
                default: '08:00:00',
                validate(value) {
                    return moment(`2001-01-01 ${value}`).isValid();
                },
            },
            endTime: {
                type: String,
                required: false,
                default: '18:00:00',
                validate(value) {
                    return moment(`2001-01-01 ${value}`).isValid();
                },
            },
        },
        components: {
            vis,
            Tooltip,
        },
        data: function() {
            let groups = [];
            if (this.withPhases) {
                groups.push({id: 0, content: 'Phases', value: 1});
                groups.push({id: 1, content: 'Milestones', value: 2});
            } else {
                groups.push({id: 0, content: 'Milestones', value: 1});
            }

            return {
                groups: groups,
                timeline: null,
                tooltips: {},
            };
        },
        methods: {
            isPhase(item) {
                return item.data.type === TYPE_PHASE;
            },
            getType(item) {
                if (this.isPhase(item)) {
                    return 'phase';
                }

                return 'milestone';
            },
            refreshTimeline() {
                if (this.timeline) {
                    this.timeline.destroy();
                }

                this.timeline = new vis.Timeline(this.$refs.timeline, this.visItems, this.groups, this.visOptions);
            },
        },
        computed: {
            startHour() {
                return moment(`2001-01-01 ${this.startTime}`).hour();
            },
            startMinute() {
                return moment(`2001-01-01 ${this.startTime}`).minute();
            },
            startSecond() {
                return moment(`2001-01-01 ${this.startTime}`).second();
            },
            endHour() {
                return moment(`2001-01-01 ${this.endTime}`).hour();
            },
            endMinute() {
                return moment(`2001-01-01 ${this.endTime}`).minute();
            },
            endSecond() {
                return moment(`2001-01-01 ${this.endTime}`).second();
            },
            hiddenDates() {
                return [
                    {start: '2018-01-06 00:00:00', end: '2018-01-08 00:00:00', repeat: 'weekly'},
                    {start: '2018-01-03 00:00:00', end: `2018-01-03 ${this.startTime}`, repeat: 'daily'},
                    {start: `2018-01-03 ${this.endTime}`, end: '2018-01-04 00:00:00', repeat: 'daily'},
                ];
            },
            visOptions: function() {
                let min = new Date(_.minBy(this.items, 'start'));
                let max = new Date(_.minBy(this.items, 'end'));

                min.setFullYear(min.getFullYear() - 2);
                max.setFullYear(max.getFullYear() + 1);

                return {
                    width: '100%',
                    horizontalScroll: true,
                    margin: {
                        item: {
                            horizontal: 0,
                            vertical: 5,
                        },
                    },
                    hiddenDates: this.hiddenDates,
                    min: min,
                    max: max,
                    zoomMax: 31536000000000,
                    zoomMin: 315360000,
                    order: (a, b) => b.id - a.id,
                    tooltip: {
                        followMouse: false,
                        overflowMethod: 'flip',
                    },
                    visibleFrameTemplate: function(item) {
                        if (item.visibleFrameTemplate) {
                            return item.visibleFrameTemplate;
                        }
                        let percentage = item.value + '%';
                        return `<span class="timeline-status"><span style="width:` + percentage + `"></span>`;
                    },
                };
            },
            visItems() {
                let items = this.items.map((item) => {
                    let title = item.title;
                    let $tooltip = document.getElementsByClassName('tooltip' + item.id)[0];
                    if ($tooltip) {
                        title = $tooltip.innerHTML;
                    }

                    item.title = title;

                    if (item.start) {
                        item.start.setHours(this.startHour);
                        item.start.setMinutes(this.startMinute);
                        item.start.setSeconds(this.startSecond);
                    }

                    if (item.end) {
                        item.end.setHours(this.endHour);
                        item.end.setMinutes(this.endMinute);
                        item.end.setSeconds(this.endSecond);
                    }

                    return item;
                });

                return items;
            },
        },
        updated() {
            this.$nextTick(() => {
                this.refreshTimeline();
            });
        },
        watch: {
            items() {
                this.$nextTick(() => {
                    this.refreshTimeline();
                });
            },
        },
        mounted() {
            this.$nextTick(() => {
                this.refreshTimeline();
            });
        },
    };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss">
    @import '../../../css/_variables';
    @import '../../../css/_mixins';
    @import '../../../../node_modules/vis/dist/vis-timeline-graph2d.min.css';
    @import '../../../css/box';
    @import '../../../css/box-task';

    .vis-timeline,
    .vis-panel.vis-bottom,
    .vis-panel.vis-center,
    .vis-panel.vis-left,
    .vis-panel.vis-right,
    .vis-panel.vis-top,
    .vis-labelset .vis-label,
    .vis-foreground .vis-group,
    .vis-time-axis .vis-grid.vis-minor,
    .vis-time-axis .vis-grid.vis-major {
        border-color: $darkColor;
    }

    .vis-timeline {
        overflow: visible;
    }

    .vis-panel {
        .vis-labelset {
            .vis-label {
                display: table;

                .vis-inner {
                    display: table-cell;
                    vertical-align: middle;
                    padding: 15px;
                    color: $lighterColor;
                    text-transform: uppercase;
                    letter-spacing: 0.1em;
                }
            }
        }
    }

    .vis-item {
        position: absolute;
        color: $lighterColor;
        border: none;
        background-color: $middleColor;
        overflow: hidden;

        .vis-item-content {
            text-transform: uppercase;
            letter-spacing: 0.1em;
            position: relative;
            padding: 15px;
        }

        .vis-item-visible-frame {
            position: absolute;
            bottom: 5px;
            height: 10px;
            width: 100%;
            min-width: 100px;
            padding: 0 15px;

            .timeline-status {
                display: block;
                @include border-radius(10px);
                background-color: $darkColor;
                width: 100%;
                left: 15px;

                span {
                    display: block;
                    height: 10px;
                    @include border-radius(10px);
                    background-color: $secondColor;
                }
            }
        }

        &.vis-range {
            border: none;
            @include border-radius(0);

            .vis-item-content {
                font-size: 12px;
                padding: 20px 15px;
            }
        }

        &.vis-box {
            border: none;
            @include border-radius(50px);

            .vis-item-content {
                font-size: 10px;
                padding: 10px 15px;
            }

            &.reached {
                color: $whiteColor;
                background-color: $secondColor;
            }

            &.on-hold {
                color: darken($warningColor, 30%);
                background-color: $warningColor;
            }

            &.overdue {
                background-color: $dangerColor;
            }
        }
        &.key-milestone {
            background-color: $dangerColor;
        }
        &.vis-dot {
            border: none;
            width: 12px;
            height: 12px;
            @include border-radius(0);
            @include rotate(-45);

            &.reached {
                background-color: $secondColor;
            }

            &.on-hold {
                background-color: $warningColor;
            }

            &.overdue {
                background-color: $dangerColor;
            }
        }

        &.vis-line {
            border: none;
            width: 2px;
            background-color: $middleColor;

            &.reached {
                background-color: $secondColor;
            }

            &.on-hold {
                background-color: $warningColor;
            }

            &.overdue {
                background-color: $dangerColor;
            }
        }
    }

    div.vis-tooltip {
        padding: 0;
        white-space: nowrap;
        font-family: Poppins;
        color: $lighterColor;
        background-color: transparent;
        @include border-radius(0);
        border: none;
        @include box-shadow(0, 0, 20px, rgba($blackColor, 0.75));
        pointer-events: auto;
        display: inline-block;

        .box {
            margin: 0;

            .user-info {
                font-size: 10px;
            }
        }

        .status {
            margin-top: 20px;
        }
    }

    .vis-time-axis {
        .vis-text {
            color: $lightColor;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 10px;

            &.vis-major {
                font-size: 12px;
            }
        }
    }
</style>
