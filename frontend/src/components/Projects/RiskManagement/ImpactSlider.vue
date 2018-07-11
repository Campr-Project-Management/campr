<template>
    <div class="impact-slider">
        <range-slider
                :disabled="disabled"
                :title="translate(this.label)"
                minSuffix=" %"
                :step="step"
                v-model="lazyValue"/>
        <div class="slider-indicator" v-if="avg >= 0">
            <indicator-icon
                    fill="middle-fill"
                    :position="avg"
                    :title="translate(avgTooltip)"/>
        </div>
    </div>
</template>

<script>
    import RangeSlider from '../../_common/_form-components/RangeSlider';
    import IndicatorIcon from '../../_common/_icons/IndicatorIcon';

    export default {
        name: 'risk-management-impact-slider',
        props: {
            value: {
                type: Number,
                required: false,
                default: 0,
            },
            label: {
                type: String,
                required: false,
                default: 'message.impact',
            },
            avg: {
                type: Number,
                required: false,
                default: -1,
            },
            avgTooltip: {
                type: String,
                required: false,
                default: 'message.average_impact_risks',
            },
            disabled: {
                type: Boolean,
                required: false,
                default: false,
            },
            step: {
                type: Number,
                required: false,
                default: 5,
            },
        },
        components: {
            IndicatorIcon,
            RangeSlider,
        },
        watch: {
            value(val) {
                this.lazyValue = this.value;
            },
            lazyValue(value) {
                this.$emit('input', value);
            },
        },
        data() {
            return {
                lazyValue: this.value,
            };
        },
    };
</script>

<style scoped lang="scss">
    .impact-slider {
        position: relative;

        .slider-indicator {
            position: absolute;
            bottom: -18px;
            left: 0;
            width: 100%;
            height: 18px;
        }
    }
</style>
