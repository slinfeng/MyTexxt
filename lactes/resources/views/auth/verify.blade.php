@extends('layouts.authframe')
@section('title', __('Verify').' | '.config('app.name', 'SmartHr'))
@section('content')

    <div class="account-box">
        <div class="account-wrapper">
            <h3 class="account-title">{{ __('Verify Your Email Address') }}</h3>
            <p class="account-subtitle">
                {{ __('Before proceeding, please check your email for a verification link.') }}
                {{ __('If you did not receive the email') }},
            </p>
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif
            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
            </form>
        </div>
    </div>
@endsection
