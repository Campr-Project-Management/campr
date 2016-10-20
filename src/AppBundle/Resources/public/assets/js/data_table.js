$(function () {
    //Command Buttons
    var grid = $('#data-table-command').bootgrid({
        ajax: true,
        columnSelection: false,
        css: {
            icon: 'zmdi icon',
            iconColumns: 'zmdi-view-module',
            iconDown: 'zmdi-sort-amount-desc',
            iconRefresh: 'zmdi-refresh',
            iconUp: 'zmdi-sort-amount-asc'
        },
        formatters: {
            'commands': function (column, row) {
                var editLink = $('#data-table-command').data('edit'),
                    showLink = $('#data-table-command').data('show');

                return '<a href=' + Routing.generate(showLink, {'id': row.id}, true) + '><button type="button" class="btn btn-icon bgm-teal waves-effect waves-circle" data-row-id="' + row.id + '"><span class="zmdi zmdi-zoom-in"></span></button></a>' +
                    '<a href=' + Routing.generate(editLink, {"id": row.id}, true) + '><button type="button" class="btn btn-icon bgm-blue command-edit waves-effect waves-circle" data-row-id="' + row.id + '"><span class="zmdi zmdi-edit"></span></button></a>' +
                    '<button type="button" class="btn btn-icon bgm-red command-delete waves-effect waves-circle" data-row-id="' + row.id + '"><span class="zmdi zmdi-delete"></span></button>';
            }
        }
    }).on('loaded.rs.jquery.bootgrid', function () {
        var deleteLink = $('#data-table-command').data('delete');

        grid.find('.command-delete').on('click', function () {
            var rowId = $(this).data('row-id');
            $.ajax({
                url: Routing.generate(deleteLink, {'id': rowId}, true),
                type: 'get',
                dataType: 'json',
                success: function (data) {
                    if (data.delete == 'success') {
                        $('#data-table-command').bootgrid('reload');
                        $('#object-id').html(rowId);
                        $('#delete-item-alert').removeClass('hidden');
                        setTimeout(function () {
                            $('#delete-item-alert').fadeOut('slow');
                        }, 2500);
                    }
                }
            });
        });
    });
});
