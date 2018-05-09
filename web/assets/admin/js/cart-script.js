function inputIncrement(){
    $('.input-with-increment').each(function(){
        var $plus = $(this).find('.increment-plus'),
            $minus = $(this).find('.increment-minus'),
            $input = $(this).find('input[type="text"]');

        console.log(parseInt($input.val()));

        if (parseInt($input.val()) == $input.data("min")) {
            $minus.addClass('disabled');
        }

        if (parseInt($input.val()) == $input.data("max")) {
            $plus.addClass('disabled');
        }

        // This button will increment the value
        $plus.on('click', function(e){
            // Stop acting like a button
            e.preventDefault();
            // Get the field name
            fieldName = $(this).attr('field');
            // Get its current value
            var currentVal = parseInt($('input[name='+fieldName+']').val());
            // If is not undefined
            if (!isNaN(currentVal)) {
                // Increment
                $('input[name='+fieldName+']').val(currentVal + 1);
            } else {
                // Otherwise put a 0 there
                $('input[name='+fieldName+']').val(0);
            }
            var plusVal = currentVal + 1;
            console.log(plusVal);
            if (plusVal >= $('input[name='+fieldName+']').data('min') && $minus.hasClass('disabled')) {
                $minus.removeClass('disabled');
            }
            if (plusVal == $('input[name='+fieldName+']').data('max')) {
                $plus.addClass('disabled');
            }
        });

        // This button will decrement the value till 0
        $minus.on('click', function(e) {
            // Stop acting like a button
            e.preventDefault();
            // Get the field name
            fieldName = $(this).attr('field');
            // Get its current value
            var currentVal = parseInt($('input[name='+fieldName+']').val());
            // If it isn't undefined or its greater than 0
            if (!isNaN(currentVal) && currentVal > 0) {
                // Decrement one
                $('input[name='+fieldName+']').val(currentVal - 1);
            } else {
                // Otherwise put a 0 there
                $('input[name='+fieldName+']').val(0);
            }  
            var minusVal = currentVal - 1; 
            console.log(minusVal);         
            if (minusVal <= $('input[name='+fieldName+']').data('max') && $plus.hasClass('disabled')) {
                $plus.removeClass('disabled');
            }
            if (minusVal == $('input[name='+fieldName+']').data('min')) {
                $minus.addClass('disabled');
            }
        });
    })
}

$(document).ready(function(){
    inputIncrement();
});