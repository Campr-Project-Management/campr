$(window).load(function(){

    /*-------------------------------------------
        Welcome Message
     ---------------------------------------------*/
    function notify(message, type){
        $.growl({
            message: message
        },{
            type: type,
            allow_dismiss: false,
            label: 'Cancel',
            className: 'btn-xs btn-inverse',
            placement: {
                from: 'bottom',
                align: 'left'
            },
            delay: 2500,
            animate: {
                enter: 'animated fadeInUp',
                exit: 'animated fadeOutDown'
            },
            offset: {
                x: 30,
                y: 30
            }
        });
    };

    setTimeout(function () {
        if (!$('.login-content')[0]) {
            notify('Welcome back Mallinda Hollaway', 'inverse');
        }
    }, 1000)
});