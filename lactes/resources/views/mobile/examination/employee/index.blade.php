@extends('layouts.backend')
@section('page_title', '社員管理')
@section('css_append')
    <link rel="stylesheet" href="{{ asset('assets/css/mobileSelect.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lactes.mobile.css') }}">
    <style>
        .content{
            padding: 30px 0 10px!important;
        }
        .back-color-white{
            border-radius: 5px;
        }
        div .content .body{
            font-family: 黑体!important;
        }
        .downborder{
            border-bottom: #f1f1f1 1px solid;
        }
        .base-info tr,.base-set tr{
            height: 46px;
        }
        .base-info td{
            padding: 10px 20px;
        }
        .base-info td:nth-of-type(1){
            width: 30%;
        }
        .base-info td:nth-of-type(2){
            text-align: right;
            color: darkgrey;
        }
        .base-info td:nth-of-type(3){
            width: 10%;
            text-align: center;
        }
        .base-set td{
            padding: 10px 20px;
        }

        .base-set td:nth-of-type(2){
            text-align: right;
            color: darkgrey;
        }
        .base-set td:nth-of-type(3){
            width: 10%;
            text-align: center;
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
            font-size: 14px;
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
    @if(sizeof($employees)==0)
        <div class="back-color-white m-2">
            <table class="w-100">
                <tr>
                    <td class="text-center" style="height: 60px;padding: 10px;width: 0">
                        編集した社員情報は存在していません
                    </td>
                </tr>
            </table>
        </div>
    @else
        @foreach($employees as $employee)
            <div class="back-color-white m-2" onclick="location.href='{{route('audit.employee',['id' => $employee->id])}}'">
                <table class="w-100">
                    <tr>
                        <td class="text-left" rowspan="0" style="height: 0;padding: 10px;width: 60px">
                            <img style="height: 40px" id="mugshot" alt="" src="{{isset($employee->icon)?$employee->icon:url('assets/img/id_photo.png')}}">
                        </td>
                        <td style="font-size: 16px;font-weight: 800">
                            {{$employee->user->name.'('.$employee->employee_code.")"}}
                        </td>
                        <td class="text-right color-darkgrey" rowspan="0"  style="vertical-align: top;padding: 10px">{{$employee->updated_at}}</td>
                    </tr>
                    <tr>
                        <td class=" color-darkgrey" style="font-size: 12px;">
                            {{$employee->departmentType->department_name}}
                            {{$employee->positionType->position_type_name}}
                        </td>
                    </tr>
                </table>
            </div>
        @endforeach
    @endif
    @include('mobile.examination.footer')
@endsection
@section('footer_append')
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/mobileSelect.min.js') }}"></script>
    <script src="{{ asset('assets/js/lactes.mobile.js') }}"></script>
@endsection
