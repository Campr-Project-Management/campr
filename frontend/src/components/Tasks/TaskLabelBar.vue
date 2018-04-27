<template>
    <div class="task-label-bar" :style="{backgroundColor: color}">
        <div class="task-label-bar-hover" :style="{backgroundColor: color}"><span>{{ title }}</span></div>
    </div>
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
    @import '../../css/_mixins';

    .task-label-bar {
        z-index: 1;
        height: 7px;
        position: relative;
        width: 100%;
        text-align: center;
        color: $whiteColor;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 600;        

        .task-label-bar-hover {
            position: absolute;
            z-index: 1;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 0;
            padding: 0;
            overflow: hidden;
            @include transition(padding, 0.2s, ease);

            span {
                font-size: 0;
                @include opacity(0);
                visibility: hidden;
                @include transition(opacity, 0.2s, ease);
            }
        }        

        &:hover {
            .task-label-bar-hover {
                padding: 1em;
                height: auto;

                span {
                    font-size: 10px;
                    @include opacity(1);
                    visibility: visible;
                }
            }
        }
    }
</style>
