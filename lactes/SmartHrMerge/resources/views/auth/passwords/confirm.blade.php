@extends('layouts.authframe')
@section('title', __('Confirm').' | '.config('app.name', 'SmartHr'))
@section('content')

    <div class="account-box">
        <div class="account-wrapper">
            <h3 class="account-title">{{ __('Confirm Password') }}</h3>
            <p class="account-subtitle">{{ __('Please confirm your password before continuing.') }}</p>


            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf
                <div class="form-group">
                    <div class="row">
                        <div class="col">
                            <label for="password" class="">{{ __('Password') }}</label>
                        </div>
                    </div>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                             <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group text-center">
                    <button class="btn btn-primary account-btn" type="submit"> {{ __('Confirm Password') }}</button>
                </div>
                <div class="account-footer">
                    <p> @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </p>
                </div>
            </form>

        </div>
    </div>
@endsection
