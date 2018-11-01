<script>
    import {costChart as colors} from '../../../frontend/src/util/colors';
    import CostsChart from '../../../frontend/src/components/Projects/Charts/CostsChart';

    export default {
        name: 'charts-costs-chart',
        extends: CostsChart,
        mounted() {
            let limit = 80;
            let x = setInterval(() => {
                if (!limit--) {
                    clearInterval(x);
                    return;
                }

                let elem = this.$el;

                while (true) {
                    if (
                        elem.style
                        && elem.style.position === 'absolute'
                        && elem.attributes
                        && elem.attributes.getNamedItem('aria-label')
                        && elem.attributes.getNamedItem('aria-label').value === 'A chart.'
                        && elem.attributes.removeNamedItem('style')
                    ) {
                        break;
                    }
                    if (!elem || !elem.children || !elem.children.length) {
                        return;
                    }
                    elem = elem.children[0];
                }

                clearInterval(x);
            }, 25);
        },
        data() {
            return {
                defaultOptions: {
                    hAxis: {
                        textStyle: {
                            color: '#000',
                        },
                    },
                    vAxis: {
                        title: '',
                        minValue: 0,
                        maxValue: 0,
                        textStyle: {
                            color: '#000',
                        },
                    },
                    width: 800,
                    height: 350,
                    curveType: 'function',
                    colors: [colors.base, colors.actual, colors.forecast, colors.remaining],
                    backgroundColor: '#fff',
                    titleTextStyle: {
                        color: '#000',
                    },
                    legend: {
                        position: 'bottom',
                        maxLines: 5,
                    },
                    legendTextStyle: {
                        color: '#000',
                    },
                }
            };
        },
    };
</script>
