@extends('layouts.authframe')
@section('title', __('Reset Password').' | '.config('app.name', 'SmartHr'))
@section('css_append')
    <style>
        h3.account-title{
            margin-bottom: 1em;
        }
    </style>
@endsection
@section('content')
    <div class="account-box">
        <div class="account-wrapper">
            <h3 class="account-title">{{ __('Reset Password') }}</h3>
            <!-- Account Form -->
            <form method="POST" action="{{ route('user.passwordUpdate') }}">
                @csrf
                <div class="form-group">
                    <label>現在のパスワード</label>
                    <input type="password"
                           class="form-control @error('old_password') is-invalid @enderror"
                           name="old_password" value="{{ old('old_password') }}" required autocomplete="old_password"
                           autofocus>
                    @error('old_password')
                    <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>新しいパスワード</label>
                    <input id="password" type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           name="password" required autocomplete="current-password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>新しいパスワード（再入力）</label>
                    <input id="password" type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           name="password_confirmation" required autocomplete="current-password">
                </div>
                <div class="form-group text-center">
                    <button class="btn btn-primary account-btn" type="submit">送信</button>
                </div>
                <div class="account-footer">
                    <p><a class="btn btn-link" href="{{ route('home') }}">ホームページへ</a></p>
                </div>
            </form>
            <!-- /Account Form -->
        </div>
    </div>
@endsection
