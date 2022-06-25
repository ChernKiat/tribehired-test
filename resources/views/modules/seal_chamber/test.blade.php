<html>
  <head>
    <style>
        tr {
            vertical-align: top;
        }

        .video {
            width: 360px;
            background-color: black;
            margin: 2px 0;
        }

        button {
            margin: 2;
        }

        #sharedBtns {
            padding: 5;
            background-color: papayawhip;
            display: flex;
            justify-content: center;
        }
    </style>
  </head>
  <body>
    <body>
        <div id="video">
            <table>
                <thead>
                    <th>Local Video</th>
                    <th>Remote Video</th>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div id="sharedBtns">
                                <video id="localVideo" autoplay class="video" ></video>
                            </div>
                        </td>
                        <td>
                            <div id="sharedBtns">
                                <video id="remoteVideo" autoplay class="video" ></video>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div id="sharedBtns">
                                <button id="btnLocalVideo">1. Get Local Video</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div id="sharedBtns">
                                <button id="btnRtpCapabilities">2. Get Rtp Capabilities</button>
                                <br />
                                <button id="btnDevice">3. Create Device</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div id="sharedBtns">
                                <button id="btnCreateSendTransport">4. Create Send Transport</button>
                                <br />
                                <button id="btnConnectSendTransport">5. Connect Send Transport & Produce</button></td>
                            </div>
                        <td>
                            <div id="sharedBtns">
                                <button id="btnRecvSendTransport">6. Create Recv Transport</button>
                                <br />
                                <button id="btnConnectRecvTransport">7. Connect Recv Transport & Consume</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
  </body>
  <footer>
      <script src="/js/bundle.js"></script>
      {{-- {!! HTML::script('/js/bundle.js') !!} --}}
  </footer>
</html>
