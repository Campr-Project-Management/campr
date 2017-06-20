<template>
    <div>
        <h3>{{ translateText('message.documents') }}</h3>
        <input
            id="attachments"
            type="file"
            name="attachments"
            style="display: none;"
            @change="updateMedias" />
        <div class="attachments" v-if="medias.length > 0">
            <div v-for="(media, index) in medias" class="attachment">
                <view-icon />
                <span class="attachment-name">{{ media.name || media.path }}</span>
                <i class="fa fa-times" @click="deleteMedia(index);"></i>
            </div>
        </div>
        <div class="text-center">
            <a class="btn-rounded btn-empty btn-md" @click="openFileSelection">{{ translateText('button.add_attachment') }}</a>
        </div>
    </div>
</template>

<script>
import ViewIcon from '../../_common/_icons/ViewIcon';

export default {
    props: {
        editMedias: {
            'type': Array,
            'default': function() {
                return [];
            },
        },
    },
    components: {
        ViewIcon,
    },
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
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
            medias: [],
        };
    },
};
</script>

<style lang="scss">
    @import '../../../css/_variables';
    @import '../../../css/_mixins';
    
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
    }
</style>
