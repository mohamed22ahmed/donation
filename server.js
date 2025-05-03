import express from 'express';
import http from 'http';
import { Server } from 'socket.io';
import bodyParser from "body-parser";
const app = express();
const server = http.createServer(app);
const io = new Server(server, {
    cors: {
        origin: "http://localhost:8000",
        methods: ["GET", "POST"],
    },
});

app.use(bodyParser.json());

const users = {};

io.on('connection', (socket) => {
    console.log('User connected:', socket.id);

    socket.on('register', (userId) => {
        users[userId] = socket.id;
        console.log(userId)
        console.log(`User registered: ${userId} with socket ID: ${socket.id}`);
    });

    socket.on('disconnect', () => {
        for (const [userId, socketId] of Object.entries(users)) {
            if (socketId === socket.id) {
                delete users[userId];
                console.log(`User disconnected: ${userId}`);
                break;
            }
        }
    });
});

app.post('/emit', (req, res) => {
    const { event, data, userId } = req.body;
    console.log(req.body)
    if (!event || !data || !userId) {
        return res.status(400).send({ error: 'Event, data, and userId are required' });
    }
    console.log(users)
    const socketId = users[userId];
    console.log(`Target userId: ${userId}, socketId: ${socketId}`);

    if (socketId) {
        io.to(socketId).emit(`notification:${userId}`, data);
        console.log(`Sent event "${event}" to userId: ${userId}, socketId: ${socketId}`);
        return res.send({ success: true });
    }

    return res.status(404).send({ error: 'User not connected' });
});

server.listen(3000, () => {
    console.log('Socket.IO server running on http://localhost:3000');
});
