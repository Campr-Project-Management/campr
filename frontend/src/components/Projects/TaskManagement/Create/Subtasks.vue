<template>
    <div>
        <h3>{{ message.subtasks }}</h3>
        <input-field
            type="text"
            v-bind:label="label.subtask_description"
            v-model="subtaskDescription"
            v-bind:content="subtaskDescription" />
        <div class="flex flex-direction-reverse">
            <a v-on:click="addSubtask()" class="btn-rounded btn-auto add-task">{{ button.add_new_subtask }} +</a>
        </div>

        <div class="row" v-for="subtask in subtasks">
            <div class="form-group">
                <div class="col-md-12">
                    <span class="title"><b><i class="fa fa-square-o"></i> {{ subtask.description }}</b></span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import InputField from '../../../_common/_form-components/InputField';

export default {
    components: {
        InputField,
    },
    methods: {
        addSubtask: function() {
            if (!this.subtaskDescription.length) {
                return;
            }
            this.subtasks.push({
                description: this.subtaskDescription,
            });
            this.subtaskDescription = '';
        },
    },
    watch: {
        subtasks: function(value) {
            this.$emit('input', value);
        },
    },
    data() {
        return {
            message: {
                subtasks: this.translate('message.subtasks'),
            },
            label: {
                subtask_description: this.translate('label.subtask_description'),
            },
            button: {
                add_new_subtask: this.translate('button.add_new_subtask'),
            },
            subtasks: [],
            subtaskDescription: '',
        };
    },
};
</script>
