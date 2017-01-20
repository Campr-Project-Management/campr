<template>
    <div class="project-contract">
        <div class="page-section flex">
            <div class="page-side">
                <div class="header">
                    <div class="flex">
                        <h1>Project Contract</h1>
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
                <input-field type="textarea" label="Project Description" :content="project.description"></input-field>
                <input-field type="textarea" label="Project Start Event"></input-field>

                <div class="flex flex-space-between">
                    <div class="input-holder">
                        <label class="active">Proposed start date</label>
                        <datepicker :value="date | moment('DD / MM / YYYY')" format="DD / MM / YYYY"></datepicker>
                        <calendar-icon fill="middle-fill" stroke="middle-stroke"></calendar-icon>
                    </div>

                    <div class="input-holder">
                        <label class="active">Proposed end date</label>
                        <datepicker :value="date | moment('DD / MM / YYYY')" format="DD / MM / YYYY"></datepicker>
                        <calendar-icon fill="middle-fill" stroke="middle-stroke"></calendar-icon>
                    </div>
                </div>

                <div class="flex flex-space-between">

                    <div class="input-holder">
                        <label class="active">Forecast end date</label>
                        <datepicker :value="date | moment('DD / MM / YYYY')" format="DD / MM / YYYY"></datepicker>
                        <calendar-icon fill="middle-fill" stroke="middle-stroke"></calendar-icon>
                    </div>
                    <div class="input-holder">
                        <label class="active">Forecast end date</label>
                        <datepicker :value="date | moment('DD / MM / YYYY')" format="DD / MM / YYYY" class="red"></datepicker>
                        <calendar-icon fill="lighter-fill" stroke="lighter-stroke"></calendar-icon>
                    </div>
                </div>

                <div class="header">
                    <div class="flex">
                        <h1>Project Objectives</h1>
                    </div>
                </div>
                <div v-dragula="colOne" drake="objectives">
                    <drag-box v-for="(item, index) in project.objectives" v-bind:item="item" v-bind:index="index"></drag-box>
                </div>
                <input-field type="text" label="New Objective Title"></input-field>
                <input-field type="textarea" label="New Objective Description"></input-field>
                <div class="flex flex-direction-reverse">
                    <a class="btn-rounded" href="">Add New Objective +</a>
                </div>

                <div class="header">
                    <div class="flex">
                        <h1>Project Limitations</h1>
                    </div>
                </div>
                <div v-dragula="colOne" drake="limitations">
                    <drag-box v-for="(item, index) in project.limitations" v-bind:item="item" v-bind:index="index"></drag-box>
                </div>
                <input-field type="text" label="New Project Limitation"></input-field>
                <div class="flex flex-direction-reverse">
                    <a class="btn-rounded" href="">Add New Limitation +</a>
                </div>

                <div class="header">
                    <div class="flex">
                        <h1>Project Deliverables</h1>
                    </div>
                </div>
                <div v-dragula="colOne" drake="deliverables">
                    <drag-box v-for="(item, index) in project.deliverables" v-bind:item="item" v-bind:index="index"></drag-box>
                </div>
                <input-field type="text" label="New Project Deliverable"></input-field>
                <div class="flex flex-direction-reverse">
                    <a class="btn-rounded" href="kk">Add New Deliverable +</a>
                </div>
            </div>
            <div class="page-side"></div>
        </div>
    </div>
</template>

<script>
import Vue from 'vue';
import {mapGetters, mapActions} from 'vuex';
import DragBox from './DragBox';
import inputField from '../_common/_form-components/inputField';
import CalendarIcon from '../_common/_icons/CalendarIcon';
import EyeIcon from '../_common/_icons/EyeIcon';
import datepicker from 'vue-date-picker';

export default {
    components: {
        DragBox,
        datepicker,
        inputField,
        CalendarIcon,
        EyeIcon,
    },
    methods: {
        ...mapActions(['getProjectById']),
    },
    created() {
        this.getProjectById(this.$route.params.id);
        const service = Vue.$dragula.$service;
        service.eventBus.$on('drop', (atrs) => {
            // TODO: Change order of elements
            console.log(atrs);
        });
    },
    computed: mapGetters({
        project: 'project',
    }),
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss">
    @import '../../css/_common';
</style>
<style scoped lang="scss">
  @import '../../css/page-section';

  .calendar-icon {
      position: absolute;
      right: 20px;
      top: 4px;
  }

  .project-contract {
      padding-bottom: 50px;
  }

  .page-side {
      width: 50%;
  }

  .btn-rounded {
      width: 220px;
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
</style>
