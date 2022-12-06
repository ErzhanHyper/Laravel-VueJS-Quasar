const httpServer = require("http").createServer();
const io = require("socket.io")(httpServer, {
    cors: {
        origin: '*',
    },
});

io.on("connection", socket => {
    console.log(socket)
    socket.on('feed-chat-channel', function(data){
        console.log(data)
    });

    socket.on('feed_chat', function(data){
        console.log(data)
        socket.broadcast.emit('chat_message_send', data);
    });

    socket.on('project_chat', function(data){
        socket.broadcast.emit('chat_message_send_project', data);
    });

    socket.on('application_channel', function(data){
        socket.broadcast.emit('application_channel_send', data);
    });

    socket.on('live_feed_channel', function(data){
        socket.broadcast.emit('live_feed_channel_update', data);
    });

    socket.on('quote_channel', function(data){
        socket.broadcast.emit('quote_channel_update', data);
    });

    socket.on('main_data_channel', function(data){
        socket.broadcast.emit('main_data_channel_update', data);
    });

});

// let Redis = require('ioredis');
// let ioredis = new Redis();

// ioredis.on('connect', function (channel, message) {
//     console.log('ioredis');
//     console.log(message);
//     console.log(channel);
// });

// ioredis.subscribe('feed-chat-channel', function(err, count) {
//     console.log("connected"+err+":"+count);
// });

// ioredis.on('message', function(channel, message) {
//     console.log(message)
//     message = JSON.parse(message);
//     io.emit(channel + ':' + message.event, message.data);
// });

httpServer.listen(3000);