<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Lactes') }}</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}">
    <style>
        body{
            background-color: white;
        }
        .form-focus input.input-content{
            border: none;
            border-bottom: 1px solid #bbb;
            width: 100%;
            border-radius: 0;
            background-color: white;
            padding-left: 0;
            padding-right: 0;
        }
        .form-focus .focus-label{
            left: 0;
        }
        .form-focus .form-control:focus ~ .focus-label {
            top: -24px;
        }
        .main-wrapper{
            margin-top: 60px;
        }
        .header{
            border: none;
            font-weight: 800;
            margin: 15px auto;
            height: 30px;
            box-shadow: none;
        }
        .account-box{
            border: none;
            box-shadow: none;
        }
        .account-box label.color-bbb{
            color: #bbb;
        }
        ::-webkit-scrollbar {
            display: none!important;
        }
        .account-page .main-wrapper {
            flex-direction: row;
        }
        .account-title{
            font-size: 16px;
        }
        .div-option{
            display: inline-block;
            font-size: 12px;
            line-height: 3em;
        }
        .div-option:first-child{
            border-right: 1px solid #bbb;
            width:58%
        }
        .account-box .account-btn{
            color: #8e8e8e;
            background: #bbb;
            font-size: 16px;
        }
        .p-btn-auth{
            padding: 0 1.5em;
        }
        .switch-save{
            display: inline-block;
            vertical-align: middle;
        }
        .div-touch{
            margin: 0 auto;
            text-align: center;
            line-height: 4em;
            font-size: 12px;
        }
        .div-presentation{
            margin: 0 auto;
            text-align: center;
            line-height: 1em;
            font-size: 12px;
        }
        .footer{
            font-size: 12px;
            width: 100%;
            position: absolute;
            bottom: 0;
        }
    </style>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
    <script src="{{ asset('assets/js/html5shiv.min.js') }}"></script>
    <script src="{{ asset('assets/js/respond.min.js') }}"></script>
    <![endif]-->
</head>
<body class="account-page position-relative">
<!-- Main Wrapper -->
<div class="main-wrapper">
    <div class="header">
        <h3 class="account-title">@yield('page_title')</h3>
    </div>
    <div class="account-box">
        <div class="account-wrapper">
            @yield('content')
        </div>
    </div>
    <div class="footer">
        <p class="text-center">安心してお使いいただくために</p>
        <p class="text-center">暗証番号は英数字8桁を推奨します</p>
    </div>
</div>
<!-- /Account Logo -->
<!-- /Main Wrapper -->
<!-- jQuery -->
<script src="{{ asset('assets/js/jquery-3.2.1.min.js') }}"></script>
<!-- Bootstrap Core JS -->
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<!-- Custom JS -->
<script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
