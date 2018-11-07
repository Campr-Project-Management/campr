<template>
    <div class="file-field" v-bind:class="{ document: isDocument }">
        <dropzone
                :id="id"
                :options="options"
                @vdropzone-file-added="onFileAdded"
                @vdropzone-success="onSuccessfullyUploaded"
                @vdropzone-error="onError"
                @vdropzone-queue-complete="onQueueCompleted"
                @vdropzone-upload-progress="onUploadProgress"/>
    </div>
</template>

<script>
    import Dropzone from 'vue2-dropzone';
    import httpConfig from '../../../config/http';
    import {mapGetters} from 'vuex';

    export default {
        name: 'file-field',
        props: {
            url: {
                type: String,
                required: true,
            },
            acceptedFiles: {
                type: String,
                required: false,
                default: 'image/png,image/jpeg,image/jpg,application/pdf',
            },
            name: {
                type: String,
                required: false,
                default: 'file',
            },
            label: {
                type: String,
                required: false,
                default: 'button.add_attachment',
            },
            isDocument: {
                type: Boolean,
                required: true,
                default: false,
            },
            maxFileSize: {
                type: Number,
                required: false,
                default: 10,
            },
            value: {
                required: false,
            },
            parallelUploads: {
                type: Number,
                required: false,
                default: 5,
                validate(value) {
                    return value > 0;
                },
            },
            chunking: {
                type: Boolean,
                required: false,
                default: false,
            },
            chunkSize: {
                type: Number,
                required: false,
                default: 1024 * 1024, // bytes
            },
            retryChunks: {
                type: Boolean,
                required: false,
                default: true,
            },
            id: {
                type: String,
                required: false,
                default() {
                    return `${this.name}_${Math.ceil(Math.random() * 1000000000)}`;
                },
            },
        },
        components: {
            Dropzone,
        },
        methods: {
            onFileAdded(file) {
                let uf = {file, progress: {percent: 0, bytes: 0}, errors: []};
                this.uploadingFiles.push(uf);
                this.$emit('add', uf);
            },
            onQueueCompleted() {
                this.$emit('queue-complete');
            },
            onSuccessfullyUploaded(file, response) {
                if (!response) {
                    response = JSON.parse(file.xhr.responseText);
                }

                if (response.error === true) {
                    if (response.messages) {
                        let uf = this.findUploadingFile(file);
                        if (uf) {
                            uf.errors = response.messages;
                        }
                    }

                    this.$emit('error', file, response.messages);
                    return;
                }

                let uf = this.findUploadingFile(file);

                this.removeUploadingFile(file);

                this.$emit('input', response);
                this.$emit('uploaded', uf.file);
            },
            onError(file, message) {
                message = this.translate(message,
                    {'size': this.$formatBytes(file.size), 'limit': this.$formatBytes(this.maxFileSize * 1024 * 1024)});
                let uf = this.findUploadingFile(file);
                if (uf) {
                    uf.errors.push(message);
                }

                this.$emit('error', file, message);
            },
            onUploadProgress(file, percent, bytesSent) {
                percent = Math.floor((bytesSent / file.size) * 100);
                let uf = this.findUploadingFile(file);
                if (!uf) {
                    return;
                }

                if ((percent - uf.progress.percent) < 5) {
                    return;
                }

                uf.progress.percent = percent;
                uf.progress.bytes = bytesSent;
            },
            findUploadingFile(file) {
                return this.uploadingFiles.find((uf) => uf.file.upload.uuid === file.upload.uuid);
            },
            removeUploadingFile(file) {
                let index = this.uploadingFiles.findIndex((uf) => uf.file.upload.uuid === file.upload.uuid);
                this.$delete(this.uploadingFiles, index);
            },
        },
        computed: {
            ...mapGetters([
                'locale',
            ]),
            options() {
                return Object.assign({}, this.dropzoneOptions, {
                    dictDefaultMessage: this.translate(this.label),
                });
            },
        },
        data() {
            return {
                dropzoneOptions: {
                    method: 'post',
                    url: this.url,
                    maxFilesize: Math.floor(this.maxFileSize),
                    clickable: true,
                    acceptedFiles: this.acceptedFiles,
                    dictDefaultMessage: this.translate(this.label),
                    dictFileTooBig: 'message.file_too_large',
                    dictInvalidFileType: '',
                    createImageThumbnails: false,
                    ignoreHiddenFiles: true,
                    autoProcessQueue: true,
                    autoQueue: true,
                    addRemoveLinks: false,
                    addedfile: () => {},
                    paramName: this.name,
                    headers: httpConfig.headers,
                    parallelUploads: this.parallelUploads,
                    chunking: this.chunking,
                    chunkSize: this.chunkSize,
                    retryChunks: this.retryChunks,
                    forceChunking: this.chunking,
                },
                uploadingFiles: [],
            };
        },
        watch: {
            locale(value) {
                if (document.querySelectorAll('.dz-message span').length) {
                    document.querySelectorAll('.dz-message span').forEach( item => {
                        item.innerHTML = this.translate(this.label);
                    });
                }

                if (document.querySelectorAll('.document .dz-message span').length) {
                    document.querySelectorAll('.document .dz-message span').forEach( item => {
                        item.innerHTML = this.translate('button.add_document');
                    });
                }
            },
        },
    };
</script>

<style lang="scss">
    .file-field {
        .vue-dropzone {
            .dz-default {
                &.dz-message {
                    text-align: center;

                    span {
                        cursor: pointer;
                        background: transparent;
                        border: 1px solid #646EA0;
                        height: 30px;
                        line-height: 30px;
                        padding: 0 14px;
                        font-size: 10px;
                        text-transform: uppercase;
                        text-align: center;
                        border-radius: 30px;
                        background-clip: padding-box;
                        display: inline-block;
                        width: 200px;
                        letter-spacing: 1.2px;
                        font-weight: 500;
                        transition: all 0.2s ease-in;
                    }
                }
            }
        }
    }
</style>
