import router from '../../../router';
import {trans} from '../../../util/Translator';
import contextMenu from 'd3-context-menu';
import * as d3 from 'd3';

/**
 * Create
 *
 * @return {object}
 */
export default function create() {
    return {
        $nodes: [],
        $g: null,
        $canvasNodesEnter: null,
        $canvasNodes: null,
        $root: null,
        $calls: [],
        config: {
            entityForegroundColor(entity) {
                return '#fff';
            },
            svg: null,
        },
        init() {
            this.$g = this.config.svg.select('g');
            this.$canvasNodes = this.$g
                .selectAll('g.node')
                .data(this.$nodes, d => {
                    return d.id || (d.id = (d.data.puid || d.data.id));
                })
            ;

            this.$canvasNodesEnter = this.$canvasNodes
                .enter()
                .append('g')
                .attr('class', 'node')
                .attr('transform', d => `translate(${d.y}, ${d.x})`)
            ;

            return this;
        },
        appendEntityWireframe() {
            let config = this.config;

            this.$calls.push(function() {
                // display the box
                this
                    .append('rect')
                    .attr('fill', '#191E37')
                    .attr('stroke', d => config.entityForegroundColor(d.data))
                    .attr('stroke-width', 1)
                    .attr('width', 220)
                    .attr('height', 100)
                ;

                // draw the lines
                this // side line left
                    .append('line')
                    .attr('stroke', d => config.entityForegroundColor(d.data))
                    .attr('stroke-width', 1)
                    .attr('x1', 30)
                    .attr('y1', 0)
                    .attr('x2', 30)
                    .attr('y2', 100)
                ;

                // in progress task cross line
                this
                    .append('line')
                    .attr('stroke', d => config.entityForegroundColor(d.data))
                    .attr('stroke-width', d => d.data.progress > 0 ? 1 : 0)
                    .attr('x1', 0)
                    .attr('y1', 0)
                    .attr('x2', 220)
                    .attr('y2', 100)
                ;

                // completed task 2n cross line
                this
                    .append('line')
                    .attr('stroke', d => config.entityForegroundColor(d.data))
                    .attr('stroke-width', d => d.data.progress === 100 ? 1 : 0)
                    .attr('x1', 0)
                    .attr('y1', 100)
                    .attr('x2', 220)
                    .attr('y2', 0)
                ;

                this // side line right
                    .append('line')
                    .attr('stroke', d => config.entityForegroundColor(d.data))
                    .attr('stroke-width', 1)
                    .attr('x1', 220 - 30)
                    .attr('y1', 0)
                    .attr('x2', 220 - 30)
                    .attr('y2', 100)
                ;

                this // right side splitter
                    .append('line')
                    .attr('stroke', d => config.entityForegroundColor(d.data))
                    .attr('stroke-width', 1)
                    .attr('x1', 220 - 30)
                    .attr('y1', 100 / 3)
                    .attr('x2', 220)
                    .attr('y2', 100 / 3)
                ;

                this // dates splitter top
                    .append('line')
                    .attr('stroke', d => config.entityForegroundColor(d.data))
                    .attr('stroke-width', 1)
                    .attr('x1', 30)
                    .attr('y1', 100 - 30)
                    .attr('x2', 220 - 30)
                    .attr('y2', 100 - 30)
                ;

                this // dates splitter center
                    .append('line')
                    .attr('stroke', d => config.entityForegroundColor(d.data))
                    .attr('stroke-width', 1)
                    .attr('x1', 220 / 2)
                    .attr('y1', 100 - 30)
                    .attr('x2', 220 / 2)
                    .attr('y2', 100)
                ;

                // task completed
                this
                    .append('line')
                    .attr('stroke-width',
                        d => [4].indexOf(d.data.workPackageStatus) !== -1 ? 1 : 0)
                    .attr('x1', 0)
                    .attr('y1', 100)
                    .attr('x2', 220)
                    .attr('y2', 0)
                ;
            }.bind(this.$canvasNodesEnter));

            return this;
        },
        appendEntityLabel(clickCb) {
            this.$calls.push(function() {
                this // text - title
                    .append('foreignObject')
                    .attr('y', 0)
                    .attr('x', 30)
                    .attr('width', 220 - 60)
                    .attr('height', 100 - 30)
                    .append('xhtml:body')
                    .attr('xmlns', 'http://www.w3.org/1999/xhtml')
                    .attr('class', 'title-body')
                    .html(d => {
                        return `<div class="title">${d.data.name}</div>`;
                    })
                    .on('click', d => {
                        if (d.contextMenuOpened) {
                            return;
                        }

                        if (d.children) {
                            d._children = d.children;
                            d.children = null;
                        } else {
                            d.children = d._children;
                            d._children = null;
                        }

                        clickCb(d);
                    })
                ;
            }.bind(this.$canvasNodesEnter));

            return this;
        },
        appendEntityTopMenu() {
            this.$calls.push(function() {
                this
                    .append('foreignObject')
                    .attr('x', 140)
                    .attr('y', 5)
                    .attr('width', 50)
                    .attr('height', 15)
                    .append('xhtml:body')
                    .attr('xmlns', 'http://www.w3.org/1999/xhtml')
                    .attr('class', 'top-menu')
                    .html(d => {
                        return getEntityTopMenuItems(d.data);
                    })
                ;
            }.bind(this.$canvasNodesEnter));

            return this;
        },
        appendTrafficLightLabel() {
            this.$calls.push(function() {
                this
                    .append('text')
                    .attr('class', 'status')
                    .attr('text-anchor', 'middle')
                    .attr('x', -(100 * 0.66))
                    .attr('y', 220 - 11)
                    .attr('fill', '#fff')
                    .attr('transform', 'rotate(-90)')
                    .text(d => trans(d.data.workPackageStatusName))
                ;
            }.bind(this.$canvasNodesEnter));

            return this;
        },
        appendProgressLabel() {
            this.$calls.push(function() {
                this
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
                ;
            }.bind(this.$canvasNodesEnter));

            return this;
        },
        appendPUIDLabel() {
            this.$calls.push(function() {
                this
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
            }.bind(this.$canvasNodesEnter));

            return this;
        },
        appendDates() {
            this.$calls.push(function() {
                // text - start date
                this
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
                this
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
            }.bind(this.$canvasNodesEnter));

            return this;
        },
        addContextMenu(menu, g) {
            let config = this.config;

            this.$calls.push(function() {
                this.on('contextmenu', (d, i) => {
                    let m = menu(d, i);
                    if (m.length === 0) {
                        d3.event.preventDefault();
                        return;
                    }

                    return contextMenu(m, {
                        onOpen() {
                            d.contextMenuOpened = true;
                            d3.zoomDisabled = true;
                        },
                        onClose() {
                            d.contextMenuOpened = false;
                            d3.zoomDisabled = false;
                        },
                    })(d, i);
                });

                config.svg.on('click', () => {
                    contextMenu('close');
                });
            }.bind(this.$canvasNodesEnter));

            return this;
        },
        addLinks(links) {
            this.$calls.push(() => {
                let diagonal = (dst, src) => {
                    return `
                M ${src.y + 220} ${src.x + 50}
                C ${(src.y + dst.y + 220) / 2} ${src.x + 50},
                    ${(src.y + dst.y + 220) / 2} ${dst.x + 50},
                    ${dst.y} ${dst.x + 50}`;
                };

                let canvasLinks = this.$g
                                      .selectAll('path.link')
                                      .data(links, d => d.id)
                ;

                let canvasLinksEnter = canvasLinks
                    .enter()
                    .insert('path', 'g')
                    .attr('class', 'link')
                    .attr('d', d => {
                        const o = {
                            x: this.$root.x0,
                            y: this.$root.y0,
                        };

                        return diagonal(o, o);
                    })
                ;

                let canvasLinksUpdate = canvasLinksEnter.merge(canvasLinks);

                canvasLinksUpdate
                    .attr('d', d => {
                        return diagonal(d, d.parent);
                    })
                ;

                canvasLinks
                    .exit()
                    .attr('d', d => {
                        const o = {x: this.$root.x, y: this.$root.y};

                        return diagonal(o, o);
                    })
                    .remove()
                ;
            });

            return this;
        },
        build() {
            this.$calls.forEach((f) => {
                f();
            });

            let canvasNodesUpdate = this.$canvasNodesEnter.merge(this.$canvasNodes);
            canvasNodesUpdate.attr('transform', d => `translate(${d.y}, ${d.x})`);

            this.$canvasNodes
                .exit()
                .attr('transform', d => `translate(${this.$root.y}, ${this.$root.x})`)
                .remove()
            ;

            this.$nodes.forEach(d => {
                d.x0 = d.x;
                d.y0 = d.y;
            });

            this.$calls = [];
        },
    };
};

/**
 * Entity target url
 *
 * @param {object} entity
 * @return {string}
 */
function getEntityTargetUrl(entity) {
    if (entity.isPhase) {
        return router.resolve({
            name: 'project-phases-view-phase',
            params: {
                id: entity.project,
                phaseId: entity.id,
            },
        }).href;
    }

    if (entity.isMilestone) {
        return router.resolve({
            name: 'project-phases-view-milestone',
            params: {
                id: entity.project,
                milestoneId: entity.id,
            },
        }).href;
    }

    if (entity.isTask) {
        return router.resolve({
            name: 'project-task-management-view',
            params: {
                id: entity.project,
                taskId: entity.id,
            },
        }).href;
    }

    return router.resolve({
        name: 'project-dashboard',
        params: {
            id: entity.project,
        },
    }).href;
}

/**
 * Entity top menu items
 *
 * @param {object} entity
 * @return {string}
 */
function getEntityTopMenuItems(entity) {
    let html = [];
    let url = getEntityTargetUrl(entity);

    html.push(
        `<a href="${url}" target="_blank" class="glyphicon glyphicon-new-window" title="Go to"></a>`);

    // if (entity.isTask) {
    //     html.push(
    //         `<a href="javascript:void(0)" target="_blank" class="glyphicon glyphicon-menu-hamburger" title="Menu"></a>`);
    // }

    return html.join('');
}
