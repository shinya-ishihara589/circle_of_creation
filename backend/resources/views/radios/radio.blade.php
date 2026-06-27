<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ラジオ配信（登録不要）</title>
    <style>
        body {
            font-family: sans-serif;
            text-align: center;
            padding-top: 50px;
        }
    </style>
</head>

<body>
    <h1>🎙️ ラジオ生配信中</h1>
    <p>ボタンを押すとマイク音声の配信が始まります。</p>
    <button id="start" style="font-size: 20px; padding: 10px 20px;">配信開始</button>

    <script>
        // 💡 HTTPS環境に合わせて wss:// に自動切り替え
        const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
        const socket = new WebSocket(`${protocol}//${window.location.host}/ws/`);

        document.getElementById('start').onclick = async () => {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    audio: true
                });
                const audioContext = new AudioContext({
                    sampleRate: 44100
                });
                const source = audioContext.createMediaStreamSource(stream);

                // 2048サンプルごとに音声を切り出す
                const processor = audioContext.createScriptProcessor(2048, 1, 1);

                processor.onaudioprocess = (e) => {
                    const inputData = e.inputBuffer.getChannelData(0);
                    if (socket.readyState === WebSocket.OPEN) {
                        // 音声バイナリデータをそのまま送信
                        socket.send(inputData.buffer);
                    }
                };

                source.connect(processor);
                processor.connect(audioContext.destination);

                document.getElementById('start').innerText = "🔴 配信中...";
                document.getElementById('start').disabled = true;
            } catch (err) {
                alert('マイクの取得に失敗しました。HTTPS環境、またはマイクの権限を確認してください: ' + err);
            }
        };
    </script>
</body>

</html>
