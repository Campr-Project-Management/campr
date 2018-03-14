<template>
    <div class="editor" :class="{disabled: disabled}">
        <span class="label" v-if="label">{{ label | trans }}</span>
        <vue-editor
            :id="id"
            :value="value"
            @input="onInput"
            :disabled="disabled"
            :editorToolbar="toolbar"/>
    </div>
</template>

<script>
    import {VueEditor} from 'vue2-editor';
    import Vue from 'vue';

    export default {
        name: 'editor',
        props: {
            id: {
                type: String,
                default: 'quill-container',
            },
            value: {
                type: String,
                required: false,
            },
            label: {
                type: String,
                required: false,
            },
            disabled: {
                type: Boolean,
                required: false,
                default: false,
            },
            toolbar: {
                type: Array,
                required: false,
                default: () => {
                    return [
                        ['bold', 'italic', 'underline'],
                        [{'list': 'ordered'}, {'list': 'bullet'}],
                        ['image'],
                    ];
                },
            },
            options: {
                type: Object,
                required: false,
                default: () => {
                    return {
                    };
                },
            },
            height: {
                type: String,
                required: false,
                default: '400px',
            },
        },
        components: {
            VueEditor,
        },
        filters: {
            trans: (str) => Vue.translate(str),
        },
        methods: {
            onInput(value) {
                this.$emit('input', value);
            },
        },
        mounted() {
            this.$nextTick(() => {
                this.$el.querySelector(`#${this.id}`).style.height = this.height;
            });
        },
    };
</script>

<style lang="scss">
    .editor {
        position: relative;
        clear: both;

        > span.label {
            top: 17px;
            position: absolute;
        }

        &.disabled {
            .ql-snow {
                opacity: .5;
                pointer-events: none !important;
            }
        }
    }

    .ql-toolbar {
        border: none !important;
        border-bottom: 1px solid #646EA0 !important;
        text-align: right;

        .ql-formats {
            color: #646EA0 !important;

            .ql-picker {
                color: #646EA0 !important;

                .ql-picker-label {
                    font-size: 12px;
                    font-weight: normal;
                }
            }
        }
    }

    .ql-snow {
        background-color: #191E37;
        color: #8794C4;
        font-size: 10px;

        .ql-stroke {
            stroke: #646EA0 !important;
            stroke-width: 1px !important;
        }
        .ql-fill {
            fill: #646EA0 !important;
        }
    }

    .ql-editor {
        font-family: Poppins !important;
        font-size: 12px !important;
        line-height: 1.5em;
    }

    .ql-container {
        border: none !important;
    }
</style>

<style scoped lang="scss">
    .editor {
        margin-top: 1em;

        .label {
            color: #8794C4;
            z-index: 2;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 400;
            margin: 0 0 0 15px;
        }
    }
</style>
