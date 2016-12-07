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
                    showLink = $('#data-table-command').data('show'),
                    filesLink = $('#data-table-command').data('files'),
                    downloadLink = $('#data-table-command').data('download'),
                    params = $('#data-table-command').data('params'),
                    routeParams = {},
                    commands = '';

                if (params) {
                    routeParams[params.name] = params.value;
                }
                routeParams['id'] = row.id;

                commands += editLink ? '<a href=' + Routing.generate(editLink, routeParams, true) + '><button type="button" class="btn btn-icon bgm-blue command-edit waves-effect waves-circle" data-row-id="' + row.id + '"><span class="zmdi zmdi-edit"></span></button></a>' : '';
                commands += showLink ? '<a href=' + Routing.generate(showLink, routeParams, true) + '><button type="button" class="btn btn-icon bgm-teal waves-effect waves-circle" data-row-id="' + row.id + '"><span class="zmdi zmdi-zoom-in"></span></button></a>' : '';
                commands += filesLink ? '<a href=' + Routing.generate(filesLink, {'project': row.id}, true) + '><button type="button" class="btn btn-icon bgm-bluegray waves-effect waves-circle" data-row-id="' + row.id + '"><span class="zmdi zmdi-file"></span></button></a>' : '';
                commands += downloadLink ? '<a href=' + Routing.generate(downloadLink, {'id': row.id}, true) + '><button type="button" class="btn btn-icon bgm-bluegray waves-effect waves-circle" data-row-id="' + row.id + '"><span class="zmdi zmdi-download"></span></button></a>' : '';
                commands += '<button type="button" class="btn btn-icon bgm-red command-delete waves-effect waves-circle" data-row-id="' + row.id + '"><span class="zmdi zmdi-delete"></span></button>';

                return  commands;
            }
        }
    }).on('loaded.rs.jquery.bootgrid', function () {
        var deleteLink = $('#data-table-command').data('delete'),
            params = $('#data-table-command').data('params'),
            routeParams = {};

        if (params) {
            routeParams[params.name] = params.value;
        }

        grid.find('.command-delete').on('click', function () {
            routeParams['id'] = $(this).data('row-id');

            $.ajax({
                url: Routing.generate(deleteLink, routeParams, true),
                type: 'get',
                dataType: 'json',
                success: function (data) {
                    if (data.delete === 'success') {
                        $('#data-table-command').bootgrid('reload');
                        $('#object-id').html(routeParams['id']);
                        $('#delete-item-alert').removeClass('hidden');
                        setTimeout(function () {
                            $('#delete-item-alert').fadeOut('slow');
                        }, 2500);
                    }
                    if (data.delete === 'failed') {
                        $('#delete-item-alert-failure').removeClass('hidden');
                        $('#object-id').html(routeParams['id']);
                        setTimeout(function () {
                            $('#delete-item-alert').fadeOut('slow');
                        }, 2500);
                    }
                }
            });
        });
    });
});
