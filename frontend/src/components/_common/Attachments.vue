<template>
    <div :class="{disabled: disabled}">
        <input
                ref="file"
                type="file"
                name="attachments"
                style="display: none;"
                @change="onChange"/>
        <div class="attachments">
            <template v-for="(media, index) in inputValue">
                <div
                        class="attachment"
                        v-if="media"
                        :key="index">
                    <view-icon/>
                    <span class="attachment-name">
                        <a @click="getMediaFile(media)" v-if="media.id">{{ media.name }}</a>
                        <span v-else>{{ media.name }}</span>
                    </span>
                    <i
                            class="fa fa-times"
                            @click="onRemove(index)"
                            v-if="!disabled && editable"></i>
                </div>
                <template v-for="(messages, errIndex) in getErrorMessages(index)">
                    <error
                            :key="`error_message${index}_${errIndex}`"
                            :message="messages"/>
                </template>
            </template>
            <error
                    v-for="(message, index) in validationErrorMessages"
                    :key="`validation_error_message${index}`"
                    :message="message"/>
        </div>
        <div class="text-center" v-if="editable">
            <a
                    class="btn-rounded btn-empty btn-md"
                    @click="onAdd">{{ translate(this.label) }}</a>
            <div class="max-upload-file-size-message" v-if="maxFileSize">
                <em>{{ 'message.max_upload_file_size'|trans({'size': $formatBytes(maxFileSize)}) }}</em>
            </div>
        </div>
    </div>
</template>

<script>
    import ViewIcon from './_icons/ViewIcon';
    import Vue from 'vue';
    import Error from './_messages/Error';

    export default {
        name: 'attachments',
        props: {
            value: {
                type: Array,
                default: () => [],
            },
            disabled: {
                type: Boolean,
                required: false,
                default: false,
            },
            label: {
                type: String,
                required: false,
                default: 'button.add_attachment',
            },
            editable: {
                type: Boolean,
                required: false,
                default: true,
            },
            maxFileSize: {
                type: Number,
                required: false,
                default: 1024 * 1024 * 10,
            },
            validate: {
                type: Boolean,
                required: false,
                default: true,
            },
            errorMessages: {
                type: Array,
                required: false,
                default: () => [],
            },
        },
        components: {
            Error,
            ViewIcon,
        },
        methods: {
            onAdd() {
                if (this.disabled) {
                    return;
                }

                this.$refs.file.click();
            },
            onChange(e) {
                if (this.disabled) {
                    return;
                }

                let files = e.target.files || e.dataTransfer.files;
                if (!files.length) {
                    return;
                }

                if (!this.isValid(files[0])) {
                    return;
                }

                this.inputValue.push(files[0]);
                this.$emit('input', this.inputValue);
                e.target.value = '';
            },
            onRemove(index) {
                if (this.disabled) {
                    return;
                }

                let inputValue = [];
                this.inputValue.forEach((value, key) => {
                    if (key === index) {
                        return;
                    }

                    inputValue[key] = value;
                });

                this.lazyErrorMessages[index] = [];

                this.$emit('input', inputValue);
            },
            getMediaFile(media) {
                if (!media.id) {
                    return;
                }

                const url = Routing.generate('app_api_media_download', {id: media.id});
                Vue.http.get(url, {responseType: 'blob'})
                   .then((response) => {
                       if (response.status !== 200) {
                           return;
                       }

                       let options = {};
                       if (response.headers && response.headers.map && response.headers.map['content-type']) {
                           options.type = response.headers.map['content-type'][0];
                       }

                       let blob = new Blob([response.body], options);
                       let a = document.createElement('a');
                       a.href = window.URL.createObjectURL(blob);
                       a.download = media.originalName;
                       document.body.appendChild(a);
                       a.click();

                       setTimeout(() => {
                           document.body.removeChild(a);
                           window.URL.revokeObjectURL(url);
                       }, 100);
                   });
            },
            isValid(file) {
                if (!this.validate) {
                    return true;
                }

                this.validationErrorMessages = [];
                if (file.size > this.maxFileSize) {
                    let message = this.translate(
                        'message.file_too_large',
                        {
                            size: this.$formatBytes(file.size),
                            limit: this.$formatBytes(this.maxFileSize),
                        },
                    );

                    this.validationErrorMessages.push(`${file.name} - ${message}`);
                    return false;
                }

                return true;
            },
            getErrorMessages(index) {
                if (!this.lazyErrorMessages[index]) {
                    return [];
                }

                return this.lazyErrorMessages[index];
            },
        },
        watch: {
            value(value) {
                this.inputValue = [...value];
            },
            errorMessages(value) {
                this.lazyErrorMessages = value;
            },
        },
        data() {
            return {
                inputValue: [],
                validationErrorMessages: [],
                lazyErrorMessages: this.errorMessages,
            };
        },
    };
</script>

<style lang="scss">
    .attachment {
        .view-icon {
            display: inline;
            margin-right: 10px;
            position: relative;
            top: 3px;

            svg {
                width: 18px;
            }
        }
    }
</style>

<style scoped lang="scss">
    @import '../../css/variables';
    @import '../../css/mixins';
    @import '../../css/common';

    .disabled {
        * {
            cursor: not-allowed !important;
        }
    }

    .attachments {
        margin: 0 0 20px;
    }

    .attachment {
        padding: 10px 20px;
        background-color: $fadeColor;
        margin-top: 3px;
        color: $secondColor;
        position: relative;

        .view-icon {
            display: inline;
            margin-right: 10px;
            position: relative;
            top: 3px;

            svg {
                width: 18px;
            }
        }

        i.fa {
            position: absolute;
            right: 20px;
            top: 16px;
            color: $dangerColor;
            cursor: pointer;
            @include transition(opacity, 0.2s, ease-in);

            &:hover,
            &:active {
                @include opacity(0.8);
            }
        }
        .attachment-name {
            a {
                cursor: pointer;
            }
        }
    }

    .max-upload-file-size-message {
        margin-top: 1em;
        color: $lightColor;
    }
</style>
