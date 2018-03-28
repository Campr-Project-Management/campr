<template>
    <div class="task-label-bar" :style="{backgroundColor: color}">{{ title }}</div>
</template>

<script>
    export default {
        name: 'task-label-bar',
        props: {
            title: {
                type: String,
                required: true,
            },
            color: {
                type: String,
                required: true,
                validation(value) {
                    return value.length >= 3;
                },
            },
        },
    };
</script>

<style lang="scss" scoped>
    @import '../../css/_variables';

    .task-label-bar {
        z-index: 1;
        height: 5px;
        position: absolute;
        width: 100%;
        left: 0;
        bottom: 0;
        text-align: center;
        color: $whiteColor;
        text-transform: uppercase;
        font-size: 0;
        padding: 0;
        cursor: pointer;

        &:hover {
            font-size: 10px;
            letter-spacing: 0.1em;
            padding: 1em;
            height: auto;
        }
    }
</style>
