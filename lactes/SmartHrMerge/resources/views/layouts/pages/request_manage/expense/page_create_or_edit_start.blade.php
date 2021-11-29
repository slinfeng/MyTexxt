@section('title', __('Expense').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('Expense'))
@section('permission_modify','expense_modify')
@section('css_append')
    @include('layouts.headers.requestmanage.modify_a')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/accounts_addANDupdate.css') }}">
@endsection
@section('route_format_out',route('expense.upload'))
@section('route_index', route('expense.index'))
@section('manage_code_name','注文番号')
@section('manage_code_route',route('expense.getPJNO'))
@section('manage_code_field_name','project_manage_code')
@section('date_name','作成日')
@section('date_field_name','created_date')
@section('print_flag',$requestSettingGlobal['print_num'])
@section('file_field_name','source')
@section('print_page_name',__("御　注　文　書"))
@section('print_func','data-target=#print_modal')
@section('amount_name','注文額（税抜）')
@section('amount_field_name','estimate_subtotal')
@section('format_out_append')
    <tr>
        <th>
            {{__("作業期間")}}
        </th>
        <td>
            @yield('period_input')
        </td>
    </tr>
@endsection
