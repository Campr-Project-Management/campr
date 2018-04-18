$(function () {
    'use strict';
    var $menuChild = $('.sidebar-main-menu > li');

    $.extend($.expr[":"], {
        "icontains": function (elem, i, match) {
            if (typeof i === 'function') { // this fools jslint
                i();
            }

            return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
        }
    });

    $('.sidebar-search').keyup(function () {
        var searchedString = $(this).val();

        $menuChild.find('a').addClass('no-transition');
        $menuChild.show();
        $menuChild.find('ul').hide();
        $menuChild.removeClass('toggled');
        $('.main-category > ul > li > a').css('color', '#989898');

        $menuChild.find('a').remove('no-transition');

        if (searchedString) {
            $menuChild.hide();
            $menuChild.find(':icontains("' + searchedString + '")').parents('.main-category').show();
            $menuChild.find(':icontains("' + searchedString + '")').parents('.main-category').addClass('toggled');
            $menuChild.find(':icontains("' + searchedString + '")').parents('.main-category').find('ul').show();
            $menuChild.find(':icontains("' + searchedString + '")').css('color', '#262626');

        } else {
            $menuChild.find('a').css('color', '#8794c4');
        }
        $menuChild.find('a').removeClass('no-transition');
    });
});
