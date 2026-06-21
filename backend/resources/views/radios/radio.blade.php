<!DOCTYPE html>
<html lang="ja">

<head>
    <title>Radio Live Stream</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://jsdelivr.net" rel="stylesheet">
    <script src="https://jquery.com"></script>
</head>

<body class="bg-light">
    <div class="container py-5 text-center">
        <h1 class="mb-4">リアルタイム音声配信テスト</h1>

        <!-- 配信者用エリア -->
        <div class="card p-4 mb-4 shadow-sm text-center">
            <h3>【配信者】マイク音声を開始する</h3>
            <button id="stream-btn" class="btn btn-success btn-lg mt-2 mx-auto" style="width: 200px;">
                配信開始
            </button>
        </div>

        <!-- 音声確認用のスピーカー（テスト用に見えるようにしています） -->
        <div class="card p-4 shadow-sm text-center">
            <h3>【リスナー側】スピーカー出力（テスト用）</h3>
            <audio id="local-audio" controls autoplay class="mx-auto mt-2"></audio>
        </div>
    </div>

    <script>
        let localStream = null;
        let isStreaming = false;

        document.addEventListener('DOMContentLoaded', () => {
            const streamBtn = document.getElementById('stream-btn');
            if (streamBtn) {
                streamBtn.addEventListener('click', toggleStream);
            }
        });

        async function toggleStream() {
            const streamBtn = document.getElementById('stream-btn');
            const audioHtml = document.getElementById('local-audio');

            if (!isStreaming) {
                try {
                    // 1. マイクの音声入力を取得
                    localStream = await navigator.mediaDevices.getUserMedia({
                        audio: true,
                        video: false
                    });

                    // 2. 【音声渡し】取得したマイクの音を、そのまま画面のスピーカー（audioタグ）に流し込む
                    audioHtml.srcObject = localStream;

                    // 3. 画面の見た目を「配信中」に切り替える
                    isStreaming = true;
                    streamBtn.textContent = '配信終了';
                    streamBtn.className = 'btn btn-danger btn-lg mt-2 mx-auto';
                    alert("マイクをオンにしました。自分の声がスピーカーから聞こえます。");

                } catch (error) {
                    console.error("マイクの取得に失敗しました:", error);
                    alert("マイクの許可が得られなかったか、マイクが見つかりません。");
                }

            } else {
                // --- 【配信終了】の処理 ---
                // 1. マイクをオフにする
                if (localStream) {
                    localStream.getTracks().forEach(track => track.stop());
                    localStream = null;
                }

                // 2. スピーカーへの音声渡しをストップする
                audioHtml.srcObject = null;

                // 3. 画面の見た目を「配信開始」に戻す
                isStreaming = false;
                streamBtn.textContent = '配信開始';
                streamBtn.className = 'btn btn-success btn-lg mt-2 mx-auto';
                alert("配信を終了しました。");
            }
        }
    </script>
</body>

</html>