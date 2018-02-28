<template>
    <div class="filters">
        <span class="title">{{ translateText('message.filter_by') }}</span>
        <div class="dropdowns">
            <div class="flex flex-space-between dates">
                <div class="input-holder left">
                    <datepicker :placeholder="translateText('message.start_date')" @cleared="clearStart()" v-bind:clear-button="true"  v-model="startDate" format="dd - MM - yyyy" :value="startDate"></datepicker>
                    <calendar-icon fill="middle-fill"></calendar-icon>
                </div>
                <div class="input-holder left">
                    <datepicker :placeholder="translateText('message.finish_date')" @cleared="clearEnd()" v-bind:clear-button="true" v-model="endDate" format="dd - MM - yyyy" :value="endDate"></datepicker>
                    <calendar-icon fill="middle-fill"></calendar-icon>
                </div>
            </div>
            <select-field
                v-bind:title="translateText('message.status')"
                v-bind:options="statusesLabel"
                v-model="statusModel"
                v-bind:currentOption="statusModel" />
            <select-field
                v-bind:title="translateText('label.responsible')"
                v-bind:options="projectUsersForSelect"
                v-model="responsibleModel"
                v-bind:currentOption="responsibleModel" />
            <a @click="clearFilters()" class="btn-rounded btn-auto second-bg">{{ translateText('button.clear_filters') }}</a>
        </div>
    </div>
</template>

<script>
import Dropdown from '../Dropdown2';
import {mapActions, mapGetters} from 'vuex';
import datepicker from '../_form-components/Datepicker';
import CalendarIcon from '../../_common/_icons/CalendarIcon';
import SelectField from '../../_common/_form-components/SelectField';

export default {
    props: ['clearAllFilters', 'selectStatus', 'selectResponsible', 'selectStartDate', 'selectEndDate'],
    created() {
        this.getTaskStatuses();
        this.getProjectUsers({id: this.$route.params.id});
    },
    components: {
        Dropdown,
        datepicker,
        CalendarIcon,
        SelectField,
    },
    computed: {
        ...mapGetters({
            taskStatuses: 'taskStatuses',
            projectUsersForSelect: 'projectUsersForSelect',
        }),
        statusesLabel: function() {
            let statuses = this.taskStatuses.map(item => ({label: this.translate(item.name), key: item.id}));
            return statuses;
        },
    },
    methods: {
        ...mapActions(['getTaskStatuses', 'getProjectUsers']),
        selectedStatusValue: function(value) {
            this.selectStatus(value);
        },
        selectedResponsible: function(value) {
            this.selectResponsible(value);
        },
        translateText: function(text) {
            return this.translate(text);
        },
        clearStart: function() {
            this.startDate = null;
        },
        clearEnd: function() {
            this.endDate = null;
        },
        clearFilters: function() {
            this.clearStart();
            this.clearEnd();
            this.statusModel = null;
            this.responsibleModel = null;
            this.$refs.responsibles.resetCustomTitle();
            this.clearAllFilters(true);
        },
    },
    data() {
        return {
            startDate: '',
            endDate: '',
            statusModel: null,
            responsibleModel: null,
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
