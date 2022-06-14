import { io } from "socket.io-client";

const socket = io("ws://localhost:8080", { autoConnect: false });

export default socket;
