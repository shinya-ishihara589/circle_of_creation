{{-- レイアウトの呼び出し --}}
@extends('layouts.default')

{{-- へッドの呼び出し --}}
@section('commons.head')
@include('commons.head')
@endsection

{{-- コンテンツの呼び出し --}}
@section('contents')

{{-- ナビゲーションの呼び出し --}}
@include('commons.nav')

{{-- ネタ帳 --}}


{{--アサイドの呼び出し --}}
@include('commons.aside')
@endsection

{{--フットの呼び出し--}}
@section('commons.foot')
@include('commons.foot')
@endsection