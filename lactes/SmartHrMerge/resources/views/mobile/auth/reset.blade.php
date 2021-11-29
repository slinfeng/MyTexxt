@extends('layouts.authmobileframe')
@section('page_title','ラクテス-リセット')
@section('content')
    <!-- Account Form -->
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <div class="form-group form-focus">
            <input type="email" class="input-content form-control floating @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            <label class="color-bbb focus-label">{{ __('E-Mail Address') }}</label>

            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group form-focus">
            <input id="password" type="password" class="input-content form-control floating @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
            <label class="color-bbb focus-label">新しいパスワード</label>
            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group form-focus">
            <input id="password" type="password" class="input-content form-control floating @error('password') is-invalid @enderror" name="password_confirmation" required autocomplete="current-password">
            <label class="color-bbb focus-label">新しいパスワード（再入力）</label>
        </div>
        <div class="form-group text-center p-btn-auth">
            <button class="btn btn-primary account-btn" type="submit">送信</button>
        </div>
    </form>
@endsection
