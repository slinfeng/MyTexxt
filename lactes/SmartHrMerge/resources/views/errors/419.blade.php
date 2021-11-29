@extends('errors::errorframe')


@section('title', __('Page Expired').' | '.config('app.name', 'ダッシュボード'))
@section('code', '419')
@section('message')
    <p>{{__('ページの有効期限が切れました') }}</p>
@endsection
