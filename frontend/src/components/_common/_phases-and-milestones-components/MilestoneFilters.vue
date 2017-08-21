<template>
    <div class="filters">
        <span class="title">{{ translateText('message.filter_by') }}</span>
        <div class="dropdowns">
            <div class="flex flex-space-between dates">
                <div class="input-holder right">
                    <label class="active">{{ translateText('label.due_date') }}</label>
                    <datepicker @cleared="clearDueDate()" v-bind:clear-button="true" v-model="dueDate" format="dd - MM - yyyy" :value="dueDate"></datepicker>
                    <calendar-icon fill="middle-fill" stroke="middle-stroke"></calendar-icon>
                </div>
            </div>
            <dropdown ref="phases" :selectedValue="selectPhase" v-if="projectPhases.items && projectPhases.items.length" v-bind:title="'Phase'" item="milestone" filter="phase" :options="projectPhasesForSelect"></dropdown>
            <dropdown ref="statuses" :selectedValue="selectStatus" v-if="!boardView" title="Status" :options="workPackageStatusesForMilestone"></dropdown>
            <dropdown ref="responsibles" :selectedValue="selectResponsible" v-bind:title="'Responsible'" item="phase" filter="responsible" :options="projectUsersForSelect"></dropdown>
            <a @click="clearFilters()" class="btn-rounded btn-auto second-bg">{{ translateText('button.clear_filters') }}</a>
        </div>
    </div>
</template>

<script>
import Dropdown from '../Dropdown2';
import {mapActions, mapGetters} from 'vuex';
import datepicker from 'vuejs-datepicker';
import CalendarIcon from '../../_common/_icons/CalendarIcon';

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
        Dropdown,
        datepicker,
        CalendarIcon,
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
            this.$refs.statuses.resetCustomTitle();
            this.$refs.responsibles.resetCustomTitle();
            this.clearAllFilters(true);
        },
    },
    data() {
        return {
            dueDate: '',
        };
    },
    watch: {
        dueDate: function(value) {
            this.dueDate = value;
            this.selectDueDate(value);
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
