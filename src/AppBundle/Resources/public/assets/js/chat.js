$(document).ready(function () {
    var url = (window.location.protocol === 'https:' ? 'wss://' : 'ws://') + window.location.host + '/wss/',
        webSocket = WS.connect(url);

    webSocket.on("socket/connect", function (session) {
        session.subscribe("chat/room/", function (uri, payload) {
            if (typeof payload.message === 'object') {
                var html = '<a data-id="' +
                    payload.message.chat_id +
                    '" data-key="" ' +
                    'class="list-group-item media chat-room"><div class="pull-left"></div>' +
                    '<div class="media-body"><div class="lgi-heading">' +
                    payload.message.chat_name +
                    '</div><div id="last-message"></div></div></a>';
                $('#mCSB_2_container').prepend(html);
                subscribeToChat($('a.chat-room[data-id="' + payload.message.chat_id + '"][data-key=""]'), session);
            }
        });

        fetchUsers(session);

        $('#create-new-chat').click(function () {
            createNewChatRoom(session);
        });

        $('a.chat-room').each(function () {
            subscribeToChat(this, session);
        });

        $(document).on('click', 'a.chat-room', function () {
            var chatId = $(this).data('id'),
                key = $(this).data('key');

            if (!$(this).hasClass('disabled')) {
                activeChat = $('.chat-room.bgm-lightgreen');
                if (typeof activeChat.data('id') !== 'undefined') {
                    activeChat.removeClass('disabled');
                    activeChat.removeClass('bgm-lightgreen');
                }
                $(this).addClass('disabled');
                $(this).addClass('bgm-lightgreen');
                loadMessages(key, chatId);
            }
        });

        $(document).on('click', 'a.user-chat', function () {
            var key = $(this).data('id') < currentUserId ? $(this).data('id') + '-' + currentUserId : currentUserId + '-' + $(this).data('id'),
                element = 'a.chat-room[data-id="' + $(this).data('id') + '"][data-key="' + key + '"]';

            if ($(element).length === 0) {
                var html = '<a data-id="' +
                    $(this).data('id') +
                    '" data-key="' + key + '" ' +
                    'class="list-group-item media chat-room"><div class="pull-left"></div>' +
                    '<div class="media-body"><div class="lgi-heading">' +
                    $(this).text() +
                    '</div><div id="last-message"></div></div></a>';
                $('#mCSB_2_container').prepend(html);
                subscribeToChat($(element), session);
            }
            $('#mCSB_2_container').find(element).trigger("click");
        });

        $('#message-form button').on('click', function () {
            sendMessageToChat($('.chat-room.bgm-lightgreen'), session);
        });

        $('#delete-chat').on('click', function () {
            activeChat = $('.chat-room.bgm-lightgreen');
            if (activeChat.data('key') !== '') {
                removePrivateMessages(activeChat, session);
            }
        });
    });

    webSocket.on("socket/disconnect", function (error) {
        console.log("Disconnected for " + error.reason + " with code " + error.code);
    });

});

function fetchUsers(session) {
    var users = new Bloodhound({
        datumTokenizer: function (data) {
            return Bloodhound.tokenizers.whitespace(data.username);
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: {
            cache: false,
            url:Routing.generate('app_admin_project_participants', {'id': projectId}),
            filter: function(list) {
                return $.map(list, function(user) {
                    return {
                        id: user.id,
                        username: user.username
                    };
                });
            }
        }
    });

    $('#user-search').typeahead(null, {
        name: 'users',
        displayKey: 'username',
        source: users.ttAdapter(),
    }).on('typeahead:selected', function (obj, data) {
        var key = data.id < currentUserId ? data.id + '-' + currentUserId : currentUserId + '-' + data.id,
            element = 'a.chat-room[data-id="' + data.id + '"][data-key="' + key + '"]';
        if ($(element).length === 0) {
            var html = '<a data-id="' +
                data.id +
                '" data-key="' + key + '" ' +
                'class="list-group-item media chat-room"><div class="pull-left"></div>' +
                '<div class="media-body"><div class="lgi-heading">' +
                '@' + data.username +
                '</div><div id="last-message"></div></div></a>';
            $('#mCSB_2_container').prepend(html);
            subscribeToChat($(element), session);
        }
        $('#mCSB_2_container').find(element).trigger("click");
    });
}

function createNewChatRoom (session) {
    swal({
        title: "Create new chat room",
        type: "input",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Write something",
    }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "") {
            swal.showInputError("You need to write something!");
            return false
        }
        session.publish("chat/room/", {
            data: {
                'project': projectId,
                'name': inputValue,
            }
        });
        swal("Created!", "New room created", "success");
    });
}

function removePrivateMessages (chat, session) {
    swal({
            title: "Are you sure?",
            text: "All messages will be deleted",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        },
        function () {
            $.ajax({
                url: Routing.generate('app_admin_project_chat_delete_private_messages', {'project': projectId, 'toUser': chat.data('id')}),
                type: 'GET',
                success: function (data) {
                    if (data.success) {
                        session.unsubscribe('chat/room/user/' + chat.data('id'));
                        chat.remove();
                        $('#chat-messages').removeAttr('data-key')
                        $('#chat-messages').removeAttr('data-id');
                        $('#chat-messages').empty();
                        swal("Deleted!", data.success, "success");
                    }
                }
            });
        });
}

function loadMessages (key, chatId) {
    var route = key.length > 0 ? 'app_admin_project_chat_private_messages' : 'app_admin_project_chat_messages';
    $.ajax({
        url: Routing.generate(route, {'project': projectId, 'id': chatId}),
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#chat-messages').html(data);
            $('#chat-messages').attr('data-id', chatId);
            key.length > 0
                ? $('#chat-messages').attr('data-key', key)
                : $('#chat-messages').attr('data-key', '')
            ;

            $('.mbl-messages').mCustomScrollbar({
                theme: 'minimal-dark',
                axis: 'yx',
                mouseWheel: {
                    enable: true,
                    axis: 'y',
                    preventDefault: true
                }
            }).mCustomScrollbar("scrollTo", "bottom", {scrollInertia: 0});
        }
    });
}


function subscribeToChat (chat, session) {
    var chatId = $(chat).data('id'),
        key = $(chat).data('key'),
        url = key.length > 0 ? "chat/room/user/" : "chat/room/";

    session.subscribe(url + chatId, function (uri, payload) {
        if (typeof payload.message === 'object' && payload.message.message.replace(/\s/g, '').length > 0) {
            var container = $('#chat-messages .mCSB_container'),
                displayClass = currentUserId == payload.message.from_id ? 'mblm-item-right' : 'mblm-item-left',
                displayUser = key.length > 0 ? '' : '<small>@' + payload.message.username + '</small>',
                messageContainer = '<div class="mblm-item ' +
                    displayClass +
                    '">' +
                    displayUser +
                    '<div>' +
                    payload.message.message +
                    '</div><small>' +
                    payload.message.time +
                    '</small></div>',
                lastMessage = '<small class="lgi-text">' +
                    payload.message.message +
                    '</small><small class="ms-time">' +
                    payload.message.time +
                    '</small>';

            if (key.length > 0) {
                dataId = payload.message.from_id == currentUserId
                    ? chatId
                    : payload.message.from_id
                ;
                key = dataId < currentUserId ? dataId + '-' + currentUserId : currentUserId + '-' + dataId;
                if (payload.message.from_id != currentUserId) {
                    element = 'a.chat-room[data-id="' + payload.message.from_id + '"][data-key="' + key + '"]';
                    if ($(element).length === 0) {
                        var html = '<a data-id="' +
                            payload.message.from_id +
                            '" data-key="' + key + '" ' +
                            'class="list-group-item media chat-room"><div class="pull-left"></div>' +
                            '<div class="media-body"><div class="lgi-heading">' +
                            '@' + payload.message.username +
                            '</div><div id="last-message"></div></div></a>';
                        $('#mCSB_2_container').prepend(html);
                        subscribeToChat($(element), session);
                    }
                }

                if (($('#chat-messages').attr('data-id') == payload.message.from_id || $('#chat-messages').attr('data-id') == chatId)
                    && ($('#chat-messages').attr('data-key') == key)
                ) {
                    container.append(messageContainer);
                    $('.mbl-messages').mCustomScrollbar("scrollTo", "bottom");
                    $('#message-form textarea').val('');
                }
                $('a.chat-room[data-id="' + chatId + '"][data-key="' + key + '"] #last-message').html(lastMessage);
                $('a.chat-room[data-id="' + payload.message.from_id + '"][data-key="' + key + '"] #last-message').html(lastMessage);
            } else {
                if ($('#chat-messages').attr('data-id') == chatId && $('#chat-messages').attr('data-key') == '') {
                    container.append(messageContainer);
                    $('.mbl-messages').mCustomScrollbar("scrollTo", "bottom");
                    $('#message-form textarea').val('');
                }
                $('a.chat-room[data-id="' + chatId + '"][data-key=""] #last-message').html(lastMessage);
            }
        }
    });
}

function sendMessageToChat (chat, session) {
    if (typeof $(chat).data('key') !== 'undefined' && typeof $(chat).data('id') !== 'undefined') {
        var url = $(chat).data('key').length > 0 ? "chat/room/user/" : "chat/room/";
        if ($.trim($('#message-form textarea').val())) {
            session.publish(url + $(chat).data('id'), {
                data: {
                    'project': projectId,
                    'chat': $(chat).data('id'),
                    'body': $('#message-form textarea').val(),
                }
            });
        }
    }
}
