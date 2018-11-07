<template>
    <div :class="{disabled: disabled}">

        <div class="attachments">
            <template v-for="(file, index) in inputValue">
                <div
                        class="attachment"
                        v-if="file"
                        :key="`attachment_${file.id}`">
                    <view-icon/>
                    <span class="attachment-name">
                        <a @click="getMediaFile(file)" v-if="file.id">{{ file.name }}</a>
                        <span v-else>{{ file.name }}</span>
                    </span>
                    <i
                            class="fa fa-times"
                            @click="onRemove(index)"
                            v-if="!disabled && editable"></i>
                </div>
            </template>
            <template v-for="uf in uploadingFiles">
                <div
                        class="attachment"
                        :key="`uploading_file_${uf.file.upload.uuid}`">
                    <div>
                        <view-icon/>
                        <span class="attachment-name">
                            <span>{{ uf.file.name }}</span> &mdash; <strong>{{ Math.floor(uf.progress.percent) }}%</strong>
                        </span>
                        <i
                                v-if="uf.errors.length > 0"
                                class="fa fa-times"
                                @click="onRemoveUploadingFile(uf.file)"></i>
                    </div>
                    <div class="uploading-file-progress-bar" v-if="uf.errors.length === 0">
                        <div class="uploading-file-progress" :style="{width: `${uf.progress.percent}%`}"></div>
                    </div>
                    <error
                            :key="`error_message_${uf.file.upload.uuid}`"
                            :message="uf.errors"/>
                </div>
            </template>
        </div>
        <div class="text-center" v-if="editable">
            <file-field
                    :url="uploadUrl"
                    :max-file-size="maxFileSize / (1024 * 1024)"
                    :label="label"
                    :chunking="true"
                    :isDocument="isDocument"
                    @input="onInput"
                    @add="onFileAdded"
                    @uploaded="onFileUploaded"
                    @queue-complete="onQueueComplete"/>
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
    import FileField from './_form-components/FileField';

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
            isDocument: {
                type: Boolean,
                required: false,
                default: false,
            },
            errorMessages: {
                type: Array,
                required: false,
                default: () => [],
            },
            url: {
                type: String,
                required: false,
                default: null,
            },
        },
        components: {
            FileField,
            Error,
            ViewIcon,
        },
        computed: {
            uploadUrl() {
                return this.url ? this.url : this.$generateUrl('app_api_project_uploader_media_upload',
                    {id: this.$route.params.id});
            },
        },
        methods: {
            onInput(file) {
                this.inputValue.push(file);
            },
            onQueueComplete() {
                this.$emit('input', this.inputValue);
            },
            onFileAdded(uf) {
                if (this.disabled) {
                    return;
                }

                this.uploadingFiles.push(uf);
            },
            onFileUploaded(file) {
                this.removeUploadingFile(file);
            },
            removeUploadingFile(file) {
                let index = this.uploadingFiles.findIndex((uf) => uf.file.upload.uuid === file.upload.uuid);
                this.$delete(this.uploadingFiles, index);
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

                this.$emit('input', inputValue);
            },
            onRemoveUploadingFile(file) {
                this.removeUploadingFile(file);
            },
            getMediaFile(media) {
                if (!media.id) {
                    return;
                }

                const url = this.$generateUrl('app_api_media_download', {id: media.id});
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
        },
        watch: {
            value(value) {
                this.inputValue = [...value];
            },
            uploadingFiles(value) {
                if (value.length > 0) {
                    this.$emit('uploading', true);
                    return;
                }

                this.$emit('uploading', false);
            },
        },
        mounted() {
            if (this.value) {
                this.inputValue = [...this.value];
            }
        },
        data() {
            return {
                inputValue: [],
                uploadingFiles: [],
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

        .uploading-file-progress-bar {
            background-color: $mainColor;
            width: 100%;
            margin-top: 5px;

            .uploading-file-progress {
                height: 3px;
                background-color: $secondColor;
                width: 0;
                @include transition(width, 0.2s, ease-in);
            }
        }
    }

    .max-upload-file-size-message {
        margin-top: 1em;
        color: $lightColor;
    }

</style>
