@extends('layouts.backend')
@section('permission_modify','invoice_modify')
@section('self_modify','invoice_self_modify')
@section('page_status','invoice')
@section('document_format',isset($client)?$client['document_format']:'')
@include('layouts.pages.sections.requestmanage.position_a')
@section('route_format_in',route('invoice.store'))
@section('route_format_out',route('invoice.store'))
@section('init_date_pay')
    data-day="{{$requestSettingExtra['request_pay_date']}}" data-month="{{$requestSettingExtra['request_pay_month']}}"
@endsection
@include('layouts.pages.sections.requestmanage.create_common_data')
@section('calc_type',0)
@section('bank_account_id',$requestSettingGlobal['bank_account_id'])
@include('layouts.pages.request_manage.invoice.page_create_or_edit_start')
@section('bank_accounts_append')
    <tr>
        <th>振込期限日</th>
        <td class="text-center">
            <input size="12" maxlength="11" name="pay_deadline" type="text" autocomplete="off"
                   data-day="{{$requestSettingExtra['request_pay_date']}}"
                   data-month="{{$requestSettingExtra['request_pay_month']}}" value="">
        </td>
    </tr>
@endsection
@include('layouts.pages.sections.requestmanage.invoice')
@include('layouts.pages.sections.requestmanage.create_common_tag')
@include('layouts.pages.request_manage.invoice.page_create_or_edit_end')
