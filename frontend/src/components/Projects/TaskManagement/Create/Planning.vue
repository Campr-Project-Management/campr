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
                            <li v-for="phase in nestedPhasesAndMilestone">
                                <a href="javascript:void(0)" class="unselectable">{{ phase.label }}</a>
                                <ul class="nested">
                                    <li v-for="milestone in phase.children">
                                        <a href="javascript:void(0)" v-on:click="updateMilestone(phase, milestone)">{{ milestone.label }}</a>
                                    </li>
                                </ul>
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
//    props: ['phase', 'milestone'],
    props: ['editPlanning'],
    components: {
    },
    methods: {
        ...mapActions(['getProjectMilestones', 'getProjectPhases']),
        translateText(text) {
            return this.translate(text);
        },
        updateMilestone: function(phase, milestone) {
            this.planning.phase = {
                key: phase.key,
                label: phase.label,
            };

            this.planning.milestone = {
                key: milestone.key,
                label: milestone.label,
            };
        },
        clearPhaseMilestone: function() {
            this.planning = {
                phase: null,
                milestone: null,
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
        this.getProjectPhases({projectId: this.$route.params.id});
        this.getProjectMilestones({projectId: this.$route.params.id});

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
        ...mapGetters({
//            initialProjectMilestonesForSelect: 'projectMilestonesForSelect',
            projectMilestonesForSelect: 'projectMilestonesForSelect',
            allProjectMilestones: 'allProjectMilestones',
        }),
        phaseOrMilestoneLabel: function() {
            if (this.planning.milestone && this.planning.phase) {
                return this.planning.phase.label + ' > ' +this.planning.milestone.label;
            }
            if (this.planning.phase ) {
                return this.planning.phase.label;
            }

            return this.translateText('message.select_phase_milestone');
        },
        nestedPhasesAndMilestone: function() {
            let nestedPM = {};

            if (this.allProjectMilestones.items === undefined) {
                return;
            }

            for(let i=0; i<this.allProjectMilestones.items.length; i++ ) {
                let currentMilestone = this.allProjectMilestones.items[i];

                if(currentMilestone.phase === null)
                    continue;

                if (nestedPM[currentMilestone.phase] == undefined) {
                    nestedPM[currentMilestone.phase] = {
                        key: currentMilestone.phase,
                        label: currentMilestone.phaseName,
                        children: [
                            {
                                key: currentMilestone.id,
                                label: currentMilestone.name,
                            },
                        ],
                    };
                } else {
                    nestedPM[currentMilestone.phase].children.push({
                        key: currentMilestone.id,
                        label: currentMilestone.name,
                    });
                }
            }
            return nestedPM;
        },
        // @TODO: re-enable this after the milestones and phases modules are implemented
//        projectMilestonesForSelect: function() {
//            if (!this.planning.phase) {
//                return this.initialProjectMilestonesForSelect;
//            }
//            let milestones = new Set(this.initialProjectMilestonesForSelect.filter(item => {
//                return item.parent === parseInt(this.planning.phase.key, 10);
//            }));
//            if (this.planning.milestone && !milestones.has(this.planning.milestone)) {
//                this.planning.milestone = '';
//            }
//            return Array.from(milestones);
//        },
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

