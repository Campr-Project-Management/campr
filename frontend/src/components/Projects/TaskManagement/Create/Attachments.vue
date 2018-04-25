<template>
    <div :class="{disabled: disabled}">
        <h3>{{ translate('message.attachments') }}</h3>
        <input
                id="attachments"
                type="file"
                name="attachments"
                style="display: none;"
                @change="onChange"/>
        <div class="attachments">
            <div
                    v-for="(media, index) in inputValue"
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
                        v-if="!disabled"></i>
            </div>
        </div>
        <div class="text-center">
            <a
                    class="btn-rounded btn-empty btn-md"
                    @click="onAdd">{{ translate('button.add_attachment') }}</a>
        </div>
    </div>
</template>

<script>
    import ViewIcon from '../../../_common/_icons/ViewIcon';
    import Vue from 'vue';

    export default {
        name: 'task-attachments',
        props: {
            value: {
                'type': Array,
                'default': () => [],
            },
            disabled: {
                type: Boolean,
                required: false,
                default: false,
            },
        },
        components: {
            ViewIcon,
        },
        methods: {
            onAdd() {
                if (this.disabled) {
                    return;
                }

                document.getElementById('attachments').click();
            },
            onChange(e) {
                if (this.disabled) {
                    return;
                }

                let files = e.target.files || e.dataTransfer.files;
                if (!files.length) {
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

                console.info(inputValue);
                this.$emit('input', inputValue);
            },
            getMediaFile(media) {
                if (!media.id) {
                    return;
                }

                const url = Routing.generate('app_api_media_download', {id: media.id});
                Vue.http.get(url, {responseType: 'blob'}).then((response) => {
                    if (response.status !== 200) {
                        return;
                    }

                    let options = {};
                    if (response.headers && response.headers.map && response.headers.map['content-type']) {
                        options.type = response.headers.map['content-type'][0];
                    }

                    let blob = new Blob([response.body], options);
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = media.originalName;
                    link.click();
                });
            },
        },
        watch: {
            value(value) {
                this.inputValue = [...value];
            },
        },
        data() {
            return {
                inputValue: [],
            };
        },
    };
</script>

<style scoped lang="scss">
    @import '../../../../css/_variables';
    @import '../../../../css/_mixins';
    @import '../../../../css/_common';

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
</style>
