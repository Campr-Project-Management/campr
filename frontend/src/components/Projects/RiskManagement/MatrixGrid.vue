<template>
    <div class="matrix-grid">
        <div class="matrix-grid-header vertical-axis-header">
            <div class="big-header">{{ translate('message.impact') }}</div>
            <div class="small-headers clearfix">
                <div class="small-header">{{ translate('message.very_low') }}</div>
                <div class="small-header">{{ translate('message.low') }}</div>
                <div class="small-header">{{ translate('message.high') }}</div>
                <div class="small-header">{{ translate('message.very_high') }}</div>
            </div>
        </div>
        <div class="matrix-grid-items clearfix">
            <template v-for="(row, rowIndex) in matrix">
                <div
                        v-for="(p, colIndex) in row"
                        :key="`${rowIndex}_${colIndex}`"
                        class="matrix-grid-item"
                        :style="{backgroundColor: getPriorityColor(p)}"
                        :class="{active: isActive(rowIndex, colIndex), clickable, selected: isSelected(rowIndex, colIndex)}"
                        @click="onPriorityClick(p, rowIndex, colIndex)">
                    <span v-if="getLabel(rowIndex, colIndex)">{{ getLabel(rowIndex, colIndex) }}</span>
                </div>
            </template>
        </div>
        <div class="matrix-grid-header horizontal-axis-header">
            <div class="small-headers clearfix">
                <div class="small-header">{{ translate('message.very_low') }}</div>
                <div class="small-header">{{ translate('message.low') }}</div>
                <div class="small-header">{{ translate('message.high') }}</div>
                <div class="small-header">{{ translate('message.very_high') }}</div>
            </div>
            <div class="big-header">{{ translate('message.probability') }}</div>
        </div>
    </div>
</template>

<script>
    import {riskManagement} from '../../../util/colors';

    export default {
        name: 'risk-management-matrix-grid',
        props: {
            active: {
                type: Array,
                required: false,
                default: () => [],
            },
            priorities: {
                type: Array,
                required: false,
                default: () => [
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
            },
            matrix: {
                type: Array,
                required: false,
                default: () => [
                    [2, 3, 4, 4],
                    [1, 2, 3, 4],
                    [0, 1, 2, 3],
                    [0, 0, 1, 2],
                ],
            },
            labels: {
                type: Array,
                required: false,
                default: () => [],
            },
            clickable: {
                type: Boolean,
                required: false,
                default: true,
            },
        },
        methods: {
            isActive(rowIndex, colIndex) {
                if (!this.hasValidActive) {
                    return false;
                }

                return this.rowIndex === rowIndex && this.colIndex === colIndex;
            },
            isSelected(impactIndex, probabilityIndex) {
                return this.selected.impactIndex === impactIndex && this.selected.probabilityIndex === probabilityIndex;
            },
            getPriorityColor(priority) {
                return this.getPriorityItem(priority).color;
            },
            getPriorityItem(priority) {
                return this.priorities.find((p) => p.value === priority);
            },
            getLabel(rowIndex, colIndex) {
                let label = this.labels.find((label) => {
                    return label.position[0] === rowIndex && label.position[1] === colIndex;
                });

                return label ? label.text : null;
            },
            onPriorityClick(priority, impactIndex, probabilityIndex) {
                if (!this.clickable) {
                    return;
                }

                priority = this.getPriorityItem(priority);
                let selected = {priority, impactIndex, probabilityIndex};
                if (this.selected.impactIndex === impactIndex && this.selected.probabilityIndex === probabilityIndex) {
                    selected = {priority: null, impactIndex: null, probabilityIndex: null};
                }

                this.selected = Object.assign({}, selected);

                this.$emit('priorityClick', this.selected);
            },
        },
        computed: {
            rowIndex() {
                if (!this.hasValidActive) {
                    return;
                }

                return this.active[0];
            },
            colIndex() {
                if (!this.hasValidActive) {
                    return;
                }

                return this.active[1];
            },
            hasValidActive() {
                return this.active && this.active.length === 2;
            },
            priority() {
                if (!this.hasValidActive) {
                    return;
                }

                return this.matrix[this.rowIndex][this.colIndex];
            },
        },
        created() {
            this.$emit('activePriority', this.getPriorityItem(this.priority));
        },
        watch: {
            active(value) {
                this.$emit('activePriority', this.getPriorityItem(this.priority));
            },
        },
        data() {
            return {
                selected: {
                    priority: null,
                    impactIndex: null,
                    probabilityIndex: null,
                },
            };
        },
    };
</script>

<style scoped lang="scss">
    @import '../../../css/variables';
    @import '../../../css/mixins';

    .matrix-grid {
        width: 100%;
        margin: 0 auto;
        padding: 0 30px 67px 67px;
        position: relative;

        .matrix-grid-header {
            text-transform: uppercase;
            letter-spacing: 0.1em;
            text-align: center;
            position: absolute;
            width: 100%;
            left: 0;

            .big-header {
                color: $lightColor;
                font-size: 0.875em;
            }

            .small-header {
                width: 25%;
                font-size: 0.75em;
                float: left;
                white-space: nowrap;
            }

            &.vertical-axis-header {
                @include rotate(-90);
                @include transform-origin(left top);
                top: calc(100% - 67px);
                width: calc(100% - 97px);

                .big-header {
                    padding-bottom: 10px;
                    margin-bottom: 10px;
                    border-bottom: 1px solid $darkerColor;
                }
            }

            &.horizontal-axis-header {
                bottom: 0;
                padding: 0 30px 0 67px;

                .big-header {
                    padding-top: 10px;
                    margin-top: 10px;
                    border-top: 1px solid $darkerColor;
                }
            }
        }

        .matrix-grid-items {
            .matrix-grid-item {
                width: 24%;
                height: 0;
                padding-bottom: 24%;
                @include border-radius(50%);
                margin: 0.5%;
                float: left;
                font-size: 3em;
                font-weight: bold;
                color: $mainColor;
                text-align: center;
                white-space: nowrap;
                position: relative;
                @include transition(all, 0.3s, ease);

                span {
                    display: inline-block;
                    vertical-align: middle;
                    width: 100%;
                    height: 30px;
                    line-height: 35px;
                    position: absolute;
                    top: 50%;
                    left: 0;
                    margin-top: -15px;
                }

                &.clickable {
                    cursor: pointer;

                    &:hover, &.selected {
                        background-color: $lightColor !important;
                        @include transition(background-color, 0.2s, ease);
                    }
                }

                &.active {
                    @include box-shadow(0, 0, 60px, $darkColor);
                    z-index: 10;

                    &:after {
                        content: '';
                        position: absolute;
                        left: -5px;
                        top: -5px;
                        width: calc(100% + 10px);
                        height: calc(100% + 10px);
                        border: 10px solid $lighterColor;
                        @include border-radius(50%);
                        @include transition(all, 0.3s, ease);
                    }
                }
            }
        }
    }
</style>
