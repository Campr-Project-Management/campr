<template>
    <div>
        <div id="tree_container"></div>
        <alert-modal v-if="showSaveFailed" @close="showSaveFailed = false" :body="errorMessage" header="message.error" />
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import * as d3 from 'd3';
import $ from 'jquery';
import _ from 'lodash';
import AlertModal from '../_common/AlertModal.vue';
import router from '../../router';

export default {
    components: {
        AlertModal,
    },
    computed: {
        ...mapGetters(['wbs', 'project', 'colorStatuses']),
    },
    methods: {
        ...mapActions([
            'getProjectById',
            'getWBSByProjectID',
            'getColorStatuses',
            'setWorkPackageColorStatus',
            'setWorkPackageProgress',
        ]),
        getTaskConditionColor(wp) {
            const conditions = {
                1: '#D8DAE5',
                2: '#5FC3A5',
                3: '#C87369',
                4: '#197252',
                5: '#000000',
            };
            // d.data.colorStatusColor || '#8794c4'
            return conditions[wp.workPackageStatus] || '#8794c4';
        },
        updateTree(source) {
            let treeData = this.tree(this.root);

            let nodes = treeData.descendants();
            let links = treeData.descendants().slice(1);

            nodes.forEach(d => {
                d.y = d.depth * 350; // (220 + (220 / 2)) + 50;
                d.x += 50;
            });

            let canvasNodes = this.g
                .selectAll('g.node')
                .data(nodes, d => {
                    return d.id || (d.id = (d.data.puid || d.data.id));
                })
            ;

            let canvasNodesEnter = canvasNodes
                .enter()
                .append('g')
                .attr('class', 'node')
                .attr('transform', d => `translate(${d.y}, ${d.x})`)
            ;

            // display the box
            canvasNodesEnter
                .append('rect')
                .attr('fill', '#191E37')
                .attr('stroke', d => this.getTaskConditionColor(d.data))
                .attr('stroke-width', 1)
                .attr('width', 220)
                .attr('height', 100)
            ;

            // draw the lines
            canvasNodesEnter // side line left
                .append('line')
                .attr('stroke', d => this.getTaskConditionColor(d.data))
                .attr('stroke-width', 1)
                .attr('x1', 30)
                .attr('y1', 0)
                .attr('x2', 30)
                .attr('y2', 100)
            ;

            canvasNodesEnter // side line right
                .append('line')
                .attr('stroke', d => this.getTaskConditionColor(d.data))
                .attr('stroke-width', 1)
                .attr('x1', 220 - 30)
                .attr('y1', 0)
                .attr('x2', 220 - 30)
                .attr('y2', 100)
            ;

            canvasNodesEnter // right side splitter
                .append('line')
                .attr('stroke', d => this.getTaskConditionColor(d.data))
                .attr('stroke-width', 1)
                .attr('x1', 220 - 30)
                .attr('y1', 100 / 3)
                .attr('x2', 220)
                .attr('y2', 100 / 3)
            ;

            canvasNodesEnter // dates splitter top
                .append('line')
                .attr('stroke', d => this.getTaskConditionColor(d.data))
                .attr('stroke-width', 1)
                .attr('x1', 30)
                .attr('y1', 100 - 30)
                .attr('x2', 220 - 30)
                .attr('y2', 100 - 30)
            ;

            canvasNodesEnter // dates splitter center
                .append('line')
                .attr('stroke', d => this.getTaskConditionColor(d.data))
                .attr('stroke-width', 1)
                .attr('x1', 220 / 2)
                .attr('y1', 100 - 30)
                .attr('x2', 220 / 2)
                .attr('y2', 100)
            ;

            canvasNodesEnter // text - title
                .append('foreignObject')
                .attr('y', 0)
                .attr('x', 30)
                .attr('width', 220 - 60)
                .attr('height', 100 - 30)
                .append('xhtml:body')
                .attr('xmlns', 'http://www.w3.org/1999/xhtml')
                .attr('class', 'title-body')
                .html(d => {
                    // tables are supposed to be ugly, but god damn it they can be the prettiest thing when they work better than anything
                    return `<table>
                        <tr>
                            <td>
                                ${d.data.name}
                            </td>
                        </tr>
                    </table>`;
                })
                .on('click', d => {
                    if (d.children) {
                        d._children = d.children;
                        d.children = null;
                    } else {
                        d.children = d._children;
                        d._children = null;
                    }

                    this.updateTree(d);
                })
            ;

            canvasNodesEnter
                .append('foreignObject')
                .attr('x', 175)
                .attr('y', 0)
                .attr('width', 15)
                .attr('height', 15)
                .append('xhtml:body')
                .attr('xmlns', 'http://www.w3.org/1999/xhtml')
                .attr('class', 'node-link')
                .html(d => {
                    /*
                        {
                            path: 'phases-and-milestones/edit-milestone/:milestoneId',
                            component: MilestoneCreate,
                            name: 'project-milestones-edit-milestone',
                        },
                     */
                    let url = '#';
                    switch (d.data.type) {
                    case 0:
                        url = router
                            .resolve({
                                name: 'project-phases-edit-phase',
                                params: {
                                    id: d.data.project,
                                    phaseId: d.data.id,
                                },
                            })
                            .href
                        ;
                        break;
                    case 1:
                        url = router
                            .resolve({
                                name: 'project-milestones-edit-milestone',
                                params: {
                                    id: d.data.project,
                                    milestoneId: d.data.id,
                                },
                            })
                            .href
                        ;
                        break;
                    case 2:
                        url = router
                            .resolve({
                                name: 'project-task-management-edit',
                                params: {
                                    id: d.data.project,
                                    taskId: d.data.id,
                                },
                            })
                            .href
                        ;
                        break;
                    default:
                        url = router
                            .resolve({
                                name: 'project-dashboard',
                                params: {
                                    id: d.data.id,
                                },
                            })
                            .href
                        ;
                        break;
                    }

                    return `<a href="${url}" target="_blank">\ue164</a>`;
                })
                .on('click', d => {
                    d3.event.stopImmediatePropagation();
                    d3.event.stopPropagation();
                    console.log(d3.event);
                })
            ;

            // text - color status
            canvasNodesEnter
                .append('text')
                .attr('class', 'color-status')
                .attr('text-anchor', 'middle')
                .attr('x', -(100 * 0.66))
                .attr('y', 220 - 11)
                .attr('fill', d => d.data.colorStatusColor || '#8794c4')
                .attr('transform', 'rotate(-90)')
                .text(d => {
                    return d.data.colorStatusName
                        ? this.translate(d.data.colorStatusName)
                        : 'N/A'
                    ;
                })
                .on('click', d => {
                    if (d === this.root || d.data.type !== 2) {
                        return;
                    }

                    nodes
                        .filter(node => node !== d)
                        .forEach(node => {
                            node.showColorStatusSelector = false;
                            node.showProgressSelector = false;
                        })
                    ;

                    d.showColorStatusSelector = !d.showColorStatusSelector;
                    d.showProgressSelector = false;

                    this.updateTree(d);
                })
            ;

            // text - progress
            canvasNodesEnter
                .append('text')
                .attr('class', 'progress')
                .attr('text-anchor', 'middle')
                .attr('dy', '20px')
                .attr('x', -(100 * 0.166))
                .attr('y', 220 - 31)
                .attr('fill', '#8794c4')
                .attr('transform', 'rotate(-90)')
                .text(d => {
                    return (+d.data.progress || 0) + '%';
                })
                .on('click', d => {
                    if (d === this.root || d.data.type !== 2) {
                        return;
                    }

                    nodes
                        .filter(node => node !== d)
                        .forEach(node => {
                            node.showColorStatusSelector = false;
                            node.showProgressSelector = false;
                        })
                    ;

                    d.showColorStatusSelector = false;
                    d.showProgressSelector = !d.showProgressSelector;

                    this.updateTree(this.root);
                })
            ;

            // text - puid
            canvasNodesEnter
                .append('text')
                .attr('class', 'puid')
                .attr('text-anchor', 'middle')
                .attr('dy', '20px')
                .attr('x', -(100 * 0.5))
                .attr('y', 0)
                .attr('fill', '#8794c4')
                .attr('transform', 'rotate(-90)')
                .text(d => {
                    return d.data.puid || d.data.id;
                })
            ;

            // text - start date
            canvasNodesEnter
                .append('text')
                .attr('class', 'start-date')
                .attr('text-anchor', 'middle')
                .attr('dy', '20px')
                .attr('x', 70)
                .attr('y', 68)
                .attr('fill', '#8794c4')
                .text(d => {
                    return d.data.startDate;
                })
            ;

            // text - end date
            canvasNodesEnter
                .append('text')
                .attr('class', 'end-date')
                .attr('text-anchor', 'middle')
                .attr('dy', '20px')
                .attr('x', 150)
                .attr('y', 68)
                .attr('fill', '#8794c4')
                .text(d => {
                    return d.data.endDate;
                })
            ;

            // task started
            canvasNodesEnter
                .append('line')
                .attr('stroke', d => this.getTaskConditionColor(d.data))
                .attr('stroke-width', d => [3, 4].indexOf(d.data.workPackageStatus) !== -1 ? 1 : 0)
                .attr('x1', 0)
                .attr('y1', 0)
                .attr('x2', 220)
                .attr('y2', 100)
            ;

            // task completed
            canvasNodesEnter
                .append('line')
                .attr('stroke', d => this.getTaskConditionColor(d.data))
                .attr('stroke-width', d => [4].indexOf(d.data.workPackageStatus) !== -1 ? 1 : 0)
                .attr('x1', 0)
                .attr('y1', 100)
                .attr('x2', 220)
                .attr('y2', 0)
            ;

            let canvasNodesUpdate = canvasNodesEnter.merge(canvasNodes);

            canvasNodesUpdate
                .attr('transform', d => `translate(${d.y}, ${d.x})`)
            ;

            canvasNodesUpdate
                .select('circle.node')
                .attr('r', 10)
                .style('fill', d => {
                    return d._children ? '#123456' : '#fff';
                })
                .attr('cursor', 'pointer')
            ;

            canvasNodes
                .exit()
                .attr('transform', d => `translate(${source.y}, ${source.x})`)
                .remove()
            ;

            const progressValues = this.progressValues;
            const colorStatuses = this.colorStatuses;
            const translate = this.translate;
            const setWorkPackageColorStatus = this.setWorkPackageColorStatus;
            const setWorkPackageProgress = this.setWorkPackageProgress;
            const getWBSByProjectID = this.getWBSByProjectID;
            const projectId = this.project.id;
            const doUpdate = () => {
                this.updateTree(this.root);
            };
            const showErrorMessage = (msg) => {
                this.showSaveFailed = true;
                this.errorMessage = msg;
            };

            this.g
                .selectAll('g.node')
                .each(function(d) {
                    let group = d3.select(this);

                    if (d.showColorStatusSelector) {
                        group
                            .append('foreignObject')
                            .attr('class', 'color-status-selector-fo')
                            .attr('x', 220)
                            .attr('y', 0)
                            .append('xhtml:body')
                            .attr('xmlns', 'http://www.w3.org/1999/xhtml')
                            .attr('class', 'color-status-selector')
                            .append('ul')
                            .selectAll('li')
                            .data(colorStatuses)
                            .enter()
                            .append('li')
                            .html(x => translate(x.name))
                            .attr('data-color-status', x => x.id)
                            .attr('data-work-package', d.id)
                            .attr('style', d => `color: ${d.color}`)
                            .attr('class', x => {
                                return x.id === d.data.colorStatus
                                    ? 'active'
                                    : ''
                                ;
                            })
                            .on('click', x => {
                                d.data.colorStatus = x.id;
                                d.data.colorStatusName = x.name;
                                d.showColorStatusSelector = false;

                                // update lines to show color status
                                // group
                                //     .selectAll('line, rect')
                                //     .attr('stroke', x.color)
                                // ;
                                group
                                    .select('text.color-status')
                                    .text(translate(x.name))
                                    .attr('fill', x.color)
                                ;

                                doUpdate();

                                setWorkPackageColorStatus({id: d.data.id, colorStatus: x.id})
                                    .then(
                                        (response) => {
                                            getWBSByProjectID(projectId);

                                            if (response.body.error) {
                                                let messages = [];
                                                _
                                                    .keys(response.body.messages)
                                                    .forEach(item => {
                                                        messages = messages.concat(response.body.messages[item]);
                                                    })
                                                ;
                                                showErrorMessage(messages.join('\n'));
                                            }
                                        },
                                        () => {
                                            showErrorMessage(translate('message.error'));

                                            getWBSByProjectID(projectId);
                                        }
                                    )
                                ;
                            })
                        ;
                    } else {
                        group
                            .selectAll('foreignObject.color-status-selector-fo')
                            .remove()
                        ;
                    }

                    if (d.showProgressSelector) {
                        group
                            .append('foreignObject')
                            .attr('class', 'progress-selector-fo')
                            .attr('x', 220)
                            .attr('y', 0)
                            .append('xhtml:body')
                            .attr('xmlns', 'http://www.w3.org/1999/xhtml')
                            .attr('class', 'color-status-selector')
                            .append('ul')
                            .selectAll('li')
                            .data(progressValues)
                            .enter()
                            .append('li')
                            .html(x => `${x}%`)
                            .attr('data-progress-value', x => x.id)
                            .attr('data-work-package', d.id)
                            .attr('class', x => {
                                return x === d.data.progress
                                    ? 'active'
                                    : ''
                                ;
                            })
                            .on('click', x => {
                                d.data.progress = x;
                                d.showProgressSelector = false;
                                group.select('text.progress').text(x + '%');
                                doUpdate();
                                setWorkPackageProgress({id: d.data.id, progress: x})
                                    .then(
                                        (response) => {
                                            getWBSByProjectID(projectId);

                                            if (response.body.error) {
                                                let messages = [];
                                                _
                                                    .keys(response.body.messages)
                                                    .forEach(item => {
                                                        messages = messages.concat(response.body.messages[item]);
                                                    })
                                                ;
                                                showErrorMessage(messages.join('\n'));
                                            }
                                        },
                                        () => {
                                            showErrorMessage(translate('message.error'));

                                            getWBSByProjectID(projectId);
                                        }
                                    )
                                ;
                            })
                        ;
                    } else {
                        group
                            .selectAll('foreignObject.progress-selector-fo')
                            .remove()
                        ;
                    }
                })
            ;

            let canvasLinks = this.g
                .selectAll('path.link')
                .data(links, d => d.id)
            ;

            let canvasLinksEnter = canvasLinks
                .enter()
                .insert('path', 'g')
                .attr('class', 'link')
                .attr('d', d => {
                    const o = {
                        x: source.x0,
                        y: source.y0,
                    };

                    return this.diagonal(o, o);
                })
            ;

            let canvasLinksUpdate = canvasLinksEnter.merge(canvasLinks);

            canvasLinksUpdate
                .attr('d', d => {
                    return this.diagonal(d, d.parent);
                })
            ;

            canvasLinks
                .exit()
                .attr('d', d => {
                    const o = {x: source.x, y: source.y};

                    return this.diagonal(o, o);
                })
            ;

            nodes.forEach(d => {
                d.x0 = d.x;
                d.y0 = d.y;
            });
        },
        diagonal(dst, src) {
            // curved lines yo!
            return `
                M ${src.y + 220} ${src.x + 50}
                C ${(src.y + dst.y + 220) / 2} ${src.x + 50},
                    ${(src.y + dst.y + 220) / 2} ${dst.x + 50},
                    ${dst.y} ${dst.x + 50}`;
        },
        initialize() {
            this.initialized = true;

            window.d3 = d3;

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

            window.root = this.root;
            window.wbs = this.wbs;

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
        this.getColorStatuses();
    },
    watch: {
        wbs(value) {
            if (value) {
                this.wbsData = _.cloneDeep(value);
            }

            if (this.wbsData && this.project.id && this.wbs && this.colorStatuses.length && !this.initialized) {
                this.initialize();
            } else if (this.initialized && this.wbsData) {
                this.g.selectAll('*').remove();

                this.root = d3.hierarchy(this.wbsData);
                this.root.x0 = this.width / 2;
                this.root.y0 = this.height / 2;

                this.updateTree(this.root);
            }
        },
        colorStatuses() {
            if (this.wbsData && this.project.id && this.wbs && this.colorStatuses.length && !this.initialized) {
                this.initialize();
            }
        },
        project() {
            if (this.wbsData && this.project.id && this.wbs && this.colorStatuses.length && !this.initialized) {
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
    body {
        &.title-body {
            min-height: initial !important;
            background-color: transparent;

            table {
                color: #ffffff;
                width: 160px;
                height: 70px;
                padding: 0;
                margin: 0;
                white-space: normal;

                td {
                    text-align: center;
                    vertical-align: middle;
                    padding: 0;
                    margin: 0;
                }
            }
        }

        &.color-status-selector {
            min-height: initial !important;
            width: auto;
            min-width: 100px;
            display: block;
            background-color: #191E37;

            ul {
                margin: 3px;
                li {
                    color: #5dff88;
                    margin: 3px;
                    font-size: 8px !important;
                    text-transform: uppercase;
                    letter-spacing: 0.1em;

                    &.active {
                        font-weight: bold;

                        &:before {
                            content: '> ';
                        }

                        .glyphicon {
                            color: #ffffff;
                        }
                    }

                    &:hover {
                        color: #ffff00;
                    }
                }
            }
        }
    }

    .node {
        cursor: pointer;
    }

    .overlay {
        background-color: #eeeeee;
    }

    .node circle {
        fill: #fff;
        stroke: steelblue;
        stroke-width: 1.5px;
    }

    .node-link {
        content: '\e164';
        color: #ffffff;
        background: transparent;
        height: auto;
        min-height: inherit;
    }

    .node text {
        font-size: 10px;
        font-weight: 400;
    }

    .node text.color-status {
        font-size: 8px !important;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .link {
        fill: none;
        stroke: #8794c4;
        stroke-width: 1px;
    }

    .title {
        font-weight: bold;
    }
</style>
