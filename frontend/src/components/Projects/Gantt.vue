<template>
    <div>
        <h1>Gantt chart</h1>
        <div ref="gantt_chart" id="gantt_chart" style="width:100%; height:100%; min-height: 600px;">
            <p>Loading...</p>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';

export default {
    name: 'gantt',
    created() {
        this.getGanttData(this.$route.params.id);
    },
    methods: {
        ...mapActions(['getGanttData']),
    },
    computed: {
        ...mapGetters(['ganttData', 'ganttDataFormatted', 'ganttDataLinks']),
        // @NOTE: This is a WIP, it's only here as POC
        ganttDataFormatted() {
            return this.ganttData.map((item, key) => {
                // {"id":11, "text":"Project #1", "start_date":"28-03-2013", "duration":"11", "progress": 0.6, "open": true},
                // scheduledStartAt
                // scheduledFinishAt
                // forecastStartAt
                // forecastFinishAt
                // actualStartAt
                // actualFinishAt
//            console.log((item.actualStartAt || item.forecastFinishAt || item.scheduledStartAt) || '28-03-2013');
                let dt = new Date();
                dt.setTime(dt.getTime() + (24 * 3600 * 1000 * key));
                let out = {
                    id: item.id,
                    text: item.puid + ' ' + item.name,
//                    start_date: (item.actualStartAt || item.forecastFinishAt || item.scheduledStartAt) || '28-03-2017',
                    start_date: dt.getDate() + '-' + (dt.getMonth() + 1) + '-' + dt.getFullYear(),
                    duration: 5,
                    progress: .5,
                    open: true,
                };
                if (item.parent) {
                    out.parent = item.parent;
                }
                return out;
            });
        },
        ganttDataLinks() {
            const out = [];
            // {"id":"1","source":"1","target":"2","type":"1"},
            this.ganttData.forEach((item) => {
                if (item.parent) {
                    out.push({
                        id: out.length + 1,
                        source: item.parent,
                        target: item.id,
//                        type: item.type,
//                        type: item.parent
//                            ? this.ganttData.filter((itm) => itm.id === item.parent)[0].type
//                            : item.type,
                        type: 0,
                    });
                }
            });
            return out;
        },
    },
    watch: {
        ganttData(value) {
            if (value && value.length) {
                this.$refs['gantt_chart'].innerHTML = '';
                gantt.init('gantt_chart');
//                console.log({
//                    data: this.ganttDataFormatted,
//                    links: this.ganttDataLinks,
//                });
                gantt.parse({
                    data: this.ganttDataFormatted,
                    links: this.ganttDataLinks,
                });
            }
            return value;
        },
    },
    data() {
        return {
            data: [],
            links: [],
        };
    },
};
</script>

<style scoped="scoped">
    /* gantt shit */
</style>
