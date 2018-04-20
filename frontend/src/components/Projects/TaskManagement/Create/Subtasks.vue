<template>
    <div>
        <h3>{{ message.subtasks }}</h3>
        <div class="row" v-for="subtask, index in subtasks">
            <div class="create-task__subtasks flex flex-v-center">
                <div class="form-group col-md-11">
                    <input-field
                        type="text"
                        v-bind:label="label.subtask_description"
                        v-model="subtask.description"
                        v-bind:content="subtask.description" />
                    <error
                        v-if="validationMessages && validationMessages[index] && validationMessages[index].name && validationMessages[index].name.length"
                        v-for="message in ((validationMessages && validationMessages[index] && validationMessages[index].name) || [])"
                        :message="message" />
                </div>
                <div class="col-md-1">
                    <button 
                        v-on:click="deleteSubtask(index);" 
                        type="button"
                        class="btn-icon">
                        <delete-icon fill="danger-fill"/>
                    </button>
                </div>
            </div>
        </div>
        <div class="flex flex-direction-reverse">
            <a v-on:click="addSubtask()" class="btn-rounded btn-auto add-task">{{ button.add_new_subtask }} +</a>
        </div>
    </div>
</template>

<script>
import InputField from '../../../_common/_form-components/InputField';
import DeleteIcon from '../../../_common/_icons/DeleteIcon';
import Error from '../../../_common/_messages/Error.vue';

export default {
    props: ['editSubtasks', 'validationMessages'],
    components: {
        InputField,
        DeleteIcon,
        Error,
    },
    methods: {
        addSubtask: function() {
            this.subtasks.push({
                description: '',
            });
        },
        deleteSubtask: function(index) {
            this.subtasks = [
                ...this.subtasks.slice(0, index),
                ...this.subtasks.slice(index + 1),
            ];
        },
    },
    watch: {
        subtasks: function(value) {
            this.$emit('input', value);
        },
        editSubtasks(value) {
            this.subtasks = this.editSubtasks;
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
        };
    },
};
</script>

<style scoped lang="scss">
    @import '../../../../css/_common';

    .create-task__subtasks {
        margin-bottom:20px;

        .form-group {
            margin-bottom: 0;
        }
    }
</style>
