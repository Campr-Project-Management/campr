<template>
    <div class="page-section">
        <div class="header flex flex-space-between">
            <h1>{{ translateText('message.gantt_chart') }}</h1>
        </div>       
        <div ref="gantt_chart" id="gantt_chart" class="gantt-chart">
            <p>Loading...</p>
        </div>
    </div>
</template>

<script>
import 'dhtmlx-gantt';
import {mapActions, mapGetters} from 'vuex';
import router from '../../router';

const DAY_IN_MILISECONDS = 86400000;

export default {
    name: 'gantt',
    created() {
        this.getGanttData(this.$route.params.id);
    },
    methods: {
        ...mapActions(['getGanttData']),

        translateText: function(text) {
            return this.translate(text);
        },
    },
    mounted() {
        gantt.init(this.$refs.gantt_chart);
    },
    watch: {
        ganttData(value) {
            if (value && value.length) {
                // config
                gantt.config.auto_scheduling_descendant_links = false;
                gantt.config.columns = [
                    {name: 'text', tree: true, width: '*', resize: true},
                    {name: 'start_date', align: 'center', resize: true, width: 90},
                    {name: 'duration', align: 'center', width: 70},
                ];
                gantt.config.drag_links = false;
                gantt.config.show_unscheduled = true;

                // init!
                gantt.init(this.$refs.gantt_chart);
                gantt.parse({
                    data: this.ganttDataFormatted,
                    links: this.ganttDataLinks,
                });

                // disable link double clicks
                gantt.attachEvent('onLinkDblClick', (id, e) => false);

                // instead of opening default edit, confirm if user wants to go to the edit page
                gantt.attachEvent('onTaskDblClick', (id, e) => {
                    id = parseInt(id, 10);
                    const task = this.ganttData.filter(item => item.id === id)[0];
                    const types = [
                        'phase',
                        'milestone',
                        'task',
                    ];

                    gantt.confirm(this.translate('message.edit_' + types[task.type]) + ' <strong>' + task.name + '</strong>?', answer => {
                        if (answer) {
                            router.push({
                                name: 'project-task-management-edit',
                                params: {
                                    'projectId': task.name,
                                    'taskId': id,
                                },
                            });
                        }
                    });
                    return false;
                });

                gantt.attachEvent('onBeforeTaskChanged', (id, mode, oldEvent) => {
                    const task = gantt.getTask(id);
                    const progress = parseInt(task.progress * 100, 10);
                    const convert = gantt.date.date_to_str('%d-%m-%Y');
                    const params = {
                        progress,
                        scheduledStartAt: convert(task.start_date),
                        scheduledFinishAt: convert(task.end_date),
                    };

                    this
                        .$http
                        .patch(
                            Routing.generate('app_api_workpackage_edit', {id}),
                            params
                        )
                        .then(
                            (response) => {
                                gantt.message(this.translate('message.saved'));
                            },
                            (response) => {
                                if (response.status === 202) {
                                    gantt.message(this.translate('message.saved'));
                                } else {
                                    task.progress = oldEvent.progress;
                                    gantt.updateTask(id);
                                    gantt.message({
                                        'type': 'error',
                                        'message': this.translate('message.unable_to_save'),
                                    });
                                }
                            }
                        )
                    ;

                    return true;
                });
            }
        },
    },
    computed: {
        ...mapGetters(['ganttData']),
        ganttDataLinks() {
            // {"id":"10","source":"11","target":"12","type":"1"}
            return this
                .ganttData
                .filter(item => item.parent || item.milestone || item.phase)
                .sort((a, b) => {
                    return a.id === b.id
                        ? 0
                        : (a.id < b.id ? -1 : 1);
                })
                .map((item, key) => {
                    return {
                        id: key,
                        source: item.parent || item.milestone || item.phase,
                        target: item.id,
                        type: 0,
                    };
                })
            ;
        },
        ganttDataFormatted() {
            const ganttType2WorkPackageType = [
                gantt.config.types.milestone,
                gantt.config.types.milestone,
                gantt.config.types.task,
            ];
            return this
                .ganttData
                .map(item => {
                    let out = {
                        id: item.id,
                        text: item.name,
                        progress: item.progress
                            ? item.progress / 100
                            : 0,
                        open: true,
                        type: ganttType2WorkPackageType[item.type],
                    };

                    let start = item.scheduledStartAt || item.forecastStartAt || item.actualStartAt;
                    let end = item.scheduledFinishAt || item.forecastFinishAt || item.actualFinishAt;

                    if (start && end) {
                        let dtStart = new Date(start);
                        let dtEnd = new Date(end);
                        out.start_date = start.split('-').reverse().join('-');
                        out.duration = (dtEnd.getTime() - dtStart.getTime()) / DAY_IN_MILISECONDS;
                    } else if (item.createdAt && item.createdAt.match(/^(\d{4}-\d{2}-\d{2})/)) {
                        out.start_date = item
                            .createdAt
                            .match(/^(\d{4}-\d{2}-\d{2})/)[0]
                            .split('-')
                            .reverse()
                            .join('-')
                        ;
                        out.duration = 1;
                    } else {
                        out.unscheduled = true;
                    }

                    switch (item.type) {
                    case 0: // phase
                        out.parent = item.parent;
                        break;
                    case 1: // milestone
                        out.parent = item.parent || item.phase;
                        break;
                    case 2: // task
                        out.parent = item.parent || item.milestone || item.phase;
                        break;
                    }

                    if (!out.parent) {
                        delete out.parent;
                    }
                    return out;
                })
                // this has to be sorted so gantt doesn't cry
                .sort((a, b) => {
                    return a.id === b.id
                        ? 0
                        : (a.id < b.id ? -1 : 1);
                })
            ;
        },
    },
};
</script>

<style scoped="scoped" lang="scss">
    @import "~dhtmlx-gantt/codebase/dhtmlxgantt.css";
</style>

<style lang="scss">
    @import '../../css/_variables';
    @import '../../css/_mixins';

    .gantt_add {
        display: none !important;
    }

    .gantt-chart {
        width: 100%;
        height: 100%;
        min-height: 600px;
    }

    .gantt_container {
        font-family: Poppins;
        font-size: 10px;
        border: 1px solid $middleColor;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .gantt_grid {
        border-right: 1px solid $middleColor;
    }

    .gantt_grid_scale,
    .gantt_task_scale {
        color: $lightColor;
        font-size: 10px;
        border-bottom: 1px solid $darkColor;
        background-color: $darkColor;
    }

    .gantt_grid_scale .gantt_grid_head_cell {
        color: $lightColor;
    }

    .gantt_row,
    .gantt_task_row,
    .gantt_row.odd,
    .gantt_task_row.odd {
        border-bottom: 1px solid $mainColor;
        background-color: $mainColor;
    }

    .gantt_task .gantt_task_scale .gantt_scale_cell {
        color: $lightColor;
        border-right: 1px solid $darkColor;
    }

    .gantt_task_line {
        @include border-radius(3px);
        background-color: $secondColor;
        border: 1px solid $mainColor;
    }

    .gantt_task_progress {
        background: $secondDarkColor;
        @include border-radii(3px, 0, 0, 3px)
    }

    .gantt_task_content {
        font-size: 10px;
        font-weight: 500;
    } 

    .gantt_task_cell {
        border-right: 1px solid $mainColor;
    }   

    .gantt_grid_data .gantt_cell {
        color: $lighterColor;
        cursor: pointer;
    }

    .gantt_cell {
        font-size: 10px;
        font-weight: 500;
    } 

    .gantt_grid_data .gantt_row.odd:hover, 
    .gantt_grid_data .gantt_row:hover {
        background-color: $darkColor;
    }

    .gantt_grid_data .gantt_row.gantt_selected, 
    .gantt_grid_data .gantt_row.odd.gantt_selected, 
    .gantt_task_row.gantt_selected {
        background-color: $darkColor;
    }  

    .gantt_task_row.gantt_selected .gantt_task_cell {
        border-right-color: $darkColor;
    } 

    .gantt_line_wrapper div {
        background-color: $lightColor;
    }

    .gantt_link_arrow_right {
        border-color: $lightColor;
    }

    .gantt_task_line.gantt_selected {
        @include box-shadow(0, 0, 7px, $darkerColor);
    }

    .gantt_tree_icon.gantt_close{
        &:before {
            content: "-";
        }
    }

    .gantt_tree_icon.gantt_open{
        &:before {
            content: "+";
        }
    }

    .gantt_tree_icon.gantt_close,
    .gantt_tree_icon.gantt_open {
        background: none;
        width: 15px;

        &:before {
            width: 15px;
            height: 15px;
            border: 1px solid $lightColor;
            color: $lightColor;
            display: inline-block;
            line-height: 14px;
            text-align: center;
        }
    }
</style>
