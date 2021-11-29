@section('format_in')
    <!--startprint-->
    <div class="syanai print-receipt" id="receiptPrintArea">
        <form id="form-A" action="@yield('route_format_in')" method="post">
            @csrf
            @yield('method_in')
            @yield('edit_in')
            <div class="w-100">
                <ul class="manage-code">
                    <li class="num"><span>@yield('manage_code_name')</span>：<input data-route="@yield('manage_code_route')" name="@yield('manage_code_field_name')" type="text" value="@yield('manage_code')" readonly></li>
                    <li class="num">
                        <span>@yield('date_name')</span>：<input name="@yield('date_field_name')" autocomplete="off"
                                                                @yield('init_date_create') type="text" value="@yield('date')"></li>
                </ul>
                <br class="clear"/>
                <h1 class="text-center">@yield('print_page_name')</h1>
                <br>
                <h3>
                    <div class="overflow-break w-100">
                        <span name="cname" class="overflow-hide underline">
                        @if($__env->yieldContent('created_by_client_id')>0)
                            {{$requestSettingGlobal['company_name']}}
                            @else
                                @yield('client_name')
                            @endif
                        </span>
                        <select name="official_name">
                            <option value="御中"><span>{{__("　御中")}}</span></option>
                            <option value="様" {{$__env->yieldContent('official_name')=="様"?'selected':''}}><span>{{__("　様")}}</span></option>
                        </select>
                    </div>
                </h3>
                <br><br>
            </div>
            <div class="w-100">
                @yield('print_page_detail')
            </div>
            <div data-print="false" class="text-center m-t-50">
                @can($__env->yieldContent('permission_modify'))
                    <input class="btn btn-primary btn-apply-request" type="button" value="{{__('保存')}}" onclick="submitForm('#form-A','@yield('action')','A')">
                @elsecan($__env->yieldContent('self_modify'))
                    @if($__env->yieldContent('page_status')=='invoice')
                        <input class="btn btn-primary btn-apply-request" type="button" value="{{__('保存')}}" onclick="submitForm('#form-A','@yield('action')','A')">
                    @endif
                @endcan

                <input class="btn btn-success btn-apply-request" type="button" data-toggle="modal" @yield('print_func') value="{{__('印刷')}}" />
            </div>
        </form>
    </div>
    <!--endprint-->
@endsection
