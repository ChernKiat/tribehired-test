const ws = new WebSocket('wss://sealkingdom.xyz:8090');

ws.addEventListener('open', function (event) {
    console.log('connected');
    ws.send(Date.now());
});

ws.addEventListener('message', function (event) {
    console.log('received: %s', event.data);
});

ws.addEventListener('close', function (event) {
    console.log('disconnected');
});
