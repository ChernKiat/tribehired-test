// import express from 'express'
// const app = express()

// import https from 'httpolyglot'
// // import { readFileSync } from 'fs'
// import fs from 'fs'
// import path from 'path'
// const __dirname = path.resolve()

// app.get('/', (req, res) => {
//     res.send('Hello from mediasoup app!')
// })

app.use('/sfu', express.static(path.join(__dirname, 'public')))

// // SSL cert for HTTPS access
// const options = {
//     key: fs.readFileSync('/home/admin/conf/web/ssl.sealkingdom.xyz.key', 'utf-8'),
//     cert: fs.readFileSync('/home/admin/conf/web/ssl.sealkingdom.xyz.crt', 'utf-8')
// }

// const httpsServer = https.createServer(options, app)
// httpsServer.listen(3000, () => {
//     console.log('listening on port: ' + 3000)
// })
