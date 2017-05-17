<template>
    <div class="filters">
        <span class="title">{{ translateText('message.filter_by') }}</span>
        <div class="dropdowns">
            <div class="flex flex-space-between dates">
                <div class="input-holder left">
                    <label class="active">{{ translateText('message.start_date') }}</label>
                    <datepicker v-model="startDate" format="dd - MM - yyyy" :value="startDate"></datepicker>
                    <calendar-icon fill="middle-fill" stroke="middle-stroke"></calendar-icon>
                </div>
                <div class="input-holder left">
                    <label class="active">{{ translateText('message.finish_date') }}</label>
                    <datepicker v-model="endDate" format="dd - MM - yyyy" :value="endDate"></datepicker>
                    <calendar-icon fill="middle-fill" stroke="middle-stroke"></calendar-icon>
                </div>
            </div>
            <dropdown :selectedValue="selectedStatusValue" filter="status" item="phase" :title="'Status'" :options="statusesLabel"></dropdown>
            <dropdown :selectedValue="selectResponsible" v-bind:title="'Responsible'" item="phase" filter="responsible" :options="projectUsersForSelect"></dropdown>
        </div>
    </div>
</template>

<script>
import Dropdown from '../Dropdown2';
import {mapActions, mapGetters} from 'vuex';
import datepicker from 'vuejs-datepicker';
import CalendarIcon from '../../_common/_icons/CalendarIcon';

export default {
    props: ['selectStatus', 'selectResponsible', 'selectStartDate', 'selectEndDate'],
    created() {
        this.getTaskStatuses();
        this.getProjectUsers({id: this.$route.params.id});
    },
    components: {
        Dropdown,
        datepicker,
        CalendarIcon,
    },
    computed: {
        ...mapGetters({
            taskStatuses: 'taskStatuses',
            projectUsersForSelect: 'projectUsersForSelect',
        }),
        statusesLabel: function() {
            let statuses = this.taskStatuses.map(item => ({label: this.translate(item.name), key: item.id}));
            statuses.unshift({label: 'Status', key: null});
            return statuses;
        },
    },
    methods: {
        ...mapActions(['getTaskStatuses', 'getProjectUsers']),
        selectedStatusValue: function(value) {
            this.selectStatus(value);
        },
        translateText: function(text) {
            return this.translate(text);
        },
    },
    data() {
        return {
            startDate: '',
            endDate: '',
        };
    },
    watch: {
        startDate: function(value) {
            this.selectStartDate(value);
            this.startDate = value;
        },
        endDate: function(value) {
            this.selectEndDate(value);
            this.endDate = value;
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../../css/filters';

  .dates {
      .input-holder {
          width: 50%;

          &.left {
              margin-right: 15px;
          }

          &.right {
              margin-left: 15px;
          }
      }
  }
</style>
