<script>
    import datepicker from 'vuejs-datepicker';
    export default {
        extends: datepicker,
        props: ['placeholder'],
        methods: {
            isOpen() {
                let scrollTop = $(window).scrollTop();
                let elementOffset = $(this.$el).offset().top;
                let currentElementOffset = (elementOffset - scrollTop);

                let windowInnerHeight = window.innerHeight;

                if (windowInnerHeight - currentElementOffset < 290) {
                    $(this.$el).find('.calendar').addClass('bottom-up');
                }else{
                    $(this.$el).find('.calendar').removeClass('bottom-up');
                }
            },
        },
    };
</script>

<style scoped lang="scss">
    .datepicker{
        .calendar{
            top: 40px;
            &.bottom-up{
                top: -280px;
             }
        }
    }
</style>