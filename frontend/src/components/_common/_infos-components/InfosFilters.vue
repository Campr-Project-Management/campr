<template>
    <div class="filters">
        <span class="title">{{ translate('message.filter_by') }}</span>
        <div class="dropdowns">            
            <member-search
                v-model="user"
                v-bind:placeholder="translate('placeholder.responsible')"
                v-bind:singleSelect="true"></member-search>
            <dropdown
                v-bind:title="translate('message.category')"
                v-bind:options="infoCategoriesForDropdown"
                :selectedValue="setFiltersInfoCategory"></dropdown>
            <button v-on:click="clearFilters" class="btn-rounded btn-auto second-bg">{{ translate('filter.clear') }}</button>
        </div>
    </div>
</template>

<script>
import Dropdown from '../../_common/Dropdown';
import MemberSearch from '../../_common/MemberSearch';
import {mapActions, mapGetters} from 'vuex';

export default {
    components: {
        Dropdown,
        MemberSearch,
    },
    created() {
        this.getInfoCategories();
    },
    methods: {
        ...mapActions(['getInfoCategories']),
        setFiltersInfoCategory(val) {
            this.$emit('set-info-category', val);
        },
        clearFilters() {
            this.$emit('clear-filters');
        },
    },
    computed: {
        ...mapGetters(['infoCategoriesForDropdown']),
    },
    watch: {
        user(val) {
            this.$emit('set-user', val);
        },
    },
    data() {
        return {
            user: null,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../../css/filters';
</style>
