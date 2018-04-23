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
                            :label="'placeholder.decision_description'"/>
                        <error at-path="description"/>
                    </div>
                    <!-- /// End Todo Title and Description /// -->

                    <!-- /// Todo Responsible, Due Date and Status /// -->
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{ translate('label.date') }}</label>
                                    <datepicker v-model="date" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill"/>
                                </div>
                                <error at-path="date"/>
                            </div>
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{ translate('label.due_date') }}</label>
                                    <datepicker v-model="dueDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill"/>
                                </div>
                                <error at-path="dueDate"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <member-search
                                        :selectedUser="responsibilityFullName"
                                        v-model="responsibility"
                                        :placeholder="translate('placeholder.responsible')"
                                        :singleSelect="true"></member-search>
                                <error at-path="responsiblity"/>
                            </div>
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
import {mapGetters, mapActions} from 'vuex';
import moment from 'moment';
import Error from '../../_common/_messages/Error.vue';
import Editor from '../../_common/Editor';
import AlertModal from '../../_common/AlertModal.vue';

export default {
    components: {
        AlertModal,
        InputField,
        SelectField,
        datepicker,
        CalendarIcon,
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
            'emptyValidationMessages',
        ]),
        saveTodo: function() {
            let data = {
                projectId: this.$route.params.id,
                data: {
                    title: this.title,
                    responsibility: (this.responsibility && this.responsibility.length > 0) ? this.responsibility[0] : null,
                    dueDate: this.dueDate ? moment(this.dueDate).format('DD-MM-YYYY') : null,
                    date: this.dueDate ? moment(this.date).format('DD-MM-YYYY') : null,
                    description: this.description,
                    status: this.status ? this.status.key : null,
                    todoCategory: this.todoCategory ? this.todoCategory.key : null,
                },
            };

            this.createTodo(data).then((response) => {
                if (response.body && response.body.error && response.body.messages) {
                    this.showFailed = true;
                    return;
                }

                this.showSaved = true;
            }, () => {

            });
        },
        updateTodo: function() {
            let data = {
                id: this.$route.params.todoId,
                title: this.title,
                responsibility: (this.responsibility && this.responsibility.length > 0) ? this.responsibility[0] : null,
                dueDate: this.dueDate ? moment(this.dueDate).format('DD-MM-YYYY') : null,
                date: this.dueDate ? moment(this.date).format('DD-MM-YYYY') : null,
                description: this.description,
                status: this.status.key,
                todoCategory: this.todoCategory.key,
            };

            this.editTodo(data).then(
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
    created() {
        this.getTodoStatuses();
        if (this.$route.params.todoId) {
            this.getTodoById(this.$route.params.todoId);
        }
        this.getTodoCategories();
    },
    computed: {
        ...mapGetters([
            'currentTodo',
            'todoStatusesForSelect',
            'validationMessages',
            'todoCategoriesForSelect',
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
            this.date = this.todo.date ? moment(this.todo.date).toDate() : null;
            this.responsibility = [this.todo.responsibility];
            this.responsibilityFullName = this.todo.responsibilityFullName;
            this.todoCategory = {key: this.todo.todoCategory, label: this.todo.todoCategoryName};
        },
    },
    data() {
        return {
            isEdit: this.$route.params.todoId,
            status: null,
            title: '',
            description: '',
            dueDate: null,
            date: null,
            responsibility: [],
            responsibilityFullName: '',
            todoCategory: null,
            showSaved: false,
            showFailed: false,
        };
    },
};
</script>
