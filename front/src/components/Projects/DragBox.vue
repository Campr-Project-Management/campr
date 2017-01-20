<template>
    <div class="box">
        <header class="flex flex-space-between" v-bind:class="{'full': !item.title}">
            <span>
                {{ index+1 }}.
                <span v-if="item.title">{{ item.title }}</span>
                <span v-if="!item.title">{{ item.content }}</span>
            </span>
            <div class="flex">
                <pencil-icon :link="{name: 'edit'}"></pencil-icon>
                <drag-icon></drag-icon>
            </div>
        </header>
        <div v-if="item.title" class="content">{{ item.content }}</div>
    </div>
</template>

<script>
import PencilIcon from '../_common/_icons/PencilIcon';
import DragIcon from '../_common/_icons/DragIcon';

export default {
    props: ['item', 'index'],
    components: {
        PencilIcon,
        DragIcon,
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/_common';
    @import '../../css/_variables';

    .box.gu-transit {
        border: 1px solid $lighterColor;
    }

    .box {
        background: $darkColor;
        padding: 20px;
        margin-bottom: 20px;

        header {
            font-weight: 300;
            text-transform: uppercase;
            height: 36px;
            border-bottom: 1px solid $mainColor;
            padding-bottom: 13px;

            &.full {
                border: none;
                height: auto;
                color: $lightColor;
                padding-bottom: 0;
            }
        }

        .content {
            padding-top: 20px;
            color: $lightColor;
        }
    }
</style>
