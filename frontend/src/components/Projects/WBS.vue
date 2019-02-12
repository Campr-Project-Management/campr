<template>
    <div>
        <div id="tree_container"></div>
        <alert-modal v-if="showSaveFailed" @close="showSaveFailed = false" :body="errorMessage" header="message.error"/>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import * as d3 from 'd3';
import $ from 'jquery';
import _ from 'lodash';
import AlertModal from '../_common/AlertModal.vue';
import builderFactory from './WBS/WBSBuilder';

export default {
    components: {
        AlertModal,
    },
    computed: {
        ...mapGetters([
            'wbs',
            'project',
            'trafficLightColorByValue',
            'trafficLightLabelByValue',
            'trafficLights',
        ]),
    },
    methods: {
        ...mapActions([
            'getProjectById',
            'getWBSByProjectID',
            'setWorkPackageProgress',
            'patchTask',
            'editProject',
        ]),
        getTrafficLightColor(wp) {
            return this.trafficLightColorByValue(wp.trafficLight);
        },
        getContextMenu(node) {
            if (!node.data.isTask && !node.data.isRoot) {
                return [];
            }

            let menu = [];
            if (node.data.isTask) {
                menu.push({
                    title: this.translate('message.task_progress'),
                });

                this.progressValues.forEach((value) => {
                    menu.push({
                        title: () => {
                            let html = `${value}%`;
                            let klass = '';
                            if (value === node.data.progress) {
                                html = '<i class="glyphicon glyphicon-ok"></i>' + html;
                                klass = 'active';
                            }

                            return `<div class="menu-item ${klass}">${html}</div>`;
                        },
                        action: (d) => {
                            this.setProgress(d.data, value);
                        },
                    });
                });

                menu.push({
                    title: this.translate('message.task_condition'),
                });
            } else {
                menu.push({
                    title: this.translate('message.project_condition'),
                });
            }

            this.trafficLights.forEach((tl) => {
                menu.push({
                    title: () => {
                        let html = tl.getLabel();
                        let klass = '';
                        if (tl.getValue() === node.data.trafficLight) {
                            html = '<i class="glyphicon glyphicon-ok"></i>' + html;
                            klass = 'active';
                        }

                        return `<div class="menu-item ${klass}" style="color: ${tl.getColor()}">${html}</div>`;
                    },
                    action: (d) => {
                        this.setTrafficLight(d.data, tl.getValue());
                    },
                });
            });

            return menu;
        },
        setProgress(entity, progress) {
            if (!entity.isTask || entity.progress === progress) {
                return;
            }

            this
                .patchTask({
                    data: {
                        progress: progress,
                    },
                    taskId: entity.id,
                })
                .then(() => {
                    this.getWBSByProjectID(this.$route.params.id);
                })
            ;
        },
        setTrafficLight(entity, trafficLight) {
            if (entity.isTask) {
                this
                    .patchTask({
                        data: {
                            trafficLight: trafficLight,
                        },
                        taskId: entity.id,
                    })
                    .then(() => {
                        this.getWBSByProjectID(this.$route.params.id);
                    })
                ;

                return;
            }

            this
                .editProject({projectId: entity.id, trafficLight: trafficLight})
                .then(() => {
                    this.getWBSByProjectID(this.$route.params.id);
                })
            ;
        },
        updateTree(source) {
            let treeData = this.tree(this.root);

            let nodes = treeData.descendants();
            let links = treeData.descendants().slice(1);

            nodes.forEach(d => {
                d.y = d.depth * 350; // (220 + (220 / 2)) + 50;
                d.x += 50;
            });

            let builder = builderFactory();
            builder.config.entityForegroundColor = this.getTrafficLightColor;
            builder.config.svg = this.svg;
            builder.$nodes = nodes;
            builder.$root = source;

            builder
                .init()
                .appendEntityWireframe()
                .appendEntityLabel(this.updateTree)
                .appendEntityTopMenu()
                .appendStatus()
                .appendProgressLabel()
                .appendPUIDLabel()
                .appendDates()
                .addContextMenu(this.getContextMenu)
                .addLinks(links)
                .build()
            ;
        },
        initialize() {
            this.initialized = true;

            this.width = $('.page').width();
            this.height = $('.page').height() - $('.page > header').outerHeight() - 20;

            // set shit up
            this.tree = d3.tree()
                .separation(() => {
                    return .5;
                })
                .nodeSize([220, 100])
            ;
            this.root = d3.hierarchy(this.wbsData);

            this.root.x0 = this.width / 2;
            this.root.y0 = this.height / 2;
            this.svg = d3
                .select('#tree_container', d => {
                    return d.children || [];
                })
                .append('svg')
                .attr('width', this.width)
                .attr('height', this.height)
            ;

            this.g = this.svg.append('g');

            this.updateTree(this.root);

            // zoom
            const zoom = d3.zoom()
                .scaleExtent([0.1, 4])
                .on('zoom', () => {
                    if (d3.zoomDisabled) {
                        return;
                    }

                    this.g.attr('transform', d3.event.transform);
                })
            ;
            this.svg.call(zoom);
        },
    },
    created() {
        if (!this.project || !this.project.id) {
            this.getProjectById(this.$route.params.id);
        }
        this.getWBSByProjectID(this.$route.params.id);
    },
    watch: {
        wbs(value) {
            if (value) {
                this.wbsData = _.cloneDeep(value);
            }

            if (this.wbsData && this.project.id && this.wbs && !this.initialized) {
                this.initialize();
            } else if (this.initialized && this.wbsData) {
                this.g.selectAll('*').remove();

                this.root = d3.hierarchy(this.wbsData);
                this.root.x0 = this.width / 2;
                this.root.y0 = this.height / 2;

                this.updateTree(this.root);
            }
        },
        project() {
            if (this.wbsData && this.project.id && this.wbs && !this.initialized) {
                this.initialize();
            }
        },
    },
    data() {
        return {
            initialized: false,
            progressValues: [
                0,
                25,
                50,
                75,
                100,
            ],
            animationSpeed: 200,
            tree: null,
            root: null,
            svg: null,
            g: null,
            width: 640,
            height: 480,
            wbsData: null,
            i: 0,
            showSaveFailed: false,
            errorMessage: this.translate('message.error'),
        };
    },
};

</script>

<style lang="scss">
    @import '../../css/mixins';
    @import '~theme/variables';

    .link {
        fill: none;
        stroke: $lightColor;
        stroke-width: 1px;
    }

    .node {
        cursor: pointer;

        text {
            font-size: 10px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.1em;

            &.status {
                font-size: 10px !important;
                font-weight: bold;
            }
        }

        .title-body {
            min-height: initial !important;
            background-color: transparent;

            .title {
                overflow: hidden;
                text-overflow: ellipsis;
                padding: 28px 5px 5px 5px;
                color: $fgColor;
                height: 70px;
                text-align: center;
                text-transform: uppercase;
            }
        }

        .top-menu {
            color: $middleColor;
            background: transparent;
            height: auto;
            min-height: inherit;
            text-align: right;

            a {
                padding: 0 5px;
            }
        }
    }

    .d3-context-menu-theme {
        position: absolute;
        background-color: $semiDarkColor;
        color: $fgColor;
        font-size: 12px;
        min-width: 200px;
        text-transform: uppercase;

        @include box-shadow(0, 0, 20px, $darkerColor);

        ul {
            margin: 0;
            padding: 5px;

            list-style-type: none;

            li {
                padding: 0;
                margin: 0;

                .menu-item {
                    padding: 10px 10px 10px 30px;
                    margin: 0;

                    &:hover:not(.is-header) {
                        background-color: $fadeColor;
                        cursor: pointer;
                    }
                    &.active {
                        padding-left: 10px;

                        i {
                            margin-right: 7px;
                        }
                    }
                }

                &.is-header:not(.is-divider) {
                    padding: 10px 10px;
                    background-color: $bgColor;
                    color: $middleColor;
                }
                &.is-divider {
                    padding: 0;
                    hr {
                        padding: 0;
                        margin: 5px 0;
                    }
                }
            }
        }
    }
</style>
