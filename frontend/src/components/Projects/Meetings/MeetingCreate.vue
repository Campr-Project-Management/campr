<template>
    <div class="row">
        <div class="col-md-6">
            <div class="create-meeting page-section">
                <!-- /// Header /// -->
                <div class="header flex-v-center">
                    <div>
                        <router-link :to="{name: 'project-meetings'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ translateText('message.back_to_meetings') }}
                        </router-link>
                        <h1>{{ translateText('message.create_new_meeting') }}</h1>
                    </div>
                </div>
                <!-- /// End Header /// -->
                
                <div class="form">
                    <!-- /// Meeting Distribution List (Event Name) and Category /// -->
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="translateText('label.distribution_list')"
                                    v-bind:options="meetingsDistributionList"
                                    v-model="details.distribution_list"
                                    v-bind:currentOption="details.distribution_list" />
                            </div>
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="translateText('label.category')"
                                    v-bind:options="projectCategories"
                                    v-model="details.category"
                                    v-bind:currentOption="details.category" />
                            </div>
                        </div>
                    </div>
                    <!-- /// End Meeting Distribution List (Event Name) and Category /// -->

                    <hr class="double">

                    <!-- /// Meeting Schedule /// -->
                    <h3>{{ translateText('message.schedule') }}</h3>
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-4">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.select_date') }}</label>
                                    <datepicker v-model="schedule.meetingDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill" stroke="middle-stroke" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.start_time') }}</label>
                                    <datepicker v-model="schedule.meetingStartTime" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill" stroke="middle-stroke" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.finish_time') }}</label>
                                    <datepicker v-model="schedule.meetingFinishTime" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill" stroke="middle-stroke" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Meeting Schedule /// -->

                    <hr class="double">

                    <!-- /// Meeting Location /// -->
                    <h3>{{ translateText('message.location') }}</h3>
                    <input-field type="text" v-bind:label="translateText('placeholder.location')" v-model="location" v-bind:content="location" />
                    <!-- /// End Meeting Location /// -->

                    <hr class="double">

                    <!-- /// Meeting Objectives /// -->
                    <h3>{{ translateText('message.objectives') }}</h3>
                    <div class="form-group">
                        <input-field type="text" v-bind:label="translateText('placeholder.objective')" v-model="objective" v-bind:content="objective" />
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a href="#" class="btn-rounded btn-auto">{{ translateText('message.add_new_objective') }} +</a>
                    </div>
                    <!-- /// End Meeting Objectives /// -->

                    <hr class="double">

                    <!-- /// Meeting Agenda /// -->
                    <h3>{{ translateText('message.agenda') }}</h3>
                    <div class="form-group">
                        <input-field type="text" v-bind:label="translateText('placeholder.topic')" v-model="topic" v-bind:content="topic" />
                    </div>
                    <div class="row">
                        <div class="form-group form-group">
                            <div class="col-md-4">
                                <member-search v-model="selectedDistribution" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
                            </div>
                            <div class="col-md-4">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.start_time') }}</label>
                                    <datepicker v-model="schedule.agendaTopicStartTime" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill" stroke="middle-stroke" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.finish_time') }}</label>
                                    <datepicker v-model="schedule.agendaTopicFinishTime" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill" stroke="middle-stroke" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a href="#" class="btn-rounded btn-auto">{{ translateText('message.add_new_topic') }} +</a>
                    </div>
                    <!-- /// End Meeting Objectives /// -->

                    <hr class="double">

                    <!-- /// Meeting Documents /// -->
                    <meeting-attachments v-on:input="setMedias" v-bind:editMedias="medias" />
                    <!-- /// End Meeting Documents /// -->

                    <hr class="double">

                    <!-- /// Decisions /// -->
                    <h3>{{ translateText('message.decisions') }}</h3>
                    <input-field type="text" v-bind:label="translateText('placeholder.decision_title')" v-model="decision" v-bind:content="decision" />
                    <div class="form-group">
                        <div class="vueditor-holder">
                            <div class="vueditor-header">{{ translateText('placeholder.decision_description') }}</div>
                            <Vueditor ref="content" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <member-search v-model="selectedDistribution" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
                            </div>
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.due_date') }}</label>
                                    <datepicker v-model="schedule.decisionDueDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill" stroke="middle-stroke" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="translateText('label.select_status')"
                                    v-bind:options="decisionStatus"
                                    v-model="details.decisionStatus"
                                    v-bind:currentOption="details.decisionStatus" />
                            </div>
                            <div class="col-md-6">
                                <div class="flex flex-direction-reverse">
                                    <a href="#" class="btn-rounded btn-auto">{{ translateText('message.add_new_decision') }} +</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Decisions /// -->

                    <hr class="double">

                    <!-- /// ToDos /// -->
                    <h3>{{ translateText('message.todos') }}</h3>
                    <input-field type="text" v-bind:label="translateText('placeholder.topic')" v-model="todo_topic" v-bind:content="todo_topic" />
                    <div class="form-group">
                        <div class="vueditor-holder">
                            <div class="vueditor-header">{{ translateText('placeholder.action') }}</div>
                            <Vueditor ref="content" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <member-search v-model="selectedDistribution" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
                            </div>
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.due_date') }}</label>
                                    <datepicker v-model="schedule.todoDueDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill" stroke="middle-stroke" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="translateText('label.select_status')"
                                    v-bind:options="todoStatus"
                                    v-model="details.todoStatus"
                                    v-bind:currentOption="details.todoStatus" />
                            </div>
                            <div class="col-md-6">
                                <div class="flex flex-direction-reverse">
                                    <a href="#" class="btn-rounded btn-auto">{{ translateText('message.add_new_todo') }} +</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /// End ToDos /// -->

                    <hr class="double">

                    <!-- /// Infos /// -->
                    <h3>{{ translateText('message.infos') }}</h3>
                    <input-field type="text" v-bind:label="translateText('placeholder.topic')" v-model="info_topic" v-bind:content="info_topic" />
                    <div class="form-group">
                        <div class="vueditor-holder">
                            <div class="vueditor-header">{{ translateText('placeholder.info_description') }}</div>
                            <Vueditor ref="content" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <member-search v-model="selectedDistribution" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
                            </div>
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.due_date') }}</label>
                                    <datepicker v-model="schedule.infoDueDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill" stroke="middle-stroke" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="translateText('label.select_status')"
                                    v-bind:options="infoStatus"
                                    v-model="details.infoStatus"
                                    v-bind:currentOption="details.infoStatus" />
                            </div>
                            <div class="col-md-6">
                                <div class="flex flex-direction-reverse">
                                    <a href="#" class="btn-rounded btn-auto">{{ translateText('message.add_new_info') }} +</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /// End ToDos /// -->

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-meetings'}" class="btn-rounded btn-auto btn-auto disable-bg">{{ translateText('button.cancel') }}</router-link>
                        <a ref="#" class="btn-rounded btn-auto btn-auto second-bg">{{ translateText('button.create_meeting') }}</a>
                    </div>
                    <!-- /// End Actions /// -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import InputField from '../../_common/_form-components/InputField';
import SelectField from '../../_common/_form-components/SelectField';
import datepicker from 'vuejs-datepicker';
import CalendarIcon from '../../_common/_icons/CalendarIcon';
import MemberSearch from '../../_common/MemberSearch';
import MeetingAttachments from './MeetingAttachments';

export default {
    components: {
        InputField,
        SelectField,
        datepicker,
        CalendarIcon,
        MemberSearch,
        MeetingAttachments,
    },
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
        setMedias(value) {
            this.medias = value;
        },
    },
    data() {
        return {
            projectCategories: [{label: 'Production', key: 1}, {label: 'Logistics', key: 2}, {label: 'Quality Management', key: 3},
             {label: 'Human Resources', key: 4}, {label: 'Purchasing', key: 5}, {label: 'Maintenance', key: 6},
              {label: 'Assembly', key: 7}, {label: 'Tooling', key: 8}, {label: 'Process Engineering', key: 9}, {label: 'Industrialization', key: 10}],
            // Distribution list values added just for testing
            meetingsDistributionList: [{label: 'TP Meeting', key: 1}, {label: 'EK Meeting', key: 2}, {label: 'ANLAGENVERWERTUNG BTF', key: 3}],
            decisionStatus: [{label: 'Undone', key: 1}, {label: 'Done', key: 2}],
            todoStatus: [{label: 'Undone', key: 1}, {label: 'Ongoing', key: 2}, {label: 'Done', key: 3}],
            infoStatus: [{label: 'Undone', key: 1}, {label: 'Ongoing', key: 2}, {label: 'Done', key: 3}],
            location: '',
            objective: '',
            topic: '',
            decision: '',
            todo_topic: '',
            info_topic: '',
            schedule: {
                meetingDate: new Date(),
                meetingStartTime: new Date(), // should be time select
                meetingFinishTime: new Date(), // should be time select
                agendaTopicStartTime: new Date(), // should be time select
                agendaTopicFinishTime: new Date(), // should be time select
                decisionDueDate: new Date(),
                todoDueDate: new Date(),
                infoDueDate: new Date(),
            },
            details: {
                decisionStatus: null,
                todoStatus: null,
                infoStatus: null,
            },
            visibleSubphase: false,
            isEdit: this.$route.params.phaseId,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_mixins';
    @import '../../../css/_variables';

    .title {
        position: relative;
        top: 15px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 700;
    }
</style>
