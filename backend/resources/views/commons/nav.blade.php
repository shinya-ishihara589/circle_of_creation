@section('commons.nav')
<nav class="d-flex p-3 sticky-top">
    {{-- メニュー一覧 --}}
    <ul class="nav flex-column gap-1">
        <li class="nav-item"><a href="/profile" class="nav-link"><i class="bi bi-house-door-fill me-2"></i>プロフィール</a></li>
        <li class="nav-item"><a href="/brainstorming" class="nav-link"><i class="bi bi-lightbulb-fill me-2"></i>ネタ帳</a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-journals me-2"></i>シナリオ</a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-image-fill me-2"></i>イラスト</a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-images me-2"></i>コミック</a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-book-half me-2"></i>ノベル</a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-display-fill me-2"></i>ゲーム</a></li>
    </ul>
</nav>
@endsection