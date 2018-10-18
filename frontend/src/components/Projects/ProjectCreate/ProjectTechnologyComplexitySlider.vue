<template>
    <div class="project-technology-complexity-slider">
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
        name: 'ProjectTechnologyComplexitySlider',
        props: {
            value: {
                type: Number,
                required: false,
                default: 1,
                validator(value) {
                    return values.indexOf(value) >= 0;
                },
            },
            title: {
                type: String,
                required: false,
                default: 'message.project_technology_complexity',
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
                this.translate('message.project_technology_complexity_slider.label.low'),
                this.translate('message.project_technology_complexity_slider.label.medium'),
                this.translate('message.project_technology_complexity_slider.label.high'),
            ];

            return {
                values: values,
                labels: labels,
            };
        },
    };
</script>
