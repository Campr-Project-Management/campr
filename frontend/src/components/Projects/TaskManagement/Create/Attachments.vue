<template>
    <div>
        <h3>{{ message.attachments }}</h3>
        <input
            id="attachments"
            type="file"
            name="attachments"
            style="display: none;"
            v-on:change="updateAttachments" />
        <div class="flex flex-center">
            <a class="btn-rounded btn-empty btn-md" v-on:click="openFileSelection">{{ button.add_attachment }}</a>
        </div>
        <div class="flex flex-row">
            <div v-for="attachment in attachments" class="attachment">
                <span v-on:click="deleteAttachment(attachment);"><delete-icon /></span>
                <upload-placeholder />
                <p class="attachment-name">{{ attachment.name }}</p>
            </div>
        </div>
    </div>
</template>

<script>
import UploadPlaceholder from '../../../_common/_form-components/UploadPlaceholder';
import DeleteIcon from '../../../_common/_icons/DeleteIcon';

export default {
    components: {
        UploadPlaceholder,
        DeleteIcon,
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
            this.$emit('input', attachments);
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

<style type="scss">
.avatar {
    margin: 20px;
}

.attachment {
    width: 205px;
}

.attachment-name {
    word-wrap: break-word;
    width: 80%;
    margin: 10%;
}
.delete-icon {
    width: 20px;
    height: 20px;
    float: right;
    margin-top: 5px;
}
</style>
