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
                        <h1>
                            <span v-if="isEdit">{{ translateText('message.edit_todo') }}</span>
                            <span v-if="!isEdit">{{ translateText('message.create_new_todo') }}</span>
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
                                    v-bind:title="translateText('placeholder.category')"
                                    v-bind:options="todoCategoriesForSelect"
                                    v-model="todoCategory"
                                    v-bind:currentOption="todoCategory" />
                            </div>
                        </div>
                    </div>
                    <!-- /// End Todo Category /// -->

                    <!-- /// Todo Title and Description /// -->
                    <div class="form-group">
                        <input-field type="text" v-bind:label="translateText('placeholder.todo_topic')" v-model="title" v-bind:content="title" />
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
                    <!-- /// End Todo Title and Description /// -->

                    <!-- /// Todo Responsible, Due Date and Status /// -->
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.date') }}</label>
                                    <datepicker v-model="date" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.due_date') }}</label>
                                    <datepicker v-model="dueDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <member-search v-bind:selectedUser="responsibilityFullName" v-model="responsibility" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
                            </div>
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="translateText('placeholder.status')"
                                    v-bind:options="todoStatusesForSelect"
                                    v-model="todoStatus"
                                    v-bind:currentOption="todoStatus" />
                            </div>
                        </div>
                    </div>

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-todos'}" class="btn-rounded btn-auto btn-auto disable-bg">{{ translateText('button.cancel') }}</router-link>
                        <a ref="#" v-if="!isEdit" @click="saveTodo" class="btn-rounded btn-auto btn-auto second-bg">{{ translateText('button.create_todo') }}</a>
                        <a ref="#" v-if="isEdit" @click="updateTodo" class="btn-rounded btn-auto btn-auto second-bg">{{ translateText('button.save_todo') }}</a>
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
import {mapGetters, mapActions} from 'vuex';
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
            'createTodo',
            'editTodo',
            'getTodoStatuses',
            'getTodoById',
            'getTodoCategories',
        ]),
        translateText: function(text) {
            return this.translate(text);
        },
        saveTodo: function() {
            let data = {
                projectId: this.$route.params.id,
                data: {
                    title: this.title,
                    responsibility: (this.responsibility && this.responsibility.length > 0) ? this.responsibility[0] : null,
                    dueDate: moment(this.dueDate).format('DD-MM-YYYY'),
                    date: moment(this.date).format('DD-MM-YYYY'),
                    description: this.$refs.description.getContent(),
                    status: this.todoStatus ? this.todoStatus.key : null,
                    todoCategory: this.todoCategory ? this.todoCategory.key : null,
                },
            };
            this.createTodo(data);
        },
        updateTodo: function() {
            let data = {
                id: this.$route.params.todoId,
                title: this.title,
                responsibility: (this.responsibility && this.responsibility.length > 0) ? this.responsibility[0] : null,
                dueDate: moment(this.dueDate).format('DD-MM-YYYY'),
                date: moment(this.date).format('DD-MM-YYYY'),
                description: this.$refs.description.getContent(),
                status: this.todoStatus.key,
                todoCategory: this.todoCategory.key,
            };
            this.editTodo(data);
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
        ...mapGetters({
            todo: 'currentTodo',
            todoStatusesForSelect: 'todoStatusesForSelect',
            validationMessages: 'validationMessages',
            todoCategoriesForSelect: 'todoCategoriesForSelect',
        }),
    },
    mounted() {
        if (this.todo) {
            this.$refs.description.setContent('');
            setTimeout(() => {
                const {description} = this.todo;
                this.$refs.description.setContent(description || '');
            }, 256);
        }
    },
    watch: {
        todo(val) {
            this.todoStatus = {key: this.todo.todoStatus, label: this.translateText(this.todo.statusName)};
            this.title = this.todo.title;
            this.$refs.description.setContent(this.todo.description);
            this.dueDate = this.todo.dueDate;
            this.date = this.todo.date;
            this.responsibility = [this.todo.responsibility];
            this.responsibilityFullName = this.todo.responsibilityFullName;
            this.todoCategory = {key: this.todo.todoCategory, label: this.todo.todoCategoryName};
        },
    },
    data() {
        return {
            isEdit: this.$route.params.todoId,
            todoStatus: null,
            title: '',
            dueDate: new Date(),
            date: new Date(),
            responsibility: [],
            responsibilityFullName: '',
            todoCategory: null,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">

</style>
