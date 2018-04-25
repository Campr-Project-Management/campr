<template>
    <div v-show="show" style="width: 100%">
        <svg :id="id" width="100%" :height="height" />
        <div
            class="team-list-wrapper"
            ref="teamList"
            v-html="teamList"
            :style="{top: teamListOffsetTop, left: teamListOffsetLeft}" />
    </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';
import * as d3 from 'd3';
import _ from 'lodash';
import $ from 'jquery';
import userHelper from '../../helpers/user';
import socialIcons from '../../helpers/social-icons';

export default {
    methods: {
        ...mapActions(['getOrganizationTree', 'clearOrganizationTree']),
        initialize() {
            if (!this.organizationTreeData || !this.organizationTreeData.id) {
                this.show = false;
                return;
            }

            if (this.initialized) {
                return;
            }

            this.show = true;
            this.initialized = true;

            this.svg = d3.select('#' + this.id);
            this.svg.selectAll('g').remove();

            this.g = this.svg.append('g');

            this.tree = d3.tree()
                .separation((d) => {
                    return d.depth < this.bigCellLimit ? 1.5 : 1;
                })
                .nodeSize([this.cellWidth, this.cellHeight])
            ;
            this.root = d3.hierarchy(this.organizationTreeData);
            this.root.x0 = 0;
            this.root.y0 = 0;

            this.root = this.collapseNode(this.root, this.collapsedNodeLevel);

            this.updateTree(this.root);

            const zoom = d3.zoom()
                .scaleExtent([0.1, 4])
                .on('zoom', () => {
                    const t = _.clone(d3.event.transform);
                    this.gTranslateX = t.x; // allows the popup to follow the scroll :]
                    t.y = 16; // force horizontal scrolling :]
                    t.k = 1; // force scale of 1
                    this.g.attr('transform', t);
                })
            ;

            const width = this.root.descendants()[0].x;
            this.svg
                .call(zoom)
                .call(zoom.transform, d3.zoomIdentity.translate(width, 16))
            ;

            // this.svg.on('click', () => {
            //     if (this.teamList !== '') {
            //         this.teamList = '';
            //     }
            // });
        },
        collapseNode(node, level) {
            if (node.children) {
                node.children = node.children.map((n) => {
                    this.collapseNode(n, level);

                    return n;
                });
            }

            if (node.depth >= level) {
                node._children = node.children;
                node.children = null;
            }

            return node;
        },
        branch(dst, src) {
            const cw = this.cellWidth;
            const ch = this.cellHeight;
            const scw = this.smallCellWidth;
            const sch = this.smallCellHeight;

            switch (true) {
            case src.depth < (this.bigCellLimit - 1):
                return `
                M ${src.x + cw/2} ${src.y + ch}
                L ${src.x + cw/2} ${src.y + ch + this.cellSpacing/2},
                    ${dst.x + cw/2} ${src.y + ch + this.cellSpacing/2},
                    ${dst.x + cw/2} ${dst.y}
            `;
            case src.depth === (this.bigCellLimit - 1) && dst.depth === this.bigCellLimit:
                return `
                M ${src.x + cw/2} ${src.y + ch}
                L ${src.x + cw/2} ${src.y + ch + this.cellSpacing/2},
                    ${dst.x + scw/2} ${src.y + ch + this.cellSpacing/2},
                    ${dst.x + scw/2} ${dst.y}
            `;
            default:
                return `
                M ${src.x + scw/2} ${src.y + sch}
                L ${src.x + scw/2} ${src.y + sch + this.cellSpacing/2},
                    ${dst.x + scw/2} ${src.y + sch + this.cellSpacing/2},
                    ${dst.x + scw/2} ${dst.y}
            `;
            }
        },
        updateTree(source) {
            let treeData = this.tree(this.root);

            let nodes = treeData.descendants();
            let links = treeData.descendants().slice(1);

            nodes.forEach(d => {
                if (d.depth < this.bigCellLimit) {
                    d.y = d.depth * (this.cellHeight + this.cellSpacing);
                } else {
                    d.y = this.bigCellLimit * (this.cellHeight + this.cellSpacing)
                        + (d.depth - this.bigCellLimit) * (this.smallCellHeight + this.cellSpacing);
                }
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
                .attr('width', d => d.depth < 2 ? this.cellWidth : this.smallCellWidth)
                .attr('height', d => d.depth < 2 ? this.cellHeight : this.smallCellHeight)
                .attr('externalResourcesRequired', 'true')
                .append('xhtml:body')
                .attr('xmlns', 'http://www.w3.org/1999/xhtml')
                .attr('style', d => d.depth < 2 ? `min-height: auto; height: ${this.cellHeight}px` : `min-height: ${this.smallCellHeight}px`)
                .html(d => this.userTpl(d))
                .on('click', d => {
                    if (this.activeTeamNode) {
                        let shouldReturn = this.activeTeamNode === d;
                        this.activeTeamNode.showTeam = false;
                        this.activeTeamNode = null;
                        this.teamList = '';
                        this.updateTree(d);
                        if (shouldReturn) {
                            return;
                        }
                    }
                    if (d.depth === this.collapsedNodeLevel) {
                        this.activeTeamNode = d;
                        d.showTeam = ! d.showTeam;
                        if (d.showTeam) {
                            this.teamList = this.teamListTpl(d);
                        }
                        this.updateTree(d);
                    }
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
                        x: source.x0,
                        y: source.y0,
                        depth: d.depth,
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
                    const o = {x: source.x, y: source.y, depth: d.depth};

                    return this.branch(o, o);
                })
            ;

            nodes.forEach(d => {
                d.x0 = d.x;
                d.y0 = d.y;
            });
        },
        teamListTpl(d) {
            const children = d.children || d._children;
            if (!children) {
                return '';
            }

            const attributes = this.dataAttributes.join(' ');
            let teamList = `<div class="arrow-up" ${attributes}></div><ul class="team-list" ${attributes}>`;

            children.map(n => {
                const u = n.data;
                const avatar = userHelper.getUserAvatar(u);
                const media = userHelper.getSocialMedia(u);

                let uTitles = '';
                u.titles.map(title => {
                    uTitles += `<span class="title" ${attributes}>${title}</span>`;
                });

                let uMediaData = '';
                _.forEach(media, (item, key) => {
                    if (!socialIcons[key] || _.isEmpty(item)) {
                        return;
                    }

                    uMediaData += `
                            <a href="${item}" target="_blank" style="margin: 0 3px;">
                                ${socialIcons[key]}
                            </a>
                        `;
                });

                if (uMediaData !== '') {
                    uMediaData = `
                            <span class="social-links" ${attributes}>
                                ${uMediaData}
                            </span>
                        `;
                }

                teamList += `
                        <li ${attributes}>
                            <img class="avatar" src="${avatar}" alt="${u.fullName}" ${attributes} />
                            <span class="name" ${attributes}>${u.fullName}</span>
                            ${uTitles}
                            ${uMediaData}
                        </li>
                    `;
            });
            teamList += '</ul>';

            return teamList;
        },
        userTpl(d) {
            const user = d.data;
            const attributes = this.dataAttributes.join(' ');

            const avatar = userHelper.getUserAvatar(user);
            const media = userHelper.getSocialMedia(user);

            const isBig = d.depth < this.bigCellLimit;

            let mediaData = '';
            _.forEach(media, (item, key) => {
                if (!socialIcons[key] || _.isEmpty(item)) {
                    return;
                }

                mediaData += `
                    <a href="${item}" target="_blank" style="margin: 0 3px;">
                        ${socialIcons[key]}
                    </a>
                `;
            });

            if (mediaData !== '') {
                mediaData = `
                    <div class="social-links flex flex-center align" ${attributes}>
                        ${mediaData}
                    </div>
                `;
            }

            let titles = '';
            user.titles.map(title => {
                titles += `<div class="title" ${attributes}>${title}</div>`;
            });

            let teamButtom = '';
            const children = d.children || d._children;

            if (d.depth === this.collapsedNodeLevel && children) {
                let btnText = d.showTeam ? this.translate('message.close_team') : this.translate('message.view_team');
                let icon = d.showTeam ? '&#xf077;' : '&#xf078;';

                teamButtom = `
                    <button class="btn-rounded btn-empty ${d.showTeam?'active':''}" ${attributes}>
                        ${btnText}
                        <text width="15" height="15" x="0" y="0" style="font-family: FontAwesome;">${icon}</text>
                    </button>
                `;
            }

            return `
                <div class="member-badge ${isBig?'big':'small'}" ${attributes}>
                    <div class="avatar" style="background-image: url('${avatar}');" ${attributes}></div>
                    <div class="name" ${attributes}>${user.fullName}</div>
                    ${titles}
                    ${mediaData}
                    ${teamButtom}
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
            this.organizationTreeData = _.cloneDeep(this.organizationTree);
            this.initialize();
        },
    },
    computed: {
        ...mapGetters(['project', 'organizationTree']),
        height: {
            get() {
                if (!this.organizationTreeData || !this.root) {
                    return 0;
                }

                if (!this.root.descendants() || !this.root.descendants().length) {
                    return 0;
                }

                let levels = 0;
                this.root.descendants().map((item) => {
                    if (item.depth > levels) {
                        levels = item.depth;
                    }
                });

                return (levels + 1) * this.cellHeight + levels * this.cellSpacing;
            },
        },
        teamListOffsetLeft: {
            get() {
                if (!this.activeTeamNode) {
                    return '-9999px'; // no active no
                }
                let out = (this.pageOffsetLeft + this.gTranslateX + this.activeTeamNode.x - 120);
                if (out < 0) {
                    out = '-9999'; // hide when it's about to go outside the page
                }
                return out + 'px';
            },
        },
        teamListOffsetTop: {
            get() {
                return (this.height + 60) + 'px';
            },
        },
    },
    mounted() {
        // allows us to use component scoped css on svg foreignObjects!
        const attributes = this.$el.attributes;

        for (let c = 0; c < attributes.length; c++) {
            if (attributes.item(c).name.indexOf('data-v-') === 0) {
                this.dataAttributes.push(attributes.item(c).name);
            }
        }

        this.pageOffsetLeft = $('.project-organization.page-section').offset().left;
    },
    beforeDestroy() {
        this.clearOrganizationTree();
    },
    data() {
        return {
            collapsedNodeLevel: 3,
            bigCellLimit: 2,
            show: false,
            dataAttributes: [],
            cellWidth: 222,
            cellHeight: 300,
            smallCellWidth: 222, // should be the same as cellWidth to for easy math :]
            smallCellHeight: 225,
            cellSpacing: 50,
            initialized: false,
            id: 'pot' + this._uid,
            svg: null,
            g: null,
            pageOffsetLeft: 0,
            gTranslateX: 0,
            teamList: '',
            activeTeamNode: null,
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

    .team-list-wrapper {
        position: absolute;
        z-index: 99999;

        .arrow-up {
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-bottom: 10px solid $darkColor;
            width: 0;
            height: 0;
            margin-left: 220px;
        }

        ul.team-list {
            width: 480px;
            margin: 0;
            padding: 0;
            background: $darkColor;
            box-shadow: 0px 5px 10px 10px $semiDarkColor;

            li {
                width: 450px;
                margin: 15px;
                text-align: left;
                list-style: none;

                &:not(:last-child) {
                    border-bottom: 1px solid $secondDarkColor;
                    padding-bottom: 15px;
                }

                .avatar {
                    width: 60px;
                    height: 60px;
                    padding: 0;
                    float: left;
                }

                .title {
                    margin: 0 10px;
                    text-align: left;
                    width: 350px;
                    display: inline-block;
                }

                .name {
                    margin: 0 10px;
                    text-align: left;
                    width: 350px;
                    display: inline-block;
                }

                .social-links {
                    margin: 0 10px;
                    text-align: left;
                    width: 350px;
                    display: inline-block;
                    justify-content: left;
                }
            }
        }
    }

    .member-badge {
        .social-links {
            position: inherit;
        }

        button.active {
            background: $middleColor;
        }

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
            font-family: Poppins,sans-serif;
        }

        .btn-rounded.btn-empty {
            padding: 0px 10px;
            width: 100%;
            line-height: 30px;
            height: 30px;
            font-size: 10px;
            letter-spacing: 0px;
            margin: 0;
            color: $lighterColor;
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
            width: 200px;
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

    .social-links {
        margin-top: 15px;

        a {
            margin: 0 3px;
        }

        &.left {
            justify-content: flex-start;

            a:first-child {
                margin-left: 0;
            }
        }
    }

    .phone {
        position: relative;
        display: block;

        &:hover .link-tooltip {
            display: block;
        }

    }

    .link-tooltip {
        display: none;
        background: $secondColor;
        color: $darkColor;
        position: absolute;
        padding: 5px 15px;
        text-align: center;
        border-radius: 5px;
        left: 50%;
        margin-left: -65px;
        top: -30px;
        min-width: 130px;

        .caret {
            border-top: 5px solid $secondColor;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            width: 0;
            height: 0;
            bottom: -5px;
            position: absolute;
            left: 60px;
            display: block;
        }
    }
</style>
