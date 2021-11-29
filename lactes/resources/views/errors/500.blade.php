@extends('errors::errorframe')

@section('title', __('Server Error 500').' | '.config('app.name', 'ダッシュボード'))
@section('code', '500')
@section('message')
    <p>{{__('申し訳ありませんが、サーバーで内部エラーが発生しました。') }}</p>
@endsection
