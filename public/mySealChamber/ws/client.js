import WebSocket, { WebSocketServer } from 'ws';

const ws = new WebSocket('wss://sealkingdom.xyz:8090');

ws.on('open', function open() {
    console.log('connected');
    ws.send(Date.now());
});

ws.on('message', function message(data) {
    console.log('received: %s', data);
});

ws.on('close', function close() {
    console.log('disconnected');
});
c
