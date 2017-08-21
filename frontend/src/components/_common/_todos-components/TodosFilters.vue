<template>
    <div class="filters">
        <span class="title">{{ translateText('message.filter_by') }}</span>
        <div class="dropdowns">            
            <member-search ref="responsibles" v-model="responsibility" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
            <dropdown ref="categories" v-bind:title="translateText('message.category')" v-bind:options="todoCategoriesForSelect" :selectedValue="selectedCategory"></dropdown>
            <div class="input-holder">
                <label class="active">{{ translateText('label.due_date') }}</label>
                <datepicker v-model="dueDate" format="dd-MM-yyyy" v-bind:clear-button="true" @cleared="clearDate()" />
                <calendar-icon fill="middle-fill" stroke="middle-stroke" />
            </div>
            <dropdown ref="statuses" v-bind:title="translateText('message.status')" v-bind:options="todoStatusesForSelect" :selectedValue="selectedStatus"></dropdown>
            <a @click="clearFilters()" class="btn-rounded btn-auto second-bg">{{ translateText('button.clear_filters') }}</a>
        </div>
    </div>
</template>

<script>
import Dropdown from '../../_common/Dropdown';
import CalendarIcon from '../../_common/_icons/CalendarIcon';
import datepicker from 'vuejs-datepicker';
import MemberSearch from '../../_common/MemberSearch';
import {mapGetters, mapActions} from 'vuex';
import moment from 'moment';

export default {
    props: ['updateFilters'],
    components: {
        Dropdown,
        CalendarIcon,
        datepicker,
        MemberSearch,
    },
    computed: {
        ...mapGetters({
            todoStatusesForSelect: 'todoStatusesForSelect',
            todoCategoriesForSelect: 'todoCategoriesForSelect',
        }),
    },
    created() {
        this.getTodoStatuses();
        this.getTodoCategories();
    },
    methods: {
        ...mapActions([
            'getTodoStatuses',
            'getTodoCategories',
        ]),
        selectedStatus: function(value) {
            this.updateFilters('status', value);
        },
        selectedCategory: function(value) {
            this.updateFilters('todoCategory', value);
        },
        translateText: function(text) {
            return this.translate(text);
        },
        clearDate: function() {
            this.dueDate = null;
        },
        clearFilters: function() {
            this.updateFilters('clear', true);
            this.clearDate();
            this.$refs.statuses.resetCustomTitle();
            this.$refs.categories.resetCustomTitle();
            this.$refs.responsibles.clearValue();
        },
    },
    watch: {
        responsibility: function(value) {
            this.updateFilters('responsibility', value);
        },
        dueDate: function(value) {
            let date = value ? moment(value).format('YYYY-MM-DD') : null;
            this.updateFilters('dueDate', date);
        },
    },
    data() {
        return {
            todoCategory: null,
            status: [],
            responsibility: null,
            dueDate: null,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../../css/filters';
</style>
