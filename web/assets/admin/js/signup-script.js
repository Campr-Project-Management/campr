(function($) {
    "use strict"; // Start of use strict 

    var $i; 

    for ($i = 1; $i < 4; $i ++) {
        var $holder = $("#slider_holder_" + $i),
            $range = $("#slider_" + $i);

        var track = function(data) {
            if ($("#" + $(data.input).attr('data-from')).length){
                if (data.from_value !== null) {
                    $("#" + $(data.input).attr('data-from')).text(data.from_value);
                } else {
                    $("#" + $(data.input).attr('data-from')).text(data.from.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }
            }

            if ($("#" + $(data.input).attr('data-to')).length){
                $("#" + $(data.input).attr('data-to')).text(data.to.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            }
        }

        function minValue() {
            var $min;

            if ($holder.attr("data-min")){
                $min = $holder.attr("data-min");
            } else {
                $min = 0;
            }

            return $min;
        }

        function maxValue() {
            var $max;
            
            if (!($holder.attr("data-max") == "")){
                $max = $holder.attr("data-max");
            } else {
                $max = 1;
            }

            return $max;
        }

        function stepValue() {
            var $step;
            
            if (!($holder.attr("data-step") == "")){
                $step = $holder.attr("data-step");
            } else {
                $step = 1;
            }

            return $step;
        }

        function typeValue() {
            var $type;
            
            if (!($holder.attr("data-type") == "")){
                $type = $holder.attr("data-type");
            } else {
                $type = "single";
            }

            return $type;
        }

        function valuesValue() {
            var $values;
            
            if ($holder.is('[data-values]')){
                var $data = $holder.attr("data-values");
                $values = $data.split(',');
            } else {
                $values = [];
            }

            return $values;
        }

        $range.ionRangeSlider({
            type: typeValue(),
            min: minValue(),
            max: maxValue(),
            step: stepValue(),
            values: valuesValue(),
            onStart: track,
            onChange: track,
            onFinish: track,
            onUpdate: track
        });
    }

    function DoOptionSliders() {
        var $j; 

        for ($j = 1; $j < 7; $j ++) {
            var $holder = $("#options_slider_holder_" + $j),
                $range = $("#options_slider_" + $j);

            var track = function(data) {
                if ($("#" + $(data.input).attr('data-from')).length){
                    if (data.from_value !== null) {
                        $("#" + $(data.input).attr('data-from')).text(data.from_value);
                    } else {
                        $("#" + $(data.input).attr('data-from')).text(data.from.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    }
                }

                if ($("#" + $(data.input).attr('data-to')).length){
                    $("#" + $(data.input).attr('data-to')).text(data.to.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }
            }

            function minValue() {
                var $min;

                if (!($holder.attr("data-min") == "")){
                    $min = $holder.attr("data-min");
                } else {
                    $min = 0;
                }

                return $min;
            }

            function maxValue() {
                var $max;
                
                if (!($holder.attr("data-max") == "")){
                    $max = $holder.attr("data-max");
                } else {
                    $max = 1;
                }

                return $max;
            }

            function stepValue() {
                var $step;
                
                if (!($holder.attr("data-step") == "")){
                    $step = $holder.attr("data-step");
                } else {
                    $step = 1;
                }

                return $step;
            }

            function typeValue() {
                var $type;
                
                if (!($holder.attr("data-type") == "")){
                    $type = $holder.attr("data-type");
                } else {
                    $type = "single";
                }

                return $type;
            }

            function valuesValue() {
                var $values;
                
                if ($holder.is('[data-values]')){
                    var $data = $holder.attr("data-values");
                    $values = $data.split(',');
                } else {
                    $values = [];
                }

                return $values;
            }

            $range.ionRangeSlider({
                type: typeValue(),
                min: minValue(),
                max: maxValue(),
                step: stepValue(),
                values: valuesValue(),
                onStart: track,
                onChange: track,
                onFinish: track,
                onUpdate: track
            });
        }
    }

    $('#self-hosted-campr-button').on('shown.bs.tab', function (e) {
        DoOptionSliders();
    })

    if (!String.prototype.trim) {
        (function() {
            // Make sure we trim BOM and NBSP
            var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
            String.prototype.trim = function() {
                return this.replace(rtrim, '');
            };
        })();
    }

    [].slice.call( document.querySelectorAll( 'input.input__field' ) ).forEach( function( inputEl ) {
        // in case the input is already filled..
        if( inputEl.value.trim() !== '' ) {
            classie.add( inputEl.parentNode, 'input--filled' );
        }

        // events:
        inputEl.addEventListener( 'focus', onInputFocus );
        inputEl.addEventListener( 'blur', onInputBlur );
    } );

    function onInputFocus( ev ) {
        classie.add( ev.target.parentNode, 'input--filled' );
    }

    function onInputBlur( ev ) {
        if( ev.target.value.trim() === '' ) {
            classie.remove( ev.target.parentNode, 'input--filled' );
        }
    }
        
    $("#input-51").select2({
        placeholder: "Title",
        allowClear: true,            
        dropdownCssClass: "select2-custom-dropdown",
        minimumResultsForSearch: -1
    }); 

})(jQuery); // End of use strict