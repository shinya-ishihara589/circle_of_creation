<!DOCTYPE html>
<html lang="ja">

<head>
    <title>Canvas基本フォーマット</title>
    <style>
        /* Canvasの枠線を見やすくする */
        canvas {
            border: 1px solid #000;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body style="text-align: center;">
    @vite('resources/js/app.js')
    名前を入力しておくとクリア後に自動入力されます。<br>
    名前入力：<input id="nameInput" type="text" maxlength="20" style="position:absolute;" value="匿名"><br>
    <canvas id="canvas" width="500" height="500"></canvas>
    <script type="module">
        // CanvasのCoreクラスをインスタンス化する
        const canvasRenderer = new CanvasRenderer('canvas');

        // パネルゲームクラスをインスタンス化する
        let panel = new NumberTouchGameManager();

        // Canvasをクリアする
        canvasRenderer.clearAll();

        canvasRenderer.drawRect(100, 200, 300, 100);
        canvasRenderer.drawText(panel.state, 250, 250);

        canvas.addEventListener('click', function(e) {
            // Canvas 内での座標を計算
            const x = e.clientX - canvasRenderer.clientRect.left;
            const y = e.clientY - canvasRenderer.clientRect.top;

            // 1.パネル状態が「READY」の場合はスタートボタンを表示する
            // 2.パネル状態が「PLAYING」の場合はパネルを表示する
            // 3.パネル状態が「CLEARED」の場合はクリアタイムを表示する
            // 4.パネル状態が「GAME_OVER」の場合はゲームオーバーを表示する
            if (panel.state === 'READY') {
                canvasRenderer.clearAll();
                panel.state = 'START';
                panel.pushStart();
                // const gt = new GameTimer();
            }

            if (panel.state === 'START') {
                canvasRenderer.clearAll();
                let panelNums = panel.panelNums;
                // 縦列のパネルの描画
                for (let panelY = 0; panelY < panel.numOfSides; panelY++) {
                    // 横列のパネルの描画
                    for (let panelX = 0; panelX < panel.numOfSides; panelX++) {
                        const startX = (panelX * panel.width);
                        const startY = (panelY * panel.height);
                        let arrNum = panelY * 5 + panelX;
                        canvasRenderer.ctx.strokeRect(startX, startY, panel.width, panel.height);
                        canvasRenderer.ctx.fillText(panelNums[arrNum] + 1, startX + 50, startY + 50);
                    }
                }
                panel.state = 'PLAYING';
            } else if (panel.state === 'PLAYING') {
                let isCorrect = panel.judge(x, y);
                if (isCorrect) {
                    let panelPsiotions = panel.getPos();
                    panel.numCount();
                    canvasRenderer.ctx.clearRect(panelPsiotions.x, panelPsiotions.y, 100, 100);
                }
            }

            if (panel.state === 'CLEARED') {
                let clearTime = panel.clearTime;

                canvasRenderer.clearAll();
                canvasRenderer.ctx.fillText(clearTime, 250, 150);
                canvasRenderer.ctx.fillText('ReStart', 250, 250);
                panel.state = 'START';
                panel = new NumberTouchGameManager(panel.numOfSides);

                let name = $('#nameInput').val();

                $.ajax({
                    url: "/update",
                    method: "POST",
                    data: {
                        name: name,
                        time: clearTime,
                        _token: $('meta[name="csrf-token"]').attr('content')

                    },
                    dataType: "json",
                }).done(function(res) {}).fail(function() {});
            }

            if (panel.state === 'GAME_OVER') {
                canvasRenderer.clearAll();
                canvasRenderer.ctx.strokeRect(100, 200, 300, 100);
                canvasRenderer.ctx.fillText('GAME ORVER', 250, 250);
                canvasRenderer.ctx.pushStart(100, 200, 300, 100);
                panel = new NumberTouchGameManager(panel.numOfSides);
            }
        });
    </script>
    <p>トップ10が表示される予定です。</p>
    @foreach ($rankingData as $no => $ranking)
    <p>{{ $no + 1 }}：{{ $ranking->name }}：{{ $ranking->time }}</p>
    @endforeach
</body>

</html>