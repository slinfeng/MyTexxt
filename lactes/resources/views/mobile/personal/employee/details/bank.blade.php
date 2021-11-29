@extends('layouts.backend')
@section('page_title', '銀行情報')
@section('css_append')
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
            <table class="w-100">
                <tr class="downborder">
                    <td class="click-after">
                        銀行名
                    </td>
                    <td class="modified" name="bank_name" data-title="銀行名" onclick="showModalCenter(this)" data-history="{{$bank->data_history['bank_name']}}">
                        {{$bank->bank_name}}
                    </td>
                    <td class="p-0 click-before">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                <tr class="downborder">
                    <td class="click-after">
                        支店名
                    </td>
                    <td class="modified" name="branch_name" data-title="支店名" onclick="showModalCenter(this)" data-history="{{$bank->data_history['branch_name']}}">
                        {{$bank->branch_name}}
                    </td>
                    <td class="p-0 click-before">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                <tr class="downborder">
                    <td class="click-after">
                        支店番号
                    </td>
                    <td class="modified" name="branch_code" data-title="支店番号" onclick="showModalCenter(this)" data-history="{{$bank->data_history['branch_code']}}">
                        {{$bank->branch_code}}
                    </td>
                    <td class="p-0 click-before">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr><tr class="downborder">
                    <td class="click-after">
                        預金種類
                    </td>
                    <td class="modified" name="account_type" onclick="showModalSelect(this)"
                        data-history="@foreach($account_types as $account_type)
                        @if($account_type->id==$bank->data_history['account_type'])
                        {{$account_type->account_type_name}}
                        @endif
                        @endforeach
                            ">
                        @foreach($account_types as $account_type)
                            @if($account_type->id==$bank->account_type)
                               {{$account_type->account_type_name}}
                            @endif
                        @endforeach
                    </td>
                    <td class="p-0 click-before">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr><tr class="downborder">
                    <td class="click-after">
                        口座番号
                    </td>
                    <td class="modified" name="account_num" data-title="口座番号" onclick="showModalCenter(this)" data-history="{{$bank->data_history['account_num']}}">
                        {{$bank->account_num}}
                    </td>
                    <td class="p-0 click-before">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr><tr>
                    <td class="click-after">
                        名義人
                    </td>
                    <td class="modified" name="account_name" data-title="名義人" onclick="showModalCenter(this)" data-history="{{$bank->data_history['account_name']}}">
                        {{$bank->account_name}}
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
        const type='bank';
        let index=0;
        @foreach($account_types as $key=>$account_type)
            modalSelect[index]='{{$account_type->account_type_name}}';
            modalSelectId[index]='{{$account_type->id}}';
        index++;
        @endforeach
        $(document).on('click', '.click-before', function () {$(this).prev().click();});
        $(document).on('click', '.click-after', function () {$(this).next().click();});
        $(function () {
            modifiedRed();
        })
    </script>
@endsection
