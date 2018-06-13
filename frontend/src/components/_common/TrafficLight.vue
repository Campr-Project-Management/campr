<template>
    <div class="status-boxes flex flex-v-center" :class="classes">
        <div
                v-for="tl in trafficLights"
                class="status-box"
                :class="{active: isActive(tl)}"
                :style="getStyle(tl)"
                @click="onClick(tl)"
                v-tooltip.top-center="getTooltip(tl)">
            <div
                    v-if="editable && !isActive(tl)"
                    :style="{backgroundColor: tl.getColor()}"></div>
        </div>
    </div>
</template>

<script>
    import {TrafficLight} from '../../util/traffic-light';

    export default {
        name: 'traffic-light',
        props: {
            value: {
                type: Number,
                required: false,
                default: 0,
            },
            size: {
                type: String,
                required: false,
                default: '',
                validator(value) {
                    let sizes = ['large', 'small'];

                    return !value || sizes.indexOf(value) >= 0;
                },
            },
            editable: {
                type: Boolean,
                required: false,
                default: false,
            },
            showTooltips: {
                type: Boolean,
                required: false,
                default: true,
            },
        },
        computed: {
            classes() {
                let classes = {
                    editable: this.editable,
                };

                if (this.size === 'large') {
                    classes['large-status-boxes'] = true;
                }

                return classes;
            },
        },
        methods: {
            isActive(tl) {
                return tl.getValue() === this.value;
            },
            getStyle(tl) {
                let data = {};

                if (this.isActive(tl)) {
                    data.background = tl.getColor();
                }

                return data;
            },
            onClick(tl) {
                if (!this.editable || this.isActive(tl)) {
                    return;
                }

                this.$emit('input', tl.getValue());
            },
            getTooltip(tl) {
                if (!this.showTooltips) {
                    return;
                }

                return this.translate(tl.getLabel());
            },
        },
        data() {
            return {
                trafficLights: [
                    TrafficLight.createGreen(),
                    TrafficLight.createYellow(),
                    TrafficLight.createRed(),
                ],
            };
        },
    };
</script>

<style scoped lang="scss">
    @import '../../css/_variables';

    @mixin normal_box {
        background: $darkerColor;
        margin-right: 5px;
        width: 30px;
        height: 30px;
        cursor: default;
        border-radius: 50%;
    }

    @mixin large_box {
        width: 56px;
        height: 56px;
        margin-right: 5px;
        background-color: $fadeColor;
        border-radius: 50%;
    }

    .status-boxes {
        .status-box {
            @include normal_box;

            div {
                @include normal_box;
                opacity: 0;
                transition: opacity 0.5s ease-in-out;
                -moz-transition: opacity 0.5s ease-in-out;
                -webkit-transition: opacity 0.5s ease-in-out;
            }
        }

        &.large-status-boxes {
            margin-bottom: 30px;

            .status-box {
                @include large_box;

                div {
                    @include large_box;
                }
            }
        }

        &.editable {
            .status-box {
                &:not(.active) {
                    cursor: pointer;

                    &:hover {
                        div {
                            cursor: pointer;
                            opacity: 0.5;
                            display: block;
                            transition: opacity .55s ease-in-out;
                            -moz-transition: opacity .55s ease-in-out;
                            -webkit-transition: opacity .55s ease-in-out;
                        }
                    }
                }
            }
        }
    }
</style>
