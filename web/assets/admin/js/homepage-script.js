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
Landing section SVG
///////////////////////////////////*/
function svgFix(w,h,img,wimg,himg) {
    wimg = img.width(),
    himg = img.height();

    function horizontalAlign() {
        img.css({
            'height' : '100%',
            'width' : 'auto'
        });
        newwimg = img.width();
        newhimg = img.height();
        difw = w - newwimg;

        if (difw >= 0) {
            img.css({
                'left' : - (difw / 2),
                'top' : 0
            });
            $('.landing-items').css({
                'width' : newwimg,
                'left' : - (difw / 2),
                'top' : 0
            });
        } else {
            img.css({
                'left' : difw / 2,
                'top' : 0
            });
            $('.landing-items').css({
                'width' : newwimg,
                'left' : difw / 2,
                'top' : 0
            });
        }
    }

    img.css({
        'width' : '100%',
        'height' : 'auto'
    });

    newhimg = img.height();     

    difh = h - newhimg;

    if (difh > 0) {
        horizontalAlign();
    } else {    
        img.css({
            'top' : difh / 2,
            'left' : 0
        })
        $('.landing-items').css({
            'width' : '100%',
            'top' : difh / 2,
            'left' : 0
        });
    } 
}

/*///////////////////////////////////
Custom functions
///////////////////////////////////*/
function moreText(container) {
    var maxLength = 140;
    $(container).each(function(){
        var myStr = $(this).text();
        var newStr = myStr.substring(0, maxLength);
        var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
        $(this).empty().html(newStr);
        $(this).append('<span class="more-dots">...</span>');
        $(this).append('<a href="javascript:void(0);" class="more-link"> more</a>');
        $(this).append('<span class="more-text">' + removedStr + '</span>');
    });
}

function fixFlexsliderHeight() {
    // Set fixed height based on the tallest slide
    $('.flexslider').each(function(){
        var sliderHeight = 0;
        $(this).find('.slides > li').each(function(){
            slideHeight = $(this).height();
            if (sliderHeight < slideHeight) {
                sliderHeight = slideHeight;
            }
        });
        $(this).find('ul.slides').css({'height' : sliderHeight});
    });
}

function responsivePlax() {
    var w = $(window).width();
    $('.plax').plaxify();
    $.plax.disable();

    if (w > 1024) {       
        $.plax.enable();
    }
}

(function($) {
    "use strict"; // Start of use strict

    var w = $('#homepage-landing').width(),
        h = $('#homepage-landing').height(),
        img = $('#homepage-landing').find('.landing-image'),
        wimg,
        himg;

    svgFix(w,h,img,wimg,himg);

    var task1SVG = new Vivus('task-1-svg', {
        type: 'scenario-sync'
    });
    task1SVG.play();

    var task2SVG = new Vivus('task-2-svg', {
        type: 'scenario-sync'
    });
    task2SVG.play();

    var task3SVG = new Vivus('task-3-svg', {
        type: 'scenario-sync'
    });
    task3SVG.play();

    var phase1SVG = new Vivus('phase-1-svg', {
        type: 'scenario-sync'
    });
    phase1SVG.play();

    var phase2SVG = new Vivus('phase-2-svg', {
        type: 'scenario-sync'
    });
    phase2SVG.play();

    var phase3SVG = new Vivus('phase-3-svg', {
        type: 'scenario-sync'
    });
    phase3SVG.play();

    var logo = new Vivus('svg-landing-logo', {
        type: 'delayed'
    }, function (obj) {
        obj.el.classList.add('finished');
    });
    logo.play();

    responsivePlax();

    var countOptions = {  
        useEasing: true,
        useGrouping: true,
        separator: ',',
        decimal: '.',
    };

    var task1 = new CountUp('task-1-data', 0, 100, 0, 2.5, countOptions);
    task1.start();

    var task2 = new CountUp('task-2-data', 0, 82.15, 2, 2.5, countOptions);
    task2.start();

    var task3 = new CountUp('task-3-data', 0, 38.89, 2, 2.5, countOptions);
    task3.start();

    $('.same-height').matchHeight();

    var organizationIcon = new Vivus('workspace-icon', {
        type: 'delayed'
    });
    var organizationIcon = new Vivus('organization-icon', {
        type: 'delayed'
    });
    var phasesAndMilestonesIcon = new Vivus('phases-and-milestones-icon', {
        type: 'delayed'
    });
    var taskManagementIcon = new Vivus('task-management-icon', {
        type: 'delayed'
    });

    var waypoint = new Waypoint({
        element: document.getElementById('workspace-feature'),
        handler: function(direction) {  
            organizationIcon.play();
            document.getElementById('workspace-img').classList.add('in-view');
        },
        offset: '25%'
    });

    var waypoint = new Waypoint({
        element: document.getElementById('organization-module'),
        handler: function(direction) {  
            organizationIcon.play();
            document.getElementById('organization-img').classList.add('in-view');
        },
        offset: '25%'
    });

    var waypoint = new Waypoint({
        element: document.getElementById('phases-and-milestones-module'),
        handler: function(direction) {  
            phasesAndMilestonesIcon.play();
            document.getElementById('phases-and-milestones-img').classList.add('in-view');
        },
        offset: '25%'
    });

    var waypoint = new Waypoint({
        element: document.getElementById('task-management-module'),
        handler: function(direction) {  
            taskManagementIcon.play();
            document.getElementById('task-management-img').classList.add('in-view');
        },
        offset: '25%'
    });

    moreText(".module-content p:first-of-type");

    $(document).delegate('.more-link', 'click', function(){
        $("body").addClass("body-hidden");
        $(this).closest(".module").addClass("module-show");
        $(this).siblings(".more-text").contents().unwrap();
        $(this).siblings(".more-dots").remove();
        $(this).remove();
    });

    $(document).delegate('.more-title', 'click', function(){
        $("body").addClass("body-hidden");
        $(this).siblings("p").find('.more-link').click();
    });

    $(document).delegate('.close-module', 'click', function(){
        $("body").removeClass("body-hidden");
        $(this).closest(".module").removeClass("module-show");
        moreText($(this).siblings(".module-content").find("p:first-of-type"));
    });    

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

/*///////////////////////////////////
Resize
///////////////////////////////////*/
$(window).smartresize(function(){
    var w = $('#homepage-landing').width(),
        h = $('#homepage-landing').height(),
        img = $('#homepage-landing').find('.landing-image'),
        wimg = img.width(),
        himg = img.height();

    svgFix(w,h,img,wimg,himg);
    responsivePlax();
    $('.same-height').matchHeight();
});