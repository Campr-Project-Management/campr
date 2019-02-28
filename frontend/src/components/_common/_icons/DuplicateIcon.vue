<template>
    <a
            :title="translate('message.duplicate')"
            class="duplicate-icon"
            @click="onClick"><i class="glyphicon glyphicon-duplicate"></i></a>
</template>

<script>
    export default {
        name: 'duplicate-icon',
        methods: {
            onClick(event) {
                this.$emit('click', event);
            },
        },
    };
</script>

<style scoped lang="scss">
    @import '~theme/variables';

    .duplicate-icon {
        font-size: 1.2em;
        color: $fadeColor;
        cursor: pointer;

        &:hover {
            color: $secondColor;
        }
    }
</style>
