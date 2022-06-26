// const io = require('socket.io-client')

// const socket = io("/mediasoup")

// socket.on('connection-success', ({ socketId }) => {
//     console.log(socketId)
// })

let device
let rtpCapabilities

// let params = {
//     // mediasoup params
// }

// const streamSuccess = async (stream) => {
//     localVideo.srcObject = stream
//     const track = stream.getVideoTracks()[0]
//     params = {
//         track,
//         ...params
//     }
// }

// const getLocalStream = () => {
//     navigator.getUserMedia({
//         audio: false,
//         video: {
//             width: {
//                 min: 640,
//                 max: 1920,
//             },
//             height: {
//                 min: 400,
//                 max: 1080,
//             }
//         }
//     }, streamSuccess, error => {
//         console.log(error.message)
//     })
// }

const getRtpCapabilities = () => {
    // make a request to the server for Router RTP Capabilities
    // see server's socket.on('getRtpCapabilities', ...)
    // the server sends back data object which contains rtpCapabilities
    socket.emit('getRtpCapabilities', (data) => {
        console.log(`Router RTP Capabilities... ${data.rtpCapabilities}`)

        // we assign to local variable and will be used when
        // loading the client Device (see createDevice above)
        rtpCapabilities = data.rtpCapabilities
    })
}

// btnLocalVideo.addEventListener('click', getLocalStream)
btnRtpCapabilities.addEventListener('click', getRtpCapabilities)
