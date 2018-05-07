<script>
    import {Line} from 'vue-chartjs';

    export default {
        name: 'status-report-trend-chart',
        extends: Line,
        props: {
            data: {
                type: Array,
                required: true,
                default: () => [],
            },
            labels: {
                type: Array,
                required: true,
                default: () => [],
            },
            height: {
                type: Number,
                required: false,
                default: 140,
            },
            pointColor: {
                type: Array,
                required: false,
                default: () => [],
            },
            options: {
                type: Object,
                required: false,
                default: () => {
                    return {
                        responsive: true,
                        title: {
                            display: false,
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false,
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true,
                        },
                        legend: {
                            display: false,
                        },
                        scales: {
                            xAxes: [
                                {
                                    gridLines: {
                                        color: '#2E3D60',
                                        drawTicks: false,
                                        lineWidth: 1,
                                    },
                                    display: true,
                                    scaleLabel: {
                                        display: false,
                                    },
                                    ticks: {
                                        padding: 10,
                                        fontColor: '#646EA0',
                                    },
                                },
                            ],
                            yAxes: [
                                {
                                    display: true,
                                    gridLines: {
                                        color: '#2E3D60',
                                        drawTicks: false,
                                        lineWidth: 1,
                                    },
                                    ticks: {
                                        padding: 10,
                                        fontColor: '#646EA0',
                                        callback: function(value, index, values) {
                                            return Math.ceil(value) !== value ? null : value;
                                        },
                                    },
                                },
                            ],
                        },
                    };
                },
            },
        },
        computed: {
            chartLabels() {
                return this.labels.map(label => label.toUpperCase());
            },
            chartData() {
                let pointBackgroundColor = '#8794C4';
                if (this.pointColor && this.pointColor.length > 0) {
                    pointBackgroundColor = this.pointColor;
                }

                return {
                    labels: this.chartLabels,
                    datasets: [
                        Object.assign({}, this.datasetOptions, {data: this.data, pointBackgroundColor, pointHoverBackgroundColor: pointBackgroundColor}),
                    ],
                };
            },
        },
        data() {
            return {
                datasetOptions: {
                    label: null,
                    backgroundColor: '#8794C4',
                    borderColor: '#8794C4',
                    borderWidth: 0,
                    pointBackgroundColor: '#8794C4',
                    pointBorderWidth: 0,
                    pointRadius: 6,
                    fill: false,
                    lineTension: 0,
                },
            };
        },
        mounted() {
            this.renderChart(this.chartData, this.options);
        },
    };
</script>
