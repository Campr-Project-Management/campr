<template>
    <div>
        <h3>{{ message.attachments }}</h3>
        <input
            id="attachments"
            type="file"
            name="attachments"
            style="display: none;"
            v-on:change="updateAttachments" />
        <div class="attachments">
            <div v-for="attachment in attachments" class="attachment">
                <view-icon />
                <span class="attachment-name">{{ attachment.name }}</span>
                <i class="fa fa-times" v-on:click="deleteAttachment(attachment);"></i>
            </div>
        </div>
        <div class="text-center">
            <a class="btn-rounded btn-empty btn-md" v-on:click="openFileSelection">{{ button.add_attachment }}</a>
        </div>
    </div>
</template>

<script>
import ViewIcon from '../../../_common/_icons/ViewIcon';

export default {
    components: {
        ViewIcon,
    },
    methods: {
        openFileSelection: function() {
            document.getElementById('attachments').click();
        },
        updateAttachments: function(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length) {
                return;
            }
            this.attachments.push(files[0]);
        },
        deleteAttachment: function(value) {
            this.attachments = this.attachments.filter(attachment => attachment.name !== value.name);
        },
    },
    watch: {
        attachments: function(value) {
            this.$emit('input', value);
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
            attachments: [],
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
    }
</style>
