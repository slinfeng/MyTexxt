@extends('errors::errorframe')

@section('title', __('Server Upgrade').' | '.config('app.name', 'ダッシュボード'))
@section('code', '426')
@section('message')
    <p>申し訳ございませんが、サーバーをアップグレードしております。</p>
    <p>少々お待ちください。</p>
@endsection
