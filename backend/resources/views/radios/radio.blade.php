<!DOCTYPE html>
<html lang="ja">

<head>
    <title>Radio Live Stream</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-light">
    <div class="container py-5 text-center">
        <h1 class="mb-4">リアルタイム音声配信テスト</h1>

        <!-- 配信者用エリア -->
        <div class="card p-4 mb-4 shadow-sm text-center">
            <h3>【配信者】マイク音声を開始する</h3>
            <!-- 初期状態を「配信開始（緑色）」に統一しました -->
            <button id="stream-btn" class="btn btn-success btn-lg mt-2 mx-auto" style="width: 200px;">
                配信開始
            </button>
        </div>
    </div>

    <script>
        // 配信の状態を管理する変数
        let localStream = null;
        let peerConnection = null;
        let isStreaming = false;

        // 💡 画面の読み込みが完了したら、ボタンにクリックイベントを正しく紐付ける処理
        document.addEventListener('DOMContentLoaded', () => {
            const streamBtn = document.getElementById('stream-btn');
            if (streamBtn) {
                streamBtn.addEventListener('click', toggleStream);
            }
        });

        // 配信開始・終了ボタンのクリックイベント
        async function toggleStream() {
            const streamBtn = document.getElementById('stream-btn');

            if (!isStreaming) {
                // --- 【配信開始】の処理 ---
                try {
                    // 1. マイクの音声入力を取得
                    localStream = await navigator.mediaDevices.getUserMedia({
                        audio: true,
                        video: false
                    });

                    // 2. 配信状態を「ON」にする
                    isStreaming = true;
                    streamBtn.textContent = '配信終了';
                    streamBtn.className = 'btn btn-danger btn-lg mt-2 mx-auto'; // 赤色ボタンに変更
                    console.log("配信を開始しました（マイクON）");

                } catch (error) {
                    console.error("配信の開始に失敗しました（マイクが拒否された等）:", error);
                    alert("マイクの許可が得られなかったか、マイクが見つかりません。");
                }

            } else {
                // --- 【配信終了】の処理 ---
                // 1. マイクの電源（デバイス）を完全に停止してランプを消す
                if (localStream) {
                    localStream.getTracks().forEach(track => track.stop());
                    localStream = null;
                }

                // 2. WebRTCの通信回線を綺麗に切断する（本番用）
                if (peerConnection) {
                    peerConnection.close();
                    peerConnection = null;
                }

                // 3. 配信状態を「OFF」にする
                isStreaming = false;
                streamBtn.textContent = '配信開始';
                streamBtn.className = 'btn btn-success btn-lg mt-2 mx-auto'; // 緑色ボタンに戻す
                console.log("配信を終了しました（マイクOFF・通信切断）");
            }
        }
    </script>
</body>

</html>
