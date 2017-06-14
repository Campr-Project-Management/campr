<template>
    <div class="filters">
        <span class="title">{{ translateText('message.filter_by') }}</span>
        <div class="dropdowns">            
            <member-search v-model="Responsible" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
            <dropdown v-bind:title="translateText('message.category')" v-bind:options="category" item="project" filter="category"></dropdown>
            <div class="input-holder">
                <label class="active">{{ translateText('label.due_date') }}</label>
                <datepicker v-model="schedule.due_date" format="dd-MM-yyyy" />
                <calendar-icon fill="middle-fill" stroke="middle-stroke" />
            </div>
            <dropdown v-bind:title="translateText('message.status')" v-bind:options="status" item="project" filter="status"></dropdown>
        </div>
    </div>
</template>

<script>
import Dropdown from '../../_common/Dropdown';
import CalendarIcon from '../../_common/_icons/CalendarIcon';
import datepicker from 'vuejs-datepicker';
import MemberSearch from '../../_common/MemberSearch';

export default {
    components: {
        Dropdown,
        CalendarIcon,
        datepicker,
        MemberSearch,
    },
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
    },
    data() {
        return {
            category: [{label: 'Production', key: 1}, {label: 'Logistics', key: 2}, {label: 'Quality Management', key: 3},
             {label: 'Human Resources', key: 4}, {label: 'Purchasing', key: 5}, {label: 'Maintenance', key: 6},
              {label: 'Assembly', key: 7}, {label: 'Tooling', key: 8}, {label: 'Process Engineering', key: 9}, {label: 'Industrialization', key: 10}],
            status: [{label: 'Initiated', key: 1}, {label: 'Ongoing', key: 2}, {label: 'On Hold', key: 3},
             {label: 'Discontinued', key: 4}, {label: 'Finished', key: 5}],
            schedule: {
                due_date: new Date(),
            },
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../../css/filters';
</style>
