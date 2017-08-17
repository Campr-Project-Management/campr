<template>
    <div class="box" v-bind:id="item.id" v-if="!showEdit">
        <header class="flex flex-space-between full">
            <span>
                {{ index+1 }}.
                <span v-if="item.title">{{ title }}</span>
                <span v-if="!item.title">{{ description }}</span>
            </span>
            <div class="flex" v-if="!disabled">
                <pencil-icon v-on:click.native="showEditBox()" :link="{name: ''}"></pencil-icon>
                <drag-icon></drag-icon>
            </div>
        </header>
    </div>
    <div v-else-if="showEdit">
        <div class="hr small"></div>
        <input-field v-if="item.title" v-model="title" :content="title" type="text" v-bind:label="dynamicLabels(type, 'title')"></input-field>
        <error
            v-if="item.title && validationMessages.dragbox && validationMessages.title && validationMessages.title.length"
            v-for="message in validationMessages.title"
            :message="message" />
        <input-field v-if="!item.title" v-model="description" :content="description" type="text" v-bind:label="dynamicLabels(type, 'description')"></input-field><br />
        <error
            v-if="!item.title && validationMessages.dragbox && validationMessages.description && validationMessages.description.length"
            v-for="message in validationMessages.description"
            :message="message" />
        <div class="flex flex-direction-reverse">
            <a v-on:click="edit(type)" class="btn-rounded">{{ dynamicLabels(type, 'button') }}</a>
        </div>
        <br />
    </div>
</template>

<script>
import PencilIcon from '../../_common/_icons/PencilIcon';
import DragIcon from '../../_common/_icons/DragIcon';
import InputField from '../../_common/_form-components/InputField';
import Error from '../../_common/_messages/Error.vue';

export default {
    props: ['item', 'index', 'type', 'disabled'],
    components: {
        InputField,
        PencilIcon,
        DragIcon,
        Error,
    },
    data: function() {
        return {
            showEdit: false,
            message: {
                new_objective_title: Translator.trans('message.new_objective_title'),
                new_objective_description: Translator.trans('message.new_objective_description'),
                edit_objective: Translator.trans('message.edit_objective'),
                new_project_limitation: Translator.trans('message.new_project_limitation'),
                edit_limitation: Translator.trans('message.edit_limitation'),
                new_project_deliverable: Translator.trans('message.new_project_deliverable'),
                edit_deliverable: Translator.trans('message.edit_deliverable'),
            },
            title: this.item.title,
            description: this.item.description,
            validationMessages: {},
        };
    },
    methods: {
        dynamicLabels: function(type, message) {
            let x = '';
            if (type !== 'objective' && message !== 'button') {
                x = 'this.message.new_project_'+ type;
            } else if (type === 'objective' && message !== 'button') {
                x = 'this.message.new_'+ type + '_' + message;
            } else if (message === 'button') {
                x = 'this.message.edit_'+ type;
            }

            return eval(x);
        },
        showEditBox: function() {
            this.showEdit = true;
        },
        edit: function(type) {
            let data = {
                itemId: this.item.id,
                title: this.title,
                description: this.description,
            };
            switch(type) {
            case 'objective':
                this
                    .$parent
                    .editObjective(data)
                    .then(
                        (response) => {
                            this.$parent.showSavedComponent = true;
                            if (response.body && response.body.error) {
                                const {messages} = response.body;
                                messages.dragbox = true;
                                this.validationMessages = messages;
                            } else {
                                this.validationMessages = {};
                                this.showEdit = false;
                            }
                        },
                        () => {
                            this.$parent.showFailedComponent = true;
                        }
                    )
                ;
                break;
            case 'limitation':
                this
                    .$parent
                    .editLimitation(data)
                    .then(
                        (response) => {
                            this.$parent.showSavedComponent = true;
                            if (response.body && response.body.error) {
                                const {messages} = response.body;
                                messages.dragbox = true;
                                this.validationMessages = messages;
                            } else {
                                this.validationMessages = {};
                                this.showEdit = false;
                            }
                        },
                        () => {
                            this.$parent.showFailedComponent = true;
                        }
                    )
                ;
                break;
            case 'deliverable':
                this
                    .$parent
                    .editDeliverable(data)
                    .then(
                        (response) => {
                            this.$parent.showSavedComponent = true;
                            if (response.body && response.body.error) {
                                const {messages} = response.body;
                                messages.dragbox = true;
                                this.validationMessages = messages;
                            } else {
                                this.validationMessages = {};
                                this.showEdit = false;
                            }
                        },
                        () => {
                            this.$parent.showFailedComponent = true;
                        }
                    )
                ;
                break;
            default:
                break;
            }
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_common';
    @import '../../../css/_variables';

    .box.gu-transit {
        border: 1px solid $lighterColor;
    }

    .box {
        background: $darkColor;
        padding: 20px;
        margin-bottom: 20px;

        header {
            font-weight: 300;
            text-transform: uppercase;
            height: 36px;
            border-bottom: 1px solid $mainColor;
            padding-bottom: 13px;

            &.full {
                border: none;
                height: auto;
                color: $lightColor;
                padding-bottom: 0;
            }
        }

        .content {
            padding-top: 20px;
            color: $lightColor;
        }
    }
</style>
