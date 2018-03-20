<template>
    <div class="color-field">
        <input-field
                @focus="toggleColorPicker"
                :value="value"
                :label="label"
                :content="value"
                :disabled="disabled"
                :css="{backgroundColor: value}"
                @input="onInput"/>
        <color-picker
                v-show="showColorPicker && !disabled"
                :value="{ hex: value }"
                @input="onColorChange"/>
    </div>
</template>

<script>
    import {Sketch} from 'vue-color';
    import InputField from './InputField';

    export default {
        name: 'color-field',
        props: {
            value: {
                type: String,
                required: false,
                default: '#194d33',
                validation(value) {
                    return value && value.length >= 4;
                },
            },
            label: {
                type: String,
                required: false,
                default: '',
            },
            css: {
                required: false,
            },
            disabled: {
                type: Boolean,
                default: false,
            },
        },
        components: {
            InputField,
            'color-picker': Sketch,
        },
        methods: {
            toggleColorPicker() {
                this.showColorPicker = !this.showColorPicker;
            },
            onInput(value) {
                this.$emit('input', value);
            },
            onColorChange(value) {
                this.$emit('input', value.hex);
            },
        },
        data() {
            return {
                showColorPicker: false,
            };
        },
    };
</script>

<style lang="scss" scoped>
    .color-field {
        position: relative;
    }

    .vc-sketch {
        position: absolute;
        left: 100%;
        bottom: 0;
        margin-left: 10px;
    }
</style>
