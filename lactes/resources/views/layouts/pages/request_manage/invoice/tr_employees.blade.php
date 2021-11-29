<tr name="employee_info">
    @if(isset($accounts_invoice_details))
        <input type="hidden" name="account_invocie_detail_id[]"
           value="{{$accounts_invoice_details->id}}"/>
    @endif
    <td class="text-center input-able"><label>
            <input oninput="maxBytesCanInput(this,12)" class="text-center" type="text"
                   name="employee_name[]" value="@if(isset($accounts_invoice_details)){{$accounts_invoice_details->employee_name}}@endif"/></label></td>
    <td class="position-relative textarea-able"><label for="employee_period_picker">
            <textarea cols="13" rows="2" maxlength="23"
                      name="employee_period_picker" onfocus="showPicker(this)">@if(isset($accounts_invoice_details)){{$accounts_invoice_details->period}}@endif</textarea>
        </label>
        <input name="employee_period[]" data-print="false" value="@if(isset($accounts_invoice_details)){{$accounts_invoice_details->period}}@endif"
                       style="height: 0;width: 0;border: none;font-size: 0;position: absolute;bottom: 10px;left: 0;">
    </td>
    <td class="textarea-able">
        <label>
            <textarea rows="1" oninput="changeLength(this)"
                      name="detail_content[]">@if(isset($accounts_invoice_details)){{$accounts_invoice_details->detail_content}}@endif</textarea>
        </label>
    </td>
    <td class="td-money-tax-out text-right input-able">
        <label class="backcolor-white">
            <input class="amount minus text-right" onchange="calcAll(this)"
                   type="text" name="unit_price_commuting_sub[]" maxlength="6"
                   value="@if(isset($accounts_invoice_details)){{$accounts_invoice_details->is_outlay==1?$accounts_invoice_details->unit_price:$requestSettingExtra->currency.'0'}}@else{{$requestSettingExtra->currency}}0 @endif"/>
        </label>
    </td>
    <td class="td-money-tax-in text-right position-relative input-able">
        <label class="backcolor-white">
            <input class="amount text-right" onchange="calcAll(this)"
                   type="text" name="unit_price_working_sub[]" maxlength="7"
                   value="@if(isset($accounts_invoice_details)){{$accounts_invoice_details->is_outlay==1?$requestSettingExtra->currency.'0':$accounts_invoice_details->unit_price}}@else{{$requestSettingExtra->currency}}0 @endif"/>
        </label>
            <span name="addAndDelete" data-print="false" style="position: absolute;top:7px;right: -18px;line-height: 18px;font-size: 18px">
                <a href="javascript:void(0);" onclick="delTr(this)" onmousemove="showLinePoint(this)" onmouseleave="hideLinePoint(this)">⊝</a>
                <span class="linePoint">行削除</span>
                <br>
                <a href="javascript:void(0);" onclick="addEmployee(this)" onmousemove="showLinePoint(this)" onmouseleave="hideLinePoint(this)">⊕</a>
                <span class="linePoint">行追加</span>
            </span>
    </td>
</tr>
