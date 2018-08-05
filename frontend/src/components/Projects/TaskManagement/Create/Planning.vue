<template>
    <div>
        <h3>{{ translate('message.planning') }}</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group last-form-group">
                    <div class="dropdown">
                        <button
                                ref="btn-dropdown"
                                class="btn btn-primary dropdown-toggle"
                                type="button"
                                data-toggle="dropdown"
                                @click="updateScrollbarTop()">
                            {{ label }}
                            <span class="caret"></span>
                        </button>
                        <scrollbar :style="{height: scrollbarHeight + 'px', top: scrollbarTop + 'px'}"
                                   class="dropdown-menu dropdown-menu-right customScrollbar">
                            <ul ref="ul">
                                <li v-for="option in options">
                                    <a
                                            href="javascript:void(0)"
                                            @click="onClick(option)"
                                            :style="{paddingLeft: ((option.level+1) * 10) + 'px'}">{{ option.name }}</a>
                                </li>
                            </ul>
                        </scrollbar>
                    </div>
                    <error at-path="parent"/>
                    <error at-path="milestone"/>
                    <error at-path="phase"/>
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
            value: {
                type: Object,
                required: false,
                default: {
                    phase: null,
                    milestone: null,
                    parent: null,
                },
            },
            placeholder: {
                type: String,
                required: false,
                default: 'message.select_phase_milestone',
            },
            maxItems: {
                type: Number,
                default: 5,
            },
        },
        methods: {
            ...mapActions([
                'getWBSByProjectID',
            ]),
            onClick(wp) {
                let value = {
                    phase: null,
                    milestone: null,
                    parent: null,
                };

                if (wp.isPhase) {
                    value.phase = wp.id;
                }

                if (wp.isMilestone) {
                    value.phase = wp.phase;
                    value.milestone = wp.id;
                }

                if (wp.isTask) {
                    value.phase = wp.phase;
                    value.milestone = wp.milestone;
                    value.parent = wp.id;
                }

                this.$emit('input', value);
            },
            updateScrollbarTop() {
                let scrollTop = $(window)
                    .scrollTop();
                let elementOffset = $(this.$el)
                    .offset().top;
                let currentElementOffset = (elementOffset - scrollTop);
                let windowInnerHeight = window.innerHeight;

                if (windowInnerHeight - currentElementOffset < (this.scrollbarHeight + this.itemHeight)) {
                    this.scrollbarTop = -1 * this.scrollbarHeight;
                } else {
                    this.scrollbarTop = this.itemHeight;
                }
            },
            getWorkPackageNameById(id) {
                let wp = this.wbsPhasesAndMilestones.find((wp) => wp.id === id);
                if (!wp) {
                    return;
                }

                return wp.name;
            },
        },
        created() {
            this.getWBSByProjectID(this.$route.params.id);
        },
        mounted() {
            let $ul = $(this.$refs.ul);

            this.itemHeight = $(this.$el)
                .find('button')
                .height() || 40;
            this.marginTop = parseInt($ul.css('margin-top'), 10);
            this.marginBottom = parseInt($ul.css('margin-bottom'), 10);
            this.paddingTop = parseInt($ul.css('padding-top'), 10);
            this.paddingBottom = parseInt($ul.css('padding-bottom'), 10);
            this.scrollbarTop = this.itemHeight;
        },
        computed: {
            ...mapGetters([
                'wbsPhasesAndMilestones',
            ]),
            label() {
                const out = [];

                if (this.value.phase) {
                    let name = this.getWorkPackageNameById(this.value.phase);
                    if (name) {
                        out.push(name);
                    }
                }

                if (this.value.milestone) {
                    let name = this.getWorkPackageNameById(this.value.milestone);
                    if (name) {
                        out.push(name);
                    }
                }

                if (this.value.parent) {
                    let name = this.getWorkPackageNameById(this.value.parent);
                    if (name) {
                        out.push(name);
                    }
                }

                return out.length ? out.join(' > ') : this.translate(this.placeholder);
            },
            options() {
                return this.wbsPhasesAndMilestones;
            },
            scrollbarHeight() {
                const itemsToShow = this.options.length < this.maxItems
                    ? this.options.length
                    : this.maxItems
                ;

                return (itemsToShow * this.itemHeight)
                    + (this.marginBottom + this.marginTop)
                    + (this.paddingBottom + this.paddingTop);
            },
        },
        data() {
            return {
                planning: {
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
