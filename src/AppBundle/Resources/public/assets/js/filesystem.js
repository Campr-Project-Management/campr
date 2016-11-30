$(document).ready(function () {
    $("body").on("change", '#create_driver', function () {
        var $form = $(this).closest('form');
        $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize(),
            success: function (html) {
                $('#ajaxForm').html(html);
                $('select.driver-create').selectpicker();
            }
        });
    });
});
