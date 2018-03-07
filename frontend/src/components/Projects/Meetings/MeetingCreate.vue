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
                    <hr class="double">

                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <multi-select-field
                                    v-bind:title="translateText('placeholder.distribution_list')"
                                    v-bind:options="distributionListsForSelect"
                                    v-bind:selectedOptions="details.distributionLists"
                                    v-model="details.distributionLists" />
                            </div>
                            <div class="col-md-6">
                                <select-field
                                    :title="translateText('placeholder.category')"
                                    :options="meetingCategoriesForSelect"
                                    v-model="details.category"
                                    :currentOption="details.category" />
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
                                    <calendar-icon fill="middle-fill"/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.start_time') }}</label>
                                    <timepicker v-model="schedule.startTime" hide-clear-button />
                                    <error
                                        v-if="validationMessages.start && validationMessages.start.length"
                                        v-for="(message, index) in validationMessages.start"
                                        :key="index"
                                        :message="message" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.finish_time') }}</label>
                                    <timepicker v-model="schedule.endTime" hide-clear-button />
                                    <error
                                        v-if="validationMessages.end && validationMessages.end.length"
                                        v-for="(message, index) in validationMessages.end"
                                        :key="index"
                                        :message="message" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Meeting Schedule /// -->

                    <hr class="double">

                    <!-- /// Meeting Location /// -->
                    <h3>{{ translateText('message.location') }}</h3>
                    <input-field type="text" v-bind:label="translateText('placeholder.location')" v-model="location" v-bind:content="location" />
                    <error
                        v-if="validationMessages.location && validationMessages.location.length"
                        v-for="(message, index) in validationMessages.location"
                        :key="index"
                        :message="message" />
                    <!-- /// End Meeting Location /// -->

                    <hr class="double">

                    <!-- /// Meeting Objectives /// -->
                    <h3>{{ translateText('message.objectives') }}</h3>
                    <div class="form-group"
                        v-for="(objective, index) in objectives"
                        :key="index">
                        <input-field type="text" v-bind:label="translateText('placeholder.objective')" v-model="objective.description" v-bind:content="objective.description" />
                        <div v-if="validationMessages.meetingObjectives && validationMessages.meetingObjectives[index.toString()]">
                            <error
                                v-if="validationMessages.meetingObjectives[index.toString()].description && validationMessages.meetingObjectives[index.toString()].description.length"
                                v-for="(message, index) in validationMessages.meetingObjectives[index.toString()].description"
                                :key="index"
                                :message="message" />
                        </div>
                        <hr>
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a @click="addObjective()" class="btn-rounded btn-auto">{{ translateText('message.add_new_objective') }} +</a>
                    </div>
                    <!-- /// End Meeting Objectives /// -->

                    <hr class="double">

                    <!-- /// Meeting Agenda /// -->
                    <h3>{{ translateText('message.agenda') }}</h3>
                    <div v-for="(agenda, index) in agendas"
                        :key="index">
                        <div class="form-group">
                            <input-field type="text" v-bind:label="translateText('placeholder.topic')" v-model="agenda.topic" v-bind:content="agenda.topic" />
                            <div v-if="validationMessages.meetingAgendas && validationMessages.meetingAgendas[index.toString()]">
                                <error
                                    v-if="validationMessages.meetingAgendas[index.toString()].topic && validationMessages.meetingAgendas[index.toString()].topic.length"
                                    v-for="(message, index) in validationMessages.meetingAgendas[index.toString()].topic"
                                    :key="index"
                                    :message="message" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group form-group">
                                <div class="col-md-4">
                                    <member-search singleSelect="false" v-model="agenda.responsible" v-bind:placeholder="translateText('placeholder.search_members')"></member-search>
                                    <div v-if="validationMessages.meetingAgendas && validationMessages.meetingAgendas[index.toString()]">
                                        <error
                                            v-if="validationMessages.meetingAgendas[index.toString()].responsibility && validationMessages.meetingAgendas[index.toString()].responsibility.length"
                                            v-for="(message, index) in validationMessages.meetingAgendas[index.toString()].responsibility"
                                            :key="index"
                                            :message="message" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-holder right">
                                        <label class="active">{{ translateText('label.start_time') }}</label>
                                        <timepicker v-model="agenda.startTime" hide-clear-button />
                                        <div v-if="validationMessages.meetingAgendas && validationMessages.meetingAgendas[index.toString()]">
                                        <error
                                            v-if="validationMessages.meetingAgendas[index.toString()].start && validationMessages.meetingAgendas[index.toString()].start.length"
                                            v-for="(message, index) in validationMessages.meetingAgendas[index.toString()].start"
                                            :key="index"
                                            :message="message" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-holder right">
                                        <label class="active">{{ translateText('label.finish_time') }}</label>
                                        <timepicker v-model="agenda.endTime" hide-clear-button />
                                        <div v-if="validationMessages.meetingAgendas && validationMessages.meetingAgendas[index.toString()]">
                                        <error
                                            v-if="validationMessages.meetingAgendas[index.toString()].end && validationMessages.meetingAgendas[index.toString()].end.length"
                                            v-for="(message, index) in validationMessages.meetingAgendas[index.toString()].end"
                                            :key="index"
                                            :message="message" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a @click="addAgenda()" class="btn-rounded btn-auto">{{ translateText('message.add_new_topic') }} +</a>
                    </div>
                    <!-- /// End Meeting Objectives /// -->

                    <hr class="double">

                    <!-- /// Meeting Documents /// -->
                    <meeting-attachments v-on:input="setMedias" v-bind:editMedias="medias" />
                    <!-- /// End Meeting Documents /// -->

                    <hr class="double">

                    <!-- /// Decisions /// -->
                    <h3>{{ translateText('message.decisions') }}</h3>
                    <div v-for="(decision, index) in decisions"
                        :key="index">
                        <input-field type="text" v-bind:label="translateText('placeholder.decision_title')" v-model="decision.title" v-bind:content="decision.title" />
                        <div v-if="validationMessages.decisions && validationMessages.decisions[index.toString()]">
                        <error
                            v-if="validationMessages.decisions[index.toString()].title && validationMessages.decisions[index.toString()].title.length"
                            v-for="(message, index) in validationMessages.decisions[index.toString()].title"
                            :key="index"
                            :message="message" />
                        </div>
                        <div class="form-group">
                            <div class="vueditor-holder">
                                <div class="vueditor-header">{{ translateText('placeholder.decision_description') }}</div>
                                <Vueditor :id="'decision.description'+index" :ref="'decision.description'+index" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <member-search singleSelect="false" v-model="decision.responsible" v-bind:placeholder="translateText('placeholder.search_members')"></member-search>
                                    <div v-if="validationMessages.decisions && validationMessages.decisions[index.toString()]">
                                    <error
                                        v-if="validationMessages.decisions[index.toString()].responsibility && validationMessages.decisions[index.toString()].responsibility.length"
                                        v-for="(message, index) in validationMessages.decisions[index.toString()].responsibility"
                                        :key="index"
                                        :message="message" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-holder right">
                                        <label class="active">{{ translateText('label.due_date') }}</label>
                                        <datepicker v-model="decision.dueDate" format="dd-MM-yyyy" />
                                        <calendar-icon fill="middle-fill"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a @click="addDecision()" class="btn-rounded btn-auto">{{ translateText('message.add_new_decision') }} +</a>
                    </div>
                    <!-- /// End Decisions /// -->

                    <hr class="double">

                    <!-- /// ToDos /// -->
                    <h3>{{ translateText('message.todos') }}</h3>
                    <div v-for="(todo, index) in todos"
                        :key="index">
                        <input-field type="text" v-bind:label="translateText('placeholder.topic')" v-model="todo.title" v-bind:content="todo.title" />
                        <div v-if="validationMessages.todos && validationMessages.todos[index.toString()]">
                        <error
                            v-if="validationMessages.todos[index.toString()].title && validationMessages.todos[index.toString()].title.length"
                            v-for="(message, index) in validationMessages.todos[index.toString()].title"
                            :key="index"
                            :message="message" />
                        </div>
                        <div class="form-group">
                            <div class="vueditor-holder">
                                <div class="vueditor-header">{{ translateText('placeholder.action') }}</div>
                                <Vueditor :id="'todo.description'+index" :ref="'todo.description'+index" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <member-search singleSelect="false" v-model="todo.responsible" v-bind:placeholder="translateText('placeholder.search_members')"></member-search>
                                    <div v-if="validationMessages.todos && validationMessages.todos[index.toString()]">
                                    <error
                                        v-if="validationMessages.todos[index.toString()].responsibility && validationMessages.todos[index.toString()].responsibility.length"
                                        v-for="(message, index) in validationMessages.todos[index.toString()].responsibility"
                                        :key="index"
                                        :message="message" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-holder right">
                                        <label class="active">{{ translateText('label.due_date') }}</label>
                                        <datepicker v-model="todo.dueDate" format="dd-MM-yyyy" />
                                        <calendar-icon fill="middle-fill"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group last-form-group">
                                <div class="col-md-6">
                                    <select-field
                                        v-bind:title="translateText('label.select_status')"
                                        v-bind:options="todoStatusesForSelect"
                                        v-model="todo.status"
                                        v-bind:currentOption="todo.status" />
                                    <div v-if="validationMessages.todos && validationMessages.todos[index.toString()]">
                                    <error
                                        v-if="validationMessages.todos[index.toString()].status && validationMessages.todos[index.toString()].status.length"
                                        v-for="(message, index) in validationMessages.todos[index.toString()].status"
                                        :key="index"
                                        :message="message" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a @click="addTodo()" class="btn-rounded btn-auto">{{ translateText('message.add_new_todo') }} +</a>
                    </div>
                    <!-- /// End ToDos /// -->

                    <hr class="double">

                    <!-- /// Infos /// -->
                    <h3>{{ translateText('message.infos') }}</h3>
                    <div v-for="(info, index) in infos"
                        :key="index">
                        <input-field type="text" v-bind:label="translateText('placeholder.topic')" v-model="info.topic" v-bind:content="info.topic" />
                        <div v-if="validationMessages.infos && validationMessages.infos[index.toString()]">
                        <error
                            v-if="validationMessages.infos[index.toString()].title && validationMessages.infos[index.toString()].title.length"
                            v-for="(message, index) in validationMessages.infos[index.toString()].title"
                            :key="index"
                            :message="message" />
                        </div>
                        <div class="form-group">
                            <div class="vueditor-holder">
                                <div class="vueditor-header">{{ translateText('placeholder.info_description') }}</div>
                                <Vueditor :id="'info.description'+index" :ref="'info.description'+index" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <member-search singleSelect="false" v-model="info.responsible" v-bind:placeholder="translateText('placeholder.search_members')"></member-search>
                                    <div v-if="validationMessages.infos && validationMessages.infos[index.toString()]">
                                    <error
                                        v-if="validationMessages.infos[index.toString()].responsibility && validationMessages.infos[index.toString()].responsibility.length"
                                        v-for="(message, index) in validationMessages.infos[index.toString()].responsibility"
                                        :key="index"
                                        :message="message" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-holder right">
                                        <label class="active">{{ translateText('label.due_date') }}</label>
                                        <datepicker v-model="info.dueDate" format="dd-MM-yyyy" />
                                        <calendar-icon fill="middle-fill"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group last-form-group">
                                <div class="col-md-6">
                                    <select-field
                                        v-bind:title="translateText('label.select_status')"
                                        v-bind:options="infoStatusesForDropdown"
                                        v-model="info.infoStatus"
                                        v-bind:currentOption="info.infoStatus" />
                                    <div v-if="validationMessages.infos && validationMessages.infoStatus[index.toString()]">
                                        <error
                                            v-if="validationMessages.infos[index.toString()].infoStatus && validationMessages.infos[index.toString()].infoStatus.length"
                                            v-for="(message, index) in validationMessages.infos[index.toString()].infoStatus"
                                            :key="index"
                                            :message="message" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <select-field
                                        v-bind:title="'label.category'"
                                        v-bind:options="infoCategoriesForDropdown"
                                        v-model="info.infoCategory"
                                        v-bind:currentOption="info.infoCategory" />
                                    <div v-if="validationMessages.infos && validationMessages.infoCategory[index.toString()]">
                                        <error
                                            v-if="validationMessages.infos[index.toString()].infoCategory && validationMessages.infos[index.toString()].infoCategory.length"
                                            v-for="(message, index) in validationMessages.infos[index.toString()].infoCategory"
                                            :key="index"
                                            :message="message" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a @click="addInfo()" class="btn-rounded btn-auto">{{ translateText('message.add_new_info') }} +</a>
                    </div>
                    <!-- /// End ToDos /// -->

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-meetings'}" class="btn-rounded btn-auto btn-auto disable-bg">{{ translateText('button.cancel') }}</router-link>
                        <a @click="saveMeeting()" class="btn-rounded btn-auto btn-auto second-bg">{{ translateText('button.save_meeting') }}</a>
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
import {mapGetters, mapActions} from 'vuex';
import InputField from '../../_common/_form-components/InputField';
import SelectField from '../../_common/_form-components/SelectField';
import datepicker from '../../_common/_form-components/Datepicker';
import CalendarIcon from '../../_common/_icons/CalendarIcon';
import MemberSearch from '../../_common/MemberSearch';
import MeetingAttachments from './MeetingAttachments';
import Timepicker from '../../_common/_form-components/Timepicker';
import {createFormData} from '../../../helpers/meeting';
import MultiSelectField from '../../_common/_form-components/MultiSelectField';
import AlertModal from '../../_common/AlertModal.vue';
import Error from '../../_common/_messages/Error.vue';
import router from '../../../router';
import {createEditor} from 'vueditor';
import vueditorConfig from '../../_common/vueditorConfig';

export default {
    components: {
        InputField,
        SelectField,
        datepicker,
        CalendarIcon,
        MemberSearch,
        MeetingAttachments,
        Timepicker,
        MultiSelectField,
        AlertModal,
        Error,
    },
    methods: {
        ...mapActions([
            'getDistributionLists',
            'getMeetingCategories',
            'getInfoStatuses',
            'getInfoCategories',
            'getTodoStatuses',
            'createProjectMeeting',
            'emptyValidationMessages',
        ]),
        translateText(text) {
            return this.translate(text);
        },
        setMedias(value) {
            this.medias = value;
        },
        addObjective() {
            this.objectives.push({description: ''});
        },
        addAgenda() {
            this.agendas.push({
                topic: '',
                responsible: [],
                startTime: {
                    HH: null,
                    mm: null,
                },
                endTime: {
                    HH: null,
                    mm: null,
                },
            });
        },
        addDecision() {
            this.decisions.push({
                title: '',
                description: '',
                responsible: [],
                dueDate: new Date(),
            });
            setTimeout(() => {
                this.decisions[this.decisions.length - 1].description =
                    createEditor(document.getElementById('decision.description' + (this.decisions.length - 1)),
                    {...vueditorConfig, id: 'decision.description' + (this.decisions.length - 1)});
            }, 500);
        },
        addTodo() {
            this.todos.push({
                title: '',
                description: '',
                responsible: [],
                dueDate: new Date(),
                status: {label: this.translateText('label.select_status')},
            });
            setTimeout(() => {
                this.todos[this.todos.length - 1].description =
                    createEditor(document.getElementById('todo.description' + (this.todos.length - 1)),
                    {...vueditorConfig, id: 'todo.description' + (this.todos.length - 1)});
            }, 500);
        },
        addInfo() {
            this.infos.push({
                topic: '',
                description: '',
                responsible: [],
                dueDate: new Date(),
                infoStatus: {label: this.translateText('label.select_status')},
                infoCategory: {label: this.translateText('label.category')},
            });
            setTimeout(() => {
                this.infos[this.infos.length - 1].description =
                    createEditor(document.getElementById('info.description' + (this.infos.length - 1))
                            , {...vueditorConfig, id: 'info.description' + (this.infos.length - 1)});
            }, 500);
        },
        getDecisions() {
            let decisionsTmp = [];
            for (let i = 0; i < this.decisions.length; i++) {
                let elemTmp = {
                    title: this.decisions[i].title,
                    description: this.decisions[i].description.getContent(),
                    responsible: this.decisions[i].responsible,
                    dueDate: this.decisions[i].dueDate,
                };
                decisionsTmp.push(elemTmp);
            }
            return decisionsTmp;
        },
        getTodos() {
            let todosTmp = [];
            for (let i = 0; i < this.todos.length; i++) {
                let elemTmp = {
                    title: this.todos[i].title,
                    description: this.todos[i].description.getContent(),
                    responsible: this.todos[i].responsible,
                    dueDate: this.todos[i].dueDate,
                    status: this.todos[i].status,
                };
                todosTmp.push(elemTmp);
            }
            return todosTmp;
        },
        getInfos() {
            let infosTmp = [];
            for (let i = 0; i < this.infos.length; i++) {
                let elemTmp = {
                    topic: this.infos[i].topic,
                    description: this.infos[i].description.getContent(),
                    responsible: this.infos[i].responsible,
                    dueDate: this.infos[i].dueDate,
                    infoStatus: this.infos[i].infoStatus,
                    infoCategory: this.infos[i].infoCategory,
                };
                infosTmp.push(elemTmp);
            }
            return infosTmp;
        },
        saveMeeting() {
            let data = {
                distributionLists: this.details.distributionLists,
                meetingCategory: this.details.category,
                date: this.schedule.meetingDate,
                start: this.schedule.startTime,
                end: this.schedule.endTime,
                location: this.location,
                objectives: this.objectives,
                agendas: this.agendas,
                medias: this.medias,
                decisions: this.getDecisions(),
                todos: this.getTodos(),
                infos: this.getInfos(),
            };

            if (this.details.distributionLists.length > 0) {
                data.name = '';
                const length = this.details.distributionLists.length;
                this.details.distributionLists.map((item, index) => {
                    data.name += index !== length - 1 ? item.label + '|' : item.label;
                });
            }

            this
                .createProjectMeeting({
                    data: createFormData(data),
                    projectId: this.$route.params.id,
                })
                .then((response) => {
                    if (response.body && response.body.error && response.body.messages) {
                        this.showFailed = true;
                    } else {
                        this.showSaved = true;
                    }
                },
                (response) => {
                    this.showFailed = true;
                })
            ;
        },
    },
    computed: {
        ...mapGetters({
            distributionListsForSelect: 'distributionListsForSelect',
            meetingCategoriesForSelect: 'meetingCategoriesForSelect',
            infoStatusesForSelect: 'infoStatusesForSelect',
            infoCategoriesForDropdown: 'infoCategoriesForDropdown',
            todoStatusesForSelect: 'todoStatusesForSelect',
            validationMessages: 'validationMessages',
            infoStatusesForDropdown: 'infoStatusesForDropdown',
        }),
    },
    watch: {
        showSaved(value) {
            if (value === false) {
                router.push({
                    name: 'project-meetings',
                    params: {
                        id: this.$route.params.id,
                    },
                });
            }
        },
    },
    created() {
        this.getDistributionLists({projectId: this.$route.params.id});
        this.getMeetingCategories();
        this.getTodoStatuses();
        this.getInfoCategories();
        this.getInfoStatuses();
    },
    beforeDestroy() {
        this.emptyValidationMessages();
    },
    data() {
        return {
            showSaved: false,
            showFailed: false,
            name: '',
            location: '',
            objectives: [],
            agendas: [],
            decisions: [],
            todos: [],
            infos: [],
            medias: [],
            schedule: {
                meetingDate: new Date(),
                startTime: {
                    HH: null,
                    mm: null,
                },
                endTime: {
                    HH: null,
                    mm: null,
                },
            },
            details: {
                distributionLists: [],
                category: null,
            },
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_mixins';
    @import '../../../css/_variables';
    @import '../../../css/common';

    .title {
        position: relative;
        top: 15px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 700;
    }
    input.display-time {
        height: 3.2em;
    }
</style>
