$(function () {
    var $menuChild = $('.sidebar-main-menu > li'),
        searchedString;

    $.extend($.expr[":"], {
        "icontains": function(elem, i, match, array) {
            return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
        }
    });

    $('.sidebar-search').keyup(function () {
        searchedString = $(this).val();

        $menuChild.css('display', 'none');
        $menuChild.find(':icontains("' + searchedString + '")').parents('.main-category').css('display', 'block');
    });
});
