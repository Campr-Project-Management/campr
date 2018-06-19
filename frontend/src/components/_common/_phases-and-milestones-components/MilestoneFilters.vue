<template>
    <div class="filters">
        <span class="title">{{ translateText('message.filter_by') }}</span>
        <div class="dropdowns">
            <div class="flex flex-space-between dates">
                <div class="input-holder right">
                    <date-field
                            :placeholder="translateText('label.due_date')"
                            @cleared="clearDueDate()"
                            :clear-button="true"
                            v-model="dueDate"/>
                </div>
            </div>
            <select-field
                    v-if="projectPhases.items && projectPhases.items.length"
                    v-bind:title="'Phase'"
                    v-bind:options="projectPhasesForSelect"
                    v-model="phaseModel"
                    v-bind:currentOption="phaseModel"/>
            <select-field
                    v-if="!boardView"
                    v-bind:title="translateText('message.status')"
                    v-bind:options="workPackageStatusesForMilestone"
                    v-model="statusModel"
                    v-bind:currentOption="statusModel"/>
            <select-field
                    v-bind:title="translateText('label.responsible')"
                    v-bind:options="projectUsersForSelect"
                    v-model="responsibleModel"
                    v-bind:currentOption="responsibleModel"/>
            <a @click="clearFilters()" class="btn-rounded btn-auto second-bg">{{ translateText('button.clear_filters') }}</a>
        </div>
    </div>
</template>

<script>
    import Dropdown from '../Dropdown2';
    import {mapActions, mapGetters} from 'vuex';
    import SelectField from '../../_common/_form-components/SelectField';
    import DateField from '../_form-components/DateField';

    export default {
        props: ['clearAllFilters', 'selectStatus', 'selectResponsible', 'selectPhase', 'selectDueDate'],
        created() {
            this.getWorkPackageStatuses();
            this.getProjectUsers({id: this.$route.params.id});
            if (!this.projectPhases.items || this.projectPhases.items.length === 0) {
                this.getProjectPhases({projectId: this.$route.params.id});
            }
        },
        components: {
            DateField,
            Dropdown,
            SelectField,
        },
        computed: {
            ...mapGetters({
                workPackageStatusesForMilestone: 'workPackageStatusesForMilestone',
                projectUsersForSelect: 'projectUsersForSelect',
                projectPhases: 'projectPhases',
                projectPhasesForSelect: 'projectPhasesForSelect',
            }),
        },
        methods: {
            ...mapActions(['getWorkPackageStatuses', 'getProjectUsers', 'getProjectPhases']),
            translateText: function(text) {
                return this.translate(text);
            },
            clearDueDate: function() {
                this.dueDate = null;
            },
            clearFilters: function() {
                this.clearDueDate();
                this.$refs.phases.resetCustomTitle();
                this.statusModel = null;
                this.phaseModel = null;
                this.responsibleModel = null;
                this.$refs.responsibles.resetCustomTitle();
                this.clearAllFilters(true);
            },
        },
        data() {
            return {
                dueDate: '',
                statusModel: null,
                responsibleModel: null,
                phaseModel: null,
            };
        },
        watch: {
            dueDate: function(value) {
                this.dueDate = value;
                this.selectDueDate(value);
            },
            statusModel: function(value) {
                if (this.statusModel !== null) {
                    this.selectStatus(value.key);
                } else {
                    this.selectStatus(null);
                }
            },
            responsibleModel: function(value) {
                if (this.responsibleModel !== null) {
                    this.selectResponsible(value.key);
                } else {
                    this.selectResponsible(null);
                }
            },
            phaseModel: function(value) {
                if (this.phaseModel !== null) {
                    this.selectPhase(value.key);
                } else {
                    this.selectPhase(null);
                }
            },
        },
    };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/filters';

    .dates {
        .input-holder {
            width: 100%;

            &.right {
                margin-right: 15px;
            }
        }
    }
</style>
