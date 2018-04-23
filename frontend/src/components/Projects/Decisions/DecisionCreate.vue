<template>
    <div class="row">
        <div class="col-md-6">
            <div class="create-meeting page-section">
                <!-- /// Header /// -->
                <div class="header flex-v-center">
                    <div>
                        <router-link :to="{name: 'project-decisions'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ translate('message.back_to_decisions') }}
                        </router-link>
                        <h1 v-if="!isEdit">{{ translate('message.create_new_decision') }}</h1>
                        <h1 v-else>{{ translate('message.edit_decision') }}</h1>
                    </div>
                </div>
                <!-- /// End Header /// -->
                
                <div class="form">
                    <!-- /// Info Category /// -->
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="translate('message.event')"
                                    v-bind:options="projectMeetingsForSelect"
                                    v-model="details.meeting"
                                    v-bind:currentOption="details.meeting" />
                                <error at-path="meeting"/>
                            </div>

                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="translate('label.category')"
                                    v-bind:options="decisionCategoriesForSelect"
                                    v-model="details.decisionCategory"
                                    v-bind:currentOption="details.decisionCategory" />
                                <error at-path="decisionCategory"/>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Info Category /// -->

                    <!-- /// Info Title and Description /// -->
                    <div class="form-group">
                        <input-field type="text" v-bind:label="translate('placeholder.decision_title')" v-model="title" v-bind:content="title" />
                        <error at-path="title"/>
                    </div>
                    <div class="form-group">
                        <editor
                                v-model="description"
                                :label="'placeholder.decision_description'"/>
                        <error at-path="description"/>
                    </div>
                    <!-- /// End Info Title and Description /// -->

                    <!-- /// Info Responsible, Due Date and Status /// -->
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <member-search v-model="responsible" v-bind:placeholder="translate('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
                            </div>
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{ translate('label.due_date') }}</label>
                                    <datepicker v-model="schedule.dueDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill"/>
                                </div>
                                <error at-path="dueDate"/>
                            </div>
                        </div>
                    </div>

                    <hr class="double">               

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-decisions'}" class="btn-rounded btn-auto btn-auto disable-bg">{{ translate('button.cancel') }}</router-link>
                        <a v-if="!isEdit" @click="createNewDecision()" class="btn-rounded btn-auto btn-auto second-bg">{{ translate('button.create_decision') }}</a>
                        <a v-if="isEdit" @click="saveDecision()" class="btn-rounded btn-auto second-bg">{{ translate('button.save') }}</a>
                    </div>
                    <!-- /// End Actions /// -->
                </div>
            </div>
        </div>

        <alert-modal v-if="showSaved" @close="showSaved = false" body="message.saved" />
        <alert-modal v-if="showFailed" @close="showFailed = false" body="message.unable_to_save" />
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
import Editor from '../../_common/Editor.vue';
import AlertModal from '../../_common/AlertModal.vue';

export default {
    components: {
        InputField,
        SelectField,
        datepicker,
        CalendarIcon,
        MemberSearch,
        moment,
        Error,
        Editor,
        AlertModal,
    },
    methods: {
        ...mapActions([
            'getProjectMeetings', 'getDecisionCategories',
            'createDecision', 'getDecision', 'editDecision', 'emptyValidationMessages',
        ]),
        getData() {
            return {
                projectId: this.$route.params.id,
                meeting: this.details.meeting ? this.details.meeting.key : null,
                title: this.title,
                description: this.description,
                decisionCategory: this.details.decisionCategory ? this.details.decisionCategory.key : null,
                responsibility: this.responsible.length > 0 ? this.responsible[0] : null,
                dueDate: this.schedule.dueDate ? moment(this.schedule.dueDate).format('DD-MM-YYYY') : null,
            };
        },
        createNewDecision: function() {
            this.createDecision(this.getData()).then(
                (response) => {
                    if (response.body && response.body.error && response.body.messages) {
                        this.showFailed = true;
                        return;
                    }

                    this.showSaved = true;
                },
                () => {
                    this.showFailed = true;
                },
            );
        },
        saveDecision: function() {
            let data = this.getData();
            data.id = this.$route.params.decisionId;
            data.redirect = true;
            this.editDecision(data).then(
                (response) => {
                    if (response.body && response.body.error && response.body.messages) {
                        this.showFailed = true;
                        return;
                    }

                    this.showSaved = true;
                },
                () => {
                    this.showFailed = true;
                }
            );
        },
    },
    computed: {
        ...mapGetters([
            'decisionCategoriesForSelect',
            'projectMeetingsForSelect',
            'validationMessages',
            'currentDecision',
        ]),
    },
    created() {
        this.getDecisionCategories();
        this.getProjectMeetings({projectId: this.$route.params.id});
        if (this.$route.params.decisionId) {
            this.getDecision(this.$route.params.decisionId);
        }
    },
    beforeDestroy() {
        this.emptyValidationMessages();
    },
    watch: {
        currentDecision(value) {
            this.title = this.currentDecision.title;
            this.description = this.currentDecision.description;
            this.details.meeting = this.currentDecision.meeting
                ? {key: this.currentDecision.meeting, label: this.currentDecision.meetingName}
                : null
            ;
            this.details.decisionCategory = this.currentDecision.decisionCategory
                ? {key: this.currentDecision.decisionCategory, label: this.currentDecision.decisionCategoryName}
                : null
            ;
            this.responsible.push(this.currentDecision.responsibility);
            this.schedule.dueDate = this.currentDecision.dueDate ? moment(this.currentDecision.dueDate).toDate() : null;
        },
    },
    data() {
        return {
            title: '',
            responsible: [],
            description: '',
            schedule: {
                dueDate: null,
            },
            details: {
                meeting: null,
                decisionCategory: null,
            },
            isEdit: this.$route.params.decisionId,
            showFailed: false,
            showSaved: false,
        };
    },
};
</script>

<style lang="scss">
    @import '../../../css/datepicker.scss';
</style>
