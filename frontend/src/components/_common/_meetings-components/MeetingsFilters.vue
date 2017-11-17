<template>
    <div class="filters">
        <span class="title">{{ translateText('message.filter_by') }}</span>
        <div class="dropdowns">
            <div class="mr20">
                <input-field type="text" :label="translateText('message.event')" v-model="name" :content="name"/>
            </div>
            <dropdown ref="categories" :selectedValue="selectedCategory" :title="translateText('message.category')"
                      :options="meetingCategoriesForSelect" item="meetings" filter="category"></dropdown>
            <div class="input-holder right">
                <datepicker @cleared="clearDate()" v-bind:clear-button="true" v-model="date" format="dd-MM-yyyy" :placeholder="translateText('label.date')"
                            :value="date"></datepicker>
                <calendar-icon fill="middle-fill"/>
            </div>
            <a @click="clearFilters()" class="btn-rounded btn-auto second-bg">{{ translateText('button.clear_filters') }}</a>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';
    import Dropdown from '../_form-components/Datepicker';
    import CalendarIcon from '../../_common/_icons/CalendarIcon';
    import datepicker from 'vuejs-datepicker';
    import InputField from '../../_common/_form-components/InputField';

    export default {
        props: ['clearAllFilters', 'selectEvent', 'selectCategory', 'selectDate'],
        components: {
            Dropdown,
            CalendarIcon,
            datepicker,
            InputField,
        },
        created() {
            this.getMeetingCategories();
        },
        methods: {
            ...mapActions(['getMeetingCategories']),
            translateText: function(text) {
                return this.translate(text);
            },
            selectedCategory: function(value) {
                this.selectCategory(value);
            },
            clearDate: function() {
                this.date = null;
            },
            clearFilters: function() {
                this.clearDate();
                this.name = '';
                this.$refs.categories.resetCustomTitle();
                this.clearAllFilters(true);
            },
        },
        computed: {
            ...mapGetters({
                meetingCategoriesForSelect: 'meetingCategoriesForSelect',
            }),
        },
        data() {
            return {
                date: '',
                name: '',
            };
        },
        watch: {
            date: function(value) {
                this.selectDate(value);
                this.date = value;
            },
            name: function(value) {
                if (value.length > 2 || value.length === 0) {
                    this.selectEvent(value);
                    this.name = value;
                }
            },
        },
    };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/filters';

    .mr20 {
        margin-right: 20px;
    }
</style>
