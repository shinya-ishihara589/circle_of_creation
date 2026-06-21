<!DOCTYPE html>
<html>

<body>
    <h2>Chat</h2>
    <input id="msg" placeholder="メッセージ">
    <button onclick="send()">送信</button>
    <div id="log"></div>

    <script>
        const ws = new WebSocket("ws://localhost:8080");
        const log = document.getElementById("log");

        ws.onmessage = (e) => {
            const div = document.createElement("div");
            div.textContent = e.data;
            log.appendChild(div);
        };

        function send() {
            ws.send(document.getElementById("msg").value);
        }
    </script>
</body>

</html>
