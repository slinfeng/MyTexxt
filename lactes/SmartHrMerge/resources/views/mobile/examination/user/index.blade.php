@extends('layouts.backend')
@section('page_title', 'アカウント')
@section('permission_modify','mobile_modify')
@section('css_append')
    <link rel="stylesheet" href="{{ asset('assets/css/mobileSelect.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lactes.mobile.css') }}">
    <style>
        body{
            background-color: #e9e9e9 !important;
        }
        .content{
            padding-left: 0!important;
            padding-right: 0!important;
        }
        div .content .body{
            font-family: 黑体!important;
        }
        .set-info tr{
            height: 46px;
        }
        .downborder{
            border-bottom: #f1f1f1 1px solid;
        }
        .set-info td{
            padding: 10px 20px;
        }
        .set-info td:nth-of-type(1){
            width: 30%;
        }
        .set-info td:nth-of-type(2){
            width: 10%;
            text-align: center;
        }
        .set-set td{
            padding: 10px 20px;
        }
        .color-darkgrey{
            color: darkgrey;
        }
        .back-color-white{
            margin-bottom: 0.5em;
        }
        input[type=button]{
            border: none;
            background-color: white;
            font-size: 12px;
            color: grey;
        }

        #base-model-select .modal-value span{
            border-bottom: darkgrey 1px solid;
            width: 100%;
            display: inline-block;
            padding: 10px;
            font-weight: 600;
        }
        #base-model-select .modal-body{
            padding: 10px 20px;
        }
        #base-model-select .modal-content{
            margin-left: 5% ;
            width: 90%;
        }
        #base-model-select .modal-button{
            padding-top: 20px;
        }
        .select-span{
            color: red;

        }
        .audioPhoto{
            display: block;
            border-radius: 40px;
            width: 80px;
            height: 80px;
            line-height: 80px;
            font-size: 20pt;
            font-weight: 800;
            background-color: orange;
            color: white;
            margin:0 auto;
            padding-top: 1px;
        }
    </style>
@endsection
@section('content')
    <div class="body">
        <div class="back-color-white mt-4" style="min-height: 120px">
            <table class="w-100">
                <tr>
                    <td class="pt-3">
                        <span class="audioPhoto text-center">
                            審査
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="p-2 pl-4 text-center" style="font-size: 18pt;font-weight: 800;">
                        {{Auth::user()->name}}
                    </td>
                </tr>
                <tr><td class="color-darkgrey p-2 pl-4 pr-3 text-center" style="font-size: 14px;">
                        @foreach(Auth::user()->roles as $role)
                            {{$role->title}}
                        @endforeach
                    </td>
                </tr>
            </table>
        </div>
        <div class="back-color-white">
            <table class="w-100 set-info">
                <tr class="downborder" onclick="location.href='{{route('user.resetPassword')}}'">
                    <td style="width: auto;">
                        {{ __('パスワードを変更') }}
                    </td>
                    <td class="p-0">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                @can($__env->yieldContent('permission_modify'))
                    <tr onclick="location.href='{{route('home')}}'">
                        <td style="width: auto;">
                            {{ __('個人端末に切り替える') }}
                        </td>
                        <td class="p-0">
                            <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                        </td>
                    </tr>
                @endcan

            </table>
        </div>
        <div class="back-color-white">
            <table class="w-100 set-info">
                <tr class="downborder" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    <td class="w-100 text-center" style="">
                        {{ __('ログアウト') }}
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    @include('mobile.examination.footer')
@endsection
@section('footer_append')
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/mobileSelect.min.js') }}"></script>
    <script src="{{ asset('assets/js/lactes.mobile.js') }}"></script>
    <script type="text/javascript">

    </script>
@endsection
