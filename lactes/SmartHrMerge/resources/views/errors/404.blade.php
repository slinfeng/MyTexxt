@extends('errors::errorframe')

@section('title', __('Not Found').' | '.config('app.name', 'ダッシュボード'))
@section('code', '404')
@section('message')
    <p>{{__('申し訳ありませんが、お探しのページが見つかりませんでした。') }}</p>
@endsection
