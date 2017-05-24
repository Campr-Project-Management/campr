<template>
    <div>
        <h3>{{ message.planning }}</h3>
        <div class="row">
            <div class="form-group last-form-group">
                <div class="col-md-6">
                    <select-field
                        v-bind:title="label.phase"
                        v-bind:options="projectPhasesForSelect"
                        v-bind:currentOption="planning.phase"
                        v-model="planning.phase" />
                </div>
                <div class="col-md-6">
                    <select-field
                        v-bind:title="label.milestone"
                        v-bind:options="projectMilestonesForSelect"
                        v-bind:currentOption="planning.milestone"
                        v-model="planning.milestone" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SelectField from '../../../_common/_form-components/SelectField';
import {mapActions, mapGetters} from 'vuex';

export default {
//    props: ['phase', 'milestone'],
    props: ['editPlanning'],
    components: {
        SelectField,
    },
    methods: {
        ...mapActions(['getProjectMilestones', 'getProjectPhases']),
    },
    created() {
        this.getProjectPhases({projectId: this.$route.params.id});
        this.getProjectMilestones({projectId: this.$route.params.id});

        this.planning = this.editPlanning;
    },
    computed: {
        ...mapGetters({
//            initialProjectMilestonesForSelect: 'projectMilestonesForSelect',
            projectMilestonesForSelect: 'projectMilestonesForSelect',
            projectPhasesForSelect: 'projectPhasesForSelect',
        }),
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
            message: {
                planning: this.translate('message.planning'),
            },
            label: {
                phase: this.translate('label.phase'),
                milestone: this.translate('label.milestone'),
            },
            planning: {
                phase: null,
                milestone: null,
            },
        };
    },
};
</script>
