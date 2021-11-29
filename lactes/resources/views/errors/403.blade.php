@extends('errors::errorframe')


@section('title', __('Forbidden').' | '.config('app.name', 'ダッシュボード'))
@section('code', '403')
@section('message')
    <p>{{__($exception->getMessage() ?: '申し訳ありませんが、このページにアクセスする権限がありません。') }}</p>
@endsection
