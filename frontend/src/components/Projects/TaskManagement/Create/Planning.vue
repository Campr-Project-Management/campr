<template>
    <div>
        <h3>{{ translateText('message.planning') }}</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group last-form-group">
                    <div class="dropdown">
                        <button ref="btn-dropdown" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" @click="dropdownToggle()">
                            {{ phaseOrMilestoneLabel }}
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right nicescroll" ref="dropdown-menu-planning">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';
import 'jquery.nicescroll/jquery.nicescroll.js';

export default {
    props: ['editPlanning'],
    methods: {
        ...mapActions(['getWBSByProjectID']),
        translateText(text) {
            return this.translate(text);
        },
        updatePlanning: function(wp) {
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
            case 2:
                this.planning = {
                    phase: {
                        key: wp.phase,
                        label: wp.phaseName,
                    },
                    milestone: {
                        key: wp.milestone,
                        label: wp.milestoneName,
                    },
                    parent: {
                        key: wp.id,
                        label: wp.name,
                    },
                };
                break;
            }
        },
        clearPhaseMilestone: function() {
            this.planning = {
                phase: null,
                milestone: null,
                parent: null,
            };
        },
        dropdownToggle: function() {
            let scrollTop = $(window).scrollTop();
            let elementOffset = $(this.$el).offset().top;
            let currentElementOffset = (elementOffset - scrollTop);

            let windowInnerHeight = window.innerHeight;

            if (windowInnerHeight - currentElementOffset < 279) {
                $(this.$el).find('.dropdown-menu').css('top', -5*this.dropdownItemHeight + 'px');
            } else {
                $(this.$el).find('.dropdown-menu').css('top', this.dropdownItemHeight + 'px');
            }
        },
    },
    created() {
        this.getWBSByProjectID(this.$route.params.id);

        this.planning = this.editPlanning;
    },
    mounted() {
        this.dropdownItemHeight = this.$refs['btn-dropdown'].clientHeight;
        $(this.$el).find('.dropdown-menu').css('height', 5*this.dropdownItemHeight + 'px');
        window.$(document).ready(function() {
            window.$('.nicescroll').niceScroll({
                autohidemode: false,
            });
        });
    },
    computed: {
        // ...mapGetters(['allProjectPhases']),
        ...mapGetters(['wbsPhasesAndMilestones']),
        phaseOrMilestoneLabel: function() {
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
        dropdownData: function() {
            return this
                .wbsPhasesAndMilestones
                .filter(wp => wp.id != this.$route.params.id)
            ;
        },
    },
    watch: {
        planning: {
            handler: function(value) {
                this.$emit('input', value);
            },
            deep: true,
        },
    },
    data: function() {
        return {
            planning: {
                phase: null,
                milestone: null,
                dropdownItemHeight: null,
            },
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../../css/_variables';
    @import '../../../../css/_mixins';

    .btn-primary {
        background: $darkColor;
        color: $lightColor;
        border: none;
        width: 100%;
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

        &:focus {
            background: $middleColor;
            color: $lighterColor;
            outline: 0;
        }

    }

    .dropdown-menu{
        &.nicescroll{
             max-height : 210px;
        }
    }

    .btn-primary.active, .btn-primary:active, .open > .dropdown-toggle.btn-primary {
        background: $middleColor;
        color: $lighterColor;
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
