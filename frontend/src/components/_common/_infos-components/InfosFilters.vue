<template>
    <div class="filters">
        <span class="title">{{ translateText('message.filter_by') }}</span>
        <div class="dropdowns">            
            <member-search v-model="Responsible" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
            <dropdown v-bind:title="translateText('message.category')" v-bind:options="category" item="project" filter="category"></dropdown>
            <dropdown v-bind:title="translateText('message.status')" v-bind:options="status" item="project" filter="status"></dropdown>
        </div>
    </div>
</template>

<script>
import Dropdown from '../../_common/Dropdown';
import MemberSearch from '../../_common/MemberSearch';

export default {
    components: {
        Dropdown,
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
             {label: 'Published', key: 4}, {label: 'Expired', key: 5}],
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../../css/filters';
</style>
