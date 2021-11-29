@extends('layouts.authframe')
@section('title', __('Reset Password').' | '.config('app.name', 'SmartHr'))
@section('content')


    <div class="account-box">
        <div class="account-wrapper">
            <h3 class="account-title">{{ __('Reset Password') }}</h3>

            <!-- Account Form -->
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group">
                    <label for="email" >{{ __('E-Mail Address') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password" class="">{{ __('Password') }}</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password-confirm" class="">{{ __('Confirm Password') }}</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                </div>
                <div class="form-group text-center">
                    <button class="btn btn-primary account-btn" type="submit"> {{ __('Reset Password') }}</button>
                </div>
                <div class="account-footer">

                </div>
            </form>
            <!-- /Account Form -->
        </div>
    </div>
@endsection
