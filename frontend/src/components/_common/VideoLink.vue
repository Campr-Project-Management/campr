<template>
    <a target="_blank" :href="translate(`video.${module}.link`)" class="button btn-rounded second-bg btn-video-link" :class="{'absolute-top-right': absoluteTopRight}">
        {{ translate('video.open.label') }}
    </a>
</template>

<script>
export default {
    props: {
        module: {
            type: String,
        },
        absoluteTopRight: {
            type: Boolean,
            default: false,
        },
    },
};
</script>
