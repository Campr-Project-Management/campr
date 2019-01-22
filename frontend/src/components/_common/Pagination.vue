<template>
    <div class="flex flex-direction-reverse flex-v-center" v-if="showDescription || numberOfPages > 1">
        <div class="pagination" v-if="numberOfPages > 1">
            <span
                    v-for="page in pagesToShow()"
                    :key="page"
                    :class="{active: (page === value)}"
                    @click="onChange(page)">
                {{ page ? page : '...' }}
            </span>
        </div>
        <div v-if="showDescription">
            <span class="pagination-info">
                {{ translate('message.displaying') }}
                {{ value }}
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
    methods: {
        pagesToShow() {
            const out = [];
            const half = Math.floor(this.showPages / 2);
            if (this.showPages < this.numberOfPages) {
                if (this.value === 1) {
                    for (let c = 1; c <= this.showPages; c++) {
                        out.push(c);
                    }
                } else if (this.value === this.numberOfPages) {
                    for (let c = this.value; c >= (this.value - this.showPages + 1); c--) {
                        out.unshift(c);
                    }
                } else {
                    let start = this.value - half;
                    let end = this.value + half;

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
            if (page === this.value) {
                return;
            }

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
