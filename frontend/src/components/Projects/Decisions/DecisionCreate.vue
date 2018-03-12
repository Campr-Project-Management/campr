<template>
    <div class="row">
        <div class="col-md-6">
            <div class="create-meeting page-section">
                <!-- /// Header /// -->
                <div class="header flex-v-center">
                    <div>
                        <router-link :to="{name: 'project-decisions'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ translateText('message.back_to_decisions') }}
                        </router-link>
                        <h1 v-if="!isEdit">{{ translateText('message.create_new_decision') }}</h1>
                        <h1 v-else>{{ translateText('message.edit_decision') }}</h1>
                    </div>
                </div>
                <!-- /// End Header /// -->
                
                <div class="form">
                    <!-- /// Info Category /// -->
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="translateText('message.event')"
                                    v-bind:options="projectMeetingsForSelect"
                                    v-model="details.meeting"
                                    v-bind:currentOption="details.meeting" />
                            </div>
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="translateText('label.category')"
                                    v-bind:options="decisionCategoriesForSelect"
                                    v-model="details.decisionCategory"
                                    v-bind:currentOption="details.decisionCategory" />
                            </div>
                        </div>
                    </div>
                    <!-- /// End Info Category /// -->

                    <!-- /// Info Title and Description /// -->
                    <div class="form-group">
                        <input-field type="text" v-bind:label="translateText('placeholder.decision_title')" v-model="title" v-bind:content="title" />
                        <error
                            v-if="validationMessages.title && validationMessages.title.length"
                            v-for="message in validationMessages.title"
                            :message="message" />
                    </div>
                    <div class="form-group">
                        <div class="vueditor-holder">
                            <div class="vueditor-header">{{ translateText('placeholder.decision_description') }}</div>
                            <Vueditor ref="description" />
                            <error
                                v-if="validationMessages.description && validationMessages.description.length"
                                v-for="message in validationMessages.description"
                                :message="message" />
                        </div>
                    </div>
                    <!-- /// End Info Title and Description /// -->

                    <!-- /// Info Responsible, Due Date and Status /// -->
                    <div class="row">
                        <div class="form-group">
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

                    <hr class="double">               

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-decisions'}" class="btn-rounded btn-auto btn-auto disable-bg">{{ translateText('button.cancel') }}</router-link>
                        <a v-if="!isEdit" @click="createNewDecision()" class="btn-rounded btn-auto btn-auto second-bg">{{ translateText('button.create_decision') }}</a>
                        <a v-if="isEdit" @click="saveDecision()" class="btn-rounded btn-auto second-bg">{{ translateText('button.save') }}</a>
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
import datepicker from '../../_common/_form-components/Datepicker';
import CalendarIcon from '../../_common/_icons/CalendarIcon';
import MemberSearch from '../../_common/MemberSearch';
import {mapActions, mapGetters} from 'vuex';
import moment from 'moment';
import Error from '../../_common/_messages/Error.vue';

export default {
    components: {
        InputField,
        SelectField,
        datepicker,
        CalendarIcon,
        MemberSearch,
        moment,
        Error,
    },
    methods: {
        ...mapActions([
            'getProjectMeetings', 'getDecisionCategories',
            'createDecision', 'getDecision', 'editDecision', 'emptyValidationMessages',
        ]),
        translateText: function(text) {
            return this.translate(text);
        },
        getData: function() {
            let data = {
                projectId: this.$route.params.id,
                meeting: this.details.meeting ? this.details.meeting.key : null,
                title: this.title,
                description: this.$refs.description.getContent(),
                decisionCategory: this.details.decisionCategory ? this.details.decisionCategory.key : null,
                responsibility: this.responsible.length > 0 ? this.responsible[0] : null,
                dueDate: moment(this.schedule.dueDate, 'DD-MM-YYYY').format('DD-MM-YYYY'),
            };
            return data;
        },
        createNewDecision: function() {
            this.createDecision(this.getData());
        },
        saveDecision: function() {
            let data = this.getData();
            data.id = this.$route.params.decisionId;
            data.redirect = true;
            this.editDecision(data);
        },
    },
    computed: {
        ...mapGetters({
            decisionCategoriesForSelect: 'decisionCategoriesForSelect',
            projectMeetingsForSelect: 'projectMeetingsForSelect',
            validationMessages: 'validationMessages',
            currentDecision: 'currentDecision',
        }),
    },
    created() {
        this.getDecisionCategories();
        this.getProjectMeetings({projectId: this.$route.params.id});
        if (this.$route.params.decisionId) {
            this.getDecision(this.$route.params.decisionId);
        }
    },
    mounted() {
        if (this.currentDecision) {
            this.$refs.description.setContent('');
            setTimeout(() => {
                const {description} = this.currentDecision;
                this.$refs.description.setContent(description || '');
            }, 256);
        }
    },
    data() {
        return {
            title: '',
            responsible: [],
            schedule: {
                dueDate: new Date(),
            },
            details: {
                meeting: null,
                decisionCategory: null,
            },
            isEdit: this.$route.params.decisionId,
        };
    },
    beforeDestroy() {
        this.emptyValidationMessages();
    },
    watch: {
        currentDecision(value) {
            this.title = this.currentDecision.title;
            this.$refs.description.setContent(this.currentDecision.description || '');
            this.details.meeting = this.currentDecision.meeting
                ? {key: this.currentDecision.meeting, label: this.currentDecision.meetingName}
                : null
            ;
            this.details.decisionCategory = this.currentDecision.decisionCategory
                ? {key: this.currentDecision.decisionCategory, label: this.currentDecision.decisionCategoryName}
                : null
            ;
            this.responsible.push(this.currentDecision.responsibility);
            this.schedule.dueDate = new Date(this.currentDecision.dueDate);
        },
    },
};
</script>

<style lang="scss">
    @import '../../../css/datepicker.scss';
</style>
