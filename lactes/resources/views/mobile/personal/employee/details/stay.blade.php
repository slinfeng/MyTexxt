@extends('layouts.backend')
@section('page_title', 'ビザ・身分情報')
@section('css_append')
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="{{ asset('assets/css/mobileSelect.css') }}">
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
        .employee-stay-card tr td:nth-of-type(2){
            width: 10%;
            text-align: center;
        }
        .employee-stay tr td:nth-of-type(1){
            font-size: 14px;
            width: 40%;
        }
        .employee-stay tr td:nth-of-type(2){
            text-align: right;
            color: darkgrey;
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
        #card-model-upload .modal-body{
            padding: 20px 30px 15px 30px;
        }
        #card-model-upload input[type=button]{
            border: none;
            background-color: white;
            font-size: 12px;
            color: grey;
            margin-top: 10px;
        }
    </style>
@endsection
@section('content')
    <div class="body">
        <div class="back-color-white">
            <table class="w-100 employee-stay-card">
                <tr onclick="$('#card-model-upload').modal('show');">
                    <td class="text-center">
                        <img class="w-100 residenceCardFront" src="{{$stay->residence_card_front!=''?$stay->residence_card_front:url('assets/img/residence_card_front.png')}}">
                    </td>
                    <td class="p-0 click-before">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
            </table>
        </div>
        <div class="back-color-white">
            <table class="w-100 employee-stay">
                <tr class="downborder">
                    <td class="click-after">
                        在留カード番号
                    </td>
                    <td class="modified" name="residence_card_num" data-title="在留カード番号" onclick="showModalCenter(this)" data-history="{{$stay->data_history['residence_card_num']}}">
                        {{$stay->residence_card_num}}
                    </td>
                    <td class="p-0 click-before">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                <tr class="downborder">
                    <td class="click-after">
                        在留資格種類
                    </td>
                    <td class="modified" name="residence_type" data-val="経営・管理" onclick="showModalSelect(this)"
                        data-history="
                        @foreach($residence_types as $residence_type)
                            @if($residence_type->id==$stay->data_history['residence_type'])
                                {{$residence_type->residence_type}}
                            @endif
                        @endforeach">
                        @foreach($residence_types as $residence_type)
                            @if($residence_type->id==$stay->residence_type)
                                {{$residence_type->residence_type}}
                            @endif
                        @endforeach
                    </td>
                    <td class="p-0 click-before">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                <tr class="downborder">
                    <td class="click-after">
                        在留満了年月日
                    </td>
                    <td class="modified dead-date-select" name="residence_deadline" data-history="{{$stay->data_history['residence_deadline']}}">
                        {{$stay->residence_deadline}}
                    </td>
                    <td class="p-0 click-before">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                <tr>
                    <td class="click-after">
                        個人番号
                    </td>
                    <td class="modified" name="personal_num" data-title="個人番号" onclick="showModalCenter(this)" data-history="{{$stay->data_history['personal_num']}}">
                        {{$stay->personal_num}}
                    </td>
                    <td class="p-0 click-before">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
            </table>
        </div>
    </div>
    @include('mobile.personal.employee.modal')
    <div class="modal custom-modal fade" id="card-model-upload" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title text-center">
                        在留カード写真
                    </div>
                    <form id="cardInfo">
                        <table>
                            <tr>
                                <td class="title">
                                    {{ __('表') }}
                                    <br><br>
                                </td>
                                <td class="text-center">
                                    <img style="max-height: 120px;max-width: 100%" id="residenceCardFront" class="residenceCardFront" src="{{$stay->residence_card_front!=''?$stay->residence_card_front:url('assets/img/residence_card_front.png')}}">
                                    <br>
                                    <div class="m-1 float-right adminModifyAfter">
                                    <span class="btn btn-file file-btns">
                                        <span class="fileinput-new file-select-btn"> 画像を選択 </span>
                                        <input type="file" name="residence_card_front" onchange="showImg(this)">
                                    </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="title">
                                    {{ __('裏') }}
                                    <br><br>
                                </td>
                                <td class="text-center">
                                    <img style="max-height: 120px;max-width: 100%" id="residenceCardBack" class="" src="{{$stay->residence_card_back!=''?$stay->residence_card_back:url('assets/img/residence_card_back.png')}}">
                                    <br>
                                    <div class="m-1 float-right adminModifyAfter">
                                    <span class="btn btn-file file-btns">
                                        <span class="fileinput-new file-select-btn"> 画像を選択 </span>
                                        <input type="file" name="residence_card_back" onchange="showImg(this)">
                                    </span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </form>
                    <div class="error-message"></div>
                    <div class="modal-button text-right">
                        <input type="button" value="キャンセル" onclick="uploadCardFile(false)">
                        <input type="button" value="確認" onclick="uploadCardFile(true)">
                    </div>
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
    <script>
        let modalSelect=[];
        let modalSelectId=[];
        const type='stay';
        let frontPhotoHistory=$('#residenceCardFront').attr('src');
        let backPhotoHistory=$('#residenceCardBack').attr('src');
        let frontPhotoModify='';
        let index=0;
        @foreach($residence_types as $residence_type)
            modalSelect[index]='{{$residence_type->residence_type}}';
            modalSelectId[index]='{{$residence_type->id}}';
        index++;
        @endforeach
        $(document).on('click', '.click-before', function () {$(this).prev().click();});
        $(document).on('click', '.click-after', function () {$(this).next().click();});
        $(function () {
            deadDateSelect();
            modifiedRed();
        });
    </script>
@endsection
