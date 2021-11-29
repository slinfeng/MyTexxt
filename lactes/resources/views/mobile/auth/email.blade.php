@extends('layouts.authmobileframe')
@section('page_title', 'ラクテス-リセット')
@section('content')
    @if(session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col">
                    <label for="email" class="">{{ __('E-Mail Address') }}</label>
                </div>
            </div>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="form-group text-center p-btn-auth">
            <button class="btn btn-primary account-btn" type="submit">送信</button>
        </div>
        <div class="account-footer">
            @if(auth()->check())
                <p><a class="btn btn-link" href="{{ route('home') }}">ホームページへ</a></p>
            @else
                <p><a class="btn btn-link" href="{{ route('login') }}">ログイン画面へ</a></p>
            @endif
        </div>
    </form>
@endsection
