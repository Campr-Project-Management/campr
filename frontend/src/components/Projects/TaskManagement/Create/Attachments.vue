<template>
    <div>
        <h3>{{ message.attachments }}aa</h3>
        <input
            id="attachments"
            type="file"
            name="attachments"
            style="display: none;"
            v-on:change="updateMedias" />
        <div class="attachments">
            <div v-for="media, index in medias" class="attachment">
                <view-icon />
                <span class="attachment-name"><a @click="getMediaFile(media)">{{ media.name || media.path }}</a></span>
                <i class="fa fa-times" v-on:click="deleteMedia(index);"></i>
            </div>
        </div>
        <div class="text-center">
            <a class="btn-rounded btn-empty btn-md" v-on:click="openFileSelection">{{ button.add_attachment }}</a>
        </div>
    </div>
</template>

<script>
import ViewIcon from '../../../_common/_icons/ViewIcon';
import Vue from 'vue';

export default {
    props: {
        editMedias: {
            'type': Array,
            'default': [],
        },
    },
    components: {
        ViewIcon,
    },
    methods: {
        openFileSelection: function() {
            document.getElementById('attachments').click();
        },
        updateMedias: function(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length) {
                return;
            }

            let newFiles = [];
            this.medias.map((item) => {
                newFiles.push(item);
            });
            newFiles.push(files[0]);

            this.$emit('input', newFiles);
        },
        deleteMedia: function(index) {
            let newFiles = [
                ...this.medias.slice(0, index),
                ...this.medias.slice(index + 1),
            ];

            this.$emit('input', newFiles);
        },
        getMediaFile: function(media) {
            if(media.id == undefined) {
                return;
            }
            Vue.http
            .get(Routing.generate('app_api_media_download', {id: media.id})).then((response) => {
                if (response.status === 200) {
                    let blob = new Blob([response.body], {type: 'mime/type'});
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = media.fileName;
                    link.click();
                }
            }, (response) => {
            });
        },
    },
    created() {
        this.medias = this.editMedias;
    },
    watch: {
        editMedias(value) {
            this.medias = this.editMedias;
        },
    },
    data: function() {
        return {
            button: {
                add_attachment: this.translate('button.add_attachment'),
            },
            message: {
                attachments: this.translate('message.attachments'),
            },
            medias: [],
        };
    },
};
</script>

<style lang="scss">
    @import '../../../../css/_variables';
    @import '../../../../css/_mixins';

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
