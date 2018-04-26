<template>
    <div class="project-meetings page-section">
        <modal v-if="showDeleteModal" @close="showDeleteModal = false">
            <p class="modal-title">{{ translateText('message.delete_todo') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showDeleteModal = false" class="btn-rounded btn-auto">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="removeTodo()" class="btn-rounded btn-empty btn-auto danger-color danger-border">{{ translateText('message.yes') }}</a>
            </div>
        </modal>
        <div class="header flex flex-space-between">
            <h1>{{ translateText('message.project_todos') }}</h1>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-todos-create-todo'}" class="btn-rounded btn-auto second-bg">{{ translateText('message.create_new_todo') }}</router-link>
            </div>
        </div>

        <div class="flex flex-direction-reverse">
            <div class="full-filters">
                <todos-filters :updateFilters="applyFilters" ></todos-filters>
            </div>
        </div>

        <div class="meetings-list">
            <scrollbar class="table-wrapper customScrollbar">
                <div class="scroll-wrapper">
                    <table class="table table-striped table-responsive table-fixed">
                        <thead>
                            <tr>
                                <th class="cell-auto">{{ translateText('table_header_cell.id') }}</th>
                                <th class="cell-auto">{{ translateText('table_header_cell.category') }}</th>
                                <th class="cell-auto">{{ translateText('table_header_cell.status') }}</th>
                                <th class="cell-auto">{{ translateText('table_header_cell.due_date') }}</th>
                                <th>{{ translateText('table_header_cell.topic') }}</th>
                                <th>{{ translateText('table_header_cell.responsible') }}</th>
                                <th class="cell-auto">{{ translateText('table_header_cell.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="todo in todos.items">
                                <td>{{ todo.id }}</td>
                                <td>{{ todo.todoCategoryName }}</td>
                                <td>{{ translateText(todo.statusName) }}</td>
                                <td>{{ todo.dueDate | moment('DD.MM.YYYY') }}</td>
                                <td class="cell-wrap">{{ todo.title }}</td>
                                <td>
                                    <div class="avatar" v-tooltip.top-center="todo.responsibilityFullName" v-bind:style="{ backgroundImage: 'url(' + todo.responsibilityAvatar + ')' }"></div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <router-link class="btn-icon" v-tooltip.top-center="translateText('message.view_todo')" :to="{name: 'project-todos-view-todo', params:{todoId: todo.id}}">
                                            <view-icon fill="second-fill"></view-icon>
                                        </router-link>
                                        <router-link class="btn-icon" v-tooltip.top-center="translateText('message.edit_todo')" :to="{name: 'project-todos-edit-todo', params:{todoId: todo.id}}">
                                            <edit-icon fill="second-fill"></edit-icon>
                                        </router-link>    
                                        <a href="javascript:void(0)" @click="initDeleteModal(todo)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_todo')"><delete-icon fill="danger-fill"></delete-icon></a>
                                    </div>
                                </td>                                
                            </tr>
                        </tbody>
                    </table>
                </div>
            </scrollbar>

            <div class="flex flex-direction-reverse flex-v-center" v-if="pages > 1">
                <div class="pagination">
                    <span v-if="pages > 1" v-for="page in pages" v-bind:class="{'active': page == activePage}" @click="changePage(page)" >{{ page }}</span>
                </div>
                <div>
                    <span class="pagination-info">{{ translateText('message.displaying') }} {{ todos.items.length }} {{ translateText('message.results_out_of') }} {{ todos.totalItems }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TodosFilters from '../_common/_todos-components/TodosFilters';
import ViewIcon from '../_common/_icons/ViewIcon';
import EditIcon from '../_common/_icons/EditIcon';
import DeleteIcon from '../_common/_icons/DeleteIcon';
import moment from 'moment';
import {mapActions, mapGetters} from 'vuex';
import Modal from '../_common/Modal';

export default {
    components: {
        TodosFilters,
        ViewIcon,
        EditIcon,
        DeleteIcon,
        moment,
        Modal,
    },
    methods: {
        ...mapActions(['getProjectTodos', 'deleteTodo', 'setTodosFilters']),
        translateText: function(text) {
            return this.translate(text);
        },
        initDeleteModal: function(todo) {
            this.showDeleteModal = true;
            this.todoId = todo.id;
        },
        removeTodo: function() {
            if (this.todoId) {
                this.deleteTodo({id: this.todoId});
                this.showDeleteModal = false;
                this.todoId = false;

                if(this.todos.items.length == 1 && this.activePage > 1) {
                    this.activePage--;
                }

                this.getData();
            }
        },
        changePage: function(page) {
            this.activePage = page;
            this.getData();
        },
        getData: function() {
            this.getProjectTodos({
                projectId: this.$route.params.id,
                queryParams: {
                    page: this.activePage,
                },
            });
        },
        applyFilters: function(key, value) {
            let filterObj = {};
            filterObj[key] = value;
            this.setTodosFilters(filterObj);
            this.getData();
        },
    },
    computed: {
        ...mapGetters({
            todos: 'todos',
            todosCount: 'todosCount',
        }),
        pages: function() {
            return Math.ceil(this.todos.totalItems / 12);
        },
    },
    created() {
        this.getData();
    },
    data: function() {
        return {
            activePage: 1,
            showDeleteModal: false,
            todoId: null,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped lang="scss">
    @import '../../css/_variables';
    @import '../../css/_mixins';
    @import '../../css/common';

    .full-filters {
        margin: 20px 0;
    }

    .meetings-list {
        overflow: hidden;
    }

    .actions {
        margin: 30px 0;
    }

    .table-wrapper {
        width: 100%;
        padding-bottom: 40px;
    } 

    .avatar {
        width: 30px;
        height: 30px;
        @include border-radius(50%);
        background-size: cover;
        display: inline-block;
        margin-right: 5px;

        &:last-child {
            margin-right: 0;
        }
    } 

    .cell-wrap {
        white-space: normal;
    }   
</style>
