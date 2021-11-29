@extends('layouts.backend')
@section('page_title', '個人情報')
@section('css_append')
    <link rel="stylesheet" href="{{ asset('assets/css/mobileSelect.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lactes.mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/cropper.css') }}">
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
    <div class="body">
        <div class="back-color-white">
            <table class="w-100">
                <tr>
                    <td rowspan="3" style="width:40%;padding: 10px 10px 10px 30px" onclick="$('#icon-model').modal('show')">
                            <label>
                                <img class="w-100 m-b-10" id="mugshot" alt="" src="{{$base->icon!=''?$base->icon:url('assets/img/id_photo.png')}}">
                            </label>
                    </td>
                    <td class="modified" name="name_phonetic" style="font-size: 16px;vertical-align: bottom" data-title="フリガナ" data-history="{{$base->data_history['name_phonetic']}}" onclick="showModalCenter(this)">
                        {{$base->name_phonetic}}
                    </td>
                </tr>
                <tr>
                    <td class="" name="" style="font-size: 18pt;font-weight: 800;height: 30px" data-title="氏名">
                        {{Auth::user()->name}}
                    </td>
                </tr>
                <tr>
                    <td class="modified color-darkgrey" name="name_roman" style="font-size: 14px;vertical-align: top" data-title="ローマ字" data-history="{{$base->data_history['name_roman']}}" onclick="showModalCenter(this)">
                        {{$base->name_roman}}
                    </td>
                </tr>
            </table>
        </div>
        <div class="back-color-white">
            <table class="w-100 base-info">
                <tr class="downborder">
                    <td class="click-after">
                        性別
                    </td>
                    <td class="modified" name="sex" data-title="性別" onclick="showModalSelect(this)" data-history="{{$base->data_history['sex']==0 ? __('Male') :($base->data_history['sex']==1 ? __('Female') :($base->data_history['sex']==2 ? __('Unisex') :''))}}">
                        {{$base->sex==0 ? __('Male') :($base->sex==1 ? __('Female') :($base->sex==2 ? __('Unisex') :""))}}
                    </td>
                    <td class="click-before p-0">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                <tr class="downborder click-after">
                    <td>
                        生年月日
                    </td>
                    <td class="modified date-selector" name="birthday" data-history="{{$base->data_history['birthday']}}">
                        {{$base->birthday}}
                    </td>
                    <td class="click-before p-0">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                <tr>
                    <td class="click-after">
                        国籍
                    </td>
                    <td class="modified" name="nationality"  data-title="国籍" onclick="showModalCenter(this)" data-history="{{$base->data_history['nationality']}}">
                        {{$base->nationality}}
                    </td>
                    <td class="click-before p-0">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
            </table>
        </div>
        <div class="back-color-white">
            <table class="w-100 base-set">
                <tr class="downborder new-page" data-url="{{route('getEmployeeInfo',['type'=>'stay'])}}">
                    <td>
                        ビザ・身分情報
                    </td>
                    <td></td>
                    <td class="p-0">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                <tr class="downborder new-page" data-url="{{route('getEmployeeInfo',['type'=>'contact'])}}">
                    <td>
                        連絡情報
                    </td>
                    <td></td>
                    <td class="p-0">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                <tr class="downborder new-page" data-url="{{route('getEmployeeInfo',['type'=>'bank'])}}">
                    <td>
                        銀行情報
                    </td>
                    <td class="click-after"></td>
                    <td class="p-0">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                <tr class="downborder">
                    <td class="click-after">
                        扶養家族
                    </td>
                    <td class="modified family-num-select" name="family_num"  data-title="扶養家族" data-history="{{$base->data_history['family_num'].'人'}}">
                        {{$base->family_num.'人'}}
                    </td>
                    <td class="p-0 click-before">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
            </table>
        </div>
    </div>
    @include('mobile.personal.employee.modal')
    <div class="modal custom-modal fade" id="icon-model" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style="padding-bottom: 10px">
                    <div class="modal-btn delete-action">
                        <form id="iconInfo" enctype="multipart/form-data" method="post">
                        <div class="modal-title" style="font-size: 18px;margin-bottom: 10px">
                            <span class="btn btn-file file-btns">
                                <span class="fileinput-new file-select-btn"> 画像を選択 </span>
                                <input type="hidden">
                                <input type="file" name="icon" onchange="showIconImg(this)">
                            </span>
                        </div>
                        </form>
                        <div class="modal-body p-0" style="width: 100%;height:350px">
                            <img src="" id="iconImg">
                        </div>
                        <div class="modal-button text-right">
                            <input type="button" value="キャンセル" onclick="iconIsClick(false)">
                            <input type="button" value="保存" onclick="iconIsClick(true)">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal custom-modal fade" id="icon-err-message" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style="padding-bottom: 10px">
                    <div class="modal-btn delete-action">
                        <div class="modal-value" style="font-size: 14px;margin-bottom: 10px;color: red">

                        </div>
                        <div class="modal-button text-right">
                            <input type="button" value="キャンセル"  onclick="$('#icon-err-message').modal('hide');">
                            <input type="button" value="確認"  onclick="$('#icon-err-message').modal('hide');">
                        </div>
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
    <script src="{{ asset('assets/js/cropper.js') }}"></script>
    <script>
        const modalSelect=['男','女','ユニセックス'];
        const modalSelectId=[ 0, 1, 2];
        const type='base';
        const editIconUrl='{{route('employee.photoSave')}}';
        let iconEvent='';

        $(document).on('click', '.click-before', function () {$(this).prev().click();});
        $(document).on('click', '.click-after', function () {$(this).next().click();});
        $(document).on('click', '.new-page', function () {
            window.location.href = $(this).data('url');
        });
        $(function () {
            dateSelector();
            familyNumSelect();
            modifiedRed();
            let iconWidth=$('body').width()-72;
            $("#iconImg").cropper({
                aspectRatio:1,
                viewMode:1,
                minContainerWidth:iconWidth,
                minContainerHeight:350,
                naturalWidth:500,
                crop:function (e) {
                    console.log(e)
                }
            });
        });
        function printErrorMsg(msg) {
            $('#icon-err-message .modal-value').html(msg);
            $('#icon-err-message').modal('show');
            $('input[type=file]').val('');
        }
        function showIconImg(event){
            let rd = new FileReader();
            let files = event.files[0];
            rd.readAsDataURL(files);
            rd.onloadend = function(e) {
                $("#iconImg").cropper('replace',this.result);
            }
        }
    </script>
@endsection
