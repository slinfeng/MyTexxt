@extends('errors::errorframe')

@section('title', __('Not available').' | '.config('app.name', 'ダッシュボード'))
@section('code', '503')
@section('message')
    <p>{{__('申し訳ありませんが、現在サービスをご利用いただけません。') }}</p>
@endsection
