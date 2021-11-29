@extends('layouts.backend')
@section('page_title', 'ホーム')
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
        .downborder{
            border-bottom: #f1f1f1 1px solid;
        }
        td{
            padding: 10px 20px;
        }
        .employee-stay tr td:nth-of-type(1){
            font-size: 14px;
            width: 35%;
            padding-right: 0;
        }
        .employee-stay tr td:nth-of-type(2){
            text-align: right;
            color: darkgrey;
            padding-left: 0;
        }
        .employee-stay tr td:nth-of-type(3){
            width: 10%;
            text-align: center;
        }
        .color-darkgrey{
            color: darkgrey;
        }
        .back-color-white{
            margin-bottom: 0.5em;
        }
    </style>
@endsection
@section('content')
    <div class="body">
        <div class="back-color-white">
            <table class="w-100 employee-stay">
                <tr class="downborder">
                    <td>
                        区分
                    </td>
                    <td colspan="2" @if($dependentRelation->relationship_type==3)style="font-size: 12px" @endif>
                        {{$dependentRelation->relationship_type==1?"配偶者":""}}
                        {{$dependentRelation->relationship_type==2?"扶養親族 (16歳以上)":""}}
                        {{$dependentRelation->relationship_type==3?"他の所得者が控除を受ける扶養親族等":""}}
                        {{$dependentRelation->relationship_type==4?"16歳未満の扶養親族":""}}
                    </td>
                </tr>
                <tr class="downborder">
                    <td>
                        氏名
                    </td>
                    <td class="modified" data-history="{{$dependentRelation->data_history["dname"]}}" name="dname" data-title="氏名" onclick="showModalCenter(this)">
                        {{$dependentRelation->dname}}
                    </td>
                    <td class="p-0">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                <tr class="downborder">
                    <td>
                        個人番号
                    </td>
                    <td class="modified" data-history="{{$dependentRelation->data_history["dependent_residence_card_num"]}}" name="dependent_residence_card_num" data-title="個人番号" onclick="showModalCenter(this)">
                        {{$dependentRelation->dependent_residence_card_num	}}
                    </td>
                    <td class="p-0">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                @if($dependentRelation->relationship_type!=1)
                    <tr class="downborder">
                        <td>
                            続柄
                        </td>
                        <td class="modified" data-history="{{$dependentRelation->data_history["relationship"]}}" name="relationship" data-title="続柄" onclick="showModalCenter(this)">
                            {{$dependentRelation->relationship}}
                        </td>
                        <td class="p-0">
                            <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                        </td>
                    </tr>
                @endif
                <tr class="downborder">
                    <td>
                        生年月日
                    </td>
                    <td class="modified date-selector" data-history="{{$dependentRelation->data_history["dependent_birthday"]}}" name="dependent_birthday">
                        {{$dependentRelation->dependent_birthday}}
                    </td>
                    <td class="p-0">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                @if($dependentRelation->relationship_type==2)
                        <tr class="downborder">
                            <td>
                                同居・老親
                            </td>
                            <td class="modified" data-history="{{$dependentRelation->data_history["live_type"]==1?"同居":""}}{{$dependentRelation->data_history["live_type"]==2?"老親":""}}" name="live_type" data-title="個人番号" onclick="showModalSelect(this)">
                                {{$dependentRelation->live_type==1?"同居":""}}
                                {{$dependentRelation->live_type==2?"老親":""}}
                            </td>
                            <td class="p-0">
                                <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                            </td>
                        </tr>
                @endif

                <tr class="downborder">
                    <td>
                        住所又は居所
                    </td>
                    <td class="modified" data-history="{{$dependentRelation->data_history["dependent_address"]}}" name="dependent_address" data-title="住所又は居所" onclick="showModalCenter(this)">
                        {{$dependentRelation->dependent_address}}
                    </td>
                    <td class="p-0">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr><tr class="downborder">
                    <td>
                        所得の見積額
                    </td>
                    <td class="modified" data-history="{{$dependentRelation->data_history["estimated"]}}" name="estimated" data-title="所得の見積額" onclick="showModalCenter(this)">
                        {{$dependentRelation->estimated}}
                    </td>
                    <td class="p-0">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
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
    <script>
        const modalSelect=['同居','老親'];
        let modalSelectId=[1, 2];
        const type='dependentRelations&id={{$dependentRelation->id}}';
        $(function () {
            dateSelector();
            modifiedRed();
        })
    </script>
@endsection
