// you need to restart your service if you modify the code inside
const fs = require('fs');
const https = require('https');
const WebSocket = require('ws');

// const simple_server = new WebSocket.Server({ port: 12345 });
const server = https.createServer({
    cert: fs.readFileSync('/home/admin/conf/web/ssl.gamejam.cf.crt'),
    key: fs.readFileSync('/home/admin/conf/web/ssl.gamejam.cf.key')
});
const secure_server = new WebSocket.Server({ server });
 
function broadcast(data) {
    secure_server.clients.forEach(ws => {
        ws.send(data);
    });
}

secure_server.on('connection', function connection(ws) {
    ws.on('message', function incoming(message) {
        broadcast(message);
    });
});

server.listen(12345);
