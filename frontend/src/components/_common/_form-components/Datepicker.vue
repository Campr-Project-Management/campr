<script>
    import datepicker from 'vuejs-datepicker';
    export default {
        extends: datepicker,
        props: ['placeholder'],
        methods: {
            isOpen() {
                console.log('open datepicker');
            },
        },
        mounted() {
            console.log('datepicker mounted');
        },
    };
</script>