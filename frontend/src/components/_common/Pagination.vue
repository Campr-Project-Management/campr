<template>
    <div class="flex flex-direction-reverse flex-v-center" v-if="showDescription || numberOfPages > 1">
        <div class="pagination" v-if="numberOfPages > 1">
            <span
                    v-for="page in pagesToShow()"
                    :key="page"
                    :class="{active: isActive(page)}"
                    @click="onChange(page)">
                {{ page ? page : '...' }}
            </span>
        </div>
        <div v-if="showDescription">
            <span class="pagination-info">
                {{ translate('message.displaying') }}
                {{ currPage }}
                {{ translate('message.results_out_of') }}
                {{ numberOfPages || 1 }}
            </span>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        value: {
            type: Number,
            required: false,
            default: 1,
        },
        numberOfPages: {
            type: Number,
            default: 1,
        },
        currentPage: {
            type: Number,
        },
        showPages: {
            type: Number,
            default() {
                return 5;
            },
        },
        showDescription: {
            type: Boolean,
            required: false,
            default: true,
        },
    },
    computed: {
        currPage() {
            return Number(this.value || this.currentPage);
        },
    },
    methods: {
        isActive(page) {
            return this.value === Number(page);
        },
        pagesToShow() {
            const out = [];
            const half = Math.floor(this.showPages / 2);
            if (this.showPages < this.numberOfPages) {
                if (this.currPage === 1) {
                    for (let c = 1; c <= this.showPages; c++) {
                        out.push(c);
                    }
                } else if (this.currPage === this.numberOfPages) {
                    for (let c = this.currPage; c >= (this.currPage - this.showPages + 1); c--) {
                        out.unshift(c);
                    }
                } else {
                    let start = this.currPage - half;
                    let end = this.currPage + half;

                    if (end < this.showPages) {
                        start = 1;
                        end = this.showPages;
                    }

                    if (end > this.numberOfPages) {
                        start = this.numberOfPages - this.showPages + 1;
                        end = this.numberOfPages;
                    }

                    for (let c = start; c <= end; c++) {
                        out.push(c);
                    }
                }
            } else {
                for (let c = 1; c < this.numberOfPages; c++) {
                    out.push(c);
                }
            }

            if (this.numberOfPages && out.indexOf(1) === -1) {
                if (out[0] > half) {
                    out.unshift(null);
                }
                out.unshift(1);
            }

            if (this.numberOfPages && out.indexOf(this.numberOfPages) === -1) {
                if (this.numberOfPages - out[out.length - 1] >= half) {
                    out.push(null);
                }
                out.push(this.numberOfPages);
            }

            return out;
        },
        onChange(page) {
            page = Number(page);
            if (page === this.currPage) {
                return;
            }

            this.$emit('change-page', page);
            this.$emit('input', page);
        },
    },
    data() {
        return {
            start: 1,
            end: 8,
        };
    },
};
</script>
