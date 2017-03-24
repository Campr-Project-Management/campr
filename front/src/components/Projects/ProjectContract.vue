<template>
    <div class="project-contract">
        <div class="page-section flex">
            <div class="page-side left">
                <div class="header">
                    <div class="flex">
                        <h1>{{ message.project_contract }}</h1>
                        <a href="#" class="pdf">
                            <svg version="1.1" id="Layer_1" width="20px" height="20px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                viewBox="0 0 23.1 23.5" style="enable-background:new 0 0 23.1 23.5;" xml:space="preserve">
                                <g id="XMLID_252_">
                                    <g id="XMLID_627_">
                                        <polyline id="XMLID_724_" class="st0" points="13.6,16 15.3,17.7 17.1,16 		"/>
                                        <circle id="XMLID_723_" class="st0" cx="15.3" cy="15.6" r="4.2"/>
                                        <line id="XMLID_722_" class="st0" x1="15.3" y1="13.6" x2="15.3" y2="17.7"/>
                                    </g>
                                    <g id="XMLID_253_">
                                        <g id="XMLID_429_">
                                            <polyline id="XMLID_614_" class="st0" points="10.4,18.4 3.5,18.4 3.5,3.8 11.1,3.8 14.6,7.3 14.6,10.1 			"/>
                                            <polyline id="XMLID_539_" class="st0" points="11.1,3.8 11.1,7.3 14.6,7.3 			"/>
                                        </g>
                                    </g>
                                    <g id="XMLID_621_">
                                        <path id="XMLID_619_" class="st0" d="M8.5,11.7V9.3h0.5c0.5,0,0.9,0.5,0.9,1.2c0,0.7-0.4,1.2-0.9,1.2H8.5z"/>
                                        <polyline id="XMLID_618_" class="st0" points="10.8,11.7 10.8,9.3 12,9.3 		"/>
                                        <line id="XMLID_616_" class="st0" x1="10.8" y1="10.3" x2="11.5" y2="10.3"/>
                                        <path id="XMLID_243_" class="st0" d="M6.1,11.7V9.3h0.6c0.3,0,0.6,0.3,0.6,0.6S7,10.5,6.7,10.5H6.1"/>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    </div>
                </div>

                <input-field v-model="description" type="textarea" v-bind:label="message.project_description" :content="project.description"></input-field>

                <input-field v-model="startEvent" type="textarea" v-bind:label="message.project_start_event"></input-field>

                <div class="flex flex-space-between dates">
                    <div class="input-holder left">
                        <label class="active">{{ message.proposed_start_date }}</label>
                        <datepicker :value="date | moment('DD / MM / YYYY')" format="DD / MM / YYYY"></datepicker>
                        <calendar-icon fill="middle-fill" stroke="middle-stroke"></calendar-icon>
                    </div>
                    <div class="input-holder right">
                        <label class="active">{{ message.proposed_end_date }}</label>
                        <datepicker :value="date | moment('DD / MM / YYYY')" format="DD / MM / YYYY"></datepicker>
                        <calendar-icon fill="middle-fill" stroke="middle-stroke"></calendar-icon>
                    </div>
                </div>

                <div class="flex flex-space-between dates right">
                    <div class="input-holder left">
                        <label class="active">{{ message.forecast_start_date }}</label>
                        <datepicker :value="date | moment('DD / MM / YYYY')" format="DD / MM / YYYY"></datepicker>
                        <calendar-icon fill="middle-fill" stroke="middle-stroke"></calendar-icon>
                    </div>
                    <div class="input-holder right">
                        <label class="active">{{ message.forecast_end_date }}</label>
                        <datepicker :value="date | moment('DD / MM / YYYY')" format="DD / MM / YYYY" class="red"></datepicker>
                        <calendar-icon fill="lighter-fill" stroke="lighter-stroke"></calendar-icon>
                    </div>
                </div>

                <div class="header">
                    <div class="flex">
                        <h1>{{ message.project_objectives }}</h1>
                    </div>
                </div>
                <div v-dragula="colOne" drake="objectives">
                    <drag-box v-for="(item, index) in project.objectives" v-bind:item="item" v-bind:index="index"></drag-box>
                </div>
                <div class="hr small"></div>
                <input-field type="text" v-bind:label="message.new_objective_title"></input-field>
                <input-field type="textarea" v-bind:label="message.new_objective_description"></input-field>
                <div class="flex flex-direction-reverse">
                    <a class="btn-rounded" href="">{{ message.add_objective }} +</a>
                </div>

                <div class="header">
                    <div class="flex">
                        <h1>{{ message.project_limitations }}</h1>
                    </div>
                </div>
                <div v-dragula="colOne" drake="limitations">
                    <drag-box v-for="(item, index) in project.limitations" v-bind:item="item" v-bind:index="index"></drag-box>
                </div>
                <div class="hr small"></div>
                <input-field type="text" v-bind:label="message.new_project_limitation"></input-field>
                <div class="flex flex-direction-reverse">
                    <a class="btn-rounded" href="">{{ message.add_limitation }} +</a>
                </div>

                <div class="header">
                    <div class="flex">
                        <h1>{{ message.project_deliverables }}</h1>
                    </div>
                </div>
                <div v-dragula="colOne" drake="deliverables">
                    <drag-box v-for="(item, index) in project.deliverables" v-bind:item="item" v-bind:index="index"></drag-box>
                </div>
                <div class="hr small"></div>
                <input-field type="text" v-bind:label="message.new_project_deliverable"></input-field>
                <div class="flex flex-direction-reverse">
                    <a class="btn-rounded" href="kk">{{ message.add_deliverable }} +</a>
                </div>
            </div>

            <div class="page-side right">
                <div class="header">
                    <h2>{{ message.sponsors_managers }}</h2>
                    <a class="btn-rounded btn-md btn-empty">{{ message.edit_sponsors_managers }}</a>
                </div>
                <div class="flex flex-row flex-center members-big">
                    <member-badge size="big"></member-badge>
                    <member-badge size="big"></member-badge>
                    <member-badge size="big"></member-badge>
                </div>

                <div class="header">
                    <h2>{{ message.team_members }}</h2>
                    <a class="btn-rounded btn-md btn-empty">{{ message.edit_team_members }}</a>
                </div>
                <div class="flex flex-row flex-center members-small">
                    <member-badge size="small"></member-badge>
                    <member-badge size="small"></member-badge>
                    <member-badge size="small"></member-badge>
                    <member-badge size="small"></member-badge>
                    <member-badge size="small"></member-badge>
                    <member-badge size="small"></member-badge>
                </div>

                <div class="header">
                    <div class="flex">
                        <h2>{{ message.project_resources }}</h2>
                    </div>
                </div>
                <vue-chart
                        chart-type="ColumnChart"
                        :columns="columns"
                        :rows="rows"
                        :options="options"
                ></vue-chart>

                <div class="header">
                    <div class="flex">
                        <h2>{{ message.project_costs }}</h2>
                    </div>
                </div>
                <vue-chart
                        chart-type="ColumnChart"
                        :columns="columns"
                        :rows="rows"
                        :options="options"
                ></vue-chart>

                <div class="hr"></div>

                <div class="flex buttons flex-center">
                    <a v-on:click="updateContract()" class="btn-rounded second-bg">{{ button.save }}</a>
                    <a class="btn-rounded second-bg flex flex-center download-pdf">
                        <p>{{ button.download_pdf }}</p>
                        <download-icon></download-icon>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from 'vue';
import {mapGetters, mapActions} from 'vuex';
import DragBox from './DragBox';
import InputField from '../_common/_form-components/InputField';
import CalendarIcon from '../_common/_icons/CalendarIcon';
import DownloadIcon from '../_common/_icons/DownloadIcon';
import EyeIcon from '../_common/_icons/EyeIcon';
import MemberBadge from '../_common/MemberBadge';
import datepicker from 'vue-date-picker';

export default {
    components: {
        DragBox,
        datepicker,
        InputField,
        CalendarIcon,
        DownloadIcon,
        EyeIcon,
        MemberBadge,
    },
    methods: {
        ...mapActions(['getProjectById', 'getContractByProjectId']),
        updateContract: function() {
            let data = {
                description: this.description,
            };
            console.log(data);
        },
    },
    created() {
        this.getProjectById(this.$route.params.id);
        this.getContractByProjectId(this.$route.params.id);
        const service = Vue.$dragula.$service;
        service.eventBus.$on('drop', (atrs) => {
            // TODO: Change order of elements
            console.log(atrs);
        });
    },
    computed: mapGetters({
        project: 'project',
        contract: 'contract',
    }),
    data: function() {
        return {
            message: {
                project_contract: Translator.trans('message.project_contract'),
                project_description: Translator.trans('message.project_description'),
                project_start_event: Translator.trans('message.project_start_event'),
                proposed_start_date: Translator.trans('message.proposed_start_date'),
                proposed_end_date: Translator.trans('message.proposed_end_date'),
                forecast_start_date: Translator.trans('message.forecast_start_date'),
                forecast_end_date: Translator.trans('message.forecast_end_date'),
                project_objectives: Translator.trans('message.project_objectives'),
                new_objective_title: Translator.trans('message.new_objective_title'),
                new_objective_description: Translator.trans('message.new_objective_description'),
                add_objective: Translator.trans('message.add_objective'),
                project_limitations: Translator.trans('message.project_limitations'),
                new_project_limitation: Translator.trans('message.new_project_limitation'),
                add_limitation: Translator.trans('message.add_limitation'),
                project_deliverables: Translator.trans('message.project_deliverables'),
                new_project_deliverable: Translator.trans('message.new_project_deliverable'),
                add_deliverable: Translator.trans('message.add_deliverable'),
                sponsors_managers: Translator.trans('message.sponsors_managers'),
                edit_sponsors_managers: Translator.trans('message.edit_sponsors_managers'),
                team_members: Translator.trans('message.team_members'),
                edit_team_members: Translator.trans('message.edit_team_members'),
                project_resources: Translator.trans('message.project_resources'),
                project_costs: Translator.trans('message.project_costs'),
            },
            button: {
                save: Translator.trans('button.save'),
                donwload_pdf: Translator.trans('button.download_pdf'),
            },
            columns: [{
                'type': 'string',
                'label': Translator.trans('message.year'),
            }, {
                'type': 'number',
                'label': Translator.trans('message.sales'),
            }, {
                'type': 'number',
                'label': 'Column2',
            }, {
                'type': 'number',
                'label': 'Column3',
            }, {
                'type': 'number',
                'label': Translator.trans('message.expenses'),
            }],
            rows: [
                ['2004', 1000, 400, 400, 99],
                ['2005', 1170, 460, 500, 800],
                ['2006', 660, 1120, 400, 1600],
                ['2007', 1030, 540, 400, 67],
            ],
            options: {
                title: Translator.trans('message.resource_chart'),
                hAxis: {
                    title: Translator.trans('message.year'),
                    minValue: '2004',
                    maxValue: '2007',
                    textStyle: {
                        color: '#D8DAE5',
                    },
                },
                vAxis: {
                    title: '',
                    minValue: 300,
                    maxValue: 1200,
                    textStyle: {
                        color: '#D8DAE5',
                    },
                },
                width: '100%',
                height: 300,
                curveType: 'function',
                colors: ['#5FC3A5', '#646EA0', '#2E3D60', '#D8DAE5'],
                backgroundColor: '#191E37',
                titleTextStyle: {
                    color: '#D8DAE5',
                },
                legendTextStyle: {
                    color: '#D8DAE5',
                },
            },
            description: '',
            startEvent: '',
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss">
    @import '../../css/_common';
</style>
<style scoped lang="scss">
    @import '../../css/page-section';

    .header .btn-md {
        margin-top: 24px;
    }

    .members-big {
        margin: 0 -15px;
    }

    .members-small {
        margin: 0 -16px;
    }

    .dates {
        .input-holder {
            width: 50%;

            &.left {
                margin-right: 15px;
            }

            &.right {
                margin-left: 15px;
            }
        }
    }

    .project-contract {
        padding-bottom: 50px;
    }

    .page-side {
        width: 50%;

        &.left {
            padding-right: 15px;
        }

        &.right {
            padding-top: 50px;
            padding-left: 15px;
        }
    }

    .btn-rounded {
        width: 220px;

        &.btn-empty {
            width: auto;
            font-size: 9px;
            padding: 0 26px;
        }
    }

    .st0{
        fill:none;
        stroke:#65BEA3;
        stroke-linecap:round;
        stroke-linejoin:round;
        stroke-miterlimit:10;
    }

    .pdf {
        margin: 30px 13px;
    }

    .download-pdf {
        padding-top: 2px;
    }

    .hr {
        margin: 67px 0;

        &.small {
            margin: 20px 0;
        }
    }

    .buttons {
        a {
            margin: 0 10px;
        }
    }

    .input-holder {
        margin-bottom: 20px;
    }
</style>
