<template>
    <div class="row">
        <div class="col-md-6">
            <div class="create-meeting page-section">
                <!-- /// Header /// -->
                <div class="header flex-v-center">
                    <div>
                        <router-link :to="{name: 'project-infos'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ translateText('message.back_to_infos') }}
                        </router-link>
                        <h1>{{ translateText(info && info.id ? 'message.edit_info' : 'message.create_new_info') }}</h1>
                    </div>
                </div>
                <!-- /// End Header /// -->
                
                <div class="form">
                    <!-- /// Info Category /// -->
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <select-field
                                    v-bind:title="translateText('label.category')"
                                    v-bind:options="infoCategoriesForDropdown"
                                    v-model="infoCategory"
                                    v-bind:currentOption="infoCategory" />
                                <error
                                    v-if="validationMessages.infoCategory && validationMessages.infoCategory.length"
                                    v-for="message in validationMessages.infoCategory"
                                    :message="message" />
                            </div>
                        </div>
                    </div>
                    <!-- /// End Info Category /// -->

                    <!-- /// Info Title and Description /// -->
                    <div class="form-group">
                        <input-field type="text" v-bind:label="translateText('placeholder.info_topic')" v-model="topic" v-bind:content="topic" />
                        <error
                            v-if="validationMessages.topic && validationMessages.topic.length"
                            v-for="message in validationMessages.topic"
                            :message="message" />
                    </div>
                    <div class="form-group">
                        <editor
                            v-model="description"
                            :label="'placeholder.info_description'"/>
                        <error
                            v-if="validationMessages.description && validationMessages.description.length"
                            v-for="message in validationMessages.description"
                            :message="message" />
                    </div>
                    <!-- /// End Info Title and Description /// -->

                    <!-- /// Info Responsible, Due Date and Status /// -->
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <member-search v-model="responsibility" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
                                <error
                                    v-if="validationMessages.responsibility && validationMessages.responsibility.length"
                                    v-for="message in validationMessages.responsibility"
                                    :message="message" />
                            </div>
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{ translateText('label.due_date') }}</label>
                                    <datepicker v-model="dueDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill"/>
                                    <error
                                        v-if="validationMessages.dueDate && validationMessages.dueDate.length"
                                        v-for="message in validationMessages.dueDate"
                                        :message="message" />
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
                                    v-model="infoStatus"
                                    v-bind:currentOption="infoStatus" />
                                <error
                                    v-if="validationMessages.infoStatus && validationMessages.infoStatus.length"
                                    v-for="message in validationMessages.infoStatus"
                                    :message="message" />
                            </div>
                        </div>
                    </div>     

                    <hr class="double">               

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-infos'}" class="btn-rounded btn-auto btn-auto disable-bg">{{ translateText('button.cancel') }}</router-link>
                        <a ref="#" class="btn-rounded btn-auto btn-auto second-bg" @click="doSave">
                            {{ translateText(info && info.id ? 'button.edit_info' : 'button.create_info') }}
                        </a>
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
import Error from '../../_common/_messages/Error.vue';
import Editor from '../../_common/Editor';
import AlertModal from '../../_common/AlertModal.vue';
import router from '../../../router';
import {mapActions, mapGetters} from 'vuex';

export default {
    components: {
        InputField,
        SelectField,
        datepicker,
        CalendarIcon,
        MemberSearch,
        Error,
        Editor,
        AlertModal,
    },
    methods: {
        ...mapActions([
            'getInfoCategories',
            'getInfoStatuses',
            'clearInfo',
            'getInfo',
            'createInfo',
            'editInfo',
            'emptyValidationMessages',
        ]),
        translateText: function(text) {
            return this.translate(text);
        },
        doSave() {
            const data = {
                topic: this.topic,
                description: this.description,
                dueDate: this.dueDate,
                infoCategory: this.infoCategory && this.infoCategory.key
                    ? this.infoCategory.key
                    : null,
                infoStatus: this.infoStatus && this.infoStatus.key
                    ? this.infoStatus.key
                    : null,
                responsibility: this.responsibility && this.responsibility.length
                    ? this.responsibility[0]
                    : null,
            };

            let method = 'createInfo';
            let params = {
                projectId: this.$route.params.id,
                data,
            };

            if (this.$route.params.infoId) {
                method = 'editInfo';
                params.id = this.$route.params.infoId;
                delete params.projectId;
            }

            this[method](params)
                .then(
                    (response) => {
                        if (response && response.body && !response.body.error) {
                            this.showSaved = true;
                        }
                    },
                    () => {}
                )
            ;
        },
    },
    watch: {
        showSaved(val) {
            if (val === false) {
                router.push({
                    name: 'project-infos-view',
                    params: {
                        id: this.$route.params.id,
                        infoId: this.info.id,
                    },
                });
            }
        },
        info(val) {
            if (val) {
                this.topic = val.topic;
                this.description = val.description;
                this.dueDate = val.dueDate;
                this.infoCategory = {
                    key: val.infoCategory,
                    label: this.translate(val.infoCategoryName),
                };
                this.infoStatus = {
                    key: val.infoStatus,
                    label: this.translate(val.infoStatusName),
                };
                this.responsibility = [val.responsibility];
            }
        },
    },
    computed: {
        ...mapGetters(['info', 'infoCategoriesForDropdown', 'infoStatusesForDropdown', 'validationMessages']),
    },
    created() {
        this.getInfoCategories();
        this.getInfoStatuses();
        if (this.$route.params.infoId) {
            this.getInfo(this.$route.params.infoId);
        }
    },
    beforeDestroy() {
        this.emptyValidationMessages();
        this.clearInfo();
    },
    data() {
        return {
            showFailed: false,
            showSaved: false,
            topic: '',
            description: '',
            dueDate: new Date(),
            infoCategory: null,
            infoStatus: null,
            responsibility: [],
//            projectCategories: [{label: 'Production', key: 1}, {label: 'Logistics', key: 2}, {label: 'Quality Management', key: 3},
//             {label: 'Human Resources', key: 4}, {label: 'Purchasing', key: 5}, {label: 'Maintenance', key: 6},
//              {label: 'Assembly', key: 7}, {label: 'Tooling', key: 8}, {label: 'Process Engineering', key: 9}, {label: 'Industrialization', key: 10}],
//            infoStatus: [{label: 'Published', key: 1}, {label: 'Expired', key: 2}],
//            info_topic: '',
//            schedule: {
//                expiryDate: new Date(),
//            },
//            details: {
//                category: null,
//                infoStatus: null,
//            },
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    
</style>
