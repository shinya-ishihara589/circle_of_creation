<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="bg-info-subtle mt-3 p-3 rounded-lg">
                <p class="h1 text-center link-body-emphasis">Circle of Creation</p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="bg-info-subtle p-5 col-5 rounded-lg">
                <div>
                    自分のアイコン
                </div>
                <div>
                    プロフィール
                </div>
                <div>
                    想い人
                </div>
                <div>
                    想われ人
                </div>
            </div>
            <div class="bg-info-subtle ml-3 p-1 col-7 rounded-lg">
                @foreach ($comments as $comment)
                <div class="bg-info p-1 m-1 rounded-lg">
                    <div class="row">
                        <div class="bg-dirk col-5 rounded-lg">
                            アイコン
                        </div>
                        <div class="bg-dirk col-7 rounded-lg">
                            {!! nl2br(e($comment->comment)) !!}
                        </div>
                    </div>
                    <div class="text-end">
                        {{ $comment->created_at->format('Y/m/d H:m:d') }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</body>

</html>


<form action="/" method="POST">
    @csrf
    <lavel for="name">名前：</lavel>
    <input type="text" name="name" id="name"><br>
    <lavel for="comment">コメント：</lavel>
    <textarea type="text" name="comment" id="comment"></textarea><br>
    <button type="submit">送信</button>
</form>