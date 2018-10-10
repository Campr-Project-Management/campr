<template>
    <div class="project-strategical-meaning-slider">
        <range-slider
                :title="translate(title)"
                :values="values"
                :value="value"
                :labels="labels"
                @input="onChange"/>
    </div>
</template>

<script>
    import RangeSlider from '../../_common/_form-components/RangeSlider';

    let values = [0, 1, 2];

    export default {
        name: 'ProjectStrategicalMeaningSlider',
        props: {
            value: {
                type: Number,
                required: false,
                default: 0,
                validator(value) {
                    return values.indexOf(value) >= 0;
                },
            },
            title: {
                type: String,
                required: false,
                default: 'message.project_strategical_meaning',
            },
        },
        components: {
            RangeSlider,
        },
        methods: {
            onChange(value) {
                this.$emit('input', value);
            },
        },
        data() {
            let labels = [
                this.translate('message.project_strategical_meaning_slider.label.low'),
                this.translate('message.project_strategical_meaning_slider.label.medium'),
                this.translate('message.project_strategical_meaning_slider.label.high'),
            ];

            return {
                values: values,
                labels: labels,
            };
        },
    };
</script>
