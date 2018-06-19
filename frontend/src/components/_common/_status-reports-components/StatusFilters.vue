<template>
    <div class="flex flex-space-between">
        <member-search ref="createdBy" v-model="createdBy" v-bind:placeholder="translateText('placeholder.search_members')" v-bind:singleSelect="true"></member-search>
        <div class="full-filters">
            <div class="filters">
                <span class="title">{{ translateText('message.filter_by') }}</span>
                <div class="dropdowns">
                    <div class="flex flex-space-between dates">
                        <div class="input-holder">
                            <date-field
                                    :placeholder="translateText('label.date')"
                                    @cleared="clearDate()"
                                    :clear-button="true"
                                    v-model="date"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import MemberSearch from '../../_common/MemberSearch';
    import moment from 'moment';
    import DateField from '../_form-components/DateField';

    export default {
        props: ['updateFilters'],
        components: {
            DateField,
            MemberSearch,
        },
        methods: {
            translateText: function(text) {
                return this.translate(text);
            },
            clearDate: function() {
                this.date = null;
            },
        },
        data() {
            return {
                date: null,
                createdBy: null,
            };
        },
        watch: {
            date: function(value) {
                let date = value ? moment(value).format('YYYY-MM-DD') : null;
                this.updateFilters('date', date);
            },
            createdBy: function(value) {
                this.updateFilters('createdBy', value);
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
