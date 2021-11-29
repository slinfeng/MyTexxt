<input hidden type="text" name="company_name" value="{{$requestSettingGlobal['company_name']}}">
<textarea hidden name="remark_confirmation">{{$requestSettingGlobal['remark_confirmation']}}</textarea>
@include('layouts.pages.request_manage.company_info_and_init_switch')
<div class="w-100 clear"><textarea class="w-100" rows="1" readonly name="remark_start" oninput="changeLength(this)"
               data-initial="{{$requestSettingGlobal['remark_start']}}">@if(isset($data)){{$data->request_setting->remark_start}}@else{{$requestSettingGlobal['remark_start']}}@endif</textarea><br></div>
<table class="bolder-border">
    <tr>
        <th>{{__("業務名称")}}</th>
        <td class="input-able">
            <label>
                <input class="blur" name="project_name" type="text" value="@if(isset($data)){{$data->project_name_or_file_name}}@else{{$requestSettingGlobal['project_name']}}@endif">
            </label>
        </td>
    </tr>
    <tr>
        <th>{{__("業務内容・範囲")}}</th>
        <td class="input-able">
            <label>
                <input name="project_content" type="text" value="@if(isset($data)){{$data->accounts_order_detail->project_content}}@else{{$requestSettingExtra['project_content']}}@endif">
            </label>
        </td>
    </tr>
    <tr>
        <th>
            {{__("作業者名")}}
        </th>
        <td class="input-able">
            <label>
                <input name="employee_name" type="text" value="@if(isset($data)){{$data->accounts_order_detail->employee_name}}@endif">
            </label>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="p-0">
            <table class="out-border-none">
                <tr>
                    <th>{{__("作業期間")}}</th>
                    <td class="input-able">
                        <label>
                            <input @if(!isset($data))data-month="{{$requestSettingGlobal['period']}}@endif" autocomplete="off" type="text" name="period" value="@if(isset($data)){{$data->period}}@endif"/>
                        </label>
                    </td>
                    <td class="input-able text-center" style="width: 8em" name="custom_title"><label>
                            <input class="text-center font-weight-bold" name="custom_title" type="text"
                                   value="@if(isset($data)){{$data->accounts_order_detail->custom_title}}@else{{$requestSettingGlobal['custom_title']}}@endif"/></label></td>
                    <td class="input-able" style="width: 32%"><label>
                            <input class="text-left" name="custom_content" type="text"
                                   value="@if(isset($data)){{$data->accounts_order_detail->custom_content}}@else{{$requestSettingGlobal['custom_content']}}@endif"/></label></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <th>
            {{__("基本金額")}}
        </th>
        <td>
            <span>　　</span>
            <label class="text-center">
                <input class="text-right w-total-price" name="estimate_subtotal"
                       readonly type="text" value="@yield('amount')">
            </label>
            <span>　　</span>
            （<label>
                <input class="text-right w-months float" onchange="calcPrice(this)" maxlength="4"
                       name="month_sum" type="text">
            </label>ヶ月分）
            <span>　　</span>
            （月額　<label>
                <input class="amount text-right w-unit-price" name="unit_price" type="text" maxlength="7"
                       onblur="onChange(this)" value="@if(isset($data)){{$data->accounts_order_detail->unit_price}}@else @yield('amount')@endif">
            </label>）
        </td>
    </tr>
    <tr>
        <th rowspan="2">{{__("支払条件")}}</th>
        <td class="payment_contract">
            <label><span>{{__("検収：")}}</span>
                <input name="payment_contract[]" value="@if(isset($data)){{$data->accounts_order_detail->payment_contract[0]}}@else{{$requestSettingGlobal['payment_contract'][0]}}@endif"></label>
        </td>
    </tr>
    <tr>
        <td class="payment_contract">
            <label><span>{{__("支払：")}}</span>
                <input name="payment_contract[]" value="@if(isset($data)){{$data->accounts_order_detail->payment_contract[1]}}@else{{$requestSettingGlobal['payment_contract'][1]}}@endif"></label>
        </td>
    </tr>
    <tr>
        <th>{{__("作業場所")}}</th>
        <td class="font-90pct">
            <label class="w-50 m-0 text-left">
                　<input type="radio" class="clip-0" name="work_place_val" value="0" onclick="checkedRadio(this)"
                        @if(isset($data))@if($data->accounts_order_detail->work_place_val == 0)checked @endif @else @if($requestSettingGlobal['work_place_val'] == 0)checked @endif @endif>
                <span class="rect  @if(isset($data))@if($data->accounts_order_detail->work_place_val == 1)backcolor-white @endif @else @if($requestSettingGlobal['work_place_val'] == 1)backcolor-white @endif @endif"></span>
                @if(isset($data)){{$data->accounts_order_detail->work_place}}
                @else{{$requestSettingGlobal['work_place']}}<input hidden name="work_place" type="text" value="{{$requestSettingGlobal['work_place']}}">
                @endif
            </label>
            <label class="w-25 m-0 text-left">
                <input type="radio" class="clip-0" name="work_place_val" value="1" onclick="checkedRadio(this)"
                       @if(isset($data))@if($data->accounts_order_detail->work_place_val == 1)checked @endif @else @if($requestSettingGlobal['work_place_val'] == 1)checked @endif @endif>
                &nbsp;<span class="rect @if(isset($data))@if($data->accounts_order_detail->work_place_val == 0)backcolor-white @endif @else @if($requestSettingGlobal['work_place_val'] == 0)backcolor-white @endif @endif"></span> 客先
            </label>
        </td>
    </tr>
    <tr>
        <th>{{__("納入物件")}}</th>
        <td class="font-90pct">
            <label class="w-25 m-0 text-left">
                　<input data-print="false" type="hidden" name="delivery_files[]" value="0">
                <input type="checkbox" class="clip-0" name="delivery_files[]" value="1" onclick="checkedBox(this)"
                       @if(isset($data))@if($data->accounts_order_detail->delivery_files[0] == '1')checked @endif @else @if($requestSettingExtra['expense_delivery_files'][0] == '1')checked @endif @endif>
                <span class="rect @if(isset($data))@if($data->accounts_order_detail->delivery_files[0] == '0')backcolor-white @endif @else @if($requestSettingExtra['expense_delivery_files'][0] == '0')backcolor-white @endif @endif"></span> {{__("勤務表")}}
            </label>
            <label class="w-25 m-0 text-left">
                <input type="checkbox" class="clip-0" name="delivery_files[]" value="2" onclick="checkedBox(this)"
                       @if(isset($data))@if($data->accounts_order_detail->delivery_files[1] == '1')checked @endif @else @if($requestSettingExtra['expense_delivery_files'][1] == '1')checked @endif @endif>
                <span class="rect @if(isset($data))@if($data->accounts_order_detail->delivery_files[1] == '0')backcolor-white @endif @else @if($requestSettingExtra['expense_delivery_files'][1] == '0')backcolor-white @endif @endif"></span> {{__("作業実績報告書")}}
            </label>
            <label class="w-45 m-0 text-left">
                <input type="checkbox" class="clip-0" name="delivery_files[]" value="3" onclick="checkedBox(this)"
                       @if(isset($data))@if($data->accounts_order_detail->delivery_files[2] == '1')checked @endif @else @if($requestSettingExtra['expense_delivery_files'][2] == '1')checked @endif @endif>
                <span class="rect @if(isset($data))@if($data->accounts_order_detail->delivery_files[2] == '0')backcolor-white @endif @else @if($requestSettingExtra['expense_delivery_files'][2] == '0')backcolor-white @endif @endif"></span> {{__("その他成果物一式")}}
            </label>
        </td>
    </tr>
    <tr>
        <th>{{__("納入場所")}}</th>
        <td class="font-90pct">
            <label class="w-50 m-0 text-left">
                　<input type="radio" class="clip-0" name="acceptance_place_val" value="0" onclick="checkedRadio(this)"
                        @if(isset($data))@if($data->accounts_order_detail->acceptance_place_val == 0)checked @endif @else @if($requestSettingGlobal['acceptance_place_val'] == 0)checked @endif @endif>
                <span class="rect @if(isset($data))@if($data->accounts_order_detail->acceptance_place_val == 1)backcolor-white @endif
                @else @if($requestSettingGlobal['acceptance_place_val'] == 1)backcolor-white @endif @endif"></span>
                @if(isset($data)){{$data->accounts_order_detail->acceptance_place}}
                @else{{$requestSettingGlobal['acceptance_place']}}<input type="hidden" name="acceptance_place" value="{{$requestSettingGlobal['acceptance_place']}}">@endif
            </label>
            <label class="w-45 m-0 text-left">
                <input type="radio" class="clip-0" name="acceptance_place_val" value="1" onclick="checkedRadio(this)"
                       @if(isset($data))@if($data->accounts_order_detail->acceptance_place_val == 1)checked @endif @else @if($requestSettingGlobal['acceptance_place_val'] == 1)checked @endif @endif>
                &nbsp;<span class="rect @if(isset($data))@if($data->accounts_order_detail->acceptance_place_val == 0)backcolor-white @endif @else @if($requestSettingGlobal['acceptance_place_val'] == 0)backcolor-white @endif @endif"></span> 発注者指定場所
            </label>
        </td>
    </tr>
    <tr>
        <th>{{__("交通・通勤費")}}</th>
        <td class="font-90pct">
            <label class="align-top w-25 m-0 text-left">
                　<input type="radio" class="clip-0" name="traffic_expence_paid_by_val" value="0" onclick="checkedRadio(this)"
                        @if(isset($data))@if($data->accounts_order_detail->traffic_expence_paid_by_val == 0)checked @endif @else @if($requestSettingExtra['expense_traffic_expence_paid_by_val'] == 0)checked @endif @endif>
                <span class="rect @if(isset($data))@if($data->accounts_order_detail->traffic_expence_paid_by_val == 1)backcolor-white @endif
                @else @if($requestSettingExtra['expense_traffic_expence_paid_by_val'] == 1)backcolor-white @endif @endif"
                ></span> @if(isset($data)){{$data->accounts_order_detail->traffic_expence_paid_by}}
                @else{{$requestSettingExtra['expense_traffic_expence_paid_by']}}<input
                        type="hidden" name="traffic_expence_paid_by" value="{{$requestSettingExtra['expense_traffic_expence_paid_by']}}">@endif
            </label>
            <label class="w-70 m-0 text-left">
                <input type="radio" class="clip-0" name="traffic_expence_paid_by_val" value="1" onclick="checkedRadio(this)"
                       @if(isset($data))@if($data->accounts_order_detail->traffic_expence_paid_by_val == 1)checked @endif @else @if($requestSettingExtra['expense_traffic_expence_paid_by_val'] == 1)checked @endif @endif>
                <span style="vertical-align: top"><span class="rect @if(isset($data))@if($data->accounts_order_detail->traffic_expence_paid_by_val == 0)backcolor-white @endif @else @if($requestSettingExtra['expense_traffic_expence_paid_by_val'] == 0)backcolor-white @endif @endif"></span> {{__("協同会社負担")}}</span>
                <span name="bikou">{{__("（発注者指定場所への通勤費・都内交通費発注単価込とする）")}}</span>
            </label>
        </td>
    </tr>
    <tr>
        <th class="remark-title">{{__("経費清算")}}</th>
        <td class="textarea-able">
            <label class="empty-remark">
                    <textarea name="outlay" rows="4"
                              oninput="changeLength(this)">@if(isset($data)){{$data->accounts_order_detail->outlay}}@else{{$requestSettingExtra['expense_outlay']}}@endif</textarea>
            </label>
        </td>
    </tr>
    <tr>
        <th class="remark-title">{{__("特記事項")}}</th>
        <td class="textarea-able">
            <label><textarea name="remark" rows="4"
                             oninput="changeLength(this)">@if(isset($data)){{$data->accounts_order_detail->remark}}@else{{$requestSettingExtra['expense_remark']}}@endif</textarea></label>
        </td>
    </tr>
</table>
<p><textarea rows="1" readonly name="remark_end" oninput="changeLength(this)" class="w-100"
             data-initial="{{$requestSettingGlobal['remark_end']}}">@if(isset($data)){{$data->request_setting->remark_end}}@else{{$requestSettingGlobal['remark_end']}}@endif</textarea></p>
