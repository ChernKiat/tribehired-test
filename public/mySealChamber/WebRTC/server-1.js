import express from 'express'
const app = express()

import https from 'httpolyglot'
import fs from 'fs'
import path from 'path'
const __dirname = path.resolve()

app.get('/', (req, res) => {
  res.send('Hello from mediasoup app!')
})

// SSL cert for HTTPS access
const options = {}

const httpsServer = https.createServer(options, app)
httpsServer.listen(3000, () => {
  console.log('listening on port: ' + 3000)
})
