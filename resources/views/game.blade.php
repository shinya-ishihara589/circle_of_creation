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
    <canvas id="myCanvas" width="500" height="500"></canvas>

    <script>
        /**
         * ステータス
         * READY：スタートボタンの表示
         * PLAYING：パネル表示
         * CLEARED：クリアタイムの表示：実施
         * GAME_OVER：ゲームオーバーとリスタートボタンの表示
         */

        /**
         * パネルのクラス
         * クリックの制御
         * 最小値の場合はTrue、それ以外の場合はFalse
         * 
         * 課題：setter getterを使用する
         *  -    set num(), get num ()など
         */
        class Panel {
            #num = 0;
            #width = 100;
            #height = 100;
            #numOfSides;
            #numOfPanel;
            #panelNums;

            #startTime;
            #endTime;

            #state = 'READY';

            #GAME_STATES = {
                READY: 'READY',
                PLAYING: 'PLAYING',
                CLEARED: 'CLEARED',
                GAME_OVER: 'GAME_OVER',
            };

            constructor(numOfSides) {
                this.#numOfSides = numOfSides;
                this.#numOfPanel = numOfSides * numOfSides;

                this.#panelNums = Array.from({
                    length: this.#numOfPanel
                }, (_, i) => i);

                for (let i = this.#panelNums.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * i);
                    [this.#panelNums[i], this.#panelNums[j]] = [this.#panelNums[j], this.#panelNums[i]];
                }
            }

            pushStart() {
                this.#startTime = new Date();
            }

            /**
             * 当たり判定
             * @return Integer
             */
            judge(clickPosX, clickPosY) {
                let panelNum = this.getNumForPos(clickPosX, clickPosY);
                let isCorrect = false;
                // 1.
                if (this.#num === this.#panelNums[panelNum]) {
                    isCorrect = true;
                }

                if (this.#panelNums[panelNum] + 1 === this.#numOfPanel) {
                    this.#endTime = new Date();
                    this.#state = this.#GAME_STATES.CLEARED;
                }
                return isCorrect;
            }

            /**
             * 列数×辺数+行数=パネル番号
             * パネル番号の配列番号を取得する
             */
            getNumForPos(posX, posY) {
                // X軸からXのパネル列を取得する
                // バグあり：ギリギリで取得すると0と6が存在する
                let panelCol = Math.floor(posX / 100);
                let panelRow = Math.floor(Math.floor(posY / 100) % this.#numOfSides);
                // 計算方法を修正する
                let num = panelRow * this.#numOfSides + panelCol;
                return num;
            }

            getPos() {
                const arrKey = this.#panelNums.indexOf(this.#num);
                let x = (arrKey % this.#numOfSides) * this.#width;
                let y = Math.floor(arrKey / this.#numOfSides) * this.#height;
                return {
                    x: x,
                    y: y
                };
            }
            numCount() {
                this.#num++;
            }

            get num() {
                return this.#num;
            }

            get panelNums() {
                return this.#panelNums;
            }

            get width() {
                return this.#width;
            }

            get height() {
                return this.#height;
            }

            get state() {
                return this.#state;
            }

            get clearTime() {
                let ms = this.#endTime - this.#startTime
                const totalSeconds = ms / 1000;
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = Math.floor(totalSeconds % 60);
                const centiseconds = Math.floor((totalSeconds - Math.floor(totalSeconds)) * 100);

                // 秒と小数点以下はゼロ埋め
                const s = String(seconds).padStart(2, '0');
                const cs = String(centiseconds).padStart(2, '0');

                return `${minutes}:${s}.${cs}`;
            }
        }

        // パネルの表示
        const numOfSides = 5;
        const panel = new Panel(numOfSides);

        let canvas = document.getElementById("myCanvas"); // 取得
        let ctx = canvas.getContext("2d"); // コンテキスト取得
        const rect = canvas.getBoundingClientRect();

        ctx.font = "50px Arial sans-serif";
        ctx.fillStyle = "black";
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle'

        let panelNums = panel.panelNums;
        // 縦列のパネルの描画
        for (let panelY = 0; panelY < numOfSides; panelY++) {
            // 横列のパネルの描画
            for (let panelX = 0; panelX < numOfSides; panelX++) {
                const startX = (panelX * panel.width);
                const startY = (panelY * panel.height);
                let arrNum = panelY * 5 + panelX;
                ctx.strokeRect(startX, startY, panel.width, panel.height);
                ctx.fillText(panelNums[arrNum] + 1, startX + 50, startY + 60);
            }
        }

        canvas.addEventListener('click', function(e) {
            // Canvas 内での座標を計算
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            // 1.パネル状態が「READY」の場合はスタートボタンを表示する
            // 2.パネル状態が「PLAYING」の場合はパネルを表示する
            // 3.パネル状態が「CLEARED」の場合はクリアタイムを表示する
            // 4.パネル状態が「GAME_OVER」の場合はゲームオーバーを表示する
            if (panel.state === 'READY') {
                ctx.clearRect(0, 0, 500, 500);
                ctx.strokeRect(100, 190, 300, 100);
                ctx.fillText('スタート', 250, 250);
                ctx.pushStart(100, 190, 300, 100);
            } else if (panel.state === 'PLAYING') {
                let isCorrect = panel.judge(x, y);
                if (isCorrect) {
                    let panelPsiotions = panel.getPos();
                    panel.numCount();
                    ctx.clearRect(panelPsiotions.x, panelPsiotions.y, 100, 100);
                }
            } else if (panel.state === 'CLEARED') {
                ctx.clearRect(0, 0, 500, 500);
                ctx.fillText(panel.clearTime, 100, 100);
            } else if (panel.state === 'GAME_OVER') {

            }
        });
    </script>
</body>

</html>