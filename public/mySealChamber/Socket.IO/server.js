const httpServer = require("http").createServer();

const io = require("socket.io")(httpServer, {
  cors: {
    origin: "*",
    // origin: "http://localhost:8080",
  },
});

io.on("connection", (socket) => {
  // const users = [];
  // for (let [id, socket] of io.of("/").sockets) {
  //   users.push({
  //     userID: id,
  //     username: socket.username,
  //   });
  // }
  // socket.emit("users", users);

  socket.on("message", (message) => {
    console.log(message);
  });
});
