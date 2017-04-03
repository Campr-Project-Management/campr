<template>
    <div class="create-task page-section">
        <div class="header flex-v-center">
            <h1>{{ message.create_new_task }}</h1>
            <a ef="javascript:void(0)" class="btn-rounded btn-auto btn-empty flex">
                <span>{{ message.import_task }}</span>
                <upload-icon></upload-icon>
            </a>
        </div>
        <div class="form">
            <!-- /// Task Title /// -->
            <input-field v-model="title" type="text" label="Task Title"></input-field>
            <!-- /// End Task Title /// -->

            <!-- /// Task Description /// -->
            <div class="vueditor-header">Task Description</div>
            <Vueditor></Vueditor>
            <div cass="vueditor-footer clearfix">
                <div class="pull-right">
                    
                </div>
            </div>
            <!-- /// End Task Description /// -->

            <hr class="double"> 

            <!-- /// Task Manual Schedule /// -->
            <h3 class="with-label">{{ message.task_schedule }}</h3>
            <div class="checkbox-input clearfix">
                <input id="manual-schedule" type="checkbox" name="" value="" @click="toggleManualSchedule" :checked="visibleManualSchedule === true">
                <label for="manual-schedule">Manual Schedule</label>
            </div>
            <span class="note">Define Base Start and Base Finish dates as well as Forecast Start and Forecast Finish dates.</span>
            <div v-show="visibleManualSchedule">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="input-holder right">
                                <label class="active">{{ label.base_start_date }}</label>
                                <datepicker :value="date | moment('DD-MM-YYYY')" format="DD-MM-YYYY"></datepicker>
                                <calendar-icon fill="lighter-fill" stroke="lighter-stroke"></calendar-icon>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-holder right">
                                <label class="active">{{ label.base_end_date }}</label>
                                <datepicker :value="date | moment('DD-MM-YYYY')" format="DD-MM-YYYY"></datepicker>
                                <calendar-icon fill="lighter-fill" stroke="lighter-stroke"></calendar-icon>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group last-form-group">    
                        <div class="col-md-6">
                            <div class="input-holder right">
                                <label class="active">{{ label.forecast_start_date }}</label>
                                <datepicker :value="date | moment('DD-MM-YYYY')" format="DD-MM-YYYY"></datepicker>
                                <calendar-icon fill="lighter-fill" stroke="lighter-stroke"></calendar-icon>
                            </div>    
                        </div>
                        <div class="col-md-6">
                            <div class="input-holder right">
                                <label class="active">{{ label.forecast_end_date }}</label>
                                <datepicker :value="date | moment('DD-MM-YYYY')" format="DD-MM-YYYY"></datepicker>
                                <calendar-icon fill="lighter-fill" stroke="lighter-stroke"></calendar-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>           
            <!-- /// End Task Manual Schedule /// -->

            <hr>  

            <!-- /// Task Automatic Schedule /// -->
            <div class="checkbox-input clearfix">
                <input id="automatic-schedule" type="checkbox" name="" value="" @click="toggleAutomaticSchedule" :checked="visibleAutomaticSchedule === true">
                <label for="automatic-schedule">Automatic Schedule</label>
            </div>
            <span class="note">Define a Predecessor or a Successor for this task, enter a time period, and the Base Start and Base Finish dates will be calculated automatically.</span>
            <div v-show="visibleAutomaticSchedule">
                <div class="form-group">
                    <div class="radio-input">
                        <input id="automatic-schedule-predecessor" type="radio" name="automatic-schedule-planning" value="task-has-predecessor" @click="togglePredecessor" :checked="visiblePredecessor === true">
                        <label for="automatic-schedule-predecessor">Task has Predecessor</label>
                        <input id="automatic-schedule-successor" type="radio" name="automatic-schedule-planning" value="task-has-successor" @click="toggleSuccessor" :checked="visibleSuccessor === true">
                        <label for="automatic-schedule-successor">Task has Successor</label>
                    </div>
                </div>
                <div v-show="visiblePredecessor">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <select-field v-bind:title="'Select Predecessor'"></select-field>
                            </div>
                            <div class="col-md-6">
                                <input-field type="text" v-bind:label="'Duration in days'"></input-field>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <span class="title">Base Start Date: </span>
                            </div>
                            <div class="col-md-6">
                                <span class="title">Base Finish Date: </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group last-form-group">    
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">Forecast Start Date</label>
                                    <datepicker :value="date | moment('DD-MM-YYYY')" format="DD-MM-YYYY"></datepicker>
                                    <calendar-icon fill="lighter-fill" stroke="lighter-stroke"></calendar-icon>
                                </div>    
                            </div>
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">Forecast Finish Date</label>
                                    <datepicker :value="date | moment('DD-MM-YYYY')" format="DD-MM-YYYY"></datepicker>
                                    <calendar-icon fill="lighter-fill" stroke="lighter-stroke"></calendar-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-show="visibleSuccessor">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <select-field v-bind:title="'Select Successor'"></select-field>
                            </div>
                            <div class="col-md-6">
                                <input-field type="text" v-bind:label="'Duration in days'"></input-field>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <span class="title">Base Start Date: </span>
                            </div>
                            <div class="col-md-6">
                                <span class="title">Base Finish Date: </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group last-form-group">    
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">Forecast Start Date</label>
                                    <datepicker :value="date | moment('DD-MM-YYYY')" format="DD-MM-YYYY"></datepicker>
                                    <calendar-icon fill="lighter-fill" stroke="lighter-stroke"></calendar-icon>
                                </div>    
                            </div>
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">Forecast Finish Date</label>
                                    <datepicker :value="date | moment('DD-MM-YYYY')" format="DD-MM-YYYY"></datepicker>
                                    <calendar-icon fill="lighter-fill" stroke="lighter-stroke"></calendar-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>           
            <!-- /// End Task Automatic Schedule /// -->

            <hr class="double"> 

            <!-- /// Task Internal Costs /// -->
            <h3>Internal Costs</h3>
            <span class="note blue-note">Project Currency: <i class="fa fa-dollar"></i> USD</span>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6">
                        <select-field v-bind:title="'Resource'"></select-field>    
                    </div>
                    <div class="col-md-2">
                        <input-field type="text" v-bind:label="'Daily Rate'" disabled></input-field>
                    </div>
                    <div class="col-md-2">
                        <input-field type="text" v-bind:label="'Qty.'"></input-field>
                    </div>
                    <div class="col-md-2">
                        <input-field type="text" v-bind:label="'Days'"></input-field>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group last-form-group">                
                    <div class="col-md-6">                    
                        <span class="title">Base Start Date: <b><i class="fa fa-dollar"></i> 0</b></span>        
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right">
                            <a ef="javascript:void(0)" class="btn-rounded btn-auto">Add new internal cost +</a>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="form-group last-form-group">                
                    <div class="col-md-4">                    
                        <span class="title">Base Total: <b><i class="fa fa-dollar"></i> 0</b></span>        
                    </div>
                    <div class="col-md-4">
                        <input-field type="text" v-bind:label="'Forecast'"></input-field>
                    </div>
                    <div class="col-md-4">
                        <input-field type="text" v-bind:label="'Actual'"></input-field>
                    </div>
                </div>
            </div>
            <!-- /// End Task Internal Costs /// -->

            <hr class="double">

            <!-- /// Task External Costs /// -->
            <h3>External Costs</h3>
            <span class="note blue-note">Project Currency: <i class="fa fa-dollar"></i> USD</span>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-10">
                        <input-field type="text" v-bind:label="'Description'"></input-field>  
                    </div>
                    <div class="col-md-2">                    
                        <input-field type="text" v-bind:label="'Qty.'"></input-field>        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group last-form-group">
                    <div class="col-md-8">    
                        <span class="title pull-left">Default Unit: </span>                     
                        <div class="radio-input radio-list clearfix">                            
                            <input id="default-unit-g" type="radio" name="default-units" value="g">
                            <label for="default-unit-g">g</label>
                            <input id="default-unit-kg" type="radio" name="default-units" value="kg">
                            <label for="default-unit-kg">Kg</label>
                            <input id="default-unit-ton" type="radio" name="default-units" value="ton">
                            <label for="default-unit-ton">Ton</label>
                            <input id="default-unit-litre" type="radio" name="default-units" value="litre">
                            <label for="default-unit-litre">l</label>
                            <input id="default-unit-meter" type="radio" name="default-units" value="meter">
                            <label for="default-unit-meter">m</label>
                            <input id="default-unit-square-meter" type="radio" name="default-units" value="square-meter">
                            <label for="default-unit-square-meter">m <sup>2</sup></label>
                        </div>
                    </div>
                    <div class="col-md-2">                        
                        <input-field type="text" v-bind:label="'Custom'"></input-field>
                    </div>
                    <div class="col-md-2">                        
                        <input-field type="text" v-bind:label="'Unit Rate'"></input-field>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group last-form-group">                
                    <div class="col-md-12">                    
                        <span class="title">Total: <b><i class="fa fa-dollar"></i> 0</b></span>        
                    </div>
                </div>
            </div>
            <hr>
            <h4>CAPEX</h4>
            <div class="row">
                <div class="form-group last-form-group">
                    <div class="col-md-6">
                        <div class="flex flex-space-between">
                            <switches v-model="test" :selected="true"></switches>
                            <span class="note no-margin-bottom"><b>Note:</b>  If an External Cost is not set up as CAPEX,it will automatically be set up as OPEX.</span> 
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right">
                            <a ef="javascript:void(0)" class="btn-rounded btn-auto">Add new external cost +</a>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="form-group">                
                    <div class="col-md-4">                    
                        <span class="title">CAPEX Subtotal: <b><i class="fa fa-dollar"></i> 0</b></span>        
                    </div>
                    <div class="col-md-4">                    
                        <span class="title">OPEX Subtotal: <b><i class="fa fa-dollar"></i> 0</b></span>        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group last-form-group">                
                    <div class="col-md-4">                    
                        <span class="title">Base Total: <b><i class="fa fa-dollar"></i> 0</b></span>        
                    </div>
                    <div class="col-md-4">
                        <input-field type="text" v-bind:label="'Forecast'"></input-field>
                    </div>
                    <div class="col-md-4">
                        <input-field type="text" v-bind:label="'Actual'"></input-field>
                    </div>
                </div>
            </div>
            <!-- /// end Task External Costs /// -->

            <hr class="double"> 

            <!-- /// Task Details /// -->
            <h3>{{ message.task_details }}</h3>
            <div class="flex flex-space-between">
                <select-field v-bind:title="label.asignee"></select-field>
                <select-field v-bind:title="label.labels" v-bind:options="labelsForChoice"></select-field>
            </div>
            <!-- /// End Task Details /// -->

            <hr class="double">

            <!-- /// Task Planning /// -->
            <h3>{{ message.planning }}</h3>
            <div class="flex flex-space-between">
                <select-field v-bind:title="label.phase"></select-field>
                <select-field v-bind:title="label.milestone"></select-field>
            </div>
            <!-- /// End Task Planning /// -->

            <hr class="double">

            <!-- /// Subtasks /// -->
            <h3>{{ message.subtasks }}</h3>
            <input-field type="text" v-bind:label="label.subtask_description"></input-field>
            <div class="flex flex-direction-reverse">
                <a ef="javascript:void(0)" class="btn-rounded btn-auto add-task">{{ button.add_new_subtask }} +</a>
            </div>
            <!-- /// End Subtasks /// -->

            <hr class="double">

            <!-- /// Task Condition /// -->
            <h3>{{ label.status }}</h3>
            <div class="status-info">
                <p v-for="status in colorStatuses"><span v-bind:style="{ color: status.color }">{{ status.name }}</span></p>
            </div>
            <div class="status flex">
                <div v-for="status in colorStatuses" v-bind:style="{ background: status.color }" class="status-item"></div>
            </div>
            <!-- /// End Task Condition /// -->

            <hr class="double">

            <!-- /// Task Actions /// -->
            <div class="flex flex-space-between">
                <router-link :to="{name: 'project-task-management-list'}" class="btn-rounded btn-auto disable-bg">{{ button.cancel }}</router-link>
                <a v-on:click="createTask" class="btn-rounded btn-auto second-bg">{{ button.create_task }}</a>
            </div>
            <!-- /// End Task Actions /// -->
        </div>
    </div>
</template>

<script>
import InputField from '../../_common/_form-components/InputField';
import SelectField from '../../_common/_form-components/SelectField';
import UploadIcon from '../../_common/_icons/UploadIcon';
import CalendarIcon from '../../_common/_icons/CalendarIcon';
import datepicker from 'vuejs-datepicker';
import Switches from '../../3rdparty/vue-switches';
import {mapActions, mapGetters} from 'vuex';

export default {
    components: {
        InputField,
        SelectField,
        UploadIcon,
        CalendarIcon,
        datepicker,
        Switches,
    },
    methods: {
        ...mapActions(['getColorStatuses', 'getProjectLabels', 'createNewTask']),
        createTask: function() {
            let data = {
                project: this.$route.params.id,
                name: this.title,
                puid: '123',
                progress: 0,
            };
            this.createNewTask(data);
        },
        toggleManualSchedule: function() {
            this.visibleManualSchedule = !this.visibleManualSchedule;
            this.visibleAutomaticSchedule = !this.visibleAutomaticSchedule;
        },
        toggleAutomaticSchedule: function() {
            this.visibleAutomaticSchedule = !this.visibleAutomaticSchedule;
            this.visibleManualSchedule = !this.visibleManualSchedule;
        },
        togglePredecessor: function() {
            this.visiblePredecessor = !this.visiblePredecessor;
            this.visibleSuccessor = !this.visibleSuccessor;
        },
        toggleSuccessor: function() {
            this.visibleSuccessor = !this.visibleSuccessor;
            this.visiblePredecessor = !this.visiblePredecessor;
        },
    },
    created() {
        if (!this.$store.state.colorStatus || this.$store.state.colorStatus.items.length == 0) {
            this.getColorStatuses();
        }
        if (!this.$store.state.project.labelsForChoice || this.$store.state.project.labelsForChoice.length == 0) {
            this.getProjectLabels(this.$route.params.id);
        }
    },
    computed: mapGetters({
        colorStatuses: 'colorStatuses',
        labelsForChoice: 'labelsForChoice',
    }),
    data() {
        return {
            message: {
                create_new_task: Translator.trans('message.create_new_task'),
                import_task: Translator.trans('message.import_task'),
                task_schedule: Translator.trans('message.task_schedule'),
                task_details: Translator.trans('message.task_details'),
                planning: Translator.trans('message.planning'),
                subtasks: Translator.trans('message.subtasks'),
            },
            label: {
                base_start_date: Translator.trans('label.base_start_date'),
                base_end_date: Translator.trans('label.base_end_date'),
                forecast_start_date: Translator.trans('label.forecast_start_date'),
                forecast_end_date: Translator.trans('label.forecast_end_date'),
                status: Translator.trans('label.status'),
                asignee: Translator.trans('label.asignee'),
                labels: Translator.trans('label.labels'),
                phase: Translator.trans('label.phase'),
                milestone: Translator.trans('label.milestone'),
                subtask_description: Translator.trans('label.subtask_description'),
            },
            button: {
                add_new_subtask: Translator.trans('button.add_new_subtask'),
                cancel: Translator.trans('button.cancel'),
                create_task: Translator.trans('button.create_task'),
            },
            visibleManualSchedule: true,
            visibleAutomaticSchedule: false,
            visiblePredecessor: true,
            visibleSuccessor: false,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_variables';
    @import '../../../css/page-section';
    @import '../../../css/_common';

    .download-icon {
        line-height: 50px;
        margin-left: 6px;
    }

    .title {
        line-height: 41px;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1.6px;
    }

    .dropdown, .datetime-picker {
        width: 400px !important;
    }

    .create-task {
        max-width: 820px;
    }

    h3 {
        font-size: 16px;
        text-transform: uppercase;
        font-weight: 300;
        letter-spacing: 1.6px;
        margin-bottom: 15px;

        &.with-label {
            margin-bottom: 25px;
        }
    }

    .dates-top {
        margin-bottom: 20px;
    }

    .status-item {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin-right: 10px;
    }

    h4 {
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1.6px;
    }

    .add-task {
        margin-top: 20px;
    }

    .status-info {
        font-weight: 300;
        font-size: 10px;

        span {
            text-transform: uppercase;
            letter-spacing: 1.6px;
        }

        p {
            letter-spacing: 0.3px;
            margin-bottom: 8px;
        }
    }

    .status {
        margin-top: 22px;
    }

    .note {
        display: block;
        font-size: 0.875em;
        margin: 10px 0 20px;

        &.blue-note {
            margin: 0 0 20px;
            color: $middleColor;
        }

        &.no-margin-bottom {
            margin: 10px 0 0;
        }
    }

    .radio-list {
        label {
            margin: 10px 0 10px 10px;
        }
    }

    .vue-switcher {
        margin: 3px 10px 0 0;
    }
</style>
