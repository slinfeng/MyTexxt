@extends('layouts.backend')
@section('page_title', 'ホーム')
@section('css_append')
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="{{ asset('assets/css/lactes.mobile.css') }}">
    <style>
        .infos-body{
            background-color: white;
        }
        .infos-body>div{
            /*margin-bottom: 1em!important;*/
            width: 100%;
            padding: 5px 10px;
            color: grey;
            border-bottom:lightgrey 1px solid ;
            position: relative;
        }
        .infos-body span:first-of-type{
            width: 10em;
        }
        .infos-body span:nth-of-type(2){
            width: calc( 100% - 10em );
        }
        .info-employee{
            min-height: 56vw;
        }
        .word-break-all{
            word-break: break-all
        }
        .top-align{
            vertical-align: top;
        }
    </style>
@endsection
@section('content')
    <div class="info-employee">
        <table class="w-100" onclick="location.href='{{route('getEmployeeInfo',['type'=>'base'])}}'">
            <tr>
                <td class="text-center" style="width: 30%">
                    <div class="w-100 p-1">
                        <img class="w-100 m-b-10" id="mugshot" alt="" src="{{$employee->icon!=''?$employee->icon:url('assets/img/id_photo.png')}}">
                    </div>
                    <div name="employee_code" class="m-b-15 font-18">
                        {{$employee->employee_code}}
                    </div>
                </td>
                <td>
                    <div class="m-2">
                        <span name="department_type">{{$employee->departmentType->department_name}}</span>
                    </div>
                    <div name="name" class="m-2 font-weight-bolder font-24">
                        {{Auth::user()->name}}
                    </div>
                    <div name="position_type" class="m-2 mb-4">
                        {{$employee->positionType->position_type_name}}
                    </div>
                    <div class="m-2">
                        <div class="d-inline-block w-25p top-align">
                            <img class="w-75" src="{{ asset('assets/img/birthday.png') }}">
                        </div>
                        <div name="birthday" class="d-inline-block w-75 word-break-all">
                            {{$employee->birthday}}
                        </div>
                    </div>
                    <div class="m-2">
                        <div class="d-inline-block w-25p top-align">
                            <img class="w-75" src="{{ asset('assets/img/phone.png') }}">
                        </div>
                        <div name="phone" class="d-inline-block w-75 word-break-all">
                            {{$employee->employeeContacts->phone}}
                        </div>
                    </div>
                    <div class="m-2">
                        <div class="d-inline-block w-25p top-align">
                            <img class="w-75" src="{{ asset('assets/img/mail.png') }}">
                        </div>
                        <div name="mail" class="d-inline-block w-75 word-break-all">
                            {{Auth::user()->email}}
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <p class="text-right infos-title">あなたへのお知らせを見る<img class="w-25p" src="{{ asset('assets/img/chevron-down.png') }}"></p>
    <div class="infos-body">
        @foreach($notifications as $notification)
        <div class="row m-0">
            <span>{{substr($notification->created_at,0,strlen($notification->created_at)-3)}}</span>
            <span>{{$notification->notify_msg}}@if($notification->notify_status==0)<span class="color-red">new!</span>@endif</span>
        </div>
        @endforeach
    </div>
    <div class="notice-body">
        <div class="notice"><a href="{{route('attendances.index')}}">オンラインで勤務表を提出</a></div>
        <div class="notice"><a href="{{route('leaves.index')}}">オンラインで休暇を申し込み</a></div>
    </div>
    @include('mobile.personal.footer')
@endsection
@section('footer_append')
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/mobileSelect.min.js') }}"></script>
    <script src="{{ asset('assets/js/lactes.mobile.js') }}"></script>
@endsection
