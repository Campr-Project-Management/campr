<template>
    <div class="risk-management-matrix">
        <matrix-grid
                :priorities="priorities"
                :matrix="matrix"
                :active="active"
                :clickable="clickable"
                :labels="labels"
                @priorityClick="onPriorityClick"
                @activePriority="onActivePriorityChanged"/>
        <div class="ro-summary" v-if="showSummary">
            <div class="text-center flex flex-center">
                <div class="text-right">
                    <p>{{ translate('message.priority') }}:</p>
                </div>
                <div class="text-left">
                    <p>
                        <b v-if="priorityLabel" class="priority" :style="{color: priorityColor}">{{ priorityLabel }}</b>
                        <b v-else>-</b>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {riskManagement} from '../../../util/colors';
    import MatrixGrid from './MatrixGrid';

    export default {
        name: 'opportunity-matrix',
        components: {MatrixGrid},
        props: {
            value: {
                type: Number,
                required: false,
                default: 0,
            },
            impact: {
                type: Number,
                required: false,
                default: null,
            },
            probability: {
                type: Number,
                required: false,
                default: null,
            },
            labels: {
                type: Array,
                required: false,
                default: () => [],
            },
            clickable: {
                type: Boolean,
                required: false,
                default: false,
            },
            showSummary: {
                type: Boolean,
                required: false,
                default: true,
            },
        },
        computed: {
            priorityLabel() {
                if (!this.priority || !this.priority.name) {
                    return;
                }

                let label = this.priority.name.replace('-', '_');

                return this.translate(`message.${label}`);
            },
            priorityColor() {
                if (!this.priority) {
                    return;
                }

                return this.priority.color;
            },
            impactIndex() {
                if (this.impact == null) {
                    return;
                }

                return this.calculateImpactIndex(this.impact);
            },
            probabilityIndex() {
                if (this.probability == null) {
                    return;
                }

                return this.calculateProbabilityIndex(this.probability);
            },
            active() {
                if (this.impactIndex == null || this.probabilityIndex == null) {
                    return [];
                }

                return [this.impactIndex, this.probabilityIndex];
            },
        },
        methods: {
            isActive(i, j) {
                return this.impactIndex === i && this.probabilityIndex === j;
            },
            calculateImpactIndex(impact) {
                if (impact < 25 || !impact) {
                    return 3;
                }

                if (impact >= 25 && impact < 50) {
                    return 2;
                }

                if (impact >= 50 && impact < 75) {
                    return 1;
                }

                return 0;
            },
            calculateProbabilityIndex(probability) {
                if (probability < 25 || !probability) {
                    return 0;
                }
                if (probability >= 25 && probability < 50) {
                    return 1;
                }
                if (probability >= 50 && probability < 75) {
                    return 2;
                }

                return 3;
            },
            onActivePriorityChanged(priority) {
                this.priority = priority;
                this.$emit('input', priority ? priority.value : null);
            },
            onPriorityClick(data) {
                this.$emit('priorityClick', data);
            },
        },
        data() {
            return {
                priority: this.value,
                priorities: [
                    {
                        value: 0,
                        name: 'very-low',
                        color: riskManagement.opportunity.getColorByPriority(0),
                    },
                    {
                        value: 1,
                        name: 'low',
                        color: riskManagement.opportunity.getColorByPriority(1),
                    },
                    {
                        value: 2,
                        name: 'medium',
                        color: riskManagement.opportunity.getColorByPriority(2),
                    },
                    {
                        value: 3,
                        name: 'high',
                        color: riskManagement.opportunity.getColorByPriority(3),
                    },
                    {
                        value: 4,
                        name: 'very-high',
                        color: riskManagement.opportunity.getColorByPriority(4),
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

<style scoped lang="scss">
    @import '../../../css/variables';
    @import '../../../css/mixins';

    .risk-management-matrix {
        .ro-summary {
            margin-top: 60px;
            padding-bottom: 30px;
            font-size: 1.333em;
            text-transform: uppercase;
            letter-spacing: 0.1em;

            p {
                line-height: 1.5em;
                color: $lightColor;

                b {
                    color: $lighterColor;
                }
            }

            .text-left {
                p {
                    padding-left: 15px;
                }
            }

            .priority {
                background-color: transparent;
            }
        }
    }
</style>
