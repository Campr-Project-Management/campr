<template>
    <div>
        <h3>{{ message.condition }}</h3>
        <p v-for="status in colorStatuses" class="condition-info">
            <span class="caps" v-bind:style="{ color: status.color }">{{ status.name }}:</span>
            {{ status.description }}
        </p>
        <div class="flex flex-space-between flex-v-center margintop20">
            <div class="status-boxes flex flex-v-center">
                <div
                    v-for="status in colorStatuses"
                    v-bind:class="{'status-box': true, 'selected': selectedStatusColor && selectedStatusColor.id === status.id }"
                    v-bind:style="'background-color:' + status.color"
                    v-on:click="selectColorStatus(status)" />
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';

export default {
    props: ['selectedStatusColor'],
    methods: {
        ...mapActions(['getColorStatuses']),
        selectColorStatus: function(status) {
            this.$emit('input', status);
        },
    },
    created() {
        if (!this.$store.state.colorStatus || this.$store.state.colorStatus.items.length == 0) {
            this.getColorStatuses();
        }
    },
    computed: mapGetters({
        colorStatuses: 'colorStatuses',
    }),
    data: function() {
        return {
            label: {
                status: this.translate('label.status'),
            },
            message: {
                condition: this.translate('message.condition'),
            },
        };
    },
};
</script>

<style scoped lang="scss">
    @import '../../../../css/_variables';

    .condition-info {
        font-weight: 300;
        font-size: 12px;
        margin-bottom: 8px;
    }

    .status-boxes {
        .status-box {
            width: 30px;
            height: 30px;
            margin-right: 5px;
            opacity: 0.2 !important;
            background-color: $darkColor;

            &.selected {
                opacity: 1 !important;
            }
        }
    }
</style>
