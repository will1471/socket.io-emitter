var redis = require('socket.io-redis');
var server = require('http').createServer();
var io = require('socket.io')(server);
server.listen(8080, "localhost");

console.log('server: starting localhost:8080');

io.adapter(redis());

io.on('connect', function (socket) {
    socket.join('room1');
    socket.join('room2');
    socket.join('room3');
});
