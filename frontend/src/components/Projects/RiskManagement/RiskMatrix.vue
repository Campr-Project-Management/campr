<script>
    import {riskManagement} from '../../../util/colors';
    import OpportunityMatrix from './OpportunityMatrix';

    export default {
        name: 'risk-matrix',
        extends: OpportunityMatrix,
        data() {
            return {
                priorities: [
                    {
                        value: 4,
                        name: 'very-high',
                        color: riskManagement.risk.getColorByPriority(4),
                    },
                    {
                        value: 3,
                        name: 'high',
                        color: riskManagement.risk.getColorByPriority(3),
                    },
                    {
                        value: 2,
                        name: 'medium',
                        color: riskManagement.risk.getColorByPriority(2),
                    },
                    {
                        value: 1,
                        name: 'low',
                        color: riskManagement.risk.getColorByPriority(1),
                    },
                    {
                        value: 0,
                        name: 'very-low',
                        color: riskManagement.risk.getColorByPriority(0),
                    },
                ],
                matrix: [
                    [2, 3, 4, 4],
                    [1, 2, 3, 4],
                    [0, 1, 2, 3],
                    [0, 0, 1, 2],
                ],
            };
        },
    };
</script>
