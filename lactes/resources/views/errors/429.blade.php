@extends('errors::errorframe')


@section('title', __('Too Many Requests').' | '.config('app.name', 'ダッシュボード'))
@section('code', '429')
@section('message')
    <p>{{__('リクエストが多すぎます') }}</p>
@endsection
