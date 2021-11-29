<table class="layer-four w-100">
    <tr><td class="p-0 border-0">
            <table class="project-end w-100 out-border-none">
                <tr>
                    <td style="height: 163px;border-right: 0">@include('layouts.pages.request_manage.bank_accounts')</td>
                    <td style="height: 163px">
                        <table class="w-100 h-100 bolder-border border-left-0">
                            <tr>
                                <th class="title border-left-0">
                                    {{ __('請求金額小計') }}
                                </th>
                                <td class="td-money-tax-out-border text-right">
                                    <input readonly class="w-100 amount text-right" type="text"
                                           name="unit_price_commuting" value="{{$requestSettingExtra['currency']}}0"/>
                                </td>
                                <td class="td-money-tax-in-border text-right">
                                    <input readonly class="w-100 amount text-right" type="text"
                                           name="unit_price_working" value="{{$requestSettingExtra['currency']}}0"/>
                                </td>
                            </tr>
                            <tr>
                                <th class="border-left-0">
                                    {{ __('消費税') }}(@if(isset($data)){{$data->request_setting->tax_rate}}@else{{$requestSettingExtra['tax_rate']}}@endif%)<input type="text" hidden name="tax_rate" value="@if(isset($data)){{$data->request_setting->tax_rate}}@else{{$requestSettingExtra->tax_rate}}@endif">
                                </th>
                                <td class="text-right"><strong>—</strong></td>
                                <td class="text-right">
                                    <input readonly class="w-100 amount text-right" type="text"
                                           name="unit_price_tax" value="{{$requestSettingExtra['currency']}}0"/>
                                </td>
                            </tr>
                            <tr>
                                <th class="amount-sum border-left-0">
                                    {{ __('請求金額合計') }}
                                </th>
                                <td class="amount-sum text-right" colspan="2">
                                    <input readonly class="w-100 amount-sum amount text-right" type="text"
                                           name="invoice_total" value="@if(isset($data)) @yield('amount')@else{{$requestSettingExtra['currency']}}0 @endif"/>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <p><textarea rows="1" readonly name="remark_end" class="w-100" oninput="changeLength(this)"
                         data-initial="{{$requestSettingGlobal['remark_end']}}">@if(isset($data)){{$data->request_setting->remark_end}}@else{{$requestSettingGlobal['remark_end']}}@endif</textarea>
            </p>
        </td>
    </tr>
</table>
