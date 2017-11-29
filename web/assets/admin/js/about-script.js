/*///////////////////////////////////
Classie
///////////////////////////////////*/
/*!
 * classie - class helper functions
 * from bonzo https://github.com/ded/bonzo
 * 
 * classie.has( elem, 'my-class' ) -> true/false
 * classie.add( elem, 'my-new-class' )
 * classie.remove( elem, 'my-unwanted-class' )
 * classie.toggle( elem, 'my-class' )
 */

/*jshint browser: true, strict: true, undef: true */
/*global define: false */

( function( window ) {

'use strict';

// class helper functions from bonzo https://github.com/ded/bonzo

function classReg( className ) {
  return new RegExp("(^|\\s+)" + className + "(\\s+|$)");
}

// classList support for class management
// altho to be fair, the api sucks because it won't accept multiple classes at once
var hasClass, addClass, removeClass;

if ( 'classList' in document.documentElement ) {
  hasClass = function( elem, c ) {
    return elem.classList.contains( c );
  };
  addClass = function( elem, c ) {
    elem.classList.add( c );
  };
  removeClass = function( elem, c ) {
    elem.classList.remove( c );
  };
}
else {
  hasClass = function( elem, c ) {
    return classReg( c ).test( elem.className );
  };
  addClass = function( elem, c ) {
    if ( !hasClass( elem, c ) ) {
      elem.className = elem.className + ' ' + c;
    }
  };
  removeClass = function( elem, c ) {
    elem.className = elem.className.replace( classReg( c ), ' ' );
  };
}

function toggleClass( elem, c ) {
  var fn = hasClass( elem, c ) ? removeClass : addClass;
  fn( elem, c );
}

var classie = {
  // full names
  hasClass: hasClass,
  addClass: addClass,
  removeClass: removeClass,
  toggleClass: toggleClass,
  // short names
  has: hasClass,
  add: addClass,
  remove: removeClass,
  toggle: toggleClass
};

// transport
if ( typeof define === 'function' && define.amd ) {
  // AMD
  define( classie );
} else {
  // browser global
  window.classie = classie;
}

})( window );


/*///////////////////////////////////
Count Up
///////////////////////////////////*/
!function(a,n){"function"==typeof define&&define.amd?define(n):"object"==typeof exports?module.exports=n(require,exports,module):a.CountUp=n()}(this,function(a,n,t){var e=function(a,n,t,e,i,r){function o(a){a=a.toFixed(l.decimals),a+="";var n,t,e,i,r,o;if(n=a.split("."),t=n[0],e=n.length>1?l.options.decimal+n[1]:"",l.options.useGrouping){for(i="",r=0,o=t.length;r<o;++r)0!==r&&r%3===0&&(i=l.options.separator+i),i=t[o-r-1]+i;t=i}return l.options.numerals.length&&(t=t.replace(/[0-9]/g,function(a){return l.options.numerals[+a]}),e=e.replace(/[0-9]/g,function(a){return l.options.numerals[+a]})),l.options.prefix+t+e+l.options.suffix}function u(a,n,t,e){return t*(-Math.pow(2,-10*a/e)+1)*1024/1023+n}function s(a){return"number"==typeof a&&!isNaN(a)}var l=this;if(l.version=function(){return"1.9.2"},l.options={useEasing:!0,useGrouping:!0,separator:",",decimal:".",easingFn:u,formattingFn:o,prefix:"",suffix:"",numerals:[]},r&&"object"==typeof r)for(var m in l.options)r.hasOwnProperty(m)&&null!==r[m]&&(l.options[m]=r[m]);""===l.options.separator?l.options.useGrouping=!1:l.options.separator=""+l.options.separator;for(var d=0,c=["webkit","moz","ms","o"],f=0;f<c.length&&!window.requestAnimationFrame;++f)window.requestAnimationFrame=window[c[f]+"RequestAnimationFrame"],window.cancelAnimationFrame=window[c[f]+"CancelAnimationFrame"]||window[c[f]+"CancelRequestAnimationFrame"];window.requestAnimationFrame||(window.requestAnimationFrame=function(a,n){var t=(new Date).getTime(),e=Math.max(0,16-(t-d)),i=window.setTimeout(function(){a(t+e)},e);return d=t+e,i}),window.cancelAnimationFrame||(window.cancelAnimationFrame=function(a){clearTimeout(a)}),l.initialize=function(){return!!l.initialized||(l.error="",l.d="string"==typeof a?document.getElementById(a):a,l.d?(l.startVal=Number(n),l.endVal=Number(t),s(l.startVal)&&s(l.endVal)?(l.decimals=Math.max(0,e||0),l.dec=Math.pow(10,l.decimals),l.duration=1e3*Number(i)||2e3,l.countDown=l.startVal>l.endVal,l.frameVal=l.startVal,l.initialized=!0,!0):(l.error="[CountUp] startVal ("+n+") or endVal ("+t+") is not a number",!1)):(l.error="[CountUp] target is null or undefined",!1))},l.printValue=function(a){var n=l.options.formattingFn(a);"INPUT"===l.d.tagName?this.d.value=n:"text"===l.d.tagName||"tspan"===l.d.tagName?this.d.textContent=n:this.d.innerHTML=n},l.count=function(a){l.startTime||(l.startTime=a),l.timestamp=a;var n=a-l.startTime;l.remaining=l.duration-n,l.options.useEasing?l.countDown?l.frameVal=l.startVal-l.options.easingFn(n,0,l.startVal-l.endVal,l.duration):l.frameVal=l.options.easingFn(n,l.startVal,l.endVal-l.startVal,l.duration):l.countDown?l.frameVal=l.startVal-(l.startVal-l.endVal)*(n/l.duration):l.frameVal=l.startVal+(l.endVal-l.startVal)*(n/l.duration),l.countDown?l.frameVal=l.frameVal<l.endVal?l.endVal:l.frameVal:l.frameVal=l.frameVal>l.endVal?l.endVal:l.frameVal,l.frameVal=Math.round(l.frameVal*l.dec)/l.dec,l.printValue(l.frameVal),n<l.duration?l.rAF=requestAnimationFrame(l.count):l.callback&&l.callback()},l.start=function(a){l.initialize()&&(l.callback=a,l.rAF=requestAnimationFrame(l.count))},l.pauseResume=function(){l.paused?(l.paused=!1,delete l.startTime,l.duration=l.remaining,l.startVal=l.frameVal,requestAnimationFrame(l.count)):(l.paused=!0,cancelAnimationFrame(l.rAF))},l.reset=function(){l.paused=!1,delete l.startTime,l.initialized=!1,l.initialize()&&(cancelAnimationFrame(l.rAF),l.printValue(l.startVal))},l.update=function(a){if(l.initialize()){if(a=Number(a),!s(a))return void(l.error="[CountUp] update() - new endVal is not a number: "+a);l.error="",a!==l.frameVal&&(cancelAnimationFrame(l.rAF),l.paused=!1,delete l.startTime,l.startVal=l.frameVal,l.endVal=a,l.countDown=l.startVal>l.endVal,l.rAF=requestAnimationFrame(l.count))}},l.initialize()&&l.printValue(l.startVal)};return e});

/*///////////////////////////////////
Custom functions
///////////////////////////////////*/
function memberStats() {
    if ($('.custom-placeholder .lazy').hasClass('lazyloaded')) {
        
    } else {
        setTimeout(memberStats(),1000);
    }
}

(function($) {
    "use strict"; // Start of use strict

    var easingFn = function (t, b, c, d) {
        var ts = (t /= d) * t;
        var tc = ts * t;
        return b + c * (tc * ts + -5 * ts * ts + 10 * tc + -10 * ts + 5 * t);
    }

    var countOptions = {  
        useEasing: true,
        easingFn: easingFn, 
        useGrouping: true,
        separator: '.',
        decimal: '',
    };

    for ($i = 1; $i < 9; $i ++){
        $('#stats-' + $i).each(function() {
            var number = $(this).data('number');

            var stats = new CountUp('stats-' + $i, 0, number, 0, 5, countOptions);
            if (!stats.error) {
                setTimeout(stats.start(),1000);
            }
        })
    };     

    $('.same-height').matchHeight();

    $('.member-badge-same-height').matchHeight();  

    var $i;

    for ($i = 1; $i < 7; $i ++) {
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
})(jQuery); // End of use strict
