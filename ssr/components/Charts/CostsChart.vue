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
    };
</script>
