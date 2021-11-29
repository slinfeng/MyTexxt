@extends('layouts.authmobileframe')
@section('page_title', 'ラクテス-ログイン')
@section('content')
    @if (session('loginMessage'))
        <div class="alert alert-danger" role="alert">
            {{session('loginMessage')}}
        </div>
    @endif
    <!-- Account Form -->
    <form method="POST" action="{{ route('login') }}" onsubmit="disableSubmit()">
        @csrf
        <div class="form-group form-focus">
            <input type="email" class="input-content form-control floating @error('email') is-invalid @enderror" name="email" value="{{ old('email',$id) }}" required autocomplete="email" autofocus>
            <label class="color-bbb focus-label">Eメール</label>
            @error('email')
            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
            @enderror
        </div>
        <div class="form-group form-focus">
            <input id="password" type="password" class="input-content form-control floating @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
            <label class="color-bbb focus-label">パスワード</label>
            @error('password')
            <span class="invalid-feedback" role="alert">
                       <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group row">
            <div class="col-md-12">
                <div class="div-option">
                    <a class="text-muted" href="{{ route('password.request') }}">
                        パスワードをお忘れの場合
                    </a>
                </div>
                <div class="div-option text-muted">
                    <span>　Eメール保存　</span>
                    <div class="status-toggle switch-save">
                        <input type="checkbox" @if(isset($id))checked @endif id="switch_annual1" name="save_id" class="check">
                        <label for="switch_annual1" class="checktoggle"></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group text-center p-btn-auth">
            <button class="btn btn-primary account-btn" type="submit">{{ __('Login') }}</button>
        </div>
        @if(false)
            <div class="account-footer">
                <p>{{ __("Don't have an account yet?") }}<a href="{{ route('register') }}">{{ __('Register') }}</a></p>
            </div>
        @endif
        <div class="form-group row">
            <div class="col-8 div-presentation">
                <p>ご利用上の注意</p>
                <p>個人情報の利用目的</p>
            </div>
        </div>
    </form>
    <script>
        function disableSubmit() {
            $('.account-btn').css('cursor','wait').prop('disabled',true);
        }
    </script>
@endsection
