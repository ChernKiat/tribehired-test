import { createServer } from 'https';
import { parse } from 'url';
// import { readFileSync } from 'fs';
import WebSocket, { WebSocketServer } from 'ws';

const server = createServer();
// const server = createServer({
//     cert: readFileSync('/path/to/cert.pem'),
//     key: readFileSync('/path/to/key.pem')
// });
// const wss = new WebSocketServer({ server });
const wss1 = new WebSocketServer({ noServer: true });
const wss2 = new WebSocketServer({ noServer: true });

// const wss = new WebSocketServer('ws://www.host.com/path');
// const wss = new WebSocketServer({ port: 8080 });

// wss.on('connection', function connection(ws, request, client) {
wss1.on('connection', function connection(ws, request, client) {
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

wss2.on('connection', function connection(ws, request, client) {
    // ...
});

server.on('upgrade', function upgrade(request, socket, head) {
    // authentication client (code your own authenticate function)
    // authenticate(request, function next(err, client) {
    //     if (err || !client) {
    //         socket.write('HTTP/1.1 401 Unauthorized\r\n\r\n');
    //         socket.destroy();
    //         return;
    //     }

    //     wss2.handleUpgrade(request, socket, head, function done(ws) {
    //         wss2.emit('connection', ws, request, client);
    //     });
    // });

    const { pathname } = parse(request.url);

    if (pathname === '/foo') {
        wss1.handleUpgrade(request, socket, head, function done(ws) {
            wss1.emit('connection', ws, request);
        });
    } else if (pathname === '/bar') {
        wss2.handleUpgrade(request, socket, head, function done(ws) {
            wss2.emit('connection', ws, request);
        });
    } else {
        socket.destroy();
    }
});

server.listen(8080);
