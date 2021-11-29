@extends('layouts.authframe')
@section('title', __('Login').' | '.config('app.name', 'SmartHr'))
@section('content')
    <div class="account-box">
        <div class="account-wrapper">
            <h3 class="account-title">{{ __('Login') }}</h3>
            <p class="account-subtitle">{{ __('Access to our dashboard') }}</p>
            @if (session('loginMessage'))
                <div class="alert alert-danger" role="alert">
                    {{session('loginMessage')}}
                </div>
        @endif
            <!-- Account Form -->
            <form method="POST" action="{{ route('login') }}" onsubmit="disableSubmit()">
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

                <div class="form-group">
                    <div class="row">
                        <div class="col">
                            <label for="password" class="">{{ __('Password') }}</label>
                        </div>
                        <div class="col-auto">
                            @if (Route::has('password.request'))
                                <a class="text-muted" href="{{ route('password.request') }}">
                                    {{ __('Forgot password?') }}
                                </a>
                            @endif
                        </div>
                    </div>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                               <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>

                        </div>
                    </div>
                </div>

                <div class="form-group text-center">
                    <button class="btn btn-primary account-btn" type="submit">{{ __('Login') }}</button>
                </div>
                @if(false)
                <div class="account-footer">
                    <p>{{ __("Don't have an account yet?") }}<a href="{{ route('register') }}">{{ __('Register') }}</a></p>
                </div>
                    @endif
            </form>
            <!-- /Account Form -->
        </div>
    </div>
    <script>
        function disableSubmit() {
            $('.account-btn').css('cursor','wait').prop('disabled',true);
        }
    </script>
@endsection
