@section('title', __('Invoice').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('Invoice'))
@section('css_append')
    @include('layouts.headers.requestmanage.modify_a')
    <link rel="stylesheet" href="{{ asset('assets/css/invoice.create.css') }}">
@endsection
@section('route_index', route('invoice.index'))
@section('manage_code_name','請求番号')
@section('manage_code_route',route('invoice.getInvoiceNum'))
@section('manage_code_field_name','invoice_manage_code')
@section('date_name','請求日')
@section('date_field_name','created_date')
@section('file_field_name','project_name_or_file_name')
@section('print_page_name',__("御　請　求　書"))
@include('layouts.pages.sections.requestmanage.print_a')
@section('amount_name',__('請求額（税込）'))
@section('amount_field_name','invoice_total')
@section('format_out_append')
    <tr>
        <th>請求日</th>
        <td><input name="@yield('date_field_name')" autocomplete="off"
                   @yield('init_date_create') type="text" value="@yield('date')"></td>
    </tr>
    <tr>
        <th>振込期限日</th>
        <td>
            <input size="12" maxlength="11" name="pay_deadline" type="text" autocomplete="off"
                   @yield('init_date_pay') value="@yield('pay_deadline')">
        </td>
    </tr>
@endsection
