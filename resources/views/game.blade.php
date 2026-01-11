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
</head>

<body>
    @vite('resources/js/app.js')
    <canvas id="canvas" width="500" height="500"></canvas>
    <script type="module">
        const canvasRenderer = new CanvasRenderer('canvas');

        // パネルの表示
        const numOfSides = 5;
        let panel = new NumberTouchGameManager(numOfSides);

        const rect = canvasRenderer.canvas.getBoundingClientRect();

        canvasRenderer.ctx.clearRect(0, 0, 500, 500);
        canvasRenderer.ctx.strokeRect(100, 190, 300, 100);
        canvasRenderer.ctx.fillText('Start', 250, 250);
        // canvasRenderer.ctx.pushStart(100, 190, 300, 100);

        canvas.addEventListener('click', function(e) {
            // Canvas 内での座標を計算
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            // 1.パネル状態が「READY」の場合はスタートボタンを表示する
            // 2.パネル状態が「PLAYING」の場合はパネルを表示する
            // 3.パネル状態が「CLEARED」の場合はクリアタイムを表示する
            // 4.パネル状態が「GAME_OVER」の場合はゲームオーバーを表示する
            if (panel.state === 'READY') {
                canvasRenderer.ctx.clearRect(0, 0, 500, 500);
                panel.state = 'START';
                panel.pushStart();
            }

            if (panel.state === 'START') {
                canvasRenderer.ctx.clearRect(0, 0, 500, 500);
                let panelNums = panel.panelNums;
                // 縦列のパネルの描画
                for (let panelY = 0; panelY < numOfSides; panelY++) {
                    // 横列のパネルの描画
                    for (let panelX = 0; panelX < numOfSides; panelX++) {
                        const startX = (panelX * panel.width);
                        const startY = (panelY * panel.height);
                        let arrNum = panelY * 5 + panelX;
                        canvasRenderer.ctx.strokeRect(startX, startY, panel.width, panel.height);
                        canvasRenderer.ctx.fillText(panelNums[arrNum] + 1, startX + 50, startY + 60);
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
                canvasRenderer.ctx.clearRect(0, 0, 500, 500);
                canvasRenderer.ctx.fillText(panel.clearTime, 250, 150);
                canvasRenderer.ctx.fillText('ReStart', 250, 250);
                panel.state = 'START';
                panel = new NumberTouchGameManager(numOfSides);
            }

            if (panel.state === 'GAME_OVER') {
                canvasRenderer.ctx.clearRect(0, 0, 500, 500);
                canvasRenderer.ctx.strokeRect(100, 190, 300, 100);
                canvasRenderer.ctx.fillText('GAME ORVER', 250, 250);
                canvasRenderer.ctx.pushStart(100, 190, 300, 100);
                panel = new NumberTouchGameManager(numOfSides);
            }
        });
    </script>
</body>

</html>