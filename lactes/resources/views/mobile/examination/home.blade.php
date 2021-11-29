@extends('layouts.backend')
@section('page_title', 'ホーム')
@section('css_append')
    <link rel="stylesheet" href="{{ asset('assets/css/lactes.mobile.css') }}">
    <style>
        .infos-body{
            margin: 0;
            margin-top: 15px;
        }
        .element {
            max-height: 35px;
            overflow: hidden;
            transition: max-height 0.2s;
        }
        .elementHover {
            transition: max-height 1s;
            max-height: 666px;
        }
        .element .infos-title::after{
            content: " ◀";
        }
        .elementHover .infos-title::after{
            content: " ▼";
        }
        .infos-title{
            line-height: 35px;
            font-size: 15px;
            background-color: #f9f9f9;
            color:grey ;
            padding:0 10px 0 10px;
            margin: 0!important;
            border-radius: 2px 2px 0 0;
        }
        .infos-body table{
            background-color: white;
        }
        .infos-body table tr{
            height: 50px;
            padding-left: 5px;
        }
        .infos-body table td{
            padding: 5px 10px;
            color: grey;
        }
        .infos-body tr td:nth-of-type(1){
            width: 7em;
        }
        .infos-body tr td:nth-of-type(3){
            width: 7em;
            text-align: center;
        }
        .downborder{
            border-bottom: #f1f1f1 1px solid;
        }
        .lactesLog{
            text-align: center;
            background-image: linear-gradient(to bottom, #f0f0f0, #fff, #fff);
            padding: 40px 0 30px 0;
            border-radius: 10px;
            /*border: orange 5px solid;*/
            /*box-shadow: 2px 2px 0 0 white;*/
            position: relative;
        }
        .lactesLog .topWord{
            font-size: 12px;
            position: absolute;
            left: 10px;
            top: 5px;
            color: darkgrey;
        }
        .lactesLog .bottomWord{
            font-size: 12px;
            position: absolute;
            right: 10px;
            bottom: 2px;
            color: lightgrey;
        }
    </style>
@endsection
@section('content')
    <div class="lactesLog">
        <div style="width: 30%;margin: 0 auto">
            <img src="http://lactes.jp/assets/img/logo_home.png" alt="Lactes">
        </div>
        <span class="topWord">
            SES管理システム　ラクテス v1.0
        </span>
        <span class="bottomWord">
            携帯端末審査
        </span>
    </div>
    <div class="infos-body elementHover">
        <p class="text-left infos-title">合計 {{sizeof($employees)}} 人の社員が個人情報を変更しました</p>
        <table class="w-100">
            @foreach($employees as $employee)
                <tr class="downborder" onclick="location.href='{{route('audit.employee',['id' => $employee->id])}}'">
                    <td>{{$employee->user->name}}</td>
                    <td>{{$employee->updated_at}}</td>
                    <td class="p-0" style="width: 10%">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="infos-body elementHover">
        <p class="text-left infos-title">合計 {{sizeof($attendances)}} 人の社員が勤務表を提出しました</p>
        <table class="w-100">
            @foreach($attendances as $attendance)
                <tr class="downborder">
                    <td>{{isset($attendance->employee)?$attendance->employee->user->name:'なし'}}</td>
                    <td>{{$attendance->updated_at ?? $attendance->created_at}}</td>
                    <td class="p-0 text-right">{{$attendance->working_time}}時間　</td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="infos-body elementHover">
        <p class="text-left infos-title">合計 {{sizeof($leaves)}} 人の社員が休暇を提出しました</p>
        <table class="w-100">
            @foreach($leaves as $leave)
                <tr class="downborder">
                    <td>{{$leave->EmployeeBase->user->name}}</td>
                    <td>{{$leave->updated_at ?? $leave->created_at}}</td>
                    <td class="p-0 text-right">{{$leave->days_of_leave.'日'}}　</td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="notice-body">
        <div class="notice"><a href="{{route('audit.employees')}}">オンラインで社員情報を審査</a></div>
        <div class="notice"><a href="{{route('audit.attendance')}}">オンラインで勤務表を審査</a></div>
        <div class="notice"><a href="{{route('audit.leaves')}}">オンラインで休暇を審査</a></div>
    </div>
    @include('mobile.examination.footer')
@endsection
@section('footer_append')
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/mobileSelect.min.js') }}"></script>
    <script src="{{ asset('assets/js/lactes.mobile.js') }}"></script>
    <script>
        $(".infos-title").click(function(){
            if($(this).parents('.infos-body').hasClass('element')){
                $(this).parents('.infos-body').addClass('elementHover');
                $(this).parents('.infos-body').removeClass('element');
            }else{
                $(this).parents('.infos-body').addClass('element');
                $(this).parents('.infos-body').removeClass('elementHover');
            }
        });
    </script>
@endsection
