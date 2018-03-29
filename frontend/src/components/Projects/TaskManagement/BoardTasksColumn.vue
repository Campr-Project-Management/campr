<template>
    <div class="column">
        <div class="column-header flex flex-v-center flex-space-between">
            <span>{{ translate(status.name) }}</span>
            <div class="flex">
                <span class="notification-balloon">{{ count }}</span>
                <span class="notification-balloon second-bg">+</span>
            </div>
        </div>
        <div class="tasks-scroll">
            <div>
                <small-task-box
                        v-bind:task="task"
                        v-for="task in tasks"
                        :key="task.id"/>
            </div>
        </div>
    </div>
</template>

<script>
import SmallTaskBox from '../../Dashboard/SmallTaskBox';

export default {
    props: {
        status: {
            type: Object,
            required: true,
        },
        tasks: {
            type: Array,
            required: true,
        },
        count: {
            type: Number,
            required: true,
            default: 0,
        },
    },
    components: {
        SmallTaskBox,
    },
};
</script>

<style scoped lang="scss">
    @import '../../../css/page-section';
    @import '../../../css/_variables';

    .board-view {
        display: inline-block;
        white-space: nowrap;
    }

    .column {
        margin-right: 10px;
        width: 394px;
    }

    .column-header {
        background: $darkColor;
        padding: 20px;
        margin-bottom: 10px;
    }

    .tasks-scroll {
        margin-bottom: 10px;
        position: relative;
        overflow: auto;
    }

    .notification-balloon {
        display: inline-block;
        position: static;
        margin-left: 10px;
    }

    .header .notification-balloon {
        margin-top: 5px
    }

    .categories-scroll {
        width: 100%;
        padding-bottom: 30px;
    }
</style>
