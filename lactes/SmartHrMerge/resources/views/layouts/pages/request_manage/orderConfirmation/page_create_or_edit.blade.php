@include('layouts.pages.sections.requestmanage.position_a')
<div class="modal-header">
    <h5 class="modal-title">注文請書 @yield('action_name')画面</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="alert alert-danger print-error-msg" style="display:none">
        <ul class="mb-0"></ul>
    </div>
    <form data-route="@yield('route_save')" method="POST" enctype="multipart/form-data">
        @csrf
        @yield('method')
        <div class="form-group">
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input onchange="disableFormat(this)" class="form-check-input" type="radio" name="our_position_type" value="@yield('position_a_val')"
                           @if(isset($data) && $data->our_position_type==$__env->yieldContent('position_a_val'))checked @endif
                           @if(!isset($data))checked @else data-position="{{$data->our_position_type}}" disabled @endif>
                    @yield('position_a_html')
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input onchange="disableFormat(this)" class="form-check-input" type="radio" name="our_position_type" value="@yield('position_b_val')"
                           @if(isset($data) && $data->our_position_type==$__env->yieldContent('position_b_val'))checked @endif
                    @if(isset($data))disabled @endif>
                    @yield('position_b_html')
                </label>
            </div>
        </div>
        <div class="form-group">
            <input hidden name="last_client_a" value="0">
            <input hidden name="last_client_b" value="0">
            <input hidden name="cname" value="@yield('cname')">
            @yield('client_input')
            <label>{{ __('取引先')}} <span class="text-danger">*</span></label>
            <select @if(isset($data))data-client="@yield('client_id')" disabled @endif
                    data-route="{{route('client.getClients')}}" name="client_id" onchange="changeNum()" class="select form-control">
                <option value="0">取引先を選択してください</option>
                @yield('client_info')
            </select>
        </div>

        <div class="form-group">
            <label>{{ __('作業期間')}} <span class="text-danger">*</span></label>
            <div class="cal-icon">
                <input data-create="@yield('period')" autocomplete="off" @yield('data-month')
                       class="form-control floating" type="text" name="period" value="@yield('period')">
            </div>
        </div>

        <div class="form-group">
            <label>{{ __('注文請書番号')}}</label>
            <input data-code="@yield('order_manage_code')" style="border: 1px solid #e3e3e3"
                data-route="{{route('confirmation.getNum')}}" class="form-control" type="text" name="order_manage_code" readonly="readonly"
                    value="@yield('order_manage_code')">
        </div>

        <div class="form-group">
            @yield('file_info')
            <label>{{ __('注文請書')}}</label><br/>
            <input type="button" class="btn btn-success btn-file-border" value="ファイルを選択" onclick="uploadFile(this)">
            <input class="width-100pct" onchange="showFileName(this)" hidden type="file" name="project_name_or_file_name">
            <span class="files-info">@yield('file_pre')</span>
        </div>
        @can($__env->yieldContent('permission_modify'))
        <div class="submit-section">
            <button type="button" onclick="saveAddOrEdit(true)" style="font-size:18px;width: 30%;height:50px;background-color:#FF9B44;display: inline-block;" class="btn btn-primary">{{ __('保存')}}</button>
        </div>
        @endcan
    </form>
</div>
