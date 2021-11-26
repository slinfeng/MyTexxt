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
          ::-webkit-scrollbar {
              display: none!important;
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

            @if (Route::has('login'))
                <div class="text-right mt-2" style="padding-right: 20px">
                    @auth
                        <a href="{{ url('/home') }}" class="btn btn-primary">{{ __('Home') }}</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">{{ __('Login') }}</a>

{{--                        @if (Route::has('register'))--}}
{{--                            <a href="{{ route('register') }}" class="btn btn-primary">{{ __('Register') }}</a>--}}
{{--                        @endif--}}
                    @endif
                </div>
            @endif


        </div>


        <div class="account-content">


            <div class="container">

                <!-- Account Logo -->
                <div>
                    <a href="{{route('home')}}" class="logo">
                        <img src="{{ asset('assets/img/logo_home.png') }}" alt="{{ config('app.name', 'Lactes') }}">
                    </a>
                </div>

            </div>
        </div>
    </div>
    <!-- /Account Logo -->
    <div style="width: 250px;right: 30px;top: 30px;color: #585858" class="text-right mt-5 position-absolute">
        <p>SES管理システム　ラクテス v1.0</p>
    </div>
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
