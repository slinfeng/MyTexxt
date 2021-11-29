@section('title', __('Estimates').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('Estimates'))
@section('permission_modify','estimate_modify')
@section('css_append')
    @include('layouts.headers.requestmanage.modify_a')
    <link href="{{ asset('assets/css/accounts_estimates_addAndUpdate.css') }}" rel="stylesheet">
@endsection
@section('route_format_out',route('estimates.upload'))
@section('route_index', route('estimates.index'))
@section('manage_code_name','見積番号')
@section('manage_code_route',route('estimates.getPJNO'))
@section('manage_code_field_name','est_manage_code')
@section('date_name','作成日')
@section('date_field_name','created_date')
@section('file_field_name','source')
@section('print_page_name',__("御　見　積　書"))
@section('amount_name','見積額（税抜）')
@include('layouts.pages.sections.requestmanage.print_a')
@section('amount_field_name','estimate_subtotal')
@section('bank_accounts_append')
    <tr>
        <th>御支払条件</th>
        <td class="text-center">
            <input class="w-100 text-center" name="payment_contract" type="text"
                   value="@yield('payment_contract')">
        </td>
    </tr>
@endsection
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
