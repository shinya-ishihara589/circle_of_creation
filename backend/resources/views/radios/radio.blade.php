<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ラジオ配信側</title>
    <!-- PeerJSの読み込み -->
    <script src="https://unpkg.com"></script>
</head>

<body>
    <h1>ラジオ配信管理画面</h1>
    <p>あなたの配信ID: <strong id="my-id">発行中...</strong></p>
    <p style="color: green;" id="status">マイクを準備しています...</p>

    <script>
        let localStream;
        // 1. 自分のマイク音声を取得
        navigator.mediaDevices.getUserMedia({
                audio: true,
                video: false
            })
            .then(stream => {
                localStream = stream;
                document.getElementById('status').innerText = 'マイク準備完了。IDをリスナーに共有してください。';

                // 2. Peerオブジェクトの作成（IDは自動発行）
                const peer = new Peer();

                // IDが確定したら画面に表示
                peer.on('open', (id) => {
                    document.getElementById('my-id').innerText = id;
                });

                // 3. リスナーから接続（コール）が来たら自動で音声ストリームを返す
                peer.on('call', (call) => {
                    // 音声ストリームを渡して応答
                    call.answer(localStream);
                });
            })
            .catch(err => {
                alert('マイクの許可が必要です: ' + err);
            });
    </script>
</body>

</html>
