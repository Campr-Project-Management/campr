<template>
    <div class="filters">
        <span class="title">{{ translateText('message.filter_by') }}</span>
        <div class="dropdowns">
            <dropdown v-bind:title="translateText('message.event')" v-bind:options="event" item="meetings" filter="event"></dropdown>
            <dropdown v-bind:title="translateText('message.category')" v-bind:options="category" item="project" filter="category"></dropdown>
            <div class="input-holder right">
                <label class="active">{{ translateText('label.date') }}</label>
                <datepicker v-model="schedule.date" format="dd-MM-yyyy" />
                <calendar-icon fill="middle-fill" stroke="middle-stroke" />
            </div>
        </div>
    </div>
</template>

<script>
import Dropdown from '../../_common/Dropdown';
import CalendarIcon from '../../_common/_icons/CalendarIcon';
import datepicker from 'vuejs-datepicker';

export default {
    components: {
        Dropdown,
        CalendarIcon,
        datepicker,
    },
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
    },
    data() {
        return {
            event: '',
            category: '',
            schedule: {
                date: new Date(),
            },
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../../css/filters';
</style>
