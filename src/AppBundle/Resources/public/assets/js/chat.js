$(function () {
    var url = (window.location.protocol === 'https:' ? 'wss://' : 'ws://') + window.location.host + '/wss/',
        webSocket = WS.connect(url);

    webSocket.on("socket/connect", function (session) {
        console.log("Successfully Connected!");
    });

    webSocket.on("socket/disconnect", function (error) {
        console.log("Disconnected for " + error.reason + " with code " + error.code);
    });
});
