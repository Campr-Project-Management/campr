<template>
    <div class="slider-holder">
        <div class="heading flex flex-space-between">
            <span class="title">{{ title }}</span>
            <span class="value">
                <span class="text" v-if="minPrefix">{{ minPrefix }}</span>
                <span class="from number">{{ currentValue | rangeValue }}</span>
                <span class="text" v-if="minSuffix">{{ minSuffix }}</span>
            </span>
        </div>

        <input type="text"
               class="range"
               ref="slider"
               :value="value"/>
    </div>
</template>

<script>
import $ from 'jquery';
import 'ion-rangeslider/js/ion.rangeSlider.js';
import 'ion-rangeslider/css/ion.rangeSlider.css';
import 'ion-rangeslider/css/ion.rangeSlider.skinHTML5.css';

export default {
    name: 'range-slider',
    props: {
        title: {
            type: String,
            required: true,
        },
        min: {
            type: [Number, String],
            required: false,
            default: 0,
        },
        max: {
            type: [Number],
            required: false,
            default: 100,
        },
        type: {
            type: String,
            required: false,
            default: 'single',
            validator(value) {
                return ['double', 'single'].indexOf(value) >= 0;
            },
        },
        minPrefix: {
            type: String,
            required: false,
        },
        minSuffix: {
            type: String,
            required: false,
        },
        maxPrefix: {
            type: String,
            required: false,
        },
        maxSuffix: {
            type: String,
            required: false,
        },
        values: {
            type: Array,
            required: false,
            default: () => [],
        },
        value: {
            type: [Number, String, Array],
            required: false,
            default: 0,
            validator(value) {
                if (Array.isArray(value)) {
                    return value.length === 2;
                }

                return true;
            },
        },
        disabled: {
            type: Boolean,
            required: false,
            default: false,
        },
        step: {
            type: Number,
            required: false,
            default: 1,
        },
    },
    computed: {
        fromIndex() {
            return this.valueToIndex(this.getFromValue(this.value));
        },
        toIndex() {
            return this.valueToIndex(this.getToValue(this.value));
        },
        minIndex() {
            if (this.values.length > 0) {
                return 0;
            }

            return this.min;
        },
        maxIndex() {
            if (this.values.length > 0) {
                return this.values.length - 1;
            }

            return this.max;
        },
    },
    mounted() {
        this.$nextTick(() => {
            const $slider = this.slider();

            this.currentValue = this.value;

            let config = {
                type: this.type,
                min: this.minIndex,
                max: this.maxIndex,
                from: this.fromIndex,
                to: this.toIndex,
                values: this.values,
                disable: this.disabled,
                step: this.step,
                onFinish: (data) => {
                    this.onFinish(data.from, data.to);
                },
                onChange: (data) => {
                    this.onChange(data.from, data.to);
                },
            };

            $slider.ionRangeSlider(config);
        });
    },
    methods: {
        slider() {
            return $(this.$refs.slider);
        },
        onChange(frm, to) {
            this.currentValue = this.outputValue(this.indexToValue(frm), this.indexToValue(to));
        },
        onFinish(frm, to) {
            this.$emit('input', this.outputValue(this.indexToValue(frm), this.indexToValue(to)));
        },
        outputValue(frm, to) {
            if (this.type === 'double') {
                return [frm, to];
            }

            return frm;
        },
        getFromValue(value) {
            if (this.type === 'double') {
                return value[0];
            }

            return value;
        },
        getToValue(value) {
            if (this.type === 'double') {
                return value[1];
            }

            return value;
        },
        indexToValue(index) {
            if (index == null) {
                return index;
            }

            if (this.values.length > 0) {
                let value = this.values[index];
                return value == null ? this.values[this.minIndex] : value;
            }

            return index;
        },
        valueToIndex(value) {
            if (value == null) {
                return value;
            }

            if (this.values.length > 0) {
                let index = this.values.indexOf(value);
                return index < 0 ? this.minIndex : index;
            }

            return value;
        },
    },
    watch: {
        value(val) {
            this.currentValue = val;
            const $slider = this.slider();
            if (!$slider) {
                return;
            }

            const irs = $slider.data().ionRangeSlider;
            if (!irs || irs.dragging) {
                return;
            }

            irs.update({
                from: this.getFromValue(this.valueToIndex(val)),
                to: this.getToValue(this.valueToIndex(val)),
            });
        },
        disabled(val) {
            const $slider = this.slider();
            const irs = $slider.data().ionRangeSlider;

            if (irs) {
                irs.update({
                    disable: val,
                });
            }
        },
    },
    filters: {
        rangeValue(value) {
            if (Array.isArray(value)) {
                return `${value[0]} - ${value[1]}`;
            }

            return value;
        },
    },
    data() {
        return {
            currentValue: 0,
        };
    },
};
</script>

<style lang="scss">
    @import '../../../css/_variables.scss';

    .slider-holder {
        .irs-min, .irs-max, .irs-from, .irs-to, .irs-single {
            display: none !important;
            visibility: hidden !important;
        }

        .irs-line {
            background: $darkColor !important;
            border: none !important;
        }

        .irs-bar {
            background: $middleColor !important;
            border: none !important;
        }

        .irs-bar-edge {
            background: $middleColor !important;
            border: none !important;
        }

        .irs-slider {
            font-size: 0 !important;
            background: $secondColor !important;
            border: 2px solid $secondDarkColor !important;
        }

        .task-sidebar {
            .irs-line {
                background: $middleColor !important;
                border: none !important;
            }

            .irs-bar,
            .irs-bar-edge {
                background: $secondColor !important;
                border: none !important;
            }
        }
    }
</style>

<style scoped lang="scss">
    @import '../../../css/_variables.scss';

    .slider-holder {
        text-transform: uppercase;
        color: $lightColor;
        position: relative;
        margin-bottom: 35px;

        .heading {
            position: absolute;
            width: 100%;
        }

        .title {
            letter-spacing: 1.9px;
        }

        .value {
            letter-spacing: 1.6px;
        }

        .number {
            color: $secondColor;
        }

        .slider {
            margin-top: 9px;
            width: 100%;
            height: 11px;
            padding: 0;
        }

        .range-slider-rail, .range-slider-fill {
            height: 10px;
            border-radius: 5px;
        }

        .range-slider-rail {
            background: $darkColor;
        }

        .range-slider-fill {
            background: $middleColor;
        }

        .range-slider-knob {
            background: $secondColor;
            border: 2px solid $secondDarkColor;
        }
    }
</style>
