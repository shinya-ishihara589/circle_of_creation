<!DOCTYPE html>
<html lang="ja">

<head>
    <title>Number Touch Game</title>
    <style>
        /* Canvasの枠線を見やすくする */
        canvas {
            border: 1px solid #000;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        canvas {
            border: 1px solid #ccc;
            background: #fff;
            cursor: pointer;
        }

        #ranking-list p {
            margin-bottom: 5px;
            border-bottom: 1px solid #eee;
            padding: 2px;
        }

        #timerDisplay {
            font-family: 'Courier New', Courier, monospace;
            display: inline-block;
            width: 80px;
            text-align: right;
        }
    </style>
</head>

<body class="bg-dark">
    <div class="container-fluid bg-white shadow" style="max-width: 1000px; min-height: 100vh;">
        <div class="row">
            <!-- 左側：メインゲーム画面 -->
            <div class="col-md-8 bg-light vh-100 p-3 d-flex flex-column align-items-center">
                @vite('resources/js/app.js')
                <h2 class="h5 mb-3">ナンバータッチゲーム</h2>

                <div class="mb-3 d-flex align-items-center justify-content-center">
                    <!-- 名前入力 -->
                    <div class="me-3">
                        名前：<input id="nameInput" type="text" maxlength="20" class="form-control d-inline-block w-auto" value="匿名">
                    </div>

                    <!-- タイマー（ここに追加） -->
                    <div class="h4 mb-0">
                        TIME: <span id="timerDisplay" class="text-primary font-monospace">0:00.00</span>
                    </div>
                </div>

                <canvas id="canvas" width="500" height="500"></canvas>
                <p class="text-muted mt-2 small">クリア後にランキングに自動登録されます</p>
            </div>

            <!-- 右側：ランキング表示 -->
            <div class="col-md-4 p-4 bg-light">
                <h3 class="h5 border-bottom pb-2 mb-3">ランキング</h3>
                <div id="ranking-list">
                    @foreach ($rankingData as $no => $ranking)
                    <p>{{ $no + 1 }}位：{{ $ranking->name }}：{{ $ranking->time }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        const canvas = document.getElementById('canvas');
        const canvasRenderer = new CanvasRenderer('canvas');
        const timer = new Timer();
        let panel = new NumberTouchGameManager();

        let startTime; // 開始時間
        let timerInterval; // タイマーを止めるための識別番号

        // 初回描画
        canvasRenderer.clearAll();
        canvasRenderer.drawRect(100, 200, 300, 100);
        canvasRenderer.drawText("START", 250, 250);

        canvas.addEventListener('click', function(e) {
            const x = e.clientX - canvas.getBoundingClientRect().left;
            const y = e.clientY - canvas.getBoundingClientRect().top;

            // 1. READY (START待ち)
            if (panel.state === 'READY') {
                // タイマー開始！ 表示の更新も任せる
                timer.start((timeString) => {
                    document.getElementById('timerDisplay').innerText = timeString;
                });

                canvasRenderer.clearAll();
                panel.state = 'START';
                panel.pushStart();
            }

            // 2. START (パネル表示)
            if (panel.state === 'START') {
                canvasRenderer.clearAll();
                let panelNums = panel.panelNums;
                for (let panelY = 0; panelY < panel.numOfSides; panelY++) {
                    for (let panelX = 0; panelX < panel.numOfSides; panelX++) {
                        const startX = (panelX * panel.width);
                        const startY = (panelY * panel.height);
                        let arrNum = panelY * panel.numOfSides + panelX;
                        canvasRenderer.ctx.strokeRect(startX, startY, panel.width, panel.height);
                        canvasRenderer.ctx.fillText(panelNums[arrNum] + 1, startX + 50, startY + 50);
                    }
                }
                panel.state = 'PLAYING';
            }
            // 3. PLAYING (クリック判定)
            else if (panel.state === 'PLAYING') {
                let isCorrect = panel.judge(x, y);
                if (isCorrect) {
                    let pos = panel.getPos();
                    panel.numCount();
                    canvasRenderer.ctx.clearRect(pos.x, pos.y, 100, 100);
                }
            }

            // 4. CLEARED (クリア処理とランキング更新)
            if (panel.state === 'CLEARED') {
                timer.stop(); // タイマー停止
                const finalTime = timer.formattedTime; // 最終タイム取得

                const name = $('#nameInput').val();

                canvasRenderer.clearAll();
                canvasRenderer.ctx.fillText(`CLEAR! ${clearTime}s`, 250, 150);
                canvasRenderer.ctx.fillText('ReStart Click', 250, 250);

                // 同期的にランキング更新 -> 取得
                $.ajax({
                    url: "/ranking/update", // ここで保存と取得の両方を行う
                    method: "POST",
                    data: {
                        name: name,
                        time: clearTime,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                }).done(function(res) {
                    // サーバーから返ってきた最新ランキング(res)で画面を書き換える
                    let html = '';
                    res.forEach((data, index) => {
                        html += `<p>${index + 1}位：${data.name}：${data.time}</p>`;
                    });
                    $('#ranking-list').html(html);
                    console.log("ランキングを更新しました");
                }).fail(function(err) {
                    console.error("保存または取得に失敗しました", err);
                });

                // ゲームリセット準備
                panel.state = 'READY';
                panel = new NumberTouchGameManager(panel.numOfSides);
            }

            // 5. GAME OVER
            if (panel.state === 'GAME_OVER') {
                // --- タイマー停止とリセット ---
                clearInterval(timerInterval);
                document.getElementById('timerDisplay').innerText = "0.00";

                canvasRenderer.clearAll();
                canvasRenderer.ctx.fillText('GAME OVER', 250, 250);
                panel.state = 'READY';
                panel = new NumberTouchGameManager(panel.numOfSides);
            }
        });



        export class Timer {
            #startTime = null;
            #endTime = null;
            #timerInterval = null;

            /**
             * タイマーを開始する
             * @param {Function} callback 毎秒（毎フレーム）実行したい処理
             */
            start(callback) {
                this.#startTime = Date.now();
                this.#endTime = null;

                // 以前のタイマーが動いていれば止める
                if (this.#timerInterval) clearInterval(this.#timerInterval);

                // 10ミリ秒ごとに計算してcallbackに渡す
                this.#timerInterval = setInterval(() => {
                    if (callback) callback(this.formattedTime);
                }, 10);
            }

            /**
             * タイマーを停止する
             */
            stop() {
                this.#endTime = Date.now();
                clearInterval(this.#timerInterval);
            }

            /**
             * リセット
             */
            reset() {
                this.stop();
                this.#startTime = null;
                this.#endTime = null;
            }

            /**
             * 現在の経過時間を「00:00.00」の形式で取得する（読み取り専用）
             */
            get formattedTime() {
                if (!this.#startTime) return "0:00.00";

                const now = this.#endTime || Date.now();
                const diffMs = now - this.#startTime;

                const m = Math.floor(diffMs / 60000);
                const s = Math.floor((diffMs % 60000) / 1000);
                const ms = Math.floor((diffMs % 1000) / 10);

                const strM = String(m).padStart(1, '0');
                const strS = String(s).padStart(2, '0');
                const strMs = String(ms).padStart(2, '0');

                return `${strM}:${strS}.${strMs}`;
            }
        }
    </script>
</body>

</html>