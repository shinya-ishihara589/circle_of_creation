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
        /* ゲームボード全体のコンテナ */
        #game-container {
            position: relative;
            width: 100%;
            max-width: 500px;
            aspect-ratio: 1 / 1;
            background: #fff;
            border: 1px solid #ccc;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-template-rows: repeat(5, 1fr);
            gap: 2px;
            user-select: none;
            touch-action: manipulation;
            overflow: hidden;
        }

        /* 数字パネルのスタイル */
        .number-node {
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            font-weight: bold;
            background: #f8f9fa;
            border: 1px solid #eee;
            cursor: pointer;
            transition: background 0.1s;
        }

        /* タップした瞬間のフィードバック（スマホでの操作感を向上） */
        .number-node:active {
            background: #e0e0e0;
        }

        /* スタート画面・クリア画面のオーバーレイ */
        .overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.9);
            z-index: 10;
        }

        #start-btn {
            padding: 20px 60px;
            font-size: 40px;
            border: 2px solid #000;
            background: #fff;
            cursor: pointer;
        }

        /* 中央に浮かぶオーバーレイ */
        .game-overlay {
            position: absolute;
            inset: 0;
            background: rgba(248, 249, 250, 1.0);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 100;
            /* 数字パネル(1)より圧倒的に高い数値にする */
            padding: 20px;
            text-align: center;
        }

        /* ルール説明のスタイル */
        .game-rules {
            margin-bottom: 30px;
            color: #555;
        }

        .game-rules h3 {
            color: #333;
            margin-bottom: 15px;
        }

        .game-rules ul {
            list-style: none;
            padding: 0;
            font-size: 0.9rem;
        }

        /* STARTボタンを今風のデザインに */
        #start-btn {
            padding: 15px 50px;
            font-size: 24px;
            font-weight: bold;
            color: #fff;
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        #start-btn:active {
            transform: scale(0.95);
            box-shadow: 0 2px 5px rgba(0, 123, 255, 0.2);
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

                <!-- ゲームエリア -->
                <div id="game-container">
                    <!-- ここにJSで .number-node が25個生成される -->

                    <!-- スタート画面（初期状態で表示） -->
                    <div id="start-screen" class="game-overlay">
                        <div class="game-rules">
                            <h3>ナンバータッチ</h3>
                            <ul>
                                <li>1から順に25までタップ！</li>
                                <li>ミスしてもペナルティはありません</li>
                                <li>最速タイムを目指そう！</li>
                            </ul>
                        </div>
                        <button id="start-btn">GAME START</button>
                    </div>
                </div>
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
        // 各クラスの読み込み（CanvasRendererとInputManagerは不要になりました）
        const timer = new Timer();
        let panel = new NumberTouchGameManager(5);

        const startScreen = document.getElementById('start-screen');
        const gameContainer = document.getElementById('game-container');
        // ゲーム開始ボタンのイベント
        document.getElementById('start-btn').addEventListener('pointerdown', () => {
            startScreen.style.display = 'none'; // ルール画面を消す

            // ゲームコンテナを「空」にしてから数字を並べる
            startGame();
        });

        /**
         * ゲーム画面の描画更新（CanvasRendererの代わり）
         */
        function render() {
            // パネルを描画する前にコンテナをクリア
            // ただし、start-screen（オーバーレイ）は消したくないので、
            // パネル専用のサブコンテナを作るか、以下のようにパネルだけを消去します。
            const oldNodes = gameContainer.querySelectorAll('.number-node');
            oldNodes.forEach(n => n.remove());

            panel.panels.forEach((num) => {
                const node = document.createElement('div');
                node.className = 'number-node';
                node.innerText = num + 1;

                node.onpointerdown = () => {
                    if (panel.checkAnswer(num)) {
                        node.style.visibility = 'hidden';
                        if (panel.isCleared) finishGame();
                    }
                };
                gameContainer.appendChild(node);
            });
        }

        /**
         * ゲーム開始処理
         */
        function startGame() {
            panel.initGame();
            panel.state = 'PLAYING';
            timer.start((timeString) => {
                timerDisplay.innerText = timeString;
            });
            render();
        }

        /**
         * クリア処理
         */
        function finishGame() {
            timer.stop();
            const finalTime = timer.formattedTime;
            const name = document.getElementById('nameInput').value;

            // クリア画面の表示
            gameContainer.innerHTML = `
            <div class="overlay">
                <div class="text-center">
                    <h3>CLEAR! ${finalTime}</h3>
                    <button class="btn btn-primary mt-3" id="start-btn" onclick="location.reload()">ReStart</button>
                </div>
            </div>
        `;

            // ランキング更新 (既存のAJAX処理)
            $.ajax({
                url: "/ranking/update",
                method: "POST",
                data: {
                    name: name,
                    time: finalTime,
                    _token: document.querySelector('meta[name="csrf-token"]').content
                },
                dataType: "json",
            }).done(function(res) {
                let html = '';
                res.forEach((data, index) => {
                    html += `<p>${index + 1}位：${data.name}：${data.time}</p>`;
                });
                $('#ranking-list').html(html);
            });
        }

        // 初回実行
        render();
    </script>
</body>

</html>
