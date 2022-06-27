const express = require('express');
const app = express()

const fs = require('fs');
const path = require('path');
const https = require('httpolyglot');
const __dirname = path.resolve()

app.get('/', (req, res) => {
    res.send('Hello from mediasoup app!')
})

app.use('/sfu', express.static(path.join(__dirname, 'public')))

// SSL cert for HTTPS access
const options = {
    key: fs.readFileSync('/home/admin/conf/web/ssl.sealkingdom.xyz.key'),
    cert: fs.readFileSync('/home/admin/conf/web/ssl.sealkingdom.xyz.crt')
}

const httpsServer = https.createServer(options, app)
httpsServer.listen(3000, () => {
    console.log('listening on port: ' + 3000)
})
