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
    .error {
        border: 1px solid #000000;
        background: #ff463f;
        color: #000000;
        border-radius: 5px;
        padding: 5px;
        margin: 10px;
    }
</style>
