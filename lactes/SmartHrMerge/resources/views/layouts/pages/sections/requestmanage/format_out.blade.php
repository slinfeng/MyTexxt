@section('format_out')
    <div class="print_receipt syagai">
        <form id="form-B" method="post" action="@yield('route_format_out')" enctype="multipart/form-data">
            @csrf
            @yield('method_out')
            @yield('edit_out')
            <table class="format_B w-100">
                @yield('format_out_append')
                <tr>
                    <th>
                        @yield('manage_code_name')
                    </th>
                    <td>
                        <input name="@yield('manage_code_field_name')" data-route="@yield('manage_code_route')" type="text" value="@yield('manage_code')" size="25" readonly>
                    </td>
                </tr>
                <tr>
                    <th>
                        @yield('page_title')
                    </th>
                    <td>
                        <input type="button" class="btn btn-success btn-file-border" value="ファイルを選択" onclick="uploadFile(this)">
                        <input class="w-100" onchange="showFileName(this)" hidden="" type="file" name="@yield('file_field_name')">
                        <span class="files-info"></span>
                        @yield('file_append')
                    </td>
                </tr>
                <tr>
                    <th>
                        @yield('amount_name')
                    </th>
                    <td>
                        <input class="text-right amount" name="@yield('amount_field_name')" type="text" value="@yield('amount')"/>
                    </td>
                </tr>
            </table>

            <div class="w-100 m-t-20">

                <div class="text-center h-p-50">
                    @canany([$__env->yieldContent('permission_modify'),$__env->yieldContent('self_modify')])
                    <input class="btn btn-primary btn-apply-request"
                           data-print="false" type="button" onclick="submitForm('#form-B','@yield('action')','B',true)" value="保存"/>
                    @endcanany
                </div>

            </div>

        </form>
    </div>
@endsection
