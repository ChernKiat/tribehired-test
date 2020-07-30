// const connection = new WebSocket('ws://35.225.237.97:12345');
const connection = new WebSocket('wss://35.225.237.97:12345/');

connection.addEventListener('open', function() {
    console.log('connected');
});

connection.addEventListener('message', function(e) {
    // what you want to do if you receive data from other players
    console.log('received', e.data);
});

// trigger it to send data to other players
function send(data) {
    if (connection.readyState === WebSocket.OPEN) {
        connection.send(data);
    } else {
        throw 'Not connected';
    }
}
setTimeout(function(){ send('yay'); }, 3000);

// demo use case
// http://gamejam.cf/ws
