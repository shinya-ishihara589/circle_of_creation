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
</head>

<body class="bg-light d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 text-center">
                        <h1 class="h4 mb-4 fw-bold">ナンバータッチゲームゲーム一覧</h1>

                        <!-- ボタン一覧 -->
                        <div class="d-grid gap-3">
                            <a href="/game/2" class="btn btn-outline-primary btn-lg">2 × 2</a>
                            <a href="/game/3" class="btn btn-outline-primary btn-lg">3 × 3</a>
                            <a href="/game/4" class="btn btn-outline-primary btn-lg">4 × 4</a>
                            <a href="/game/5" class="btn btn-outline-primary btn-lg">5 × 5</a>
                        </div>
                        <h1 class="h4 mt-5 mb-4 fw-bold">不等号ゲーム</h1>
                        <!-- ボタン一覧 -->
                        <div class="d-grid gap-3">
                            <a href="/inequality-game" class="btn btn-outline-primary btn-lg">不等号ゲーム</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
