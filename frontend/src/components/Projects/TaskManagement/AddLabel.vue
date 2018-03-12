<template>
    <div class="add-label page-section">
        <div class="header">
            <h1>{{ message.add_new_label }}</h1>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-task-management-list'}" class="btn-rounded btn-auto">{{ message.view_grid }}</router-link>
                <router-link :to="{name: 'project-task-management-list'}" class="btn-rounded btn-auto">{{ message.view_board }}</router-link>
                <router-link :to="{name: 'project-task-management-create'}" class="btn-rounded btn-auto second-bg">{{ message.new_task }}</router-link>
            </div>
        </div>
        <div class="form">
            <input-field v-model="title" type="text" v-bind:content="title" v-bind:label="message.label_title"></input-field>
            <error
                v-if="validationMessages.title && validationMessages.title.length"
                v-for="message in validationMessages.title"
                :message="message" />
            <input-field v-model="description" type="textarea" v-bind:content="description" v-bind:label="message.label_description"></input-field>
            <div class="color">
                <input-field @click.native="toggleSketch" v-model="color" type="text" v-bind:label="message.label_color" :content="color" :css="css"></input-field>
                <error
                    v-if="validationMessages.color && validationMessages.color.length"
                    v-for="message in validationMessages.color"
                    :message="message" />
                <sketch-picker v-show="showSketch" v-model="colors" @change-color="onChange"></sketch-picker>
            </div>
            <p class="note">{{ message.label_note }}</p>
            <div class="flex flex-space-between actions">
                <router-link :to="{name: 'project-task-management-edit-labels'}" class="btn-rounded btn-auto disable-bg">{{  button.cancel }}</router-link>
                <a v-if="!isEdit" @click="createLabel" class="btn-rounded btn-auto">{{ button.create_label }}</a>
                <a v-if="isEdit" @click="editLabel" class="btn-rounded btn-auto">{{ button.edit_label }}</a>
            </div>
        </div>

        <alert-modal v-if="showSaved" @close="showSaved = false" body="message.saved" />
        <alert-modal v-if="showFailed" @close="showFailed = false" body="message.unable_to_save" />
    </div>
</template>

<script>
import InputField from '../../_common/_form-components/InputField';
import {Sketch} from 'vue-color';
import {mapGetters, mapActions} from 'vuex';
import Error from '../../_common/_messages/Error.vue';
import AlertModal from '../../_common/AlertModal';
import router from '../../../router';

export default {
    components: {
        InputField,
        'sketch-picker': Sketch,
        Error,
        AlertModal,
    },
    created() {
        if (this.$route.params.labelId) this.getProjectLabel(this.$route.params.labelId);
    },
    computed: mapGetters({
        label: 'label',
        validationMessages: 'validationMessages',
    }),
    beforeDestroy() {
        this.emptyValidationMessages();
    },
    watch: {
        label(value) {
            this.title = this.label.title;
            this.description = this.label.description;
            this.color = this.label.color;
            this.colors.hex = this.label.color;
            this.css = 'background: ' + this.label.color;
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
        ...mapActions(['createProjectLabel', 'getProjectLabel', 'editProjectLabel', 'emptyValidationMessages']),
        onChange(color) {
            this.color = color.hex;
            this.css = 'background: ' + color.hex;
        },
        toggleSketch() {
            this.showSketch = !this.showSketch;
        },
        createLabel: function() {
            let data = {
                projectId: this.$route.params.id,
                title: this.title,
                description: this.description,
                color: this.color,
            };
            this
                .createProjectLabel(data)
                .then(
                    (response) => {
                        if (response.body && response.body.error) {
                            this.showFailed = true;
                        } else {
                            this.showSaved = true;
                        }
                    },
                    () => {
                        this.showFailed = true;
                    }
                )
            ;
        },
        editLabel: function() {
            let data = {
                labelId: this.$route.params.labelId,
                title: this.title,
                description: this.description,
                color: this.color,
            };
            this
                .editProjectLabel(data)
                .then(
                    (response) => {
                        if (response.body && response.body.error) {
                            this.showFailed = true;
                        } else {
                            this.showSaved = true;
                        }
                    },
                    () => {
                        this.showFailed = true;
                    },
                )
            ;
        },
    },
    data() {
        return {
            showSaved: false,
            showFailed: false,
            color: '',
            title: '',
            description: '',
            showSketch: false,
            message: {
                add_new_label: this.translate('add.label'),
                new_label: this.translate('message.new_label'),
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
            isEdit: this.$route.params.labelId,
            css: 'background: #194D33',
            colors: {
                hex: '#194d33',
                hsl: {
                    h: 150,
                    s: 0.5,
                    l: 0.2,
                    a: 1,
                },
                hsv: {
                    h: 150,
                    s: 0.66,
                    v: 0.30,
                    a: 1,
                },
                rgba: {
                    r: 25,
                    g: 77,
                    b: 51,
                    a: 1,
                },
                a: 1,
            },
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
    @import '../../../css/_variables';

    .form {
        max-width: 820px;
    }

    .input-holder {
        margin-bottom: 20px;
    }

    .header .btn-rounded {
        margin-left: 10px;
    }

    .color {
        position: relative;
    }

    .vue-color__sketch {
        position: absolute;
        left: 100%;
        bottom: 0;
        margin-left: 10px;
    }

    .actions {
        margin-top: 15px;
    }
</style>
