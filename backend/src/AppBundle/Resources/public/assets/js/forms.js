$(document).ready(function () {
    // Workpackage create/edit form update labels field on project change
    $("body").on("change", '#create_project', function () {
        ajaxSubmit(this);
    });

    // Filesystem create/edit form update configs based on driver
    $("body").on("change", '#create_driver', function () {
        ajaxSubmit(this);
    });
});


function ajaxSubmit(focus) {
    var form = $(focus).closest('form');
    $.ajax({
        type: form.attr('method'),
        url: form.attr('action'),
        data: form.serialize(),
        success: function (html) {
            $('#ajaxForm').html(html);
            $('select').selectpicker();
        }
    });
}
