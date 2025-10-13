@foreach ($comments as $comment)
{{ $comment->name }}：{{ $comment->comment }}：{{ $comment->created_at }}<br>
@endforeach

<form action="/comments" method="POST">
    @csrf
    <lavel for="name">名前：</lavel>
    <input type="text" name="name" id="name"><br>
    <lavel for="comment">コメント：</lavel>
    <input type="text" name="comment" id="comment"><br>
    <button type="submit">送信</button>
</form>