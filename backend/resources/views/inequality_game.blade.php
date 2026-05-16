<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>不等号ゲーム - Brain Equalizer</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        #game-container {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 90%;
            max-width: 450px;
        }

        .stats {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: bold;
            color: #555;
            margin-bottom: 20px;
        }

        #formula-container {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 28px;
            font-weight: bold;
            min-height: 100px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 30px;
            border: 2px solid #e9ecef;
        }

        .expr {
            width: 35%;
        }

        #sign-box {
            width: 20%;
            color: #007bff;
            background: #e7f1ff;
            border-radius: 6px;
            padding: 5px 0;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        button {
            flex: 1;
            padding: 15px;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            border-radius: 8px;
            background-color: #007bff;
            color: white;
            transition: background 0.2s, transform 0.1s;
        }

        button:hover {
            background-color: #0056b3;
        }

        button:active {
            transform: scale(0.95);
        }

        #start-screen,
        #end-screen {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-radius: 16px;
            z-index: 10;
        }

        .hidden {
            display: none !important;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .score-display {
            font-size: 48px;
            color: #28a745;
            font-weight: bold;
            margin: 20px 0;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <div id="game-container" style="position: relative;">
        <!-- スタート画面 -->
        <div id="start-screen">
            <h1>不等号ゲーム</h1>
            <p>30秒で2つの式を比べて記号を選べ！</p>
            <button onclick="startGame()" style="flex:none; width:200px; font-size:18px;">ゲームスタート</button>
        </div>

        <!-- リザルト画面 -->
        <div id="end-screen" class="hidden">
            <h1>ゲーム終了！</h1>
            <p>あなたのスコア</p>
            <div id="final-score" class="score-display">0</div>
            <button onclick="startGame()" style="flex:none; width:200px; font-size:18px;">もう一度プレイ</button>
        </div>

        <!-- ゲームプレイ画面 -->
        <div class="stats">
            <div>残り時間: <span id="timer">30</span>秒</div>
            <div>スコア: <span id="score">0</span></div>
        </div>

        <div id="formula-container">
            <div id="left-expr" class="expr">--</div>
            <div id="sign-box">？</div>
            <div id="right-expr" class="expr">--</div>
        </div>

        <div class="btn-container">
            <button onclick="checkAnswer('>')">＞</button>
            <button onclick="checkAnswer('=')">＝</button>
            <button onclick="checkAnswer('<')">＜</button>
        </div>
    </div>

    <script>
        let score = 0;
        let timeLeft = 30;
        let timerInterval;
        let correctAnswer = '';

        function generateFormula() {
            // スコアが上がるほど数字を大きくする
            const maxNum = 10 + Math.floor(score / 3) * 5;
            const ops = ['+', '-'];

            function makeExpr() {
                const n1 = Math.floor(Math.random() * maxNum) + 1;
                const n2 = Math.floor(Math.random() * maxNum) + 1;
                const op = ops[Math.floor(Math.random() * ops.length)];
                const val = op === '+' ? n1 + n2 : Math.max(1, n1 - n2);
                return {
                    text: `${op === '+' ? n1 : Math.max(n1, n2)} ${op} ${op === '+' ? n2 : Math.min(n1, n2)}`,
                    val: val
                };
            }

            const left = makeExpr();
            const right = makeExpr();

            // 稀に全く同じにして「＝」を作る調整
            if (Math.random() < 0.2) {
                right.val = left.val;
                right.text = left.text;
            }

            document.getElementById('left-expr').innerText = left.text;
            document.getElementById('right-expr').innerText = right.text;
            document.getElementById('sign-box').innerText = '？';

            if (left.val < right.val) correctAnswer = '<';
            else if (left.val > right.val) correctAnswer = '>';
            else correctAnswer = '=';
        }

        function checkAnswer(playerAnswer) {
            if (timeLeft <= 0) return;

            if (playerAnswer === correctAnswer) {
                score++;
                document.getElementById('score').innerText = score;
                generateFormula();
            } else {
                // お手つき：少し画面を赤くするなどの演出用（今回は一瞬ストップのみ）
                score = Math.max(0, score - 1);
                document.getElementById('score').innerText = score;
                generateFormula();
            }
        }

        function startGame() {
            score = 0;
            timeLeft = 1;
            document.getElementById('score').innerText = score;
            document.getElementById('timer').innerText = timeLeft;
            document.getElementById('start-screen').classList.add('hidden');
            document.getElementById('end-screen').classList.add('hidden');

            generateFormula();

            clearInterval(timerInterval);
            timerInterval = setInterval(() => {
                timeLeft--;
                document.getElementById('timer').innerText = timeLeft;
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    endGame();
                }
            }, 1000);
        }

        function endGame() {
            document.getElementById('final-score').innerText = score;
            document.getElementById('end-screen').classList.remove('hidden');

            // ランキング更新 (既存のAJAX処理)
            $.ajax({
                url: "/inequality-game",
                method: "POST",
                data: {
                    name: name,
                    _token: document.querySelector('meta[name="csrf-token"]').content
                },
                dataType: "json",
            }).done(function() {});

        }
    </script>

</body>

</html>
