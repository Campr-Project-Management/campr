<template>
    <div class="status-boxes flex flex-v-center" :class="klass">
        <div
                class="status-box"
                v-for="color in trafficLightColors"
                :style="getStyle(color)"></div>
    </div>
</template>

<script>
    import colors from '../../util/colors';

    export default {
        name: 'traffic-light',
        props: {
            status: {
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
        },
        computed: {
            trafficLightColors() {
                return [
                    colors.trafficLight.green,
                    colors.trafficLight.yellow,
                    colors.trafficLight.red,
                ];
            },
            trafficLightColor() {
                return colors.getTrafficLightColorByStatus(this.status);
            },
            klass() {
                if (this.size === 'large') {
                    return {'large-status-boxes': true};
                }

                return '';
            },
        },
        methods: {
            getStyle(color) {
                let data = {};

                if (this.trafficLightColor === color) {
                    data.background = color;
                }

                return data;
            },
        },
    };
</script>

<style scoped lang="scss">
    @import '../../css/_variables';

    .status-boxes {
        &.large-status-boxes {
            margin-bottom: 30px;

            .status-box {
                width: 56px;
                height: 56px;
                margin-right: 5px;
                background-color: $fadeColor;
            }
        }

        .status-box {
            background: $darkerColor;
            margin-right: 5px;
            width: 30px;
            height: 30px;
            cursor: default;
        }
    }
</style>
