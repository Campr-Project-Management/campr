<template>
    <div class="filters">
        <span class="title">{{ translateText('message.filter_by') }}</span>
        <div class="dropdowns">
            <member-search v-model="responsibility" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
            <dropdown v-bind:title="translateText('message.event')" v-bind:options="projectMeetingsForSelect" :selectedValue="selectedEvent"></dropdown>
            <dropdown v-bind:title="translateText('message.category')" v-bind:options="decisionCategoriesForSelect" :selectedValue="selectedCategory"></dropdown>
        </div>
    </div>
</template>

<script>
import Dropdown from '../../_common/Dropdown';
import MemberSearch from '../../_common/MemberSearch';
import {mapGetters, mapActions} from 'vuex';

export default {
    props: ['updateFilters'],
    components: {
        Dropdown,
        MemberSearch,
    },
    methods: {
        ...mapActions([
            'getDecisionCategories', 'getProjectMeetings',
        ]),
        translateText: function(text) {
            return this.translate(text);
        },
        selectedStatus: function(value) {
            this.updateFilters('status', value);
        },
        selectedCategory: function(value) {
            this.updateFilters('decisionCategory', value);
        },
        selectedEvent: function(value) {
            this.updateFilters('meeting', value);
        },
    },
    computed: {
        ...mapGetters({
            decisionCategoriesForSelect: 'decisionCategoriesForSelect',
            projectMeetingsForSelect: 'projectMeetingsForSelect',
        }),
    },
    created() {
        this.getDecisionCategories();
        this.getProjectMeetings({projectId: this.$route.params.id});
    },
    watch: {
        responsibility: function(value) {
            this.updateFilters('responsibility', value);
        },
    },
    data() {
        return {
            responsibility: null,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../../css/filters';
</style>
