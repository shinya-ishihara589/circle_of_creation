import { WebSocketServer } from 'ws';

const wss = new WebSocketServer({ port: 8080 });
console.log('Radio WebSocket Server running on port 8080');

wss.on('connection', (ws) => {
    ws.on('message', (data, isBinary) => {
        wss.clients.forEach((client) => {
            // 念のため client 自体が存在し、自分以外かつ接続中の場合のみ転送
            if (client && client !== ws && client.readyState === 1) {
                client.send(data, { binary: isBinary });
            }
        });
    });
});
