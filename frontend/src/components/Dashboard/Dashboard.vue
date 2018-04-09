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
};
</script>
