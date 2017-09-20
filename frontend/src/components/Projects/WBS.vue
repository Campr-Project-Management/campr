<template>
    <div id="tree_container"></div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import * as d3 from 'd3';
import $ from 'jquery';

export default {
    computed: {
        ...mapGetters(['wbs', 'project']),
    },
    methods: {
        ...mapActions(['getProjectById', 'getWBSByProjectID']),
        updateTree(source) {
            let treeData = this.tree(this.root);

            let nodes = treeData.descendants();
            let links = treeData.descendants().slice(1);

            nodes.forEach((d) => {
                d.y = d.depth * (220 + (220 / 2)) + 50;
                d.x += 50;
            });

            let i = 0;
            let canvasNodes = this.g
                .selectAll('g.node')
                .data(nodes, (d) => {
                    return d.id || (d.id = i++);
                })
            ;

            let canvasNodesEnter = canvasNodes
                .enter()
                .append('g')
                .attr('class', 'node')
                .attr('transform', (d) => `translate(${d.y}, ${d.x})`)
                .on('click', (d) => {
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

            // display the box
            canvasNodesEnter
                .append('rect')
                .attr('fill', '#191E37')
                .attr('stroke', '#6f898e')
                .attr('stroke-width', 1)
                .attr('width', 220)
                .attr('height', 100)
            ;

            // draw the lines
            canvasNodesEnter // side line left
                .append('line')
                .attr('stroke', '#6f898e')
                .attr('stroke-width', 1)
                .attr('x1', 30)
                .attr('y1', 0)
                .attr('x2', 30)
                .attr('y2', 100)
            ;
            canvasNodesEnter // side line right
                .append('line')
                .attr('stroke', '#6f898e')
                .attr('stroke-width', 1)
                .attr('x1', 220 - 30)
                .attr('y1', 0)
                .attr('x2', 220 - 30)
                .attr('y2', 100)
            ;
            canvasNodesEnter // right side splitter
                .append('line')
                .attr('stroke', '#6f898e')
                .attr('stroke-width', 1)
                .attr('x1', 220 - 30)
                .attr('y1', 100 / 3)
                .attr('x2', 220)
                .attr('y2', 100 / 3)
            ;
            canvasNodesEnter // dates splitter top
                .append('line')
                .attr('stroke', '#6f898e')
                .attr('stroke-width', 1)
                .attr('x1', 30)
                .attr('y1', 100 - 30)
                .attr('x2', 220 - 30)
                .attr('y2', 100 - 30)
            ;
            canvasNodesEnter // dates splitter center
                .append('line')
                .attr('stroke', '#6f898e')
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
                .append('xhtml:boxy')
                .html((d) => {
                    // tables are supposed to be ugly, but god damn it they can be the prettiest thing when they work better than anything
                    return `<table style="width: ${220 - 60}px; height: ${100 - 30}px; padding: 0; margin: 0;white-space: normal">
                        <tr>
                            <td style="text-align: center; vertical-align: middle; padding: 0; margin: 0;">${d.data.name}</td>
                        </tr>
                    </table>`;
                })
            ;
            // text - color status
            canvasNodesEnter
                .append('text')
                .attr('class', 'color-status')
                .attr('text-anchor', 'middle')
                .attr('x', -(100 * 0.66))
                .attr('y', 220 - 10)
                .attr('fill', d => d.data.colorStatusColor || 'green')
                .attr('transform', 'rotate(-90)')
                .text(d => {
                    return d.data.colorStatusName
                        ? this.translate(d.data.colorStatusName)
                        : ''
                    ;
                })
            ;

            // text - progress
            canvasNodesEnter
                .append('text')
                .attr('class', 'progress')
                .attr('text-anchor', 'middle')
                .attr('dy', '20px')
                .attr('x', -(100 * 0.166))
                .attr('y', 220 - 30)
                .attr('fill', '#337ab7')
                .attr('transform', 'rotate(-90)')
                .text(d => {
                    return (+d.data.progress || 0) + '%';
                })
            ;
            // text - puid
            canvasNodesEnter
                .append('text')
                .attr('class', 'progress')
                .attr('text-anchor', 'middle')
                .attr('dy', '20px')
                .attr('x', -(100 * 0.5))
                .attr('y', 0)
                .attr('fill', '#337ab7')
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
                .attr('fill', '#337ab7')
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
                .attr('fill', '#337ab7')
                .text(d => {
                    return d.data.endDate;
                })
            ;

            let canvasNodesUpdate = canvasNodesEnter.merge(canvasNodes);

            canvasNodesUpdate
                .attr('transform', (d) => `translate(${d.y}, ${d.x})`)
            ;

            canvasNodesUpdate
                .select('circle.node')
                .attr('r', 10)
                .style('fill', (d) => {
                    return d._children ? '#123456' : '#fff';
                })
                .attr('cursor', 'pointer')
            ;

            canvasNodes
                .exit()
                .attr('transform', (d) => `translate(${source.y}, ${source.x})`)
                .remove()
            ;

            let canvasLinks = this.g
                .selectAll('path.link')
                .data(links, (d) => d.id)
            ;

            let canvasLinksEnter = canvasLinks
                .enter()
                .insert('path', 'g')
                .attr('class', 'link')
                .attr('d', (d) => {
                    const o = {
                        x: source.x0,
                        y: source.y0,
                    };

                    return this.diagonal(o, o);
                });

            let canvasLinksUpdate = canvasLinksEnter.merge(canvasLinks);

            canvasLinksUpdate
                .attr('d', (d) => {
                    return this.diagonal(d, d.parent);
                })
            ;

            canvasLinks
                .exit()
                .attr('d', (d) => {
                    const o = {x: source.x, y: source.y};

                    return this.diagonal(o, o);
                })
            ;

            nodes.forEach((d) => {
                d.x0 = d.x;
                d.y0 = d.y;
            });
        },
        diagonal(dst, src) {
            // curved lines yo!
            return `
                M ${src.y + 200} ${src.x + 50}
                C ${(src.y + dst.y + 200) / 2} ${src.x + 50},
                    ${(src.y + dst.y + 200) / 2} ${dst.x + 50},
                    ${dst.y} ${dst.x + 50}`;
        },
        initialize() {
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
            this.root = d3.hierarchy(this.wbs);
            this.root.x0 = this.width / 2;
            this.root.y0 = this.height / 2;
            this.svg = d3
                .select('#tree_container', (d) => {
                    return d.children;
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
        if (!this.project) {
            this.getProjectById(this.$route.params.id);
        }
        this.getWBSByProjectID(this.$route.params.id);
    },
    watch: {
        wbs() {
            this.initialize();
        },
    },
    data() {
        return {
            animationSpeed: 200,
            tree: null,
            root: null,
            svg: null,
            g: null,
            width: 640,
            height: 480,
        };
    },
};
</script>

<style lang="scss">
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

    .node text {
        font-size: 12px;
    }

    .node text.color-status {
        font-size: 10px !important;
    }

    .link {
        fill: none;
        stroke: #ccc;
        stroke-width: 1.5px;
    }

    .title {
        font-weight: bold;
    }
</style>
