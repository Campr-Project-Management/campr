<template>
    <div>
        <h3>{{ translateText('message.planning') }}</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group last-form-group">
                    <div class="dropdown">
                        <button
                            ref="btn-dropdown"
                            class="btn btn-primary dropdown-toggle"
                            type="button"
                            data-toggle="dropdown"
                            @click="updateScrollbarTop()"
                        >
                            {{ phaseOrMilestoneLabel }}
                            <span class="caret"></span>
                        </button>
                        <scrollbar :style="{height: scrollbarHeight + 'px', top: scrollbarTop + 'px'}" class="dropdown-menu dropdown-menu-right customScrollbar">
                            <ul ref="ul">
                                <li v-for="item in dropdownData">
                                    <a
                                        href="javascript:void(0)"
                                        v-on:click="updatePlanning(item)"
                                        :style="{paddingLeft: ((item.level+1) * 10) + 'px'}"
                                    >
                                        {{ item.name }}
                                    </a>
                                </li>
                            </ul>
                        </scrollbar>
                    </div>
                    <error at-path="parent" />
                    <error at-path="milestone" />
                    <error at-path="phase" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';
import $ from 'jquery';
import Error from '../../../_common/_messages/Error';

export default {
    components: {Error},
    props: {
        editPlanning: {},
        maxItems: {
            type: Number,
            default: 5,
        },
    },
    methods: {
        ...mapActions(['getWBSByProjectID']),
        translateText(text) {
            return this.translate(text);
        },
        updatePlanning(wp) {
            switch (wp.type) {
            case 0:
                this.planning = {
                    phase: {
                        key: wp.id,
                        label: wp.name,
                    },
                    milestone: null,
                    parent: null,
                };
                break;
            case 1:
                this.planning = {
                    phase: {
                        key: wp.phase,
                        label: wp.phaseName,
                    },
                    milestone: {
                        key: wp.id,
                        label: wp.name,
                    },
                    parent: null,
                };
                break;
            }
        },
        clearPhaseMilestone() {
            this.planning = {
                phase: null,
                milestone: null,
                parent: null,
            };
        },
        updateScrollbarTop() {
            let scrollTop = $(window).scrollTop();
            let elementOffset = $(this.$el).offset().top;
            let currentElementOffset = (elementOffset - scrollTop);
            let windowInnerHeight = window.innerHeight;

            if (windowInnerHeight - currentElementOffset < (this.scrollbarHeight + this.itemHeight)) {
                this.scrollbarTop = -1 * this.scrollbarHeight;
            } else {
                this.scrollbarTop = this.itemHeight;
            }
        },
    },
    created() {
        this.getWBSByProjectID(this.$route.params.id);

        this.planning = this.editPlanning;
    },
    mounted() {
        let $ul = $(this.$refs.ul);

        this.itemHeight = $(this.$el).find('button').height() || 40;
        this.marginTop = parseInt($ul.css('margin-top'), 10);
        this.marginBottom = parseInt($ul.css('margin-bottom'), 10);
        this.paddingTop = parseInt($ul.css('padding-top'), 10);
        this.paddingBottom = parseInt($ul.css('padding-bottom'), 10);
        this.scrollbarTop = this.itemHeight;
    },
    computed: {
        // ...mapGetters(['allProjectPhases']),
        ...mapGetters(['wbsPhasesAndMilestones']),
        phaseOrMilestoneLabel() {
            const out = [];

            if (this.planning.phase) {
                out.push(this.planning.phase.label);
            }
            if (this.planning.milestone) {
                out.push(this.planning.milestone.label);
            }
            if (this.planning.parent) {
                out.push(this.planning.parent.label);
            }

            return out.length ? out.join(' > ') : this.translateText('message.select_phase_milestone');
        },
        dropdownData() {
            return this
                .wbsPhasesAndMilestones
                .filter(wp => wp.id != this.$route.params.id)
            ;
        },
        scrollbarHeight() {
            const itemsToShow = this.dropdownData.length < this.maxItems
                ? this.dropdownData.length
                : this.maxItems
            ;

            return (itemsToShow * this.itemHeight)
                + (this.marginBottom + this.marginTop)
                + (this.paddingBottom + this.paddingTop);
        },
    },
    watch: {
        planning: {
            handler(value) {
                this.$emit('input', value);
            },
            deep: true,
        },
    },
    data() {
        return {
            planning: {
                phase: null,
                milestone: null,
                itemHeight: 0,
                paddingTop: 0,
                paddingBottom: 0,
                marginTop: 0,
                marginBottom: 0,
                scrollbarTop: 0,
            },
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../../css/_variables';
    @import '../../../../css/_mixins';
    @import '../../../../css/_common';

    .dropdown {
        .dropdown-menu {
            position: absolute;

            ul {
                list-style: none;
                margin: 0;
                padding: 5px;
            }
        }
    }

    .btn-primary {
        background: $darkColor;
        color: $lightColor;
        border: none;
        width: 100% !important;
        text-transform: uppercase;
        height: 40px;
        font-size: 11px;
        line-height: 43px;
        letter-spacing: 0.1em;
        border-radius: 1px;
        text-align: left;
        padding: 0 35px 0 20px;
        position: relative;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        @include transition(all, 0.2s, ease-in);

        @media screen and (max-width: 1440px) {
            width: 120px;
        }

        .caret {
            right: 20px;
            top: 18px;
            position: absolute;
        }

        &:hover,
        &:focus {
            background: $middleColor;
            border-color: $darkColor;
        }

    }

    .btn-primary.active, .btn-primary:active,
    .open > .dropdown-toggle.btn-primary {
        background-color: $middleColor;
        border-color: $darkColor;

        &:hover,
        &:focus {
            background-color: $middleColor;
            border-color: $darkColor;
        }
    }

    .nested {
        margin-left: 45px;
    }

    .unselectable:hover {
        cursor: default;
        color: $lightColor;
        background-color: $darkColor;
    }
</style>
