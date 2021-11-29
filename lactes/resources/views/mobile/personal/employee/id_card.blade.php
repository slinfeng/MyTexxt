@extends('layouts.backend')
@section('page_title', '社員証')
@section('css_append')
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="{{ asset('assets/css/lactes.mobile.css') }}">
    <style>
        body{
            /*background-color: aquamarine!important;*/
            background-image:
                url('/public/assets/img/IT-card-background.jpg');
        }
        .title{
            display: inline-block;
            text-align: justify;
            text-align-last: justify;
            width: 5em;
            margin-left: 8%;
            margin-right: 10px;
        }
        .content-name-phonetic{
            font-size: 12px;
            color: darkgrey;
            display: inline-block;
        }
        .member-head{
            position: relative;
            height: 80px;
            padding: 10px;
            vertical-align: bottom;
        }
        .member-head .company-name{
            margin-left: 60px;
            position: relative;
            height: 60px;
            width: calc( 100% - 120px );
            vertical-align: middle;
            text-align: center;
            z-index: 10;
        }
        .logo-img{
            height: 60px;
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 5;
        }
    </style>
@endsection
@section('content')
<div class="body">
{{--    $companyInfo->logo==''?$companyInfo->logo:--}}
    <div class="member-head back-color-white">
        <div class="logo-img">
            <img class="h-100" style="height: 2.5em" src="{{ $companyInfo->logo!=''?$companyInfo->logo:asset('assets/img/employee-logo.png') }}">
        </div>
        <table class="company-name">
            <tr>
                <td>
                    {{$companyInfo->company_short_name}}
                </td>
            </tr>
        </table>
    </div>
    <div class="member-body back-color-white">
        <div class="member-title">社　員　証</div>
        <div class="member-icon">
            <img class="w-50 m-b-10" id="mugshot" alt="" src="{{$base->icon!=''?$base->icon:url('assets/img/id_photo.png')}}">
        </div>
        <div class="w-100 text-left m-0">
            <div class="title">　</div>
            <div name="name_phonetic" class="content-name-phonetic">
                {{$base->name_phonetic}}
            </div>
        </div>
        <div class="w-100 text-left m-1 m-b-15 m-t-0">
            <div class="title">氏名 </div>
            <div name="name" class="content-name">{{Auth::user()->name}}</div>
        </div>
        <div class="w-100 text-left m-1 m-b-15">
            <div class="title">所属 </div>
            <div name="department_type" class="content">
                {{$base->departmentType->department_name}}
            </div>
        </div>
        <div class="w-100 text-left m-1 m-b-15">
            <div class="title">社員番号 </div>
            <div name="employee_code" class="content">
                {{$base->employee_code}}
            </div>
        </div>
    </div>
</div>
    @include('mobile.personal.footer')
@endsection
@section('footer_append')
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/mobileSelect.min.js') }}"></script>
    <script src="{{ asset('assets/js/lactes.mobile.js') }}"></script>
@endsection
