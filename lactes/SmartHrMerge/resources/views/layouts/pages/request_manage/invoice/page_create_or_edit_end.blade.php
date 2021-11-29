@section('format_out_append')
    <tr>
        <th>@yield('date_name')</th>
        <td><input class="w-100" onchange="changeNum()" @if(!isset($data))data-month="{{$requestSettingGlobal['create_month']}}"@endif
                   autocomplete="off"
                   @if(!isset($data))data-day="{{$requestSettingGlobal['create_day']}}"@endif type="text" name="created_date"
                   value="@if(isset($data)){{$data->created_date}}@else @yield('date') @endif"/>
        </td>
    </tr>
    <tr>
        <th>{{ __('振込期限日') }}</th>
        <td>
            <input size="12" maxlength="11" name="pay_deadline" type="text" class="w-100" autocomplete="off"
                   @if(!isset($data))data-day="{{$requestSettingExtra['request_pay_date']}}"
                   data-month="{{$requestSettingExtra['request_pay_month']}}" value="" @else value="{{$data->pay_deadline}}"@endif></td>
    </tr>
@endsection
@section('content')
    @include('layouts.pages.request_manage.page_create_or_edit')
@endsection
@section('footer_append')
    @include('layouts.footers.requestmanage.modify_a')
    <script src="{{ asset('assets/js/invoice.edit.js') }}"></script>
    @include('layouts.footers.requestmanage.exclude')
    <script>
    @can($__env->yieldContent('self_modify')) const outFlag = true; @endcan
    @can($__env->yieldContent('self_modify'))
    /**
     * Ajaxでフォームをサブミット
     * @param selector
     * @param successHandle
     * @param hasFile
     */

    function ajaxSubmitForm(selector, successHandle, hasFile = false) {
        const obj = lockAjax();
        let action = $(selector).attr('action');
        let ajaxSetting = {
            url: action,
            type: "post",
            success: function (res) {
                ajaxSuccessAction(res,function (res) {
                    successHandle();
                });
            },
            error: function (jqXHR, testStatus, error) {
                ajaxErrorAction(jqXHR, testStatus, error);
            },complete:function (event) {
                removeCommonInput(selector);
                unlockAjaxWhenFail(obj,event);
            }
        };
        ajaxSetting.data = new FormData($(selector).get(0));
        ajaxSetting.contentType = false;
        ajaxSetting.processData = false;
        $.ajax(ajaxSetting);
    }
    @endcan
    </script>
@endsection
