<template>
    <div>
        <svg :id="id" />
    </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';
import * as d3 from 'd3';
import _ from 'lodash';
import $ from 'jquery';

export default {
    methods: {
        ...mapActions(['getOrganizationTree', 'clearOrganizationTree']),
        initialize() {
            if (this.initialized) {
                return;
            }
            this.initialized = true;

            this.svg = d3.select('#' + this.id);
            this.svg
                .attr('width', '100%')
                .attr('height', parseInt(1.5 * this.cellHeight, 10))
            ;
            this.svg.selectAll('g').remove();

            this.g = this.svg.append('g');

            this.tree = d3.tree()
                .separation(() => {
                    return 1.5;
                })
                .nodeSize([this.cellWidth, this.cellHeight])
            ;
            this.root = d3.hierarchy(this.organizationTreeData);
            this.root.x0 = this.width / 2;
            this.root.y0 = this.height / 2;

            this.updateTree(this.root);

            const zoom = d3.zoom()
                .scaleExtent([0.1, 4])
                .on('zoom', () => {
                    this.g.attr('transform', d3.event.transform);
                })
            ;
            this.svg
                .call(zoom)
                .call(zoom.transform, d3.zoomIdentity.translate($('#'+this.id).width() / 3, 16).scale(0.4))
            ;
        },
        branch(dst, src) {
            const cw = this.cellWidth;
            const ch = this.cellHeight;

            // cornered lines yo!
            return `
                M ${src.x + cw/2} ${src.y + ch}
                L ${src.x + cw/2} ${src.y + ch + this.cellSpacing/2},
                    ${dst.x + cw/2} ${src.y + ch + this.cellSpacing/2},
                    ${dst.x + cw/2} ${dst.y}
            `;
        },
        updateTree(source) {
            let treeData = this.tree(this.root);

            let nodes = treeData.descendants();
            let links = treeData.descendants().slice(1);

            nodes.forEach(d => {
                d.y = d.depth * (this.cellHeight + this.cellSpacing);
                d.x += this.cellWidth;
            });

            let canvasNodes = this.g
                .selectAll('g.node')
                .data(nodes, d => d.id)
            ;

            let canvasNodesEnter = canvasNodes
                .enter()
                .append('g')
                .attr('class', 'node')
                .attr('transform', d => `translate(${d.x}, ${d.y})`)
            ;

            canvasNodesEnter
                .append('foreignObject')
                .attr('y', 0)
                .attr('x', 0)
                .attr('width', this.cellWidth)
                .attr('height', this.cellHeight)
                .attr('externalResourcesRequired', 'true')
                .append('xhtml:body')
                .attr('xmlns', 'http://www.w3.org/1999/xhtml')
                .attr('style', `min-height: ${this.cellHeight}px`)
                .html(d => this.userTpl(d.data))
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

            let canvasNodesUpdate = canvasNodesEnter.merge(canvasNodes);

            canvasNodesUpdate
                .attr('transform', d => `translate(${d.x}, ${d.y})`)
            ;

            canvasNodes
                .exit()
                .attr('transform', d => `translate(${source.x}, ${source.y})`)
                .remove()
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
                        x: source.x0 || source.x,
                        y: source.y0 || source.y,
                    };

                    return this.branch(o, o);
                })
            ;

            let canvasLinksUpdate = canvasLinksEnter.merge(canvasLinks);

            canvasLinksUpdate
                .attr('d', d => {
                    return this.branch(d, d.parent);
                })
            ;

            canvasLinks
                .exit()
                .attr('d', d => {
                    const o = {x: source.x, y: source.y};

                    return this.branch(o, o);
                })
            ;

            nodes.forEach(d => {
                d.x0 = d.x;
                d.y0 = d.y;
            });
        },
        userTpl(user) {
            const avatar = user.avatar
                ? '/uploads/avatars/' + user.avatar
                : user.gravatar
            ;
            const attributes = this.dataAttributes.join(' ');

            return `
                <div class="member-badge big" ${attributes}>
                    <div class="avatar" style="background-image: url('${avatar}');" ${attributes}></div>
                    <div class="name" ${attributes}>${user.fullName}</div>
                    <div class="title" ${attributes}>${user.title||''}</div>
                </div>
            `;
        },
    },
    watch: {
        project(val) {
            if (this.project && this.project.id) {
                this.getOrganizationTree(this.project.id);
            }
        },
        organizationTree(val) {
            this.organizationTreeData = _.clone(this.organizationTree);
            this.initialize();
        },
    },
    computed: {
        ...mapGetters(['project', 'organizationTree']),
    },
    mounted() {
        // allows us to use component scoped css on svg foreignObjects!
        const attributes = this.$el.attributes;

        for (let c = 0; c < attributes.length; c++) {
            if (attributes.item(c).name.indexOf('data-v-') === 0) {
                this.dataAttributes.push(attributes.item(c).name);
            }
        }
    },
    beforeDestroy() {
        this.clearOrganizationTree();
    },
    data() {
        return {
            dataAttributes: [],
            cellWidth: 256,
            cellHeight: 350,
            cellSpacing: 50,
            initialized: false,
            id: 'pot' + this._uid,
            svg: null,
            g: null,
            tree: null,
            root: null,
            organizationTreeData: {},
        };
    },
};
</script>

<style scoped lang="scss">
    @import '../../css/_variables';
    @import '../../css/_mixins';

    .member-badge {
        text-align: center;
        display: inline-block;
        margin: 10px;

        .avatar {
            width: 100%;
            height: 0;
            padding-bottom: 100%;
            @include border-radius(50%);
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }

        .social-links {
            margin-top: 5px;

            a {
                margin: 0 3px;
            }

        }

        .name {
            margin-top: 17px;
            text-transform: uppercase;
            letter-spacing: 2.1px;
            line-height: 1.1em;
            font-family: Poppins,sans-serif;
            color: $lighterColor;
        }

        .title {
            text-transform: uppercase;
            font-size: 10px;
            margin-top: 3px;
            color: $middleColor;
        }

        &.small {
            margin: 10px;
            width: 112px;

            .social-links {
                a {
                    margin: 0 1px;
                }
            }
        }

        &.big {
            width: 252px;
        }

        &.first-member-badge {
            &:before {
                display: none;
            }
        }

        &:before {
            content: '';
            width: 1px;
            height: 30px;
            background-color: $middleColor;
            position: absolute;
            top: -40px;
            left: 50%;
        }
    }
</style>
