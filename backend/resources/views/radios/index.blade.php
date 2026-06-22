<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ラジオ視聴側</title>
    <!-- PeerJSの読み込み -->
    <script src="https://unpkg.com"></script>
</head>

<body>
    <h1>ラジオ視聴画面</h1>

    <input type="text" id="broadcast-id" placeholder="配信IDを入力">
    <button id="join-btn">入室して聴く</button>

    <p id="status">IDを入力してボタンを押してください。</p>

    <!-- 音声再生用のタグ（ブラウザ制限回避のため controls を推奨、非表示でも可） -->
    <audio id="remote-audio" autoplay controls></select>

        <script>
            const peer = new Peer();

            document.getElementById('join-btn').addEventListener('click', () => {
                const broadcastId = document.getElementById('broadcast-id').value;
                if (!broadcastId) {
                    alert('IDを入力してください');
                    return;
                }

                document.getElementById('status').innerText = '接続中...';

                // ブラウザの仕様上、ユーザーが画面を操作（クリック）した後にしか音声を再生できないためここで処理
                const audio = document.getElementById('remote-audio');

                // 配信者に対して空のダミーストリームでコールをかける（聴くだけなのでマイクは不要）
                const call = peer.call(broadcastId, new MediaStream());

                // 配信者から音声ストリームが届いたら audio タグにセット
                call.on('stream', (remoteStream) => {
                    document.getElementById('status').innerText = '受信中（生放送）';
                    audio.srcObject = remoteStream;
                    audio.play().catch(e => {
                        document.getElementById('status').innerText = '再生エラー。画面をクリックして再生を許可してください。';
                    });
                });

                call.on('close', () => {
                    document.getElementById('status').innerText = '配信が終了しました。';
                });
            });
        </script>
</body>

</html>
