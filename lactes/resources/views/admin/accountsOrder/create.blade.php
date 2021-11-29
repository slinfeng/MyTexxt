@section('route_save',route('confirmations.store'))
@section('permission_modify','orderconfirm_modify')
@section('data-month')
    data-month="{{$requestSettingGlobal->period}}"
@endsection
@include('layouts.pages.sections.requestmanage.action_create')
@include('layouts.pages.request_manage.orderConfirmation.page_create_or_edit')
<script>$('input[name=our_position_type][value='+$('select[name=position]').val()+']').prop('checked',true);</script>
