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
                var $grid = $('#data-table-command'),
                    editLink = $grid.data('edit'),
                    showLink = $grid.data('show'),
                    filesLink = $grid.data('files'),
                    downloadLink = $grid.data('download'),
                    impersonateUserLink = $grid.data('impersonate'),
                    params = $grid.data('params'),
                    url = $grid.data('url'),
                    routeParams = {},
                    commands = '';

                if (params) {
                    routeParams[params.name] = params.value;
                }
                routeParams['id'] = row.id;

                if ((typeof row.roles !== 'undefined' && row.roles.indexOf("ROLE_SUPER_ADMIN") >= 0 && currentUserId == row.id)
                    || (typeof row.roles !== 'undefined' && row.roles.indexOf("ROLE_SUPER_ADMIN") < 0)
                    || url !== Routing.generate('app_admin_user_list_filtered')
                ) {
                    commands += editLink ? '<a href="' + Routing.generate(editLink, routeParams, true) + '"><button type="button" class="btn btn-icon bgm-blue command-edit waves-effect waves-circle" data-row-id="' + row.id + '"><span class="zmdi zmdi-edit"></span></button></a>' : '';
                }

                commands += showLink ? '<a href="' + Routing.generate(showLink, routeParams, true) + '"><button type="button" class="btn btn-icon bgm-teal waves-effect waves-circle" data-row-id="' + row.id + '"><span class="zmdi zmdi-zoom-in"></span></button></a>' : '';
                commands += filesLink ? '<a href="' + Routing.generate(filesLink, {'project': row.id}, true) + '"><button type="button" class="btn btn-icon bgm-bluegray waves-effect waves-circle" data-row-id="' + row.id + '"><span class="zmdi zmdi-file"></span></button></a>' : '';
                commands += downloadLink ? '<a href="' + Routing.generate(downloadLink, {'id': row.id}, true) + '"><button type="button" class="btn btn-icon bgm-bluegray waves-effect waves-circle" data-row-id="' + row.id + '"><span class="zmdi zmdi-download"></span></button></a>' : '';

                if ((typeof row.roles !== 'undefined' && row.roles.indexOf("ROLE_SUPER_ADMIN") < 0)
                    || url != Routing.generate('app_admin_user_list_filtered')
                ) {
                    commands += impersonateUserLink ? '<button type="button" class="btn btn-icon bgm-bluegray waves-effect waves-circle command-impersonate" data-row-id="' + row.id + '"><span class="zmdi zmdi-account-circle"></span></button></a>' : '';
                    commands += '<button type="button" class="btn btn-icon bgm-red command-delete waves-effect waves-circle" data-row-id="' + row.id + '"><span class="zmdi zmdi-delete"></span></button>';
                }

                return  commands;
            }
        }
    }).on('loaded.rs.jquery.bootgrid', function () {
        var $grid = $('#data-table-command'),
            deleteLink = $grid.data('delete'),
            impersonateUserLink = $grid.data('impersonate'),
            params = $grid.data('params'),
            routeParams = {};

        if (params) {
            routeParams[params.name] = params.value;
        }

        grid.find('.command-impersonate').on('click', function () {
            var row = $(this).data('row-id'),
                email = $('#data-table-command tbody tr[data-row-id=' + row + '] td:nth-child(3)').html();

            window.location.href = Routing.generate(impersonateUserLink, {'_switch_user': email}, true);
        });

        grid.find('.command-delete').on('click', function () {
            routeParams['id'] = $(this).data('row-id');

            swal(
                {
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this item.',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel please!",
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function (confirmed) {
                    if (!confirmed) {
                        return;
                    }

                    swal('Deleting!', 'Your item is currently being deleted.', 'warning');
                    $.ajax({
                        url: Routing.generate(deleteLink, routeParams, true),
                        type: 'get',
                        dataType: 'json',
                        success: function (data) {
                            switch (data.delete) {
                                case 'success':
                                    $grid.bootgrid('reload');
                                    $('#object-id').html(routeParams['id']);
                                    swal({
                                        type: 'success',
                                        title: 'Deleted!',
                                        text: $('#object-id').parent().text().replace(/\s+/g, ' '),
                                        timer: 2500,
                                        showConfirmButton: false
                                    });
                                    break;
                                case 'failed':
                                    swal({
                                        type: 'error',
                                        title: 'Deleted!',
                                        text: data.message,
                                        timer: 2500,
                                        showConfirmButton: false
                                    });
                                    break;
                                case 'not_allowed':
                                    swal({
                                        type: 'warning',
                                        title: 'Unable to delete!',
                                        text: 'You are not allowed to delete this item.',
                                        timer: 2500,
                                        showConfirmButton: false
                                    });
                                    break;
                                default:
                                    swal({
                                        type: 'error',
                                        title: 'Unable to delete!',
                                        text: 'Something went wrong.',
                                        timer: 2500,
                                        showConfirmButton: false
                                    });
                            }
                        }
                    });
                }
            );
        });
    });
});
