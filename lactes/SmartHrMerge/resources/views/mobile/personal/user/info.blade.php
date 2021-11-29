@extends('layouts.backend')
@section('page_title', 'アカウント')
@section('css_append')
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select.dataTables.min.css') }}">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">

    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/css/datatables/fixedHeader.dataTables.min.css') }}">

    <!-- daterangepicker CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lactes.mobile.css') }}">
    <style type="text/css">
        ul{
            list-style-type:none;
        }
    </style>
@endsection
@section('content')
    <!-- Account Form -->
    <form method="POST" action="{{ route('user.infoUpdate') }}">
        @csrf
        <div class="form-group form-focus">
            <input type="text" class="input-content form-control floating @error('name') is-invalid @enderror" name="name" value="{{isset($user)?$user->name:''}}" required autocomplete="name" autofocus>
            <label class="color-bbb focus-label">{{ __('名前') }}</label>

            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group text-center p-btn-auth">
            <button class="btn btn-primary account-btn" type="submit">名前を再設定する</button>
        </div>
    </form>
    @include('mobile.personal.footer')
@endsection
@section('footer_append')
    <!-- Datatable JS -->
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Slimscroll JS -->
    <script src="{{asset('assets/js/jquery.slimscroll.min.js')}}"></script>

    <!-- Select2 JS -->
    <script src="{{asset('assets/js/select2.min.js')}}"></script>

    <!-- daterangepicker JS -->
    <script src="{{asset('assets/js/moment.min.js')}}"></script>
    <script src="{{asset('assets/js/daterangepicker.min.js')}}"></script>
    <script src="{{asset('assets/js/laydate.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>

    <script src="{{ asset('assets/js/dataTables.select.min.js') }}"></script>

    <script src="{{ asset('assets/js/datatables/dataTables.fixedHeader.min.js') }}"></script>

    <script src="{{ asset('assets/js/bootstrap-notify-3.1.3/bootstrap-notify.min.js') }}"></script>
    <script src="{{asset('assets/js/leaves.js')}}"></script>
    <script src="{{ asset('assets/js/lactes.mobile.js') }}"></script>
    <script type="text/javascript">


    </script>
@endsection
