<template>
    <div class="row">
        <div class="col-md-6">
            <div class="create-meeting page-section">
                <!-- /// Header /// -->
                <div class="header flex-v-center">
                    <div>
                        <router-link :to="{name: 'project-todos'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ translateText('message.back_to_todos') }}
                        </router-link>
                        <h1>{{ translateText('message.create_new_todo') }}</h1>
                    </div>
                </div>
                <!-- /// End Header /// -->
                
                <div class="form">
                    <!-- /// Todo Category /// -->
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="translateText('label.category')"
                                    v-bind:options="projectCategories"
                                    v-model="details.category"
                                    v-bind:currentOption="details.category" />
                            </div>
                        </div>
                    </div>
                    <!-- /// End Todo Category /// -->

                    <!-- /// Todo Title and Description /// -->
                    <div class="form-group">
                        <input-field type="text" v-bind:label="translateText('placeholder.todo_topic')" v-model="topic" v-bind:content="topic" />
                    </div>
                    <div class="form-group">
                        <div class="vueditor-holder">
                            <div class="vueditor-header">{{ translateText('placeholder.decision_description') }}</div>
                            <Vueditor ref="content" />
                        </div>
                    </div>
                    <!-- /// End Todo Title and Description /// -->

                    <!-- /// Todo Responsible, Due Date and Status /// -->
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <member-search v-model="Responsible" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
                            </div>
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.due_date') }}</label>
                                    <datepicker v-model="schedule.dueDate" format="dd-MM-yyyy" />
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
                        </div>
                    </div>     

                    <hr class="double">               

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-meetings'}" class="btn-rounded btn-auto btn-auto disable-bg">{{ translateText('button.cancel') }}</router-link>
                        <a ref="#" class="btn-rounded btn-auto btn-auto second-bg">{{ translateText('button.create_todo') }}</a>
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

export default {
    components: {
        InputField,
        SelectField,
        datepicker,
        CalendarIcon,
        MemberSearch,
    },
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
    },
    data() {
        return {
            projectCategories: [{label: 'Production', key: 1}, {label: 'Logistics', key: 2}, {label: 'Quality Management', key: 3},
             {label: 'Human Resources', key: 4}, {label: 'Purchasing', key: 5}, {label: 'Maintenance', key: 6},
              {label: 'Assembly', key: 7}, {label: 'Tooling', key: 8}, {label: 'Process Engineering', key: 9}, {label: 'Industrialization', key: 10}],
            todoStatus: [{label: 'Initiated', key: 1}, {label: 'Ongoing', key: 2}, {label: 'On Hold', key: 3},
             {label: 'Discontinued', key: 4}, {label: 'Finished', key: 5}],
            todo_topic: '',
            schedule: {
                dueDate: new Date(),
            },
            details: {
                category: null,
                todoStatus: null,
            },
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    
</style>
