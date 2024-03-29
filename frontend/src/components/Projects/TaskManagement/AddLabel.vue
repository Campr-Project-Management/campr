<template>
    <can role="roles.project_manager|roles.project_sponsor" :subject="project">
        <div class="add-label page-section">
            <div class="header">
                <h1>{{ isEdit ? translate('message.edit_label') : translate('message.new_label') }}</h1>

                <div class="flex flex-v-center">
                    <router-link :to="{name: 'project-task-management-list'}" class="btn-rounded btn-auto">{{ message.view_grid }}
                    </router-link>
                    <router-link :to="{name: 'project-task-management-list'}" class="btn-rounded btn-auto">{{ message.view_board }}
                    </router-link>
                    <router-link :to="{name: 'project-task-management-create'}" class="btn-rounded btn-auto second-bg">
                        {{ message.new_task }}
                    </router-link>
                </div>
            </div>
            <div class="form">
                <input-field
                        v-model="title"
                        type="text"
                        :content="title"
                        :label="message.label_title"/>
                <error
                        v-if="validationMessages.title && validationMessages.title.length"
                        v-for="message in validationMessages.title"
                        :message="message"/>
                <input-field
                        v-model="description"
                        type="textarea"
                        :content="description"
                        :label="message.label_description"/>
                <color-field
                        v-model="color"
                        :label="message.label_color"/>
                <error
                        v-if="validationMessages.color && validationMessages.color.length"
                        v-for="message in validationMessages.color"
                        :message="message"/>
                <p class="note">{{ message.label_note }}</p>
                <div class="flex flex-space-between actions">
                    <router-link :to="{name: 'project-task-management-edit-labels'}"
                                 class="btn-rounded btn-auto disable-bg">{{ button.cancel }}
                    </router-link>
                    <a v-if="!isEdit" @click="doCreateLabel" class="btn-rounded btn-auto">{{ button.create_label }}</a>
                    <a v-if="isEdit" @click="doEditLabel" class="btn-rounded btn-auto">{{ button.edit_label }}</a>
                </div>
            </div>
        </div>
    </can>
</template>

<script>
import InputField from '../../_common/_form-components/InputField';
import ColorField from '../../_common/_form-components/ColorField';
import {mapGetters, mapActions} from 'vuex';
import Error from '../../_common/_messages/Error.vue';
import router from '../../../router';

export default {
    name: 'add-label',

    components: {
        InputField,
        ColorField,
        Error,
    },
    created() {
        if (this.$route.params.labelId) this.getLabel(this.$route.params.labelId);
    },
    computed: {
        ...mapGetters([
            'label',
            'validationMessages',
            'project',
        ]),
        isEdit() {
            return !!this.$route.params.labelId;
        },
    },
    beforeDestroy() {
        this.emptyValidationMessages();
    },
    watch: {
        label(value) {
            this.title = this.label.title;
            this.description = this.label.description;
            this.color = this.label.color;
        },
        showSaved(value) {
            if (value === false) {
                router.push({
                    name: 'project-task-management-edit-labels',
                    params: {
                        id: this.$route.params.id,
                    },
                });
            }
        },
    },
    methods: {
        ...mapActions(['createLabel', 'getLabel', 'editLabel', 'emptyValidationMessages']),
        doCreateLabel: function() {
            if (!this.isSaving) {
                let data = {
                    title: this.title,
                    description: this.description,
                    color: this.color,
                };
                this.isSaving = true;
                this
                    .createLabel(data)
                    .then(
                        (response) => {
                            this.isSaving = false;
                            if (response.body && response.body.error) {
                                this.$flashError('message.unable_to_save');
                            } else {
                                this.showSaved = true;
                                this.$flashSuccess('message.saved');
                            }
                            this.active = true;
                        },
                        () => {
                            this.$flashError('message.unable_to_save');
                            this.active = true;
                        }
                    )
                ;
            }
        },
        doEditLabel: function() {
            let data = {
                labelId: this.$route.params.labelId,
                title: this.title,
                description: this.description,
                color: this.color,
            };
            this
                .editLabel(data)
                .then(
                    (response) => {
                        if (response.body && response.body.error) {
                            this.$flashError('message.unable_to_save');
                        } else {
                            this.showSaved = true;
                            this.$flashSuccess('message.saved');
                        }
                    },
                    () => {
                        this.$flashError('message.unable_to_save');
                    },
                )
            ;
        },
    },
    data() {
        return {
            showSaved: false,
            color: '',
            title: '',
            description: '',
            message: {
                view_grid: this.translate('message.view_grid'),
                view_board: this.translate('message.view_board'),
                new_task: this.translate('message.new_task'),
                label_title: this.translate('placeholder.label_title'),
                label_description: this.translate('placeholder.label_description'),
                label_color: this.translate('placeholder.label_color'),
                label_note: this.translate('message.label_note'),
            },
            button: {
                cancel: this.translate('button.cancel'),
                create_label: this.translate('button.create_label'),
                edit_label: this.translate('button.edit_label'),
            },
            isSaving: false,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style lang="scss">
    .color {
        input[type=text] {
            color: #ffffff;
        }
    }
</style>

<style scoped lang="scss">
    @import '../../../css/page-section';

    .form {
        max-width: 820px;
    }

    .input-holder {
        margin-bottom: 20px;
    }

    .header .btn-rounded {
        margin-left: 10px;
    }

    .actions {
        margin-top: 15px;
    }
</style>
