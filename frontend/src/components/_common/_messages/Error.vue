<template>
    <div>
        <template v-if="message">
            <div class="error">{{ message }}</div>
        </template>
        <template v-else-if="atPath">
            <error
                    v-for="message in messages"
                    :message="message"/>
        </template>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: 'error',
        props: {
            message: {
                type: String,
                required: false,
            },
            atPath: {
                type: String,
                required: false,
            },
        },
        computed: {
            ...mapGetters([
                'validationMessagesFor',
                'validationMessages',
            ]),
            messages() {
                if (!this.atPath) {
                    return [];
                }

                return this.validationMessagesFor(this.atPath);
            },
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
