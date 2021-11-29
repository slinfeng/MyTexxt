@extends('layouts.backend')
@section('page_title', 'アカウント')
@section('css_append')
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lactes.mobile.css') }}">
    <style type="text/css">
        ul{
            list-style-type:none;
        }
        .account-box .account-btn{
            color: #8e8e8e;
            font-size: 16px!important;
        }
        .p-btn-auth{
            padding: 0 1.5em;
        }
    </style>
@endsection
@section('content')
    <div class="account-box">
        <div class="account-wrapper">
            <!-- Account Form -->
            <form method="POST" action="{{ route('user.passwordUpdate') }}">
                @csrf
                <div class="form-group form-focus">
                    <input type="password" class="input-content form-control floating @error('old_password') is-invalid @enderror" name="old_password" value="{{ old('old_password') }}" required autocomplete="old_password" autofocus>
                    <label class="color-bbb focus-label">現在のパスワード</label>
                    @error('old_password')
                    <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
                    @enderror
                </div>
                <br>
                <div class="form-group form-focus">
                    <input id="password" type="password" class="input-content form-control floating @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    <label class="color-bbb focus-label">新しいパスワード</label>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
                    @enderror
                </div>
                <br>
                <div class="form-group form-focus">
                    <input id="password" type="password" class="input-content form-control floating @error('password') is-invalid @enderror" name="password_confirmation" required autocomplete="current-password">
                    <label class="color-bbb focus-label">新しいパスワード（再入力）</label>
                </div>
                <br>
                <p>6文字以上16文字以内の半角英数字で入力してください。</p>
                <p><a href="{{ route('password.request') }}">パスワードを忘れた場合</a></p>
                <div class="form-group text-center p-btn-auth">
                    <button class="btn btn-primary account-btn" type="submit">送信</button>
                </div>
                <div class="account-footer">

                </div>
            </form>
        </div>
    </div>
{{--    @include('mobile.personal.footer')--}}
@endsection
