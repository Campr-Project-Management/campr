<template>
    <div class="page-section position-relative">
        <div class="header flex flex-space-between">
            <h1>{{ translateText('message.gantt_chart') }}</h1>
        </div>

        <div class="right-sided">
            <!--<input-->
                <!--id="importXmlFile"-->
                <!--type="file"-->
                <!--name="importXmlFile"-->
                <!--style="display: none;"-->
                <!--v-on:change="uploadImportTaskFile" />-->

            <!--<a class="btn-rounded btn-auto btn-empty flex" v-on:click="openFileSelection">-->
                <!--<span>{{ translateText('message.import_tasks') }}</span>-->
                <!--<upload-icon></upload-icon>-->
            <!--</a>-->

            <!--<a v-if="project && project.id" :href="exportProjectURL" class="btn-rounded btn-auto btn-empty flex" id="export_project_button">-->
                <!--{{ translateText('message.export_project') }}-->
            <!--</a>-->

            <input
                type="button"
                id="toggle_minimap"
                value="Toggle minimap"
                class="btn-rounded btn-auto btn-empty flex"
                v-on:click="showMinimap = !showMinimap">
        </div>

        <ul class="gantt-tabs">
            <li role="presentation" v-for="(label, availableDate) in availableDates" :class="{active: availableDate === currentDate}">
                <button v-on:click="setCurrentDate(availableDate)">{{ translateText(label) }}</button>
            </li>
        </ul>
        <div ref="gantt_chart" id="gantt_chart" class="gantt-chart">
            <p>Loading...</p>
        </div>

        <svg id="gantt_map" :class={hidden:!showMinimap}></svg>

        <alert-modal v-if="showSaved" @close="showSaved = false" body="message.saved" />
        <alert-modal v-if="showFailed" @close="showFailed = false" body="message.unable_to_save" />
    </div>
</template>

<script>
import 'dhtmlx-gantt';
import AlertModal from '../_common/AlertModal';
import UploadIcon from '../_common/_icons/UploadIcon';
import {mapActions, mapGetters} from 'vuex';
import router from '../../router';
import * as d3 from 'd3';
import $ from 'jquery';
import moment from 'moment';

const MONTH_IN_MILISECONDS = 2592000000;

export default {
    components: {
        AlertModal,
        UploadIcon,
    },
    name: 'gantt',
    created() {
        if (!this.project) {
            this.getProjectById(this.$route.params.id);
        }
        this.getGanttData(this.$route.params.id);
    },
    beforeDestroy() {
        this.removeAllEvents();
    },
    methods: {
        ...mapActions(['getGanttData', 'getProjectById', 'importTask']),

        openFileSelection: function() {
            $('#importXmlFile').click();
        },
        uploadImportTaskFile: function(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length) {
                return;
            }
            let formData = new FormData();
            formData.append('file', files[0]);

            this
                .importTask({
                    data: formData,
                    projectId: this.$route.params.id,
                })
                .then(
                    (response) => {
                        if (response.body && response.body.error && response.body.messages) {
                            this.showFailed = true;
                        } else {
                            this.showSaved = true;

                            this.getGanttData(this.$route.params.id);
                        }
                    },
                    () => {
                        this.showFailed = true;
                    }
                )
            ;
        },
        translateText: function(text) {
            return this.translate(text);
        },
        removeAllEvents: function() {
            let event = null;

            while (event = this.ganttEvents.pop()) {
                gantt.detachEvent(event);
            }
        },
        initGanttMap: function() {
            const $ganttDataArea = $('.gantt_data_area');
            const $ganttTask = $('.gantt_task');
            const $ganttTaskBG = $('.gantt_task_bg');

            const ratio = $ganttTask.width() / $ganttTask.height();
            let svgWidth = 200;
            let svgHeight = Math.ceil(svgWidth / ratio);
            const rectWidth = svgWidth * 0.2;
            const rectHeight = svgHeight * 0.2;

            if (svgHeight < 120) {
                svgHeight = 120;
                svgWidth = Math.ceil(svgHeight * ratio);
            }

            this.svg = d3.select('#gantt_map');

            this.svg.selectAll('g').remove();

            this.preview = this
                .svg
                .append('g')
                .attr('id', 'preview')
                .attr('transform', `scale(${svgWidth / $ganttTaskBG.width()} ${svgHeight / $ganttTaskBG.height()})`)
            ;

            this.g = this.svg.append('g');

            this.svg
                .attr('width', svgWidth)
                .attr('height', svgHeight)
            ;

            this.rect = this.g
                .append('rect')
                .attr('fill', 'transparent')
                .attr('stroke', '#000000')
                .attr('stroke-width', 1)
                .attr('width', rectWidth)
                .attr('height', rectHeight)
            ;

            let chartData = $('.gantt_bars_area [task_id]')
                .map((_, item) => {
                    const $item = $(item);

                    return {
                        width: $item.width(),
                        height: $item.height(),
                        x: $item.css('left').replace(/(\d+)(\w*)/gi, '$1'),
                        y: $item.css('top').replace(/(\d+)(\w*)/gi, '$1'),
                    };
                })
            ;

            this
                .preview
                .selectAll('rect')
                .data(chartData)
                .enter()
                    .append('rect')
                    .attr('fill', 'green')
                    .attr('width', d => d.width)
                    .attr('height', d => d.height)
                    .attr('x', d => d.x)
                    .attr('y', d => d.y)
                .exit()
            ;

            let that = this;

            this
                .rect
                .call(
                    d3
                        .drag()
                        .on('start', function(d) {
                            that.dragScroll = true;

                            let node = d3.select(this);
                            node
                                .attr('original-x', node.attr('x'))
                                .attr('original-y', node.attr('y'))
                                .raise()
                                .classed('active', true)
                            ;
                        })
                        .on('drag', function(d) {
                            const node = d3.select(this);

                            let originalX = parseInt(node.attr('original-x'), 10);
                            let originalY = parseInt(node.attr('original-y'), 10);

                            if (isNaN(originalX)) {
                                originalX = 0;
                            }
                            if (isNaN(originalY)) {
                                originalY = 0;
                            }

                            const offsetX = originalX - d3.event.subject.x;
                            const offsetY = originalY - d3.event.subject.y;

                            let x = d3.event.x + offsetX;
                            let y = d3.event.y + offsetY;

                            if (x < 0) {
                                x = 0;
                            }

                            if (y < 0) {
                                y = 0;
                            }

                            if (x > (svgWidth - rectWidth)) {
                                x = svgWidth - rectWidth;
                            }

                            if (y > (svgHeight - rectHeight)) {
                                y = svgHeight - rectHeight;
                            }

                            node
                                .attr('x', x)
                                .attr('y', y)
                            ;

                            // there's some border or padding here
                            let scrollableAreaY = $('.gantt_ver_scroll > div').height() - $ganttDataArea.height() - 2;
                            let scrollableAreaX = $ganttDataArea.width() - $('.gantt_task').width();

                            let scrollX = (scrollableAreaX / 100) * ((x / svgWidth) * 100) / 8 * 10;
                            let scrollY = (scrollableAreaY / 100) * ((y / svgHeight) * 100) / 8 * 10;
                            gantt.scrollTo(scrollX, scrollY);
                        })
                        .on('end', function(d) {
                            that.dragScroll = false;

                            d3
                                .select(this)
                                .attr('original-x', null)
                                .attr('original-y', null)
                                .classed('active', false)
                            ;
                        })
                )
            ;
        },
        setCurrentDate(value) {
            this.currentDate = value;

            // config
            const startDate = new Date(this.ganttStartDate);
            const endDate = new Date();
            const realEndDate = new Date(this.ganttEndDate);
            const now = new Date();

            if (realEndDate.getTime() > now.getTime()) {
                endDate.setTime(realEndDate.getTime() + MONTH_IN_MILISECONDS);
            } else {
                endDate.setTime(now.getTime() + MONTH_IN_MILISECONDS);
            }

            startDate.setTime(startDate.getTime() - MONTH_IN_MILISECONDS);

            gantt.config.start_date = startDate;
            gantt.config.end_date = endDate;

            gantt.parse({
                data: this.ganttDataFormatted,
                links: this.ganttDataLinks,
            });

            this.initGanttMap();
        },
    },
    mounted() {
        gantt.config.auto_scheduling_descendant_links = false;
        gantt.config.columns = [
            {name: 'text', tree: true, width: '*', resize: true},
            {
                name: 'start_date',
                align: 'center',
                resize: true,
                width: 90,
                template: (obj) => {
                    if (obj.unscheduled) {
                        return '';
                    }

                    return this.$formatDate(obj.start_date);
                },
            },
            {
                name: 'duration',
                align: 'center',
                width: 70,
                template: (obj) => {
                    if (obj.unscheduled) {
                        return '';
                    }

                    return this.$formatNumber(obj.duration);
                },
            },
        ];
        gantt.config.drag_links = false;
        gantt.config.show_unscheduled = true;
        gantt.config.scale_unit = 'day';
        gantt.config.date_scale = '%j %M %y';
        gantt.init(this.$refs.gantt_chart);
    },
    watch: {
        ganttData(value) {
            if (!value || !value.length) {
                return;
            }

            // config
            const startDate = new Date(this.ganttStartDate);
            const endDate = new Date();
            const realEndDate = new Date(this.ganttEndDate);
            const now = new Date();

            if (realEndDate.getTime() > now.getTime()) {
                endDate.setTime(realEndDate.getTime() + MONTH_IN_MILISECONDS);
            } else {
                endDate.setTime(now.getTime() + MONTH_IN_MILISECONDS);
            }

            startDate.setTime(startDate.getTime() - MONTH_IN_MILISECONDS);

            gantt.config.start_date = startDate;
            gantt.config.end_date = endDate;

            // init!
            gantt.init(this.$refs.gantt_chart);
            gantt.parse({
                data: this.ganttDataFormatted,
                links: this.ganttDataLinks,
            });

            this.removeAllEvents();
            // disable link double clicks
            this.ganttEvents.push(
                gantt.attachEvent('onLinkDblClick', (id, e) => false),
            );

            this.ganttEvents.push(
                gantt.attachEvent('onTaskClosed', (id) => {
                    this.initGanttMap();
                })
            );

            this.ganttEvents.push(
                gantt.attachEvent('onTaskOpened', (id) => {
                    this.initGanttMap();
                })
            );

            // instead of opening default edit, confirm if user wants to go to the edit page
            this.ganttEvents.push(
                gantt.attachEvent('onTaskDblClick', (id) => {
                    id = parseInt(id, 10);
                    const task = this.ganttData.filter(item => item.id === id)[0];

                    router.push({
                        name: 'project-task-management-view',
                        params: {
                            'projectId': task.name,
                            'taskId': id,
                        },
                    });

                    return false;
                }),
            );

            this.ganttEvents.push(
                gantt.attachEvent('onBeforeTaskChanged', (id, mode, oldEvent) => {
                    const task = gantt.getTask(id);
                    const progress = parseInt(task.progress * 100, 10);
                    const convert = gantt.date.date_to_str('%d-%m-%Y');
                    const params = {progress};

                    params[this.currentDate + 'StartAt'] = convert(task.start_date);
                    params[this.currentDate + 'FinishAt'] = convert(task.end_date);

                    this.$http.patch(
                        Routing.generate('app_api_workpackage_edit', {id}),
                        params,
                    ).then(
                        (response) => {
                            gantt.message(this.translate('message.saved'));
                            let {x, y} = gantt.getScrollState();

                            this.getGanttData(this.$route.params.id).then(
                                () => {
                                    gantt.scrollTo(x, y);
                                },
                                () => {
                                    gantt.scrollTo(x, y);
                                },
                            );
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
                            let {x, y} = gantt.getScrollState();

                            this.getGanttData(this.$route.params.id).then(
                                () => {
                                    gantt.scrollTo(x, y);
                                },
                                () => {
                                    gantt.scrollTo(x, y);
                                },
                            );
                        },
                    );

                    return true;
                }),
            );

            this.ganttEvents.push(
                gantt.attachEvent('onGanttScroll', (left, top) => {
                    if (this.dragScroll) {
                        return;
                    }

                    let svgWidth = parseInt(this.svg.attr('width'), 10);
                    let svgHeight = parseInt(this.svg.attr('height'), 10);

                    // there's some border or padding here
                    let scrollableAreaY = $('.gantt_ver_scroll > div').height() - $('.gantt_data_area').height() - 2;
                    let scrollableAreaX = $('.gantt_data_area').width() - $('.gantt_task').width();

                    let {x, y} = gantt.getScrollState();

                    this.rect.attr('x', (d) => {
                        return (svgWidth * 0.8 / 100)
                            * (x / scrollableAreaX * 100);
                    }).attr('y', (d) => {
                        return (svgHeight * 0.8 / 100)
                            * (y / scrollableAreaY * 100);
                    });
                }),
            );

            this.initGanttMap();
        },
    },
    computed: {
        ...mapGetters(['ganttData', 'localUser', 'project']),
        exportProjectURL() {
            return Routing.generate('main_project_xml', {id: this.project.id});
        },
        ganttStartDate() {
            const key = this.currentDate + 'StartAt';
            const data = this
                .ganttData
                .map(item => item[key] || item.createdAt)
                .filter(item => item !== null)
            ;

            return _.min(data);
        },
        ganttEndDate() {
            const key = this.currentDate + 'FinishAt';
            const data = this
                .ganttData
                .map(item => item[key])
                .filter(item => item !== null)
            ;

            return _.max(data);
        },
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
                    let task = {
                        id: item.id,
                        text: item.name,
                        progress: item.progress
                            ? item.progress / 100
                            : 0,
                        open: true,
                        type: ganttType2WorkPackageType[item.type],
                        unscheduled: true,
                        start_date: '',
                        duration: '',
                    };

                    let start = item[this.currentDate + 'StartAt'];
                    let duration = item[this.currentDate + 'DurationDays'];

                    if (start) {
                        task.start_date = moment(start).format('DD-MM-YYYY');
                        task.duration = duration;
                        task.unscheduled = false;
                    }

                    switch (item.type) {
                    case 0: // phase
                        task.parent = item.parent;
                        break;
                    case 1: // milestone
                        task.parent = item.parent || item.phase;
                        break;
                    case 2: // task
                        task.parent = item.parent || item.milestone || item.phase;
                        break;
                    }

                    if (!task.parent) {
                        delete task.parent;
                    }
                    return task;
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
    data() {
        return {
            currentDate: 'scheduled',
            availableDates: {
                scheduled: 'label.base',
                forecast: 'label.forecast',
                actual: 'label.actual',
            },
            showSaved: false,
            showFailed: false,
            showMinimap: true,
            ganttEvents: [],
            svg: null,
            g: null,
            preview: null,
            rect: null,
            dragScroll: false,
        };
    },
};
</script>

<style scoped="scoped" lang="scss">
    @import "~dhtmlx-gantt/codebase/dhtmlxgantt.css";
</style>

<style lang="scss">
    @import '../../css/_variables';
    @import '../../css/_mixins';

    .gantt-tabs {
        margin-bottom: 0px !important;

        li {
            border-top: 1px solid #646EA0;
            border-left: 1px solid #646EA0;
            display: inline-block;
            padding: 0.5em;

            &:last-child {
                border-right: 1px solid #646EA0;
            }

            button {
                background: transparent;
                border: none;
                outline: none;
                text-transform: uppercase;
            }

            &.active {
                button {
                    color: #5FC3A5;
                }
            }
        }
    }

    .position-relative {
        position: relative;
    }

    .right-sided {
        top: 22px;
        right: 10px;
        position: absolute;

        & .btn-rounded {
            display: inline-block !important;
        }
    }

    .hidden {
        display: none;
    }

    #gantt_map {
        position: absolute;
        bottom: 40px;
        right: 20px;
        background: #232D4B;
        border: 2px solid #000000;
    }

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
