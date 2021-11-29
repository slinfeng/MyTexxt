@extends('errors::errorframe')

@section('title', __('Unauthorized').' | '.config('app.name', 'ダッシュボード'))
@section('code', '401')
@section('message')
    <h3><i class="fa fa-warning"></i>{{ __('おっとっと！ ページが見つかりません！') }}</h3>
    <p>{{ __('あなたが要求したページは見つかりませんでした。') }}</p>
@endsection
