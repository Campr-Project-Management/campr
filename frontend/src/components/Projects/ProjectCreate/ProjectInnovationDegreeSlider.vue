<template>
    <div class="project-innovation-degree-slider">
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

    let values = [0, 1, 2, 3];

    export default {
        name: 'ProjectInnovationDegreeSlider',
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
                default: 'message.project_innovation_degree',
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
                this.translate('message.project_innovation_degree_slider.label.enhancements'),
                this.translate('message.project_innovation_degree_slider.label.incremental'),
                this.translate('message.project_innovation_degree_slider.label.strategic_innovation'),
                this.translate('message.project_innovation_degree_slider.label.radical_innovation'),
            ];

            return {
                values: values,
                labels: labels,
            };
        },
    };
</script>
