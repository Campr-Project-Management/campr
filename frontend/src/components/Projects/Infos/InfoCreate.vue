<template>
    <div class="row">
        <div class="col-md-6">
            <div class="create-meeting page-section">
                <!-- /// Header /// -->
                <div class="header flex-v-center">
                    <div>
                        <router-link :to="{name: 'project-infos'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ translate('message.back_to_infos') }}
                        </router-link>
                        <h1>{{ translate(info && info.id ? 'message.edit_info' : 'message.create_new_info') }}</h1>
                    </div>
                </div>
                <!-- /// End Header /// -->
                
                <div class="form">
                    <!-- /// Info Category /// -->
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-12">
                                <select-field
                                        :title="translate('label.category')"
                                        :options="infoCategoriesForDropdown"
                                        v-model="infoCategory"/>
                                <error at-path="infoCategory"/>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Info Category /// -->

                    <!-- /// Info Title and Description /// -->
                    <div class="form-group">
                        <input-field type="text" :label="translate('placeholder.info_topic')" v-model="topic" :content="topic" />
                        <error at-path="topic"/>
                    </div>
                    <div class="form-group">
                        <editor
                            v-model="description"
                            :label="'placeholder.info_description'"/>
                        <error at-path="description"/>
                    </div>
                    <!-- /// End Info Title and Description /// -->

                    <!-- /// Info Responsible, Due Date and Status /// -->
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <member-search
                                        v-model="responsibility"
                                        :placeholder="translate('placeholder.responsible')"
                                        :singleSelect="true"></member-search>
                                <error at-path="responsibility"/>
                            </div>
                            <div class="col-md-6">
                                <div class="input-holder right">
                                    <label class="active">{{ translate('label.expiry_date') }}</label>
                                    <datepicker v-model="expiresAt" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill"/>
                                </div>
                                <error at-path="dueDate"/>
                            </div>
                        </div>
                    </div>

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-infos'}" class="btn-rounded btn-auto btn-auto disable-bg">{{ translate('button.cancel') }}</router-link>
                        <a class="btn-rounded btn-auto btn-auto second-bg" @click="doSave">
                            {{ translate(info && info.id ? 'button.edit_info' : 'button.create_info') }}
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
import moment from 'moment';

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
            'clearInfo',
            'getInfo',
            'createInfo',
            'editInfo',
            'emptyValidationMessages',
        ]),
        doSave() {
            const data = {
                topic: this.topic,
                description: this.description,
                expiresAt: this.expiresAt ? moment(this.expiresAt).format('DD-MM-YYYY') : null,
                infoCategory: this.infoCategory && this.infoCategory.key
                    ? this.infoCategory.key
                    : null,
                responsibility: this.responsibility && this.responsibility.length
                    ? this.responsibility[0]
                    : null,
            };

            let method = this.createInfo;
            let params = {
                projectId: this.$route.params.id,
                data,
            };

            if (this.$route.params.infoId) {
                method = this.editInfo;
                params.id = this.$route.params.infoId;
                delete params.projectId;
            }

            method(params)
                .then(
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
                this.expiresAt = val.expiresAt ? moment(val.expiresAt).toDate() : null;
                this.infoCategory = {
                    key: val.infoCategory,
                    label: this.translate(val.infoCategoryName),
                };
                this.responsibility = [val.responsibility];
            }
        },
    },
    computed: {
        ...mapGetters(['info', 'infoCategoriesForDropdown', 'validationMessages']),
    },
    created() {
        this.getInfoCategories();
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
            expiresAt: null,
            infoCategory: null,
            responsibility: [],
        };
    },
};
</script>
