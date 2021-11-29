@extends('layouts.backend')
@section('title', __('LetterOfTransmittal').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('LetterOfTransmittal'))
@section('page_type',isset($letterOfTransmittal)?'edit':'')
@section('permission_modify','transmittal_modify')
@section('self_modify','transmittal_self_modify')
@section('use_init_val',isset($letterOfTransmittal)?$letterOfTransmittal->requestSetting->use_init_val:$requestSettingGlobal->use_init_val)
@section('company_info',isset($letterOfTransmittal)?$letterOfTransmittal->requestSetting->company_info:$requestSettingGlobal->company_info)
@section('isLetter',true)
@section('route_index',route('letteroftransmittal.index'))
@section('framed_mark',isset($letterOfTransmittal)?$letterOfTransmittal->requestSetting->use_seal:$requestSettingGlobal->use_seal)
@section('css_append')
    @include('layouts.headers.requestmanage.modify_a')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/letteroftransmittal.css') }}">
@endsection
@section('content')
    <div class="create">
    <div class="w-100 common">
        @include('layouts.pages.request_manage.hidden_initval')
        @if(isset($letterOfTransmittal))
            <h3>{{__("送付状編集画面")}}</h3>
        @else
            <h3>{{__("送付状新規作成画面")}}</h3>
        @endif
        <div class="row p-0" style="margin: 0 0 8px;">
                    <span
                        style="text-align: justify;text-align-last: justify;display: inline-block;">{{__("送付状名称・メモ")}}</span>：<input
                    name="memo" class="right_input" style="width: calc(100% - 9rem);"
                    type="text" onchange="nameAndMemoChange('memo',this.value)"
                    value="{{isset($letterOfTransmittal)?$letterOfTransmittal->memo:''}}">
        </div>

            <div class="row p-0" style="margin: 0 0 8px;">
                <div id="top_margin" style="padding-left: 0;margin-right: 1rem;">縦距離：<input onchange="numFormat(this,0)" name="top_bottom_distance" class="initDistance"
                                                                oninput="value = value.replace(/[^0-9|.]/g, '');"
                                                                type="text"
                                                                value="{{isset($letterOfTransmittal)?$letterOfTransmittal->top_bottom_distance:$requestSettingExtra->vertical_distance}}">mm
                </div>
                <div id="left_margin">横距離：<input onchange="numFormat(this,1)" name="left_right_distance" class="initDistance"
                                                                 oninput="value = value.replace(/[^0-9|.]/g, '');"
                                                                 type="text"
                                                                 value="{{isset($letterOfTransmittal)?$letterOfTransmittal->left_right_distance:$requestSettingExtra->horizontal_distance}}">mm
                </div>
            </div>
            @include('layouts.pages.request_manage.client_select')
        @include('layouts.pages.request_manage.gotoback')
    </div>

    <div id="lop" data-store="{{route('letteroftransmittal.store')}}"
         data-update="{{route('letteroftransmittal.update',':id')}}" class="hide"><input name="printHtml" value="{{isset($printHtml)?$printHtml:''}}"><div class=""><span name="postcode" class=""></span>
<span name="companyAddress"></span>
<span name="companyName">&nbsp　&nbsp　&nbsp　&nbsp　&nbsp</span><span style="vertical-align: top;">{{__("　御中")}}</span>
ご担当者様</div></div>

        <div class="syagai print-receipt" id="receiptPrintArea" style="padding: 10mm;position: relative;">
            <form id="aForm" class="w-100 h-100" name="aForm" method="post">
            @csrf
            <!--startprint-->
                <div class="layout_border w-100 h-100">
                    <input name="init_client_id" value="{{isset($letterOfTransmittal)?$letterOfTransmittal->client_id:''}}" hidden>
                    <input name="client_id" value="{{isset($letterOfTransmittal)?$letterOfTransmittal->client_id:''}}" hidden>
                    <input name="top_bottom_distance" value="" hidden>
                    <input name="left_right_distance" value="" hidden>
                    <input name="name" value="{{isset($letterOfTransmittal)?$letterOfTransmittal->name:''}}" hidden>
                    <input name="memo" value="{{isset($letterOfTransmittal)?$letterOfTransmittal->memo:''}}" hidden>
                    <input name="id" value="{{isset($letterOfTransmittal)?$letterOfTransmittal->id:0}}" hidden>
                    <div class="w-100" style="z-index: 10;margin-bottom: 2rem;" name="first_page">
                        <div class="row m-0">
                            <div class="w-50 d-flex flex-column">
{{--                                <div class="hide_label" id="address_move" style="border: 1px solid grey;" onmousedown="boxOnmousedown();" onmousemove="boxOnmousemove();" onmouseup="boxOnmouseup();" onmouseout="boxOnmouseup();">ドラッグするにはここをクリックしてください</div>--}}
                                <div class="client-position"><textarea  style="padding: 10px;" onkeyup="changeLength(this)" oninput="changeLength(this)"
                                               name="client_address" class="client_address">{{isset($letterOfTransmittal)?$letterOfTransmittal->client_address:''}}</textarea>
                                </div>
                            </div>
                            <div class="w-50">
                                <div>
                                    <div style="width: 36%;min-width: 24rem;float: right;">
                                    <ul class="text-left w-100" style="padding-left:0.5rem; ">
                                        <li style="text-align: right;"><p class="hide p-0 m-0 text-right w-100" name="datetime"></p></li>
                                        <li data-print="false" class="hide_label w-100">
                                           送付日：<input data-print="false"
                                                class="datetime hide_label"
                                                name="delivery_date"
                                                type="text" style="width: calc(100% - 5rem);margin:0 0 10px 10px;padding: 0 0 0 10px"
                                                value="{{isset($letterOfTransmittal)?$letterOfTransmittal->delivery_date:date('Y-m-d')}}">
                                        </li>
                                    </ul>
                                    </div>
                                    <div class="layer-company-info">@include('layouts.pages.request_manage.company_info_and_init_switch')</div>
                                </div>
                            </div>
                        </div>
                        <br class="clear"/>
                    </div>
                    <div class="position-relative">
                        <div class="d-flex justify-content-center title_name"><input id="title_name" name="title" type="text" class="title_name p-0 m-0" onkeydown="widthChange();" onkeyup="widthChange();" oninput="widthChange();"
                                                                          value="{{isset($letterOfTransmittal)?$letterOfTransmittal->title:$requestSettingGlobal->project_name}}">
                        </div>
                        <div class="row"><div id="float_text" class="title_name d-flex justify-content-center"></div><div class="clear"></div></div>
<div class="p-0 m-0 text-left backcolor-white"><textarea style="padding:0 10px;" class="m-0 text-left backcolor-white" rows="1" readonly>拝啓</textarea></div>
                        <div><textarea style="padding: 10px;" class="m-0" onkeyup="changeLength(this)" oninput="changeLength(this)" name="content">{{isset($letterOfTransmittal)?$letterOfTransmittal->content:$requestSettingGlobal->remark_start}}</textarea></div>
                            <div class="p-0 m-0 text-right backcolor-white"><textarea class="p-0 m-0 text-right backcolor-white" rows="1" readonly>敬具</textarea></div>

                        <p class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;</p>
                        <p class="text-center">記</p>
                        <p class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;</p>
                    </div>
                    <div class="">
                        <div class="book-type row p-0 m-0">【送付書類】 <div data-print="false" class="hide_label">
                            <label name="checkbox" onclick="addLine(0)">
                                {{__("見積書")}}
                                <a href="javascript:void(0);">⊕</a>
                            </label>
                            <label name="checkbox" onclick="addLine(1)">
                                {{__("注文書")}}
                                <a href="javascript:void(0);">⊕</a>
                            </label>
                            <label name="checkbox" onclick="addLine(2)">
                                {{__("注文請書")}}
                                <a href="javascript:void(0);">⊕</a>
                            </label>
                            <label name="checkbox" onclick="addLine(3)">
                                {{__("請求書")}}
                                <a href="javascript:void(0);">⊕</a>
                            </label>

                            <a href="javascript:void(0);" class="btn btn-secondary" onclick="clearContent()" style="font-size: 10px;padding: 2px;"> {{__('Reset')}} </a>

                            <div class="hide" id="send_content">
<div name="estimates_content">・見積書　()　　　　　　　　　　1通
</div>
<div name="expense_content">・注文書　()　　　　　　　　　　1通
</div>
<div name="confirmations_content">・注文請書　()　　　　　　　　　1通
</div>
<div name="invoice_content">・請求書　()　　　　　　　　　　1通
</div>
                                <textarea></textarea>
                                <textarea></textarea>
                                <textarea></textarea>
                                <textarea></textarea>
                                <textarea></textarea>
                            </div>
                        </div>
                        </div>

                        <div class="row p-0" style="margin: 1rem 40px;">
                            <textarea  style="padding: 10px;" onkeyup="changeLength(this);" oninput="changeLength(this);" onchange="changeContent(this.value);"
                                                          name="document_send">{{isset($letterOfTransmittal)?$letterOfTransmittal->document_send:$requestSettingGlobal->remark_end}}</textarea>
                        </div>
                    </div>
                    <p class="text-right p-0 m-0">以上</p>
                </div>
                <!--endprint-->
                <div name="button" class="" style="margin-top: 3rem;">
                    @canany([$__env->yieldContent('permission_modify'),$__env->yieldContent('self_modify')])
                    <input data-print="false" class="hide_label btn btn-primary btn-apply-request"
                           name="hide" type="button" value="{{__('保存')}}" onclick="addEditLetterOfTransmittal();">
                    @endcanany
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
    <script src="{{ asset('assets/js/letteroftransmittal.edit.js') }}"></script>
@endsection
