<template>
    <div class="row">
        <div class="col-md-6">
            <div class="create-meeting page-section">
                <!-- /// Header /// -->
                <div class="header flex-v-center">
                    <div>
                        <router-link :to="{name: 'project-todos'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ translate('message.back_to_todos') }}
                        </router-link>
                        <h1>
                            <span v-if="isEdit">{{ translate('message.edit_todo') }}</span>
                            <span v-if="!isEdit">{{ translate('message.create_new_todo') }}</span>
                        </h1>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <div class="form">
                    <!-- /// Todo Category /// -->
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <select-field
                                    :title="translate('placeholder.category')"
                                    :options="todoCategoriesForSelect"
                                    v-model="todoCategory"
                                    :currentOption="todoCategory" />
                                <error at-path="category"/>
                            </div>
                            <div class="col-md-6">
                                <select-field
                                    :title="translate('placeholder.distribution_list')"
                                    :options="distributionListsForSelect"
                                    v-model="distributionList"
                                    :currentOption="distributionList" />
                                <error at-path="distributionList" />
                            </div>
                        </div>
                    </div>
                    <!-- /// End Todo Category /// -->

                    <!-- /// Todo Title and Description /// -->
                    <div class="form-group">
                        <input-field type="text" :label="translate('placeholder.todo_topic')" v-model="title" :content="title" />
                        <error at-path="title"/>
                    </div>
                    <div class="form-group">
                        <editor
                            v-model="description"
                            :label="'placeholder.todo_description'"/>
                        <error at-path="description"/>
                    </div>
                    <!-- /// End Todo Title and Description /// -->

                    <!-- /// Todo Responsible, Due Date and Status /// -->
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <member-search
                                    :selectedUser="responsibilityFullName"
                                    v-model="responsibility"
                                    :placeholder="resposible"
                                    :singleSelect="true" />
                                <error at-path="responsiblity"/>
                            </div>
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{ translate('label.due_date') }}</label>
                                    <date-field v-model="dueDate" v-on:input="checkData"/>
                                </div>
                                <error at-path="dueDate" v-bind:message="calendarCorrect"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <select-field
                                    :title="translate('placeholder.status')"
                                    :options="todoStatusesForSelect"
                                    v-model="status"/>
                                <error at-path="status"/>
                            </div>
                        </div>
                    </div>

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-todos'}" class="btn-rounded btn-auto btn-auto disable-bg">{{ translate('button.cancel') }}</router-link>
                        <a v-if="!isEdit" @click="saveTodo" class="btn-rounded btn-auto btn-auto second-bg">{{ translate('button.create_todo') }}</a>
                        <a v-if="isEdit" @click="updateTodo" class="btn-rounded btn-auto btn-auto second-bg">{{ translate('button.save_todo') }}</a>
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
import MemberSearch from '../../_common/MemberSearch';
import {mapGetters, mapActions} from 'vuex';
import moment from 'moment';
import Error from '../../_common/_messages/Error.vue';
import Editor from '../../_common/Editor';
import DateField from '../../_common/_form-components/DateField';
import {calendarNotPast} from '../../../util/validator_helper';

export default {
    components: {
        DateField,
        InputField,
        SelectField,
        MemberSearch,
        moment,
        Error,
        Editor,
    },
    methods: {
        ...mapActions([
            'createTodo',
            'editTodo',
            'getTodoStatuses',
            'getTodoById',
            'getTodoCategories',
            'getDistributionLists',
            'emptyValidationMessages',
        ]),
        checkData: function(value) {
            const message = this.translate('before.now');
            this.calendarCorrect = calendarNotPast(message, value);
        },
        saveTodo: function() {
            if (!this.isSaving) {
                let data = {
                    projectId: this.$route.params.id,
                    data: {
                        title: this.title,
                        responsibility: (this.responsibility && this.responsibility.length > 0) ? this.responsibility[0] : null,
                        dueDate: this.dueDate ? moment(this.dueDate).format('DD-MM-YYYY') : null,
                        description: this.description,
                        status: this.status ? this.status.key : null,
                        todoCategory: this.todoCategory ? this.todoCategory.key : null,
                        distributionList: this.distributionList ? this.distributionList.key : null,
                    },
                };

                this.isSaving = true;

                this
                    .createTodo(data)
                    .then(
                        (response) => {
                            this.isSaving = false;
                            if (response.body && response.body.error && response.body.messages) {
                                this.$flashError('message.unable_to_save');
                                return;
                            }

                            this.$flashSuccess('message.saved');
                        },
                        () => {
                            this.isSaving = true;
                            this.$flashError('message.unable_to_save');
                        }
                    )
                ;
            }
        },
        updateTodo: function() {
            let data = {
                id: this.$route.params.todoId,
                title: this.title,
                responsibility: (this.responsibility && this.responsibility.length > 0) ? this.responsibility[0] : null,
                dueDate: this.dueDate ? moment(this.dueDate).format('DD-MM-YYYY') : null,
                description: this.description,
                status: this.status ? this.status.key : null,
                todoCategory: this.todoCategory ? this.todoCategory.key : null,
                distributionList: this.distributionList ? this.distributionList.key : null,
                project: this.$route.params.id,
            };

            this
                .editTodo(data)
                .then(
                    (response) => {
                        if (response.body && response.body.error && response.body.messages) {
                            this.$flashError('message.unable_to_save');
                            return;
                        }

                        this.$flashSuccess('message.saved');
                    },
                    () => {
                        this.$flashError('message.unable_to_save');
                    }
                )
            ;
        },
    },
    created() {
        this.getTodoStatuses();
        this.getDistributionLists({projectId: this.$route.params.id});
        this.getTodoCategories();
        if (this.$route.params.todoId) {
            this.getTodoById(this.$route.params.todoId);
        }
    },
    computed: {
        ...mapGetters([
            'currentTodo',
            'todoStatusesForSelect',
            'validationMessages',
            'todoCategoriesForSelect',
            'distributionListsForSelect',
        ]),
        todo() {
            return this.currentTodo;
        },
    },
    beforeDestroy() {
        this.emptyValidationMessages();
    },
    watch: {
        todo(val) {
            this.status = {key: this.todo.status, label: this.translate(this.todo.statusName)};
            this.title = this.todo.title;
            this.description = this.todo.description;
            this.dueDate = this.todo.dueDate ? moment(this.todo.dueDate).toDate() : null;
            this.responsibility = [this.todo.responsibility];
            this.responsibilityFullName = this.todo.responsibilityFullName;
            this.todoCategory = this.todo.todoCategory
                ? {key: this.todo.todoCategory, label: this.todo.todoCategoryName}
                : null;
            this.distributionList = this.todo.distributionList
                ? {key: this.todo.distributionList, label: this.todo.distributionListName}
                : null;
        },
    },
    data() {
        return {
            isEdit: this.$route.params.todoId,
            status: null,
            title: '',
            description: '',
            dueDate: null,
            responsibility: [],
            responsibilityFullName: '',
            todoCategory: null,
            isSaving: false,
            distributionList: null,
            calendarCorrect: null,
        };
    },
};
</script>
