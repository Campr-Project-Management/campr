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
        input{
            color: #8794C4;
            text-transform: uppercase;
            font-size: 11px;
            text-align: left;
            &::-webkit-input-placeholder { /* WebKit, Blink, Edge */
                color:    #8794C4;
            }
            &::-moz-placeholder { /* Mozilla Firefox 4 to 18 */
                color:    #8794C4;
                opacity:  1;
            }
            &::-moz-placeholder { /* Mozilla Firefox 19+ */
                color:    #8794C4;
                opacity:  1;
            }
            &::-ms-input-placeholder { /* Internet Explorer 10-11 */
                color:    #8794C4;
            }
            &::-ms-input-placeholder { /* Microsoft Edge */
                color:    #8794C4;
            }
        }
        .calendar{
            top: 40px;
            &.bottom-up{
                top: -280px;
             }
        }
    }
</style>