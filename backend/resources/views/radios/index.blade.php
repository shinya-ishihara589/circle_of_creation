<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ラジオを聴く（登録不要）</title>
    <style>
        body {
            font-family: sans-serif;
            text-align: center;
            padding-top: 50px;
        }
    </style>
</head>

<body>
    <h1>📻 リアルタイムラジオ視聴</h1>
    <p>ボタンを押すと音声が流れます。</p>
    <button id="listen" style="font-size: 20px; padding: 10px 20px;">ラジオを聴く</button>

    <script>
        document.getElementById('listen').onclick = () => {
            const audioContext = new AudioContext({
                sampleRate: 44100
            });

            // 💡 配信側と同じく wss:// パスに接続
            const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
            const socket = new WebSocket(`${protocol}//${window.location.host}/ws/`);
            socket.binaryType = 'arraybuffer'; // データをバイナリとして受信

            let nextStartTime = 0;

            socket.onmessage = async (event) => {
                const float32Array = new Float32Array(event.data);
                const audioBuffer = audioContext.createBuffer(1, float32Array.length, audioContext.sampleRate);
                audioBuffer.getChannelData(0).set(float32Array);

                const bufferSource = audioContext.createBufferSource();
                bufferSource.buffer = audioBuffer;
                bufferSource.connect(audioContext.destination);

                const currentTime = audioContext.currentTime;
                if (nextStartTime < currentTime) {
                    nextStartTime = currentTime; // 初回、または遅延時は現在時刻に同期
                }

                bufferSource.start(nextStartTime);
                nextStartTime += audioBuffer.duration; // 次のピースの再生時間を予約
            };

            document.getElementById('listen').innerText = "🔊 視聴中...";
            document.getElementById('listen').disabled = true;
        };
    </script>
</body>

</html>
