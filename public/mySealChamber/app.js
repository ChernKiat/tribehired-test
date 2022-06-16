const { createServer } = require('https');
const { readFileSync } = require('fs');
var WebSocket, { WebSocketServer } = require('ws');

const server = createServer({
    // cert: readFileSync('/home/admin/conf/web/ssl.sealkingdom.xyz.pem'),
    cert: readFileSync('/home/admin/conf/web/ssl.sealkingdom.xyz.crt'),
    key: readFileSync('/home/admin/conf/web/ssl.sealkingdom.xyz.key')
});
const wss = new WebSocketServer({ server });

// const wss = new WebSocketServer('ws://www.host.com/path');
// const wss = new WebSocketServer({ port: 8080 });

wss.on('connection', function connection(ws, request) {
// wss.on('connection', function connection(ws, request, client) {
    const ip = request.socket.remoteAddress; // ip address

    ws.on('message', function message(data, isBinary) {
        ws.send('something');

        wss.clients.forEach(function each(client) {
            // if (client.readyState === WebSocket.OPEN) { // broadcast including itself
            if (client !== ws && client.readyState === WebSocket.OPEN) {
                client.send(data, { binary: isBinary });
            }
        });
    });
});

server.listen(8080);
