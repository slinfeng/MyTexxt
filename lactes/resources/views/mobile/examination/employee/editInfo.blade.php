@extends('layouts.backend')
@section('page_title', '詳細情報')
@section('css_append')
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="{{ asset('assets/css/lactes.mobile.css') }}">
    <style>
        body{
            background-color: #e9e9e9 !important;
        }
        .body{
            padding-top: 20px;
            margin-bottom: 20px;
        }
        .content{
            padding-left: 0!important;
            padding-right: 0!important;
        }
        .downborder{
            border-bottom: #f1f1f1 1px solid;
        }
        td{
            padding: 10px 10px;
        }
        tr td:nth-of-type(1){
            width: 12em;
        }
        tr td:nth-of-type(2){
            width: calc( 50% - 7em );
            color: grey;
            text-decoration:line-through;
        }
        tr td:nth-of-type(3){
            width: calc( 50% - 7em );
            color: orange;
        }
        .color-grey{
            color: grey;
        }
        .back-color-white{
            margin-bottom: 0.5em;
        }
        #adminConfirm{
            width: 100%;
            text-align: center;
            /*font-size: 13px;*/
        }
        #adminConfirm button{
            width: 35%;
            margin: 10px;
        }
        #admin-confirm-model .modal-value{
            margin-bottom: 0;
        }
        #admin-confirm-model .modal-body{
            /*padding-bottom: 10px;*/
        }
        #admin-confirm-model .modal-button button{
            width: 40%;
            margin-top: 10px;
            color:grey;
        }

        #admin-confirm-model p{
            margin-bottom: 0.25em;
        }
        #admin-confirm-model .modal-dialog,#delete .modal-dialog{
            max-width: 350px!important;
        }
        #admin-confirm-model .modal-btn{
            margin-top: 10px;
        }
        #admin-confirm-model .modal-body{
            width: 301px;
            margin: 0 auto;
        }
        #admin-confirm-model .btn,#delete .btn{
            width: 100%;
            font-size: 13px;
            padding: 6px 12px;
        }
        .error-message{
            text-align: left;
            font-size: 12px!important;
        }
    </style>
@endsection
@section('content')
    <div class="body">
        <div class="back-color-white">
            <table class="w-100">
                <thead>
                <tr class="downborder">
                    <th class="text-center p-2 color-grey" colspan="3">
                        基本情報
                    </th>
                </tr>
                <tr class="downborder">
                    <td>トップ画像</td>
                    <td>
                        <img style="height: 40px"  src="{{isset($employee->data_history['icon'])?$employee->data_history['icon']:url('assets/img/id_photo.png')}}">
                    </td>
                    <td>
                        <img style="height: 40px" src="{{isset($employee->icon)?$employee->icon:url('assets/img/id_photo.png')}}">
                    </td>
                </tr>
                </thead>
                <tr class="downborder">
                    <td>
                        フリガナ
                    </td>
                    <td>
                        {{$employee->data_history['name_phonetic']}}
                    </td>
                    <td>
                        {{$employee->name_phonetic}}
                    </td>
                </tr>
                <tr class="downborder">
                    <td>
                        ローマ字
                    </td>
                    <td>
                        {{$employee->data_history['name_roman']}}
                    </td>
                    <td>
                        {{$employee->name_roman}}
                    </td>
                </tr>
                <tr class="downborder">
                    <td>
                        国籍
                    </td>
                    <td>
                        {{$employee->data_history['nationality']}}
                    </td>
                    <td>
                        {{$employee->nationality}}
                    </td>
                </tr>
                <tr class="downborder">
                    <td>
                        生年月日
                    </td>
                    <td>
                        {{$employee->data_history['birthday']}}
                    </td>
                    <td>
                        {{$employee->birthday}}
                    </td>
                </tr>
                <tr class="downborder">
                    <td>
                        性別
                    </td>
                    <td>
                        {{$employee->data_history['sex']==0 ? __('Male') :($employee->data_history['sex']==1 ? __('Female') :($employee->data_history['sex']==2 ? __('Unisex') :""))}}
                    </td>
                    <td>
                        {{$employee->sex==0 ? __('Male') :($employee->sex==1 ? __('Female') :($employee->sex==2 ? __('Unisex') :""))}}
                    </td>
                </tr>

            </table>
        </div>
        <div class="back-color-white">
            <table class="w-100">
                <thead>
                <tr class="downborder">
                    <th class="text-center p-2 color-grey" colspan="3">
                        連絡情報
                    </th>
                </tr>
                </thead>
                <tr class="downborder">
                    <td>
                        携帯電話
                    </td>
                    <td>
                        {{$employee->employeeContacts->data_history['phone']}}
                    </td>
                    <td>
                        {{$employee->employeeContacts->phone}}
                    </td>
                </tr>
                <tr class="downborder">
                    <td>
                        電話番号
                    </td>
                    <td>
                        {{$employee->employeeContacts->data_history['telephone']}}
                    </td>
                    <td>
                        {{$employee->employeeContacts->telephone}}
                    </td>
                </tr>
                <tr class="downborder">
                    <td>
                        FAX
                    </td>
                    <td>
                        {{$employee->employeeContacts->data_history['fax']}}
                    </td>
                    <td>
                        {{$employee->employeeContacts->fax}}
                    </td>
                </tr>
                <tr class="downborder">
                    <td>
                        郵便番号
                    </td>
                    <td>
                        {{$employee->employeeContacts->data_history['postcode']}}
                    </td>
                    <td>
                        {{$employee->employeeContacts->postcode}}
                    </td>
                </tr>
                <tr class="downborder">
                    <td>
                        住所
                    </td>
                    <td>
                        {{$employee->employeeContacts->data_history['address']}}
                    </td>
                    <td>
                        {{$employee->employeeContacts->address}}
                    </td>
                </tr>
                <tr class="downborder">
                    <td>
                        最寄駅
                    </td>
                    <td>
                        {{$employee->employeeContacts->data_history['nearest_station']}}
                    </td>
                    <td>
                        {{$employee->employeeContacts->nearest_station}}
                    </td>
                </tr>
                <tr class="downborder" id="homeData">
                    <th class="text-center p-2 color-grey" colspan="3">
                        本籍
                    </th>
                </tr>
                <tr class="downborder homeData">
                    <td>
                        郵便番号
                    </td>
                    <td>
                        {{$employee->employeeContacts->data_history['home_town_postcode']}}
                    </td>
                    <td>
                        {{$employee->employeeContacts->home_town_postcode}}
                    </td>
                </tr>
                <tr class="downborder homeData">
                    <td>
                        住所
                    </td>
                    <td>
                        {{$employee->employeeContacts->data_history['home_town_address']}}
                    </td>
                    <td>
                        {{$employee->employeeContacts->home_town_address}}
                    </td>
                </tr>
                <tr class="downborder" id="emergencyData">
                    <th class="text-center p-2 color-grey" colspan="3">
                        緊急連絡先
                    </th>
                </tr>
                <tr class="downborder emergencyData">
                    <td>
                        氏名
                    </td>
                    <td>
                        {{$employee->employeeContacts->data_history['emergency_name']}}
                    </td>
                    <td>
                        {{$employee->employeeContacts->emergency_name}}
                    </td>
                </tr>
                <tr class="downborder emergencyData">
                    <td>
                        関係
                    </td>
                    <td>
                        {{$employee->employeeContacts->data_history['emergency_relationship']}}
                    </td>
                    <td>
                        {{$employee->employeeContacts->emergency_relationship}}
                    </td>
                </tr>
                <tr class="downborder emergencyData">
                    <td>
                        電話
                    </td>
                    <td>
                        {{$employee->employeeContacts->data_history['emergency_phone']}}
                    </td>
                    <td>
                        {{$employee->employeeContacts->emergency_phone}}
                    </td>
                </tr>
            </table>
        </div>
        <div class="back-color-white">
            <table class="w-100">
                <thead>
                <tr class="downborder">
                    <th class="text-center p-2 color-grey" colspan="3">
                        滞在情報
                    </th>
                </tr>
                </thead>
                <tr class="downborder">
                    <td>
                        在留カード番号
                    </td>
                    <td>
                        {{$employee->employeeStay->data_history['residence_card_num']}}
                    </td>
                    <td>
                        {{$employee->employeeStay->residence_card_num}}
                    </td>
                </tr>
                <tr class="downborder">
                    <td>
                        在留資格種類
                    </td>
                    <td>
                        @foreach($residenceTypes as $residenceType)
                            @if($residenceType->id==$employee->employeeStay->data_history['residence_type'])
                                {{$residenceType->residence_type}}
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($residenceTypes as $residenceType)
                            @if($residenceType->id==$employee->employeeStay->residence_type)
                                {{$residenceType->residence_type}}
                            @endif
                        @endforeach
                    </td>
                </tr>
                <tr class="downborder">
                    <td>
                        在留満了年月日
                    </td>
                    <td>
                        {{$employee->employeeStay->data_history['residence_deadline']}}
                    </td>
                    <td>
                        {{$employee->employeeStay->residence_deadline}}
                    </td>
                </tr>
                <tr class="downborder">
                    <td>
                        個人番号
                    </td>
                    <td>
                        {{$employee->employeeStay->data_history['personal_num']}}
                    </td>
                    <td>
                        {{$employee->employeeStay->personal_num}}
                    </td>
                </tr>
                <tr class="downborder">
                    <td>
                        在留カードの表
                    </td>
                    <td>
                        <img style="height: 40px" src="{{isset($employee->employeeStay->data_history['residence_card_front'])?$employee->employeeStay->data_history['residence_card_front']:url('assets/img/residence_card_front.png')}}">
                    </td>
                    <td>
                        <img style="height: 40px" src="{{isset($employee->employeeStay->residence_card_front)?$employee->employeeStay->residence_card_front:url('assets/img/residence_card_front.png')}}">
                    </td>
                </tr>
                <tr class="downborder">
                    <td>
                        在留カードの裏
                    </td>
                    <td>
                        <img style="height: 40px" src="{{isset($employee->employeeStay->data_history['residence_card_back'])?$employee->employeeStay->data_history['residence_card_back']:url('assets/img/residence_card_back.png')}}">
                    </td>
                    <td>
                        <img style="height: 40px" src="{{isset($employee->employeeStay->residence_card_back)?$employee->employeeStay->residence_card_back:url('assets/img/residence_card_back.png')}}">
                    </td>
                </tr>
            </table>
        </div>
        <div class="back-color-white">
            <table class="w-100">
                <thead>
                <tr class="downborder">
                    <th class="text-center p-2 color-grey" colspan="3">
                        銀行情報
                    </th>
                </tr>
                </thead>
                <tr class="downborder">
                    <td>
                        銀行名
                    </td>
                    <td>
                        {{$employee->employeeBank->data_history['bank_name']}}
                    </td>
                    <td>
                        {{$employee->employeeBank->bank_name}}
                    </td>
                </tr>
                <tr class="downborder">
                    <td>
                        支店名
                    </td>
                    <td>
                        {{$employee->employeeBank->data_history['branch_name']}}
                    </td>
                    <td>
                        {{$employee->employeeBank->branch_name}}
                    </td>
                </tr>
                <tr class="downborder">
                    <td>
                        支店番号
                    </td>
                    <td>
                        {{$employee->employeeBank->data_history['branch_code']}}
                    </td>
                    <td>
                        {{$employee->employeeBank->branch_code}}
                    </td>
                </tr>
                <tr class="downborder">
                    <td>
                        預金種類
                    </td>
                    <td>@foreach($accountTypes as $accountType)
                            @if($accountType->id==$employee->employeeBank->data_history['account_type'])
                                {{$accountType->account_type_name}}
                            @endif
                        @endforeach</td>
                    <td>@foreach($accountTypes as $accountType)
                            @if($accountType->id==$employee->employeeBank->account_type)
                                {{$accountType->account_type_name}}
                            @endif
                        @endforeach</td>
                </tr>
                <tr class="downborder">
                    <td>
                        口座番号
                    </td>
                    <td>
                        {{$employee->employeeBank->data_history['account_num']}}
                    </td>
                    <td>
                        {{$employee->employeeBank->account_num}}
                    </td>
                </tr>
                <tr class="downborder">
                    <td>
                        口座名義
                    </td>
                    <td>
                        {{$employee->employeeBank->data_history['account_name']}}
                    </td>
                    <td>
                        {{$employee->employeeBank->account_name}}
                    </td>
                </tr>
            </table>
        </div>
        <div class="back-color-white">
            <table class="w-100">
                <thead>
                <tr class="downborder">
                    <th class="text-center p-2 color-grey" colspan="3">
                        扶養家族
                    </th>
                </tr>
                <tr class="downborder">
                    <td>扶養家族</td>
                    <td>
                        {{$employee->data_history['family_num']}}人
                    </td>
                    <td>
                        {{$employee->family_num}}人
                    </td>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="adminConfirm">
        <button type="button" class="adminConfirm btn btn-success submitButton roundButton" onclick="adminConfirm(true)">
            <span style="font-size: 15px">承認</span>
        </button>
        <button type="button" class="adminDeny btn btn-danger roundButton" onclick="adminConfirm(false)">
            <span style="font-size: 15px">拒否</span>
        </button>
    </div>

    <div class="modal custom-modal fade" id="admin-confirm-model" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">

                    <div class="form-header modal-value">
                        <h3>編集した情報の<span class="modifySpan"></span></h3>
                        <p><span class="modifySpan"></span>してもよろしいでしょうか?</p>
                        <p class="error-message"></p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <a href="javascript:void(0);" onclick="modalSubmit()"
                                   class="btn btn-primary continue-btn">確認</a>
                            </div>
                            <div class="col-6">
                                <a href="javascript:void(0);" data-dismiss="modal"
                                   class="btn btn-primary cancel-btn">{{__('Cancel')}}</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('mobile.examination.footer')
@endsection
@section('footer_append')
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/mobileSelect.min.js') }}"></script>
    <script src="{{ asset('assets/js/lactes.mobile.js') }}"></script>
    <script>
        let type;
        const updateTime = ['{{$employee->updated_at}}','{{$employee->EmployeeBank->updated_at}}','{{$employee->EmployeeContacts->updated_at}}','{{$employee->EmployeeStay->updated_at}}'];
        $(function () {
            showDiffer();
            hideTitle();
        });
        function showDiffer() {
            $('tr').each(function () {
                if($(this).find('td').length>0){
                    let historyVal=$(this).find('td:eq(1)').html();
                    let modifyVal = $(this).find('td:eq(2)').html();
                    if(historyVal==modifyVal) $(this).hide();
                }
            })
        }
        function hideTitle() {
            $('table').each(function (){
                if($(this).find('td:visible').length<=0) $(this).hide();
            });
            if($(".homeData:visible").length<=0) $("#homeData").hide();
            if($(".emergencyData:visible").length<=0) $("#emergencyData").hide();
        }
        function adminConfirm(boo) {
            type=boo;
            if(boo) $('#admin-confirm-model .modifySpan').html("承認");
            else $('#admin-confirm-model .modifySpan').html("拒否");
            $('#admin-confirm-model').modal('show');
        }
        function modalSubmit() {
            $.ajax({
                url:"{{route('employees.adminConfirm')}}",
                type:"GET",
                data: {"id":"{{$employee->id}}","type":type,"updateTime":updateTime},
                success:function(response){
                    ajaxSuccessAction(response,function () {
                        window.location.href="{{route('audit.employees')}}";
                    });
                },
                error: function(jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                }
            });
        }
    </script>
@endsection
