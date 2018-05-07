<template>
    <table class="table table-striped table-responsive table-fixed table-small" v-if="items.length > 0">
        <thead>
        <tr>
            <th style="width:11%">{{ translate('table_header_cell.status') }}</th>
            <th style="width:14%">{{ translate('table_header_cell.due_date') }}</th>
            <th style="width:25%">{{ translate('table_header_cell.topic') }}</th>
            <th style="width:36%">{{ translate('table_header_cell.description') }}</th>
            <th style="width:14%">{{ translate('table_header_cell.responsible') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in items">
            <td>{{ translate(item.statusName) }}</td>
            <td>{{ item.dueDate | date }}</td>
            <td class="cell-wrap">{{ item.title }}</td>
            <td class="cell-wrap" v-html="item.description "></td>
            <td>
                <div class="avatar" v-tooltip.top-center="item.responsibilityFullName"
                     :style="{ backgroundImage: 'url(' + item.responsibilityAvatar + ')' }"></div>
            </td>
        </tr>
        </tbody>
    </table>
    <span v-else>{{ translate('label.no_data') }}</span>
</template>

<script>
    export default {
        name: 'status-report-todos',
        props: {
            items: {
                type: Array,
                required: true,
                default: () => [],
            },
        },
    };
</script>
