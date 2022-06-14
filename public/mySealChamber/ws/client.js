import WebSocket, { WebSocketServer } from 'ws';

const ws = new WebSocket('ws://localhost:8080');

ws.on('open', function open() {
  console.log('connected');
});

ws.on('message', function message(data) {
  console.log('received: %s', data);
});

ws.send(Date.now());

ws.on('close', function close() {
  console.log('disconnected');
});
