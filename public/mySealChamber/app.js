import { createServer } from 'https';
import { readFileSync } from 'fs';
import WebSocket, { WebSocketServer } from 'ws';

const server = createServer({
    cert: readFileSync('/path/to/cert.pem'),
    key: readFileSync('/path/to/key.pem')
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
