<template>
    <div class="add-label page-section">
        <div class="header">
            <h1>Add New Label</h1>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-task-management-list'}" class="btn-rounded btn-auto">View Grid</router-link>
                <router-link :to="{name: 'project-task-management-list'}" class="btn-rounded btn-auto">View Board</router-link>
                <router-link :to="{name: 'project-task-management-add-label'}" class="btn-rounded btn-auto second-bg">New Label</router-link>
                <router-link :to="{name: 'project-task-management-create'}" class="btn-rounded btn-auto second-bg">New Task</router-link>
            </div>
        </div>
        <div class="form">
            <input-field type="text" label="Label Title"></input-field>
            <input-field type="textarea" label="Label Description"></input-field>
            <div class="color">
                <input-field type="text" label="Label Color" :content="color" :css="css"></input-field>
                <sketch-picker v-model="colors" @change-color="onChange"></sketch-picker>
            </div>
            <p class="note">Note: the Label text color is white so pick a background color that makes it easy to read the white text above it.</p>

            <div class="flex flex-space-between actions">
                <a class="btn-rounded btn-auto disable-bg">Cancel</a>
                <a class="btn-rounded btn-auto">Create Label</a>
            </div>
        </div>
    </div>
</template>

<script>
import InputField from '../../_common/_form-components/InputField';
import {Sketch} from 'vue-color';

export default {
    components: {
        InputField,
        'sketch-picker': Sketch,
    },
    methods: {
        onChange(color) {
            this.color = color.hex;
            this.css = 'background: ' + color.hex;
        },
    },
    data() {
        return {
            css: 'background: #194D33',
            color: '#194D33',
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
