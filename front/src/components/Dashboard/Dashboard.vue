<template>
    <div>
        <navigation></navigation>
        <recent-projects></recent-projects>
        <recent-tasks></recent-tasks>
    </div>
</template>

<script>
import Navigation from '../Navigation';
import RecentProjects from './RecentProjects';
import RecentTasks from './RecentTasks';

export default {
    components: {
        Navigation,
        RecentProjects,
        RecentTasks,
    },
    created() {
        window.$(document).ready(function () {
            const progressBar = window.$('.filled');
            let speed = 1000;

            progressBar.each(function (i, elem) {
                const $this = window.$(elem);
                const $percentageNumber = $this.parents('.box').find('.number');

                $this.animate({'width': $this.data('number') + '%'}, speed);

                window.$({Counter: 0})
                .animate({Counter: $this.data('number')}, {
                    duration: speed,
                    step: function () {
                        $percentageNumber.text(Math.ceil(this.Counter));
                    },
                });
            });
        });
    },
    data() {
        return {
            title: 'CAMPR',
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
</style>
