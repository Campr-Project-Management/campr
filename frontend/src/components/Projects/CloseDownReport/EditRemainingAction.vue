<template>
    <div class="row">
        <div class="col-md-6">
            <div class="create-meeting page-section">
                <!-- /// Header /// -->
                <div class="header flex-v-center">
                    <div>
                        <router-link :to="{name: 'project-close-down-report'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ translateText('message.back_to') }} {{ translateText('message.close_down_report') }}
                        </router-link>
                        <h1>{{ translateText('message.edit_remaining_action') }}</h1>
                    </div>
                </div>
                <!-- /// End Header /// -->
                
                <div class="form">
                    <!-- /// Title and Description /// -->
                    <div class="form-group">
                        <input-field type="text" v-bind:label="translateText('placeholder.title')" v-model="title" v-bind:content="title" />
                    </div>

                    <div class="form-group">
                        <div class="vueditor-holder">
                            <div class="vueditor-header">{{ translateText('placeholder.description') }}</div>
                            <Vueditor ref="description" />
                        </div>
                    </div>
                    <!-- /// Title and Description /// -->

                    <!-- /// Responsible and Due Date /// -->
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <member-search v-model="responsible" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
                            </div>
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.due_date') }}</label>
                                    <datepicker v-model="schedule.dueDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill"/>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <!-- /// End Responsible and Due Date /// -->  

                    <hr class="double">               

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-close-down-report'}" class="btn-rounded btn-auto btn-auto disable-bg">{{ translateText('button.cancel') }}</router-link>
                        <a href="#" class="btn-rounded btn-auto second-bg">{{ translateText('button.save') }}</a>
                    </div>
                    <!-- /// End Actions /// -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import InputField from '../../_common/_form-components/InputField';
import datepicker from 'vuejs-datepicker';
import CalendarIcon from '../../_common/_icons/CalendarIcon';
import MemberSearch from '../../_common/MemberSearch';
import moment from 'moment';

export default {
    components: {
        InputField,
        datepicker,
        CalendarIcon,
        MemberSearch,
        moment,
    },
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
    },
    data() {
        return {
            title: '',
            responsible: [],
            schedule: {
                dueDate: new Date(),
            },
        };
    },
};
</script>

<style lang="scss">
    @import '../../../css/datepicker.scss';
</style>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">

</style>
