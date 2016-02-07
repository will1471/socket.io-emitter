var io = require('socket.io-client');
var socket = io.connect('http://localhost:8080');

socket.on('connect', function (socket) {
    console.log('client: connected');
});

socket.on('event1', function (msg) {
    console.log('client: on.event1', msg);
});

socket.on('event2', function (msg) {
    console.log('client: on.event2', msg);
});
