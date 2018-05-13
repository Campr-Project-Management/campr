<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <div class="view-todo page-section">
                    <modal v-if="showDeleteModal" @close="showDeleteModal = false">
                        <p class="modal-title">{{ translate('message.delete_todo') }}</p>
                        <div class="flex flex-space-between">
                            <a href="javascript:void(0)" @click="showDeleteModal = false" class="btn-rounded btn-auto">{{ translate('message.no') }}</a>
                            <a href="javascript:void(0)" @click="removeTodo()" class="btn-rounded btn-empty btn-auto danger-color danger-border">{{ translate('message.yes') }}</a>
                        </div>
                    </modal>
                    <modal v-if="showRescheduleModal" @close="cancelRescheduleModal" v-bind:hasSpecificClass="true">
                        <p class="modal-title">{{ translate('message.reschedule_todo') }}</p>
                        <div class="form-group last-form-group">
                            <div class="col-md-4">
                                <div class="input-holder">
                                    <label class="active">{{ translate('label.select_date') }}</label>
                                    <datepicker :clear-button="false" v-model="rescheduleObj.date" format="dd-MM-yyyy" :value="rescheduleObj.date"></datepicker>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-holder">
                                    <label class="active">{{ translate('label.select_due_date') }}</label>
                                    <datepicker :clear-button="false" v-model="rescheduleObj.dueDate" format="dd-MM-yyyy" :value="rescheduleObj.dueDate"></datepicker>
                                </div>
                            </div>
                        </div>
                        <hr class="double">

                        <div class="flex flex-space-between">
                            <a href="javascript:void(0)" @click="cancelRescheduleModal" class="btn-rounded btn-auto">{{ translate('button.cancel') }}</a>
                            <a href="javascript:void(0)" @click="rescheduleTodo" class="btn-rounded btn-auto second-bg">{{ translate('button.save') }}</a>
                        </div>
                    </modal>
                    <!-- /// Header /// -->
                    <div class="header flex-v-center">
                        <div>
                            <router-link :to="{name: 'project-todos'}" class="small-link">
                                <i class="fa fa-angle-left"></i>
                                {{ translate('message.back_to_todos') }}
                            </router-link>
                            <h1>{{todo.title}}</h1>
                            <!-- /// to implement this after the categories will be added /// -->
                            <h3 class="category"><b>{{todo.todoCategoryName}}</b></h3>
                            <h4>{{ translate('message.created') }}: <b>{{todo.date | moment('DD.MM.YYYY') }}</b> | {{ translate('message.due_date') }}: <b>{{todo.dueDate | moment('DD.MM.YYYY') }}</b> | {{ translate('message.status') }}: <b>{{todo.statusName}}</b></h4>
                            <div class="entry-responsible flex flex-v-center">
                                <div class="user-avatar" v-bind:style="{ backgroundImage: 'url(' + todo.responsibilityAvatar + ')' }"></div>
                                <div>
                                    {{ translate('message.responsible') }}:
                                    <b>{{todo.responsibilityFullName}}</b>
                                </div>
                            </div>
                            <a @click="initRescheduleModal" class="btn-rounded btn-auto btn-md btn-empty">{{ translate('button.reschedule') }} <reschedule-icon></reschedule-icon></a>
                        </div>
                    </div>
                    <!-- /// End Header /// -->
                </div>

                <div class="entry-body" v-html="todo.description"></div>
            </div>
            <div class="col-md-6">
                <div class="create-meeting page-section">
                    <!-- /// Header /// -->
                    <div class="margintop20 text-right">
                        <div class="buttons">
                            <router-link class="btn-rounded btn-auto" :to="{name: 'project-todos-edit-todo', params:{todoId: todo.id}}">
                                {{ translate('button.edit_todo') }}
                            </router-link>    
                            <router-link :to="{name: 'project-todos-create-todo'}" class="btn-rounded btn-auto second-bg">
                                {{ translate('button.new_todo') }}
                            </router-link>
                            <a @click="initDeleteModal" class="btn-rounded btn-auto danger-bg">{{ translate('button.delete_todo') }}</a>
                        </div>
                    </div>
                    <!-- /// End Header /// -->
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="text-right footer-buttons">
                    <div class="buttons">
                        <router-link class="btn-rounded btn-auto" :to="{name: 'project-todos-edit-todo', params:{todoId: todo.id}}">
                            {{ translate('button.edit_todo') }}
                        </router-link> 
                        <router-link :to="{name: 'project-todos-create-todo'}" class="btn-rounded btn-auto second-bg">
                            {{ translate('button.new_todo') }}
                        </router-link>
                        <a @click="initDeleteModal" class="btn-rounded btn-auto danger-bg">{{ translate('button.delete_todo') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import RescheduleIcon from '../../_common/_icons/RescheduleIcon';
import {mapGetters, mapActions} from 'vuex';
import Modal from '../../_common/Modal';
import router from '../../../router';
import datepicker from '../../_common/_form-components/Datepicker';
import moment from 'moment';

export default {
    components: {
        RescheduleIcon,
        Modal,
        datepicker,
    },
    methods: {
        ...mapActions([
            'getTodoById',
            'deleteTodo',
            'editTodo',
        ]),
        initDeleteModal: function(todo) {
            this.showDeleteModal = true;
        },
        removeTodo: function() {
            if (this.$route.params.todoId) {
                this.deleteTodo({id: this.$route.params.todoId});
                this.showDeleteModal = false;
                router.push({name: 'project-todos', params: {id: this.$route.params.id}});
            }
        },
        initRescheduleModal: function() {
            this.showRescheduleModal = true;
        },
        rescheduleTodo: function() {
            let data = {
                id: this.$route.params.todoId,
                dueDate: moment(this.rescheduleObj.dueDate).format('DD-MM-YYYY'),
                date: moment(this.rescheduleObj.date).format('DD-MM-YYYY'),
            };
            this.editTodo(data);
            this.showRescheduleModal = false;
        },
        cancelRescheduleModal: function() {
            this.showRescheduleModal = false;
            this.rescheduleObj.date = this.todo.date ? moment(this.todo.date).toDate() : null;
            this.rescheduleObj.dueDate = this.todo.dueDate ? moment(this.todo.dueDate).toDate() : null;
        },
    },
    created() {
        if (this.$route.params.todoId) {
            this.getTodoById(this.$route.params.todoId);
        }
    },
    computed: {
        ...mapGetters({
            todo: 'currentTodo',
        }),
    },
    watch: {
        todo(val) {
            this.rescheduleObj.date = this.todo.date ? moment(this.todo.date).toDate() : null;
            this.rescheduleObj.dueDate = this.todo.dueDate ? moment(this.todo.dueDate).toDate() : null;
        },
    },
    data() {
        return {
            showDeleteModal: false,
            showRescheduleModal: false,
            rescheduleObj: {
                date: moment().toDate(),
                dueDate: moment().toDate(),
            },
        };
    },
};
</script>

<style lang="scss">
    @import '../../../css/_mixins';
    @import '../../../css/_variables';

    ul.attachments {
        li {
            a {
                .icon {
                    svg {
                        width: 12px;
                        height: 12px;
                        @include transition(color, 0.2s, ease);
                    }
                }

                &:hover {
                    .icon {
                        svg {
                            fill: $secondDarkColor;
                        }
                    }                    
                }
            }
        }
    }
</style>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_mixins';
    @import '../../../css/_variables';

    .entry-responsible {
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 10px;
        line-height: 1.5em;
        margin: 20px 0;

        b {
            display: block;
            font-size: 12px;
        }
    }

    .user-avatar {
        width: 30px;
        height: 30px;
        display: inline-block;        
        margin: 0 10px 0 0;  
        position: relative;
        top: -2px;
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        @include border-radius(50%);
    }

    .entry-body {
        ul {
            list-style-type: disc;
            list-style-position: inside;

            &:last-child {
                margin-bottom: 0;
            }
        }

        ol {
            list-style-type: decimal;
            list-style-position: inside;
            padding: 0;

            &:last-child {
                margin-bottom: 0;
            }
        }

        img {
            @include box-shadow(0, 0, 20px, $darkColor);
        }
    }

    .entry-responsible {
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 10px;
        line-height: 1.5em;

        b {
            display: block;
            font-size: 12px;
        }
    }

    .category {
        margin-top: 0;
    }

    .footer-buttons {
        margin-top: 60px;
        padding: 30px 0;
        border-top: 1px solid $darkerColor; 
    }

    .buttons {
      a {
        margin: 5px 0 5px 10px;
      }
    }
</style>
