<template>
    <vis-timeline :items="items" :with-phases="true"/>
</template>

<script>
    import VisTimeline from '../../../_common/_phases-and-milestones-components/VisTimeline';
    import moment from 'moment';

    export default {
        name: 'status-report-timeline',
        props: {
            phases: {
                type: Array,
                required: true,
                default: () => [],
            },
            milestones: {
                type: Array,
                required: true,
                default: () => [],
            },
        },
        components: {
            VisTimeline,
        },
        computed: {
            phaseItems() {
                let items = this.phases.map((phase) => {
                    let start = phase.actualStartAt || phase.scheduledStartAt || phase.forecastStartAt;
                    if (!start) {
                        return null;
                    }

                    start = moment(start).toDate();

                    let end = phase.actualFinishAt || phase.scheduledFinishAt || phase.forecastFinishAt;
                    if (!end) {
                        end = new Date();
                    }

                    end = moment(end).toDate();

                    if (end < start) {
                        end = moment(start).toDate();
                    }

                    let data = Object.assign({}, phase, {responsibility: phase.responsibilityId});

                    return {
                        id: phase.id,
                        group: 0,
                        content: phase.name,
                        start: start,
                        end: end,
                        value: phase.workPackageStatusId,
                        data: data,
                    };
                });

                return items.filter(item => item);
            },
            milestoneItems() {
                let items = this.milestones.map((milestone) => {
                    let start = milestone.actualFinishAt || milestone.scheduledFinishAt || milestone.forecastFinishAt;
                    start = moment(start).toDate();
                    if (!start) {
                        return null;
                    }

                    let data = Object.assign({}, milestone, {responsibility: milestone.responsibilityId});

                    return {
                        id: milestone.id,
                        group: 1,
                        content: milestone.name,
                        start: start,
                        value: milestone.workPackageStatusId,
                        className: milestone.isKeyMilestone ? 'key-milestone' : '',
                        data: data,
                    };
                });

                return items.filter(item => item);
            },
            items() {
                return [...this.phaseItems, ...this.milestoneItems];
            },
        },
    };
</script>
