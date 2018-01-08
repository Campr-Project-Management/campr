<template>
    <div id="vis"></div>
</template>

<script>
import vis from 'vis';
import VisTimelineTooltip from './VisTimelineTooltip';

export default {
    props: ['pmData', 'withPhases'],
    components: {
        vis,
        VisTimelineTooltip,
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
            items: [
                {
                    id: 0,
                    group: 0,
                    content: 'Phase 1',
                    value: '100',
                    start: new Date(2017, 0, 1),
                    end: new Date(2017, 1, 28),
                    title: `<div class="task-box box">
                                <div class="box-header">
                                    <div class="user-info flex flex-v-center">
                                        <img class="user-avatar" src="http://dev.campr.biz/uploads/avatars/60.jpg" alt="Phase responsable: Sandy Fanning-Choi"/>
                                        <p class="caps">Sandy Fanning-Choi</p>
                                    </div>
                                    <h2 class="simple-link">Phase 1</h2>
                                    <p class="task-id">#1</p>
                                </div>
                                <div class="content">
                                    <table class="table table-small">
                                        <thead>
                                            <tr>
                                                <th>Schedule</th>
                                                <th>Start</th>
                                                <th>Finish</th>
                                                <th>Duration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Base</td>
                                                <td>01.01.2017</td>
                                                <td>28.02.2017</td>
                                                <td>59</td>
                                            </tr>
                                            <tr>
                                                <td>Forecast</td>
                                                <td>01.01.2017</td>
                                                <td>28.02.2017</td>
                                                <td>59</td>
                                            </tr>
                                            <tr>
                                                <td>Actual</td>
                                                <td>01.01.2017</td>
                                                <td>28.02.2017</td>
                                                <td>59</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="status">
                                    <p><span>Status:</span> Finished</p>
                                    <bar-chart position="right" :percentage="85" :color="Green" v-bind:title-right="green"></bar-chart>
                                </div>
                            </div>`,
                },
                {
                    id: 8,
                    group: 1,
                    content: 'Milestone 1',
                    start: new Date(2017, 1, 15),
                    className: 'reached',
                    title: `<div class="task-box box">
                                <div class="box-header">
                                    <div class="user-info flex flex-v-center">
                                        <img class="user-avatar" src="http://dev.campr.biz/uploads/avatars/60.jpg" alt="Phase responsable: Sandy Fanning-Choi"/>
                                        <p class="caps">Sandy Fanning-Choi</p>
                                    </div>
                                    <h2 class="simple-link">Phase 1</h2>
                                    <p class="task-id">#1</p>
                                </div>
                                <div class="content">
                                    <table class="table table-small">
                                        <thead>
                                            <tr>
                                                <th>Schedule</th>
                                                <th>Due Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Base</td>
                                                <td>15.02.2017</td>
                                            </tr>
                                            <tr>
                                                <td>Forecast</td>
                                                <td>15.02.2017</td>
                                            </tr>
                                            <tr>
                                                <td>Actual</td>
                                                <td>15.02.2017</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="status">
                                    <p><span>Status:</span> Reached</p>
                                    <bar-chart position="right" :percentage="85" :color="Green" v-bind:title-right="green"></bar-chart>
                                </div>
                            </div>`,
                },
            ],
            container: '',
            timeline: null,
        };
    },
    computed: {
        visOptions: function() {
            let min = new Date();
            let max = new Date(0);
            if (this.pmData) {
                this.pmData.map((item) => {
                    if (min > item.start) {
                        min = new Date(item.start);
                    }
                    if (max < item.start) {
                        max = new Date(item.start);
                    }
                    if (max < item.end) {
                        max = new Date(item.end);
                    }
                });
            }
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
                hiddenDates: [
                    {start: '2018-01-06 00:00:00', end: '2018-01-08 00:00:00', repeat: 'weekly'},
                    {start: '2018-01-03 18:00:00', end: '2018-01-04 08:00:00', repeat: 'daily'},
                ],
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
    },
    watch: {
        pmData: function() {
            if (this.timeline) {
                this.timeline.destroy();
            }
            this.timeline = new vis.Timeline(this.container, this.pmData, this.groups, this.visOptions);
        },
    },
    mounted() {
        this.container = document.getElementById('vis');
        this.timeline = new vis.Timeline(this.container, this.pmData, this.groups, this.visOptions);
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
                color: darken($warningColor,30%);
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
        @include box-shadow(0, 0, 30px, $darkColor);
        pointer-events: auto;
        width: 420px;

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
