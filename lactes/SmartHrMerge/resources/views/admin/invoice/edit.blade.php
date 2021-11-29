@extends('layouts.backend')
@section('permission_modify','invoice_modify')
@section('self_modify','invoice_self_modify')
@section('page_status','invoice')
@include('layouts.pages.sections.requestmanage.method_a')
@include('layouts.pages.sections.requestmanage.position_a')
@section('page_type','edit')
@section('pay_deadline',$data->pay_deadline)
@section('manage_code',$data->invoice_manage_code)
@section('amount',$data->invoice_total)
@section('created_by_client_id',$data->created_by_client_id)
@section('bank_account_id',$data->bank_account_id)
@section('document_format',isset($client)?$client['document_format']:'')
@include('layouts.pages.sections.requestmanage.edit_common_data')
@section('route_format_in',route('invoice.update',$data->id))
@section('route_format_out',route('invoice.update',$data->id))
@section('calc_type',$data->calc_type)
@include('layouts.pages.request_manage.invoice.page_create_or_edit_start')
@section('bank_accounts_append')
    <tr>
        <th>振込期限日</th>
        <td class="text-center">
            <input size="12" maxlength="11" name="pay_deadline" type="text" autocomplete="off"
                   value="{{$data->pay_deadline}}">
        </td>
    </tr>
@endsection

@if($data->file_format_type==0)
@section('in-content')
    @include('layouts.pages.sections.requestmanage.invoice')
@endsection
@endif
@include('layouts.pages.sections.requestmanage.edit_common_tag')
@include('layouts.pages.request_manage.invoice.page_create_or_edit_end')
