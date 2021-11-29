@section('route_save',route('confirmations.update', $data->id))
@section('permission_modify','orderconfirm_modify')
@include('layouts.pages.sections.requestmanage.action_edit')
@section('method')@method('PUT')@endsection
@section('client_id',$data->client_id)
@section('cname',$data->cname)
@section('period',$data->period)
@section('order_manage_code',$data->order_manage_code)
@section('file_info')
    <input name="file_id" type="hidden" value="{{$data->file_id}}"/>
    <input name="file_name" type="hidden" value="{{$data->file->basename}}"/>
@endsection
@section('file_pre')
    <a href="javascript:void(0)" onclick="openFile('{{$data->file->id}}','{{$data->file->type}}')">{{$data->project_name_or_file_name}}</a>
@endsection
@section('client_input')<input hidden name="client_id" value="@yield('client_id')">@endsection
@section('client_info')
    <option value="{{$clients->id}}" selected>（{{$clientSortType==0?str_pad($clients->id,4,"0",STR_PAD_LEFT):$clients->client_abbreviation}}）{{$clients->client_name}}（{{$clientSortType==1?str_pad($clients->id,4,"0",STR_PAD_LEFT):$clients->client_abbreviation}}）</option>
@endsection
@include('layouts.pages.request_manage.orderConfirmation.page_create_or_edit')
