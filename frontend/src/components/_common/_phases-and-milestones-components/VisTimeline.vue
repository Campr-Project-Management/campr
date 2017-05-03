<template>
    <div id="vis"></div>
</template>

<script>
import vis from 'vis';
import VisTimelineTooltip from './VisTimelineTooltip';

export default {
    components: {
        vis,
        VisTimelineTooltip,
    },
    data: function() {
        return {
            groups: [
                {id: 0, content: 'Phases', value: 1},
                {id: 1, content: 'Milestones', value: 2},
            ],

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
                    id: 1,
                    group: 0,
                    content: 'Phase 2',
                    value: '82',
                    start: new Date(2017, 2, 1),
                    end: new Date(2017, 3, 15),
                },
                {
                    id: 2,
                    group: 0,
                    content: 'Phase 3',
                    value: '0',
                    start: new Date(2017, 3, 16),
                    end: new Date(2017, 4, 25),
                },
                {
                    id: 3,
                    group: 0,
                    content: 'Phase 4',
                    value: '0',
                    start: new Date(2017, 4, 26),
                    end: new Date(2017, 6, 25),
                },
                {
                    id: 4,
                    group: 0,
                    content: 'Phase 5',
                    value: '0',
                    start: new Date(2017, 6, 26),
                    end: new Date(2017, 8, 10),
                },
                {
                    id: 5,
                    group: 0,
                    content: 'Phase 6',
                    value: '0',
                    start: new Date(2017, 8, 1),
                    end: new Date(2017, 9, 31),
                },
                {
                    id: 6,
                    group: 0,
                    content: 'Phase 7',
                    value: '0',
                    start: new Date(2017, 10, 1),
                    end: new Date(2017, 10, 18),
                },
                {
                    id: 7,
                    group: 0,
                    content: 'Phase 8',
                    value: '0',
                    start: new Date(2017, 10, 19),
                    end: new Date(2017, 11, 20),
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
                {
                    id: 13,
                    group: 1,
                    content: 'Milestone 6',
                    start: new Date(2017, 2, 1),
                    className: 'reached',
                },
                {
                    id: 14,
                    group: 1,
                    content: 'Milestone 6',
                    start: new Date(2017, 3, 17),
                    className: 'ok',
                },
                {
                    id: 9,
                    group: 1,
                    content: 'Milestone 2',
                    start: new Date(2017, 3, 22),
                    className: 'overdue',
                },
                {
                    id: 10,
                    group: 1,
                    content: 'Milestone 3',
                    start: new Date(2017, 5, 10),
                    className: 'on-hold',
                },
                {
                    id: 11,
                    group: 1,
                    content: 'Milestone 4',
                    start: new Date(2017, 6, 30),
                    className: 'ok',
                },
                {
                    id: 12,
                    group: 1,
                    content: 'Milestone 5',
                    start: new Date(2017, 7, 10),
                    className: 'ok',
                },
            ],

            options: {
                width: '100%',
                horizontalScroll: true,
                margin: {
                    item: {
                        horizontal: 0,
                        vertical: 5,
                    },
                },
                min: '2017, 1, 1',
                max: '2017, 12, 20',
                zoomMax: 3153600000000,
                zoomMin: 315360000,
                order: function(a, b) {
                    return a.id - b.id;
                },
                tooltip: {
                    followMouse: true,
                    overflowMethod: 'cap',
                },
                visibleFrameTemplate: function(item) {
                    if (item.visibleFrameTemplate) {
                        return item.visibleFrameTemplate;
                    }
                    let percentage = item.value + '%';
                    return `<span class="timeline-status"><span style="width:` + percentage + `"></span>`;
                },
            },
            container: '',
            timeline: null,
        };
    },
    mounted() {
        this.container = document.getElementById('vis');
        this.timeline = new vis.Timeline(this.container, this.items, this.groups, this.options);
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
