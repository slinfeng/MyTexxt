@extends('layouts.authframe')
@section('title', __('Reset Password').' | '.config('app.name', 'SmartHr'))
@section('content')

    <div class="account-box">
        <div class="account-wrapper">
            <h3 class="account-title">{{ __('Reset Password') }}</h3>

            @if (session('status'))
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

                <div class="form-group text-center">
                    <button class="btn btn-primary account-btn" type="submit"> {{ __('Send Password Reset Link') }}</button>
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
