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
                        <error
                            v-if="validationMessages.title && validationMessages.title.length"
                            v-for="message in validationMessages.title"
                            :message="message" />
                    </div>

                    <div class="form-group">
                        <editor
                            v-model="description"
                            :label="'placeholder.description'"/>
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
                                    <date-field v-model="dueDate"/>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <!-- /// End Responsible and Due Date /// -->  

                    <hr class="double">               

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-close-down-report'}" class="btn-rounded btn-auto btn-auto disable-bg">{{ translateText('button.cancel') }}</router-link>
                        <a @click="saveAction" class="btn-rounded btn-auto second-bg">{{ translateText('button.save') }}</a>
                    </div>
                    <!-- /// End Actions /// -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import InputField from '../../_common/_form-components/InputField';
import MemberSearch from '../../_common/MemberSearch';
import moment from 'moment';
import {mapActions, mapGetters} from 'vuex';
import Error from '../../_common/_messages/Error.vue';
import Editor from '../../_common/Editor';
import router from '../../../router';
import DateField from '../../_common/_form-components/DateField';

export default {
    components: {
        DateField,
        InputField,
        MemberSearch,
        moment,
        Error,
        Editor,
    },
    methods: {
        ...mapActions(['getCloseDownAction', 'editCloseDownAction']),
        translateText: function(text) {
            return this.translate(text);
        },
        saveAction: function() {
            this.editCloseDownAction({
                id: this.currentCloseDownAction.id,
                title: this.title,
                description: this.description,
                responsibility: this.responsible.length > 0 ? this.responsible[0] : null,
                dueDate: moment(this.dueDate, 'DD-MM-YYYY').format('DD-MM-YYYY'),
            }).then(
                (response) => {
                    if (response && response.body && !response.body.error) {
                        router.push({
                            name: 'project-close-down-report',
                            params: {
                                id: this.$route.params.id,
                            },
                        });
                    }
                },
            );
        },
    },
    computed: {
        ...mapGetters({currentCloseDownAction: 'currentCloseDownAction', validationMessages: 'validationMessages'}),
    },
    created() {
        if (this.$route.params.actionId) {
            this.getCloseDownAction(this.$route.params.actionId);
        }
    },
    watch: {
        currentCloseDownAction(value) {
            this.title = this.currentCloseDownAction.title;
            this.description = this.currentCloseDownAction.description;
            this.responsible.push(this.currentCloseDownAction.responsibility);
            this.dueDate = new Date(this.currentCloseDownAction.dueDate);
        },
    },
    data() {
        return {
            title: '',
            responsible: [],
            dueDate: new Date(),
            description: '',
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
