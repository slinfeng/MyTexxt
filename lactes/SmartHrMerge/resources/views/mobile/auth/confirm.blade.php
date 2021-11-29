@extends('layouts.authmobileframe')
@section('page_title', 'ラクテス-コンファーム')
@section('content')
<form method="POST" action="{{ route('password.confirm') }}">
    @csrf
    <div class="form-group">
        <div class="row">
            <div class="col">
                <label for="password" class="">{{ __('Password') }}</label>
            </div>
        </div>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="off">

        @error('password')
        <span class="invalid-feedback" role="alert">
             <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group text-center p-btn-auth">
        <button class="btn btn-primary account-btn" type="submit"> {{ __('Confirm Password') }}</button>
    </div>
    <div class="account-footer">
        <p><a class="text-muted" href="{{ route('mobile.login') }}">ログイン画面へ移行する</a></p>
    </div>
</form>
@endsection
