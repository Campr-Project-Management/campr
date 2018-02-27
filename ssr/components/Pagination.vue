<template>
    <div class="flex flex-direction-reverse flex-v-center">
        <div class="pagination" v-if="numberOfPages > 1">
            <span
                v-for="page in pagesToShow()"
                :class="{active: (page == currentPage)}"
                v-on:click="page && $emit('change-page', page)">
                {{ page ? page : '...' }}
            </span>
        </div>
        <div>
            <span class="pagination-info">{{ translateText('message.displaying') }} {{ currentPage }} {{ translateText('message.results_out_of') }} {{ numberOfPages }}</span>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        numberOfPages: {
            type: Number,
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
    },
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
        pagesToShow() {
            const out = [];
            const half = Math.floor(this.showPages / 2);
            if (this.showPages < this.numberOfPages) {
                if (this.currentPage === 1) {
                    for (let c = 1; c <= this.showPages; c++) {
                        out.push(c);
                    }
                } else if (this.currentPage === this.numberOfPages) {
                    for (let c = this.currentPage; c >= (this.currentPage - this.showPages + 1); c--) {
                        out.unshift(c);
                    }
                } else {
                    let start = this.currentPage - half;
                    let end = this.currentPage + half;

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
    },
    created() {
        //
    },
    data() {
        return {
            start: 1,
            end: 8,
        };
    },
};
</script>
