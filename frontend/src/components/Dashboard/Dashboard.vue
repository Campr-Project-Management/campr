<template>
    <div>
        <recent-projects></recent-projects>
        <recent-tasks></recent-tasks>
    </div>
</template>

<script>
import RecentProjects from './RecentProjects';
import RecentTasks from './RecentTasks';

export default {
    components: {
        RecentProjects,
        RecentTasks,
    },
    created() {
//        $(document).ready(function() {
//            const progressBar = $('.filled');
//            let speed = 1000;
//
//            progressBar.each(function(i, elem) {
//                const $this = $(elem);
//                const $percentageNumber = $this.parents('.box').find('.number');
//
//                $this.animate({'width': $this.data('number') + '%'}, speed);
//
//                $({Counter: 0})
//                .animate({Counter: $this.data('number')}, {
//                    duration: speed,
//                    step: function() {
//                        $percentageNumber.text(Math.ceil(this.Counter));
//                    },
//                });
//            });
//        });
    },
};
</script>
