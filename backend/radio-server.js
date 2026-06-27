const { WebSocketServer } = require('ws');
const wss = new WebSocketServer({ port: 8080 });
console.log('Radio WebSocket Server running on port 8080');
wss.on('connection', (ws) => {
  ws.on('message', (data, isBinary) => {
    wss.clients.forEach((client) => {
      if (client !== ws && client.readyState === 1) {
        client.send(data, { binary: isBinary });
      }
    });
  });
});
