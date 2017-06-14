<template>
    <div>
        <h1>Gantt chart</h1>
        <div ref="gantt_chart" id="gantt_chart" style="width:100%; height:100%; min-height: 600px;">
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
    },
    mounted() {
        gantt.init(this.$refs.gantt_chart);
    },
    watch: {
        ganttData(value) {
            if (value && value.length) {
                // config
                gantt.config.auto_scheduling_descendant_links = false;
//                gantt.config.buttons_left = [];
//                gantt.config.buttons_right = [];
//                gantt.config.details_on_create = false;
//                gantt.config.details_on_dblclick = false;
//                gantt.config.show_unscheduled = false; // woot?
                gantt.config.drag_links = false;
                gantt.config.show_unscheduled = true;

                // init!
                gantt.init(this.$refs.gantt_chart);
                gantt.parse({
                    data: this.ganttDataFormatted,
                    links: this.ganttDataLinks,
                });

                // this mostly helps in dev mode to prevent events from popping up twice
                gantt.detachAllEvents();

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

<style scoped="scoped">
@import "~dhtmlx-gantt/codebase/dhtmlxgantt.css";
.gantt_add {
    display: none !important;
}
</style>
