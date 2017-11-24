<template>
    <div class="error">
        {{ message }}
    </div>
</template>

<script>
export default {
    props: {
        message: {
            type: String,
        },
        // @TODO: add hide button and autohide functionality
//        hideButton: {
//            type: Boolean,
//            default() {
//                return false;
//            },
//        },
//        autoHide: {
//            type: Number,
//            default() {
//                return 0;
//            },
//        },
    },
};
</script>

<style scoped lang="scss">
    @import '../../../css/_variables';
    .error {
        background: $dangerColor;
        color: $blackColor;
        border-radius: 2px;
        padding: 10px 15px;
        margin: 10px 0 0 0;
    }
</style>
