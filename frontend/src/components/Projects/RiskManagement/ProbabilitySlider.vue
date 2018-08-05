<script>
    import ImpactSlider from './ImpactSlider';

    export default {
        name: 'risk-management-probability-slider',
        extends: ImpactSlider,
        props: {
            label: {
                type: String,
                required: false,
                default: 'message.probability',
            },
            avgTooltip: {
                type: String,
                required: false,
                default: 'message.average_probability_risks',
            },
        },
    };
</script>
