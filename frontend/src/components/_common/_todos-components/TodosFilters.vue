<template>
    <div class="filters">
        <span class="title">{{ translateText('message.filter_by') }}</span>
        <div class="dropdowns">
            <member-search ref="responsibles" v-model="responsibility"
                           v-bind:placeholder="translateText('placeholder.responsible')"
                           v-bind:singleSelect="true"></member-search>
            <dropdown ref="categories" v-bind:title="translateText('message.category')"
                      v-bind:options="todoCategoriesForSelect" :selectedValue="selectedCategory"></dropdown>
            <div class="input-holder">
                <date-field
                        :placeholder="translateText('label.due_date')"
                        v-model="dueDate"
                        :clear-button="true"
                        @cleared="clearDate()"/>
            </div>
            <select-field
                    v-bind:title="translateText('message.status')"
                    v-bind:options="todoStatusesForSelect"
                    v-model="statusModel"
                    v-bind:currentOption="statusModel"/>
            <a @click="clearFilters()" class="btn-rounded btn-auto second-bg">{{ translateText('button.clear_filters') }}</a>
        </div>
    </div>
</template>

<script>
    import Dropdown from '../../_common/Dropdown';
    import MemberSearch from '../../_common/MemberSearch';
    import SelectField from '../../_common/_form-components/SelectField';
    import {mapGetters, mapActions} from 'vuex';
    import moment from 'moment';
    import DateField from '../_form-components/DateField';

    export default {
        props: ['updateFilters'],
        components: {
            DateField,
            Dropdown,
            MemberSearch,
            SelectField,
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
                this.statusModel = null;
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
            statusModel: function(value) {
                if (this.statusModel != null) {
                    this.updateFilters('status', value.key);
                } else {
                    this.updateFilters('status', null);
                }
            },
        },
        data() {
            return {
                todoCategory: null,
                status: [],
                responsibility: null,
                dueDate: null,
                statusModel: null,
            };
        },
    };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/filters';
</style>
