// import express from 'express'
// const app = express()

// import https from 'httpolyglot'
// // import { readFileSync } from 'fs'
// import fs from 'fs'
// import path from 'path'
// const __dirname = path.resolve()

import { Server } from 'socket.io'

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

const io = new Server(httpsServer)

// socket.io namespace (could represent a room?)
const peers = io.of('/mediasoup')

peers.on('connection', async socket => {
    console.log(socket.id)
    socket.emit('connection-success', {
        socketId: socket.id
    })
})
