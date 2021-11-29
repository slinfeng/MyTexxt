<div class="layer-three w-100">
    <table id="invoice-details" class="w-100 bolder-border">
        <thead>
        <tr class="bolder-border">
            <th>
                {{ __('作業担当者') }}
            </th>
            <th>
                {{ __('作業期間') }}
            </th>
            <th>
                {{ __('内　訳　明　細') }}
            </th>
            <th colspan="2">
                {{ __('請求金額') }}
            </th>
        </tr>
        </thead>
        <tbody>
            @if(isset($data))
                @foreach($data->accounts_invoice_details as $accounts_invoice_details)
                    @include('layouts.pages.request_manage.invoice.tr_employees')
                @endforeach
            @else
                @include('layouts.pages.request_manage.invoice.tr_employees')
            @endif
        </tbody>
    </table>
    <p style="height: 1em"></p>
</div>
<div class="layer-remark w-100 bolder-border" style="margin-bottom: 2em;padding: 0.25em">
    <table class="w-100 backcolor-E8F0FE">
        <tr>
            <td class="textarea-able border-0">
                <label>備考</label>
                <textarea name="remark" rows="2" oninput="changeLength(this)">@if(isset($data)){{$data->remark}}@endif</textarea>
            </td>
        </tr>
    </table>
</div>
