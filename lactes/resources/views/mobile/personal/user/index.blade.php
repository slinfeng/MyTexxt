@extends('layouts.backend')
@section('page_title', 'マイページ')
@section('permission_modify','mobile_audit')
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
    </style>
@endsection
@section('content')
    <div class="body">
        <div class="back-color-white">
            <table class="w-100">
                <tr>
                    <td rowspan="3" style="width:40%;padding: 10px 10px 0 20px">
                        <img class="w-100 m-b-10"  alt="" src="{{$base->icon!=''?$base->icon:url('assets/img/id_photo.png')}}">
                    </td>
                    <td class="" name="name_phonetic" style="font-size: 16px;vertical-align: middle" data-title="フリガナ">
                        <p>{{$base->name_phonetic}}</p>
                        <p>{{Auth::user()->name}}</p>
                        <p>{{$base->name_roman}}</p>
                    </td>
                </tr>
            </table>
        </div>
        <div class="back-color-white">
            <table class="w-100 set-info">
                <tr class="downborder" onclick="location.href='{{route('getEmployeeInfo',['type'=>'base'])}}'">
                    <td style="width: auto;">
                        {{ __('個人情報') }}
                    </td>
                    <td class="p-0">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                <tr class="downborder" onclick="location.href='{{route('user.resetPassword')}}'">
                    <td style="width: auto;">
                        {{ __('パスワードを変更') }}
                    </td>
                    <td class="p-0">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                @can($__env->yieldContent('permission_modify'))
                    <tr onclick="location.href='{{route('audit.home')}}'">
                        <td style="width: auto;">
                            {{ __('審査端末に切り替える') }}
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
    @include('mobile.personal.employee.modal')
    @include('mobile.personal.footer')
@endsection
@section('footer_append')
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/mobileSelect.min.js') }}"></script>
    <script src="{{ asset('assets/js/lactes.mobile.js') }}"></script>
    <script type="text/javascript">
    </script>
@endsection
