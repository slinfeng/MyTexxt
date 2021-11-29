<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="{{ config('app.name', '') }}">
    <meta name="author" content="{{ config('app.author', '') }}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title',config('app.name', 'SmartHr'))</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="{{ asset('assets/js/html5shiv.min.js') }}"></script>
    <script src="{{ asset('assets/js/respond.min.js') }}"></script>
    <![endif]-->
    @yield('css_append')
</head>
<body class="account-page">
<!-- Account Logo -->
<div class="account-logo">
    <a href="{{ url('/') }}"><img style="position: absolute;top: 0;padding-left: 27px;"
                                  src="{{ asset('assets/img/logoa.png') }}" alt="{{ config('app.name', 'SmartHr') }}"></a>
</div>
<!-- Main Wrapper -->
<div class="main-wrapper">
    <div class="account-content">

        <div class="container">

            <!-- /Account Logo -->
            @yield('content')

        </div>
    </div>
</div>

<!-- /Main Wrapper -->

<!-- jQuery -->
<script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>

<!-- Bootstrap Core JS -->
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('assets/js/app.js') }}"></script>

</body>
</html>
