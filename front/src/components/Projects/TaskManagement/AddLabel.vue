<template>
    <div class="add-label page-section">
        <div class="header">
            <h1>{{ message.add_new_label }}</h1>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-task-management-list'}" class="btn-rounded btn-auto">{{ message.view_grid }}</router-link>
                <router-link :to="{name: 'project-task-management-list'}" class="btn-rounded btn-auto">{{ message.view_board }}</router-link>
                <router-link :to="{name: 'project-task-management-add-label'}" class="btn-rounded btn-auto second-bg">{{ message.new_label }}</router-link>
                <router-link :to="{name: 'project-task-management-create'}" class="btn-rounded btn-auto second-bg">{{ message.new_task }}</router-link>
            </div>
        </div>
        <div class="form">
            <input-field v-model="title" type="text" label="message.label_title"></input-field>
            <input-field v-model="description" type="textarea" label="message.label_description"></input-field>
            <div class="color">
                <input-field v-model="color" type="text" label="message.label_color" :content="color" :css="css"></input-field>
                <sketch-picker v-model="colors" @change-color="onChange"></sketch-picker>
            </div>
            <p class="note">{{ message.label_note }}</p>
            <div class="flex flex-space-between actions">
                <router-link :to="{name: 'project-task-management-edit-labels'}" class="btn-rounded btn-auto disable-bg">{{  button.cancel }}</router-link>
                <a v-on:click="createLabel" class="btn-rounded btn-auto">{{ button.create_label }}</a>
            </div>
        </div>
    </div>
</template>

<script>
import InputField from '../../_common/_form-components/InputField';
import {Sketch} from 'vue-color';
import {mapActions} from 'vuex';

export default {
    components: {
        InputField,
        'sketch-picker': Sketch,
    },
    methods: {
        ...mapActions(['createProjectLabel']),
        onChange(color) {
            this.color = color.hex;
            this.css = 'background: ' + color.hex;
        },
        createLabel: function() {
            let data = {
                projectId: this.$route.params.id,
                title: this.title,
                description: this.description,
                color: this.color,
            };
            this.createProjectLabel(data);
        },
    },
    data() {
        return {
            color: '#194d33',
            message: {
                add_new_label: window.Translator.trans('message.add.label'),
                new_label: window.Translator.trans('message.new_label'),
                view_grid: window.Translator.trans('message.view_grid'),
                view_board: window.Translator.trans('message.view_board'),
                new_task: window.Translator.trans('message.new_task'),
                label_title: window.Translator.trans('placeholder.label_title'),
                label_description: window.Translator.trans('placeholder.label_description'),
                label_color: window.Translator.trans('placeholder.label_color'),
                label_note: window.Translator.trans('message.label_note'),
            },
            button: {
                cancel: window.Translator.trans('button.cancel'),
                create_label: window.Translator.trans('button.create_label'),
            },
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
