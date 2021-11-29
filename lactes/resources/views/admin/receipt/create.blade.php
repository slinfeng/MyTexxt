@extends('layouts.backend')
@section('title', __('receipt').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('receipt'))
@section('permission_modify','receipt_modify')
@section('self_modify','receipt_self_modify')
@section('use_init_val',isset($receipt)?$receipt->requestSetting->use_init_val:$assetSetting->use_init_val)
@section('use_seal',isset($receipt)?$receipt->requestSetting->use_seal:$assetSetting->use_seal)
@section('company_info',isset($receipt)?$receipt->requestSetting->company_info:$assetSetting->company_info)
@section('isLetter',true)
@section('route_index',route('receipt.index'))
@section('framed_mark',isset($receipt)?$receipt->requestSetting->use_seal:$assetSetting->use_seal)
@section('css_append')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/request.manage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/receipt.create.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.searchableSelect.css')}}">
@endsection
@section('content')
    <div class="create">
    <div class="w-100 common">
        @include('layouts.pages.request_manage.hidden_initval')
        @if(isset($receipt))
            <h3>{{__("領収書編集画面")}}</h3>
        @else
            <h3>{{__("領収書新規作成画面")}}</h3>
        @endif

        <div class="row p-0" style="margin: 0 0 8px;">
                    <span style="padding-top: 2px;">{{__("領収書名称・メモ")}}</span>：<input
                name="name_or_memo" class="right_input" style="width: calc(100% - 9rem);"
                type="text" onchange="nameAndMemoChange('name_or_memo',this.value)"
                value="{{isset($receipt)?$receipt->name_or_memo:''}}">
        </div>

        @if(isset($receipt))
            <h4 style="display: inline-block;width: 80%">&nbsp;</h4>
        @else
            @include('layouts.pages.request_manage.client_select')
        @endif
        @include('layouts.pages.request_manage.gotoback')
    </div>

    <div id="lop" data-store="{{route('receipt.store')}}"
         data-update="{{route('receipt.update',':id')}}" class="hide"></div>

        <div class="syanai print-receipt" id="receiptPrintArea" style="padding: 10mm;position: relative;">
            <form id="aForm" class="w-100 h-100" name="aForm" method="post">
            @csrf
            <!--startprint-->
                <div class="w-100 h-100">
                    <input name="client_id" value="{{isset($receipt)?$receipt->client_id:''}}" hidden>
                    <input name="name_or_memo" value="{{isset($receipt)?$receipt->name_or_memo:''}}" hidden>
                    <input name="id" value="{{isset($receipt)?$receipt->id:0}}" hidden>
                    <div class="w-100" style="z-index: 10;" name="first_page">
                        <div class="row m-0">
                            <div class="d-flex flex-column w-75"></div>
                        <div class="w-25 right">
                            <div>
                                <div style="width: 36%;min-width: 24rem;float: right;">
                                    <ul class="text-left w-100" style="padding-left:0.5rem; ">
                                        <li style="text-align: right;"><p class="hide p-0 m-0 text-right w-100" name="datetime"></p></li>
                                        <li data-print="false" class="hide_label w-100">
                                            領収日：<input data-print="false"
                                                       class="datetime hide_label"
                                                       name="receipt_date"
                                                       type="text" style="width: calc(100% - 5rem);margin:0 0 10px 10px;padding: 0 0 0 10px"
                                                       value="{{isset($receipt)?$receipt->receipt_date:date('Y-m-d')}}">
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                            <br class="clear"/>
                        </div>
                        <div class="row m-0">
                            <div class="d-flex flex-column">
                                <h3>
                                    <div class="overflow-break w-100">
                                        <input class="{{isset($receipt)?'hide':''}}" name="client_name" style="width: 20rem;background-color: #E8F0FE;border-bottom: 2px #0c0c0c solid;" value="{{isset($receipt)?$receipt->client_name:''}}">
                                        <span name="cname" class="overflow-hide underline">{{isset($receipt)?$receipt->client_name:''}}</span>
                                        <span style="vertical-align: top;">{{__("　様")}}</span>
                                    </div>
                                </h3>
                            </div>
                        </div>
                    </div>

                    <p style="text-align: center;font-size: 25px;margin-bottom: 20px;">領収証</p>
                    <div class="row m-0 p-0">
                        <div class="col-3"></div>
                        <div class="col-6" style="">
                            <table class="" style="margin-top: 40px;">
                                <tr>
                                    <td style="border: 1px solid black;width: 200px;">領収金額</td>
                                    <td style="border: 1px solid black;width: 200px;text-align: right;"><input style="font-size: 20px;" name="receipt_amount" class="amount text-right none-border w-100" value="{{isset($receipt)?$receipt->receipt_amount:$requestSettingExtra->currency.'0'}}"></td>
                                </tr>
                            </table>
                            </div>
                        <div class="col-1">&nbsp;</div>
                        <div class="col-2"><div style="width: 7rem;height: 7rem;border: 1px solid black;padding: 5px;">電子領収書につき印紙不要</div></div>
                        </div>

                    <div class="row p-0" style="margin:20px 0 20px 0;"><div class="col-3">&nbsp;&nbsp;&nbsp;&nbsp;</div><div class="col-7" style="margin-right: 0;"><textarea rows="1" cols="20" oninput="changeLength(this)" name="document_end" class="none-border w-100">{{isset($receipt)?$receipt->document_end:$assetSetting->document_end}}</textarea></div></div>

                    <div class="row p-0" style="margin:80px 0 20px 3rem;">
                        <div class="layer-company-info"><div class="company-info">
                                <ul class="w-100">
                                    <li class="row hide_label" style="padding-right: 25px;margin: 0">
                                        <div class="row col-auto ml-auto"
                                             style="padding: 0;margin-left:0!important;margin-right: auto">
                                            <span>初期値使用　</span>
                                            <div class="status-toggle">
                                                <input type="checkbox" id="switch_annual1" name="use_init_val" class="check"
                                                       onclick="initCompanyInfoAndRemark()"
                                                       @if($__env->yieldContent('use_init_val')==1)
                                                       checked
                                                @endif>
                                                <label for="switch_annual1" class="checktoggle">checkbox</label>
                                            </div>
                                        </div>
                                        <div class="row col-auto ml-auto p-0">
                                            <span>電子印鑑使用　</span>
                                            <div class="status-toggle">
                                                <input type="checkbox" id="switch_annual2" name="use_seal" class="check"
                                                       onclick="showSeal(this)"
                                                       @if($__env->yieldContent('use_seal')==1)
                                                       checked
                                                    @endif>
                                                <label for="switch_annual2" class="checktoggle">checkbox</label>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="position-relative">
            <textarea readonly oninput="changeLength(this)" name="company_info" class="w-100" rows="6"
                      data-initial="{{$assetSetting->company_info}}">{{isset($receipt)?$receipt->requestSetting->company_info:$assetSetting->company_info}}</textarea>
                                        <img class="electronicSeal" src="{{$assetSetting->seal_file}}">
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    </div>
                <!--endprint-->
                <div name="button" class="m-1">
                    @canany([$__env->yieldContent('permission_modify'),$__env->yieldContent('self_modify')])
                    <input data-print="false" class="hide_label btn btn-primary btn-apply-request"
                           name="hide" type="button" value="{{__('保存')}}" onclick="addEditReceipt();">
                    @endcan
                    <input data-print="false" class="hide_label btn btn-success btn-apply-request"
                           name="hide" type="button" data-toggle="modal" data-target="#print_modal" onclick="doPrint();"
                           value="{{__('印刷')}}"/>
                </div>
            </form>
        </div>

    <footer>
        <br/> <br/> <br/> <br/>
    </footer>
    </div>
    <textarea name="remark_start" hidden></textarea>
    <textarea name="remark_end" hidden></textarea>
@endsection
@section('footer_append')
    @include('layouts.footers.requestmanage.modify_a')
    <script src="{{ asset('assets/js/receipt.edit.js') }}"></script>
@endsection
