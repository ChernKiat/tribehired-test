// import express from 'express'
// const app = express()

// import https from 'httpolyglot'
// // import { readFileSync } from 'fs'
// import fs from 'fs'
// import path from 'path'
// const __dirname = path.resolve()

// import { Server } from 'socket.io'
// import mediasoup from 'mediasoup'

// app.get('/', (req, res) => {
//     res.send('Hello from mediasoup app!')
// })

// app.use('/sfu', express.static(path.join(__dirname, 'public')))

// // SSL cert for HTTPS access
// const options = {
//     key: fs.readFileSync('/home/admin/conf/web/ssl.sealkingdom.xyz.key'),
//     cert: fs.readFileSync('/home/admin/conf/web/ssl.sealkingdom.xyz.crt')
// }

// const httpsServer = https.createServer(options, app)
// httpsServer.listen(3000, () => {
//     console.log('listening on port: ' + 3000)
// })

// const io = new Server(httpsServer)

// // socket.io namespace (could represent a room?)
// const peers = io.of('/mediasoup')

// let worker
// let router
let producerTransport
let consumerTransport

// const createWorker = async () => {
//     worker = await mediasoup.createWorker({
//         rtcMinPort: 2000,
//         rtcMaxPort: 2020,
//     })
//     console.log(`worker pid ${worker.pid}`)

//     worker.on('died', error => {
//         // This implies something serious happened, so kill the application
//         console.error('mediasoup worker has died')
//         setTimeout(() => process.exit(1), 2000) // exit in 2 seconds
//     })

//     return worker
// }

// worker = createWorker()

// const mediaCodecs = [
//     {
//         kind: 'audio',
//         mimeType: 'audio/opus',
//         clockRate: 48000,
//         channels: 2,
//     },
//     {
//         kind: 'video',
//         mimeType: 'video/VP8',
//         clockRate: 90000,
//         parameters: {
//             'x-google-start-bitrate': 1000,
//         },
//     },
// ]

// peers.on('connection', async socket => {
//     console.log(socket.id)
//     socket.emit('connection-success', {
//         socketId: socket.id
//     })

//     socket.on('disconnect', () => {
//         // do some cleanup
//         console.log('peer disconnected')
//     })

//     router = await worker.createRouter({ mediaCodecs, })

//     socket.on('getRtpCapabilities', (callback) => {

//         const rtpCapabilities = router.rtpCapabilities

//         console.log('rtp Capabilities', rtpCapabilities)

//         // call callback from the client and send back the rtpCapabilities
//         callback({ rtpCapabilities })
//     })

    socket.on('createWebRtcTransport', async ({ sender }, callback) => {
        console.log(`Is this a sender request? ${sender}`)
        // The client indicates if it is a producer or a consumer
        // if sender is true, indicates a producer else a consumer
        if (sender)
            producerTransport = await createWebRtcTransport(callback)
        else
            consumerTransport = await createWebRtcTransport(callback)
    })
// })

const createWebRtcTransport = async (callback) => {
    try {
        // https://mediasoup.org/documentation/v3/mediasoup/api/#WebRtcTransportOptions
        const webRtcTransport_options = {
            listenIps: [
                {
                    ip: '127.0.0.1',
                    announcedIp: 'sealkingdom.com'
                }
            ],
            enableUdp: true,
            enableTcp: true,
            preferUdp: true,
        }

        // https://mediasoup.org/documentation/v3/mediasoup/api/#router-createWebRtcTransport
        let transport = await router.createWebRtcTransport(webRtcTransport_options)
        console.log(`transport id: ${transport.id}`)

        transport.on('dtlsstatechange', dtlsState => {
            if (dtlsState === 'closed') {
                transport.close()
            }
        })

        transport.on('close', () => {
            console.log('transport closed')
        })

        // send back to the client the following prameters
        callback({
            // https://mediasoup.org/documentation/v3/mediasoup-client/api/#TransportOptions
            params: {
                id: transport.id,
                iceParameters: transport.iceParameters,
                iceCandidates: transport.iceCandidates,
                dtlsParameters: transport.dtlsParameters,
            }
        })

        return transport

    } catch (error) {
        console.log(error)
        callback({
            params: {
                error: error
            }
        })
    }
}
