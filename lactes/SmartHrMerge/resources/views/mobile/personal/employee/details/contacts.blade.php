@extends('layouts.backend')
@section('page_title', '連絡情報')
@section('css_append')
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="{{ asset('assets/css/lactes.mobile.css') }}">
    <style>
        body{
            background-color: #e9e9e9 !important;
        }
        .body{
            font-size: 14px;
        }
        .body tr,.base-set tr{
            height: 46px;
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
        td:nth-of-type(1){
            width: 30%;
        }
        td:nth-of-type(2){
            text-align: right;
            color: darkgrey;
        }
        td:nth-of-type(3){
            width: 10%;
            text-align: center;
        }
        .color-grey{
            color: grey;
        }
        .back-color-white{
            margin-bottom: 0.5em;
        }
    </style>
@endsection
@section('content')
    <div class="body">
        <div class="back-color-white">
            <table class="w-100">
{{--                <tr class="downborder">--}}
{{--                    <td>--}}
{{--                        メール--}}
{{--                    </td>--}}
{{--                    <td class="modified" name="mail" data-title="メール" onclick="showModalCenter(this)">--}}

{{--                    </td>--}}
{{--                    <td class="p-0">--}}
{{--                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">--}}
{{--                    </td>--}}
{{--                </tr>--}}
                <tr class="downborder">
                    <td class="click-after">
                        携帯
                    </td>
                    <td class="modified" name="phone" data-title="携帯" onclick="showModalCenter(this)" data-history="{{$contact->data_history['phone']}}">
                        {{$contact->phone}}
                    </td>
                    <td class="p-0 click-before">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                <tr class="downborder">
                    <td class="click-after">
                        電話
                    </td>
                    <td class="modified" name="telephone" data-title="電話" onclick="showModalCenter(this)" data-history="{{$contact->data_history['telephone']}}">
                        {{$contact->telephone}}
                    </td>
                    <td class="p-0 click-before">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                <tr class="downborder">
                    <td class="click-after">
                        FAX
                    </td>
                    <td class="modified" name="fax" data-title="FAX" onclick="showModalCenter(this)" data-history="{{$contact->data_history['fax']}}">
                        {{$contact->fax}}
                    </td>
                    <td class="p-0 click-before">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                <tr class="downborder">
                    <td class="click-after">
                        郵便
                    </td>
                    <td class="modified" name="postcode" data-title="郵便" onclick="showModalCenter(this)" data-history="{{$contact->data_history['postcode']}}">
                        {{$contact->postcode}}
                    </td>
                    <td class="p-0 click-before">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                <tr class="downborder">
                    <td class="click-after">
                        住所
                    </td>
                    <td class="modified" name="address" data-title="住所" onclick="showModalCenter(this)" data-history="{{$contact->data_history['address']}}">
                        {{$contact->address}}
                    </td>
                    <td class="p-0">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                <tr>
                    <td class="click-after">
                        最寄駅
                    </td>
                    <td class="modified" name="nearest_station" data-title="最寄駅" onclick="showModalCenter(this)" data-history="{{$contact->data_history['nearest_station']}}">
                        {{$contact->nearest_station}}
                    </td>
                    <td class="p-0 click-before">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
            </table>
        </div>
        <div class="back-color-white">
            <table class="w-100">
                <thead>
                <tr class="downborder" style="height: 35px">
                    <th class="text-center p-2 color-grey" colspan="3">
                        本籍
                    </th>
                </tr>
                </thead>
                <tr class="downborder">
                    <td class="click-after">
                        郵便
                    </td>
                    <td class="modified" name="home_town_postcode" data-title="本籍地郵便" onclick="showModalCenter(this)" data-history="{{$contact->data_history['home_town_postcode']}}">
                        {{$contact->home_town_postcode}}
                    </td>
                    <td class="p-0">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                <tr class="downborder">
                    <td class="click-after">
                        住所
                    </td>
                    <td class="modified" name="home_town_address" data-title="本籍地住所" onclick="showModalCenter(this)" data-history="{{$contact->data_history['home_town_address']}}">
                        {{$contact->home_town_address}}
                    </td>
                    <td class="p-0 click-before">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
            </table>
        </div>
        <div class="back-color-white">
            <table class="w-100">
                <thead>
                <tr class="downborder" style="height: 35px">
                    <th class="text-center p-2 color-grey" colspan="3">
                        緊急連絡先
                    </th>
                </tr>
                </thead>
                <tr class="downborder">
                    <td class="click-after">
                        氏名
                    </td>
                    <td class="modified" name="emergency_name" data-title="氏名" onclick="showModalCenter(this)" data-history="{{$contact->data_history['emergency_name']}}">
                        {{$contact->emergency_name}}
                    </td>
                    <td class="p-0 click-before">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                <tr class="downborder">
                    <td class="click-after">
                        関係
                    </td>
                    <td class="modified" name="emergency_relationship" data-title="関係" onclick="showModalCenter(this)" data-history="{{$contact->data_history['emergency_relationship']}}">
                        {{$contact->emergency_relationship}}
                    </td>
                    <td class="p-0">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                <tr class="downborder">
                    <td class="click-after">
                        電話
                    </td>
                    <td class="modified" name="emergency_phone" data-title="電話" onclick="showModalCenter(this)" data-history="{{$contact->data_history['emergency_phone']}}">
                        {{$contact->emergency_phone}}
                    </td>
                    <td class="p-0 click-before">
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
        let modalSelect=[];
        let modalSelectId=[];
        const type='contact';
        $(document).on('click', '.click-before', function () {$(this).prev().click();});
        $(document).on('click', '.click-after', function () {$(this).next().click();});
        $(function () {
            modifiedRed();
        })
    </script>
@endsection
