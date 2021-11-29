<div class="tab-content">
    <div class="pro-overview tab-pane fade show active">
<div class="row">
    <div class="col-sm-3 col-md-3 col-lg-2 col-xl-3">
        @can($__env->yieldContent('permission_modify'))
        <a href="#" class="btn btn-primary btn-block" data-toggle="modal" data-target="#add_role"><i class="fa fa-plus"></i> {{ __('役割の追加') }}</a>
        @endcan
        <div class="roles-menu">
            <ul class="" data-toggle="tabs" role="tablist">
                @foreach ($roles as $index=>$role)
                    <li role="presentation" class="{{$index==0 ? 'active' : ''}}">
                        <a class="tab-link" href="javascript:void(0)"  tab="top-tab{{$loop->iteration}}">{{ $role->title}}
                            @can($__env->yieldContent('permission_modify'))
                            <span class="role-action">
                                    <span class="action-circle large edit_role" data-toggle="modal" data-target="#edit_role"
                                          data-id="{{$role->id}}">
                                        <i class="material-icons">{{ __('edit') }}</i>
                                    </span>
                                @if($role->id>8)
                                    <span class="action-circle large delete-btn" data-toggle="modal" data-target="#delete_role" data-id="{{$role->id}}">
                                        <i class="material-icons">{{ __('delete') }}</i>
                                    </span>
                                @endif
                            </span>
                            @endcan
                        </a>
                    </li>
                    @push('tabs')
                        <div class="role-tab" role="tab-item" id="top-tab{{$loop->iteration}}">
                            <form autocomplete="off">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-9"><h4 class="role-title">{{$role->title}}{{ __('のモジュールアクセス設定') }}</h4></div>
                                            <div class="col-3">
                                                @if(auth()->user()->roles[0]->id==1)
                                                    <button class="btn btn-primary float-right roles-setting-btn" type="button"
                                                    onclick="EditRole(this);" data-id="{{$role->id}}">{{__('Submit')}}</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row" >
                                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" >
                                                    <div class="table-responsive role-table">
                                                        <table class="table home-info">
                                                            <tr>
                                                                <th colspan="3" style="font-weight: 400">{{ __('ホーム') }}</th>
                                                            </tr>
                                                            <tbody>
                                                            <tr class="checked-radio">
                                                                <td><label><input type="checkbox" name="home_show[]" value="87" {{$role->permissions->pluck('title')->contains('in-house_information_statistics') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>{{ __('　　社内情報統計') }}</label></td>
                                                                <td><label><input type="checkbox" name="home_show[]" value="88" {{$role->permissions->pluck('title')->contains('annual_change_statistics') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>{{ __('　　年度変化統計') }}</label></td>
                                                                <td><label><input type="checkbox" name="home_show[]" value="89" {{$role->permissions->pluck('title')->contains('sales_information_statistics') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>{{ __('　　売上情報統計') }}</label></td>
                                                            </tr>
                                                            <tr class="checked-radio">
                                                                <td><label><input type="checkbox" name="home_show[]" value="90" {{$role->permissions->pluck('title')->contains('business_information') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>{{ __('　　営業情報') }}</label></td>
                                                                <td><label><input type="checkbox" name="home_show[]" value="91" {{$role->permissions->pluck('title')->contains('cryptocurrency') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>{{ __('　　仮想通貨') }}</label></td>
                                                                <td><label><input type="checkbox" name="home_show[]" value="92" {{$role->permissions->pluck('title')->contains('stock_information') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>{{ __('　　株式情報') }}</label></td>
                                                            </tr>
                                                            <tr class="checked-radio">
                                                                <td><label><input type="checkbox" name="home_show[]" value="93" {{$role->permissions->pluck('title')->contains('economic_news') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>{{ __('　　経済ニュース') }}</label></td>
                                                                <td><label><input type="checkbox" name="home_show[]" value="94" {{$role->permissions->pluck('title')->contains('international_news') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>{{ __('　　国際ニュース') }}</label></td>
                                                                <td></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{ __('請求管理') }}</th>
                                                                    <th class="text-center width-14">{{ __('非表示') }}</th>
                                                                    <th class="text-center width-14">{{ __('表示のみ') }}</th>
                                                                    <th class="text-center width-14">{{ __('操作可') }}</th>
                                                                    <th class="text-center width-42">{{ __('自己分のみ表示・操作可') }}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr class="checked-radio">
                                                                <td>{{ __('取引先管理') }}</td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="1" name="permission[client]" {{$role->permissions->pluck('title')->contains('client_hide') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="2" name="permission[client]" {{$role->permissions->pluck('title')->contains('client_view') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="3" name="permission[client]" {{$role->permissions->pluck('title')->contains('client_modify') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center"></td>
                                                            </tr>
                                                            <tr class="checked-radio">
                                                                <td>{{ __('見積書管理') }}</td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="6" name="permission[estimate]" {{$role->permissions->pluck('title')->contains('estimate_hide') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="7" name="permission[estimate]" {{$role->permissions->pluck('title')->contains('estimate_view') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="8" name="permission[estimate]" {{$role->permissions->pluck('title')->contains('estimate_modify') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>

                                                                </td>
                                                                <td class="text-center"></td>
                                                            </tr>
                                                            <tr class="checked-radio">
                                                                <td>{{ __('注文書管理') }}</td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="11" name="permission[expense]" {{$role->permissions->pluck('title')->contains('expense_hide') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="12" name="permission[expense]" {{$role->permissions->pluck('title')->contains('expense_view') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="13" name="permission[expense]" {{$role->permissions->pluck('title')->contains('expense_modify') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    @if($role->id==8)
                                                                        <input type="radio" class="removeInput" checked>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr class="checked-radio">
                                                                <td>{{ __('注文請書管理') }}</td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="16" name="permission[orderconfirm]" {{$role->permissions->pluck('title')->contains('orderconfirm_hide') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="17" name="permission[orderconfirm]" {{$role->permissions->pluck('title')->contains('orderconfirm_view') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="18" name="permission[orderconfirm]" {{$role->permissions->pluck('title')->contains('orderconfirm_modify') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center"></td>
                                                            </tr>
                                                            <tr class="checked-radio">
                                                                <td>{{ __('請求書管理') }}</td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="21" name="permission[invoice]" {{$role->permissions->pluck('title')->contains('invoice_hide') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="22" name="permission[invoice]" {{$role->permissions->pluck('title')->contains('invoice_view') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="23" name="permission[invoice]" {{$role->permissions->pluck('title')->contains('invoice_modify') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    @if($role->id==8)
                                                                        <input type="radio" class="removeInput" checked>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr class="checked-radio">
                                                                <td>{{ __('送付状管理') }}</td>

                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="27" name="permission[transmittal]" {{$role->permissions->pluck('title')->contains('transmittal_hide') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td></td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="28" name="permission[transmittal]" {{$role->permissions->pluck('title')->contains('transmittal_modify') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="29" name="permission[transmittal]" {{$role->permissions->pluck('title')->contains('transmittal_self_modify') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                            <tr class="checked-radio">
                                                                <td>{{ __('初期設定') }}</td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="32" name="permission[requestsetting]"  {{$role->permissions->pluck('title')->contains('requestsetting_hide') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="33" name="permission[requestsetting]"  {{$role->permissions->pluck('title')->contains('requestsetting_view') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="34" name="permission[requestsetting]"  {{$role->permissions->pluck('title')->contains('requestsetting_modify') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    @if($role->id==8)
                                                                        <input type="radio" class="removeInput" checked>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                        <table class="table">
                                                            <thead>
                                                            <tr>
                                                                <th rowspan="2" style="vertical-align: middle">{{ __('人事管理') }}</th>
                                                                <th colspan="3" class="text-center border-left">{{ __('PC側') }}</th>
                                                                <th colspan="3" class="text-center border-left">{{ __('スマホ側') }}</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center border-left width-14">{{ __('非表示') }}</th>
                                                                <th class="text-center width-14">{{ __('表示のみ') }}</th>
                                                                <th class="text-center width-14">{{ __('操作可') }}</th>
                                                                <th class="text-center border-left width-14">{{ __('非表示') }}</th>
                                                                <th class="text-center width-14">{{ __('審査') }}</th>
                                                                <th class="text-center width-14">{{ __('操作可') }}</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>{{ __('社員管理') }}</td>
                                                                <td class="text-center pl-0 pr-0 vertical-middle">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="37" name="permission[employee]" {{$role->permissions->pluck('title')->contains('employee_hide') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center pl-0 pr-0 vertical-middle">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="38" name="permission[employee]"  {{$role->permissions->pluck('title')->contains('employee_view') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center pl-0 pr-0 vertical-middle">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="39" name="permission[employee]" {{$role->permissions->pluck('title')->contains('employee_modify') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td rowspan="3" class="pl-0 pr-0 text-center border-left vertical-middle">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="57" name="permission[mobile]" {{$role->permissions->pluck('title')->contains('mobile_hide') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td  rowspan="3" class="pl-0 pr-0 text-center vertical-middle">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="58" name="permission[mobile]" {{$role->permissions->pluck('title')->contains('mobile_audit') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td  rowspan="3" class="pl-0 pr-0 text-center vertical-middle">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="59" name="permission[mobile]" {{$role->permissions->pluck('title')->contains('mobile_modify') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('勤務管理') }}</td>
                                                                <td class="text-center pl-0 pr-0 vertical-middle">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="42" name="permission[attendance]" {{$role->permissions->pluck('title')->contains('attendance_hide') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center pl-0 pr-0 vertical-middle">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="43" name="permission[attendance]" {{$role->permissions->pluck('title')->contains('attendance_view') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center pl-0 pr-0 vertical-middle">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="44" name="permission[attendance]" {{$role->permissions->pluck('title')->contains('attendance_modify') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('休暇管理') }}</td>
                                                                <td class="text-center pl-0 pr-0 vertical-middle">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="47" name="permission[leave]" {{$role->permissions->pluck('title')->contains('leave_hide') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center pl-0 pr-0 vertical-middle">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="48" name="permission[leave]" {{$role->permissions->pluck('title')->contains('leave_view') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center pl-0 pr-0 vertical-middle">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="49" name="permission[leave]" {{$role->permissions->pluck('title')->contains('leave_modify') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('初期設定') }}</td>
                                                                <td class="text-center pl-0 pr-0 vertical-middle">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="52" name="permission[hrsetting]" {{$role->permissions->pluck('title')->contains('hrsetting_hide') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center pl-0 pr-0 vertical-middle">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="53" name="permission[hrsetting]" {{$role->permissions->pluck('title')->contains('hrsetting_view') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                <td class="text-center pl-0 pr-0 vertical-middle">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="54" name="permission[hrsetting]" {{$role->permissions->pluck('title')->contains('hrsetting_modify') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>

                                                                </td>
                                                                <td class="text-center pl-0 pr-0 vertical-middle border-left">
                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                        <table class="table">
                                                            <thead>
                                                            <tr>
                                                                <th>{{ __('資産管理') }}</th>
                                                                <th class="text-center width-14">{{ __('非表示') }}</th>
                                                                <th class="text-center width-14">{{ __('表示のみ') }}</th>
                                                                <th class="text-center width-14">{{ __('操作可') }}</th>
                                                                <th class="text-center width-42">{{ __('自己分のみ表示・操作可') }}</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr class="checked-radio">
                                                                <td>{{ __('設備管理') }}</td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="60" name="permission[asset]" {{$role->permissions->pluck('title')->contains('asset_hide') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="61" name="permission[asset]" {{$role->permissions->pluck('title')->contains('asset_view') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="62" name="permission[asset]" {{$role->permissions->pluck('title')->contains('asset_modify') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                </td>
                                                            </tr>
                                                            <tr class="checked-radio">
                                                                <td>{{ __('領収書管理') }}</td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="65" name="permission[receipt]" {{$role->permissions->pluck('title')->contains('receipt_hide') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td></td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="66" name="permission[receipt]" {{$role->permissions->pluck('title')->contains('receipt_modify') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="67" name="permission[receipt]" {{$role->permissions->pluck('title')->contains('receipt_self_modify') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                            <tr class="checked-radio">
                                                                <td>{{ __('初期設定') }}</td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="70" name="permission[assetsetting]" {{$role->permissions->pluck('title')->contains('assetsetting_hide') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="71" name="permission[assetsetting]" {{$role->permissions->pluck('title')->contains('assetsetting_view') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="72" name="permission[assetsetting]" {{$role->permissions->pluck('title')->contains('assetsetting_modify') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center"></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                        <table class="table setting-table" style="width: 58%">
                                                            <thead>
                                                            <tr>
                                                                <th>{{ __('その他') }}</th>
                                                                <th class="text-center" style="width: 24%">{{ __('非表示') }}</th>
                                                                <th class="text-center" style="width: 24%">{{ __('表示のみ') }}</th>
                                                                <th class="text-center" style="width: 24%">{{ __('操作可') }}</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr class="checked-radio">
                                                                <td>{{ __('ユーザー管理') }}</td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="75" name="permission[user]" {{$role->permissions->pluck('title')->contains('user_hide') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="76" name="permission[user]" {{$role->permissions->pluck('title')->contains('user_view') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                        <input type="radio" value="77" name="permission[user]" {{$role->permissions->pluck('title')->contains('user_modify') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                            <tr class="checked-radio">
                                                                <td>{{ __('設定') }}</td>

                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                    <input type="radio" value="80" name="permission[setting]" {{$role->permissions->pluck('title')->contains('setting_hide') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    <label class="cursor-point w-100 h-100">
                                                                    <input type="radio" value="81" name="permission[setting]" {{$role->permissions->pluck('title')->contains('setting_view') ? "checked" : ""}} @if(auth()->user()->roles[0]->id!=1) disabled @endif>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center">
                                                                    @if($role->id==1)<input type="radio" class="removeInput" value="" checked onclick="return false">@endif
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="alert alert-primary print-success-msg" style="display:none">
                                            <ul class="mb-0"></ul>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endpush
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col-sm-9 col-md-9 col-lg-10 col-xl-9">

        <div class="m-b-30">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 tab-content">
                    @stack('tabs')
                </div>
            </div>

        </div>
    </div>
</div>
    </div></div>

<!-- Add Role Modal -->
<div id="add_role" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('役割の追加') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    <input id="edit_role_id" name="id" type="hidden">
                    <div class="form-group">
                        <label for="validationDefault01">{{ __('役割名') }}<span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="title">
                        @error('title')
                        <div class="invalid-div">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="button" onclick="addRole()">{{ __('追加') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Add Role Modal -->

<!-- Edit Role Modal -->
<div class="modal custom-modal fade" id="edit_role" role="dialog" data-edit="{{ route('roles.edit', ':id') }}"
     data-update="{{ route("roles.update", ':id') }}">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-md">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('役割の編集') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="edit_role_form">
                    @csrf
                    @method('PUT')
                    <input id="edit_role_id" name="id" type="hidden">
                    <div class="form-group">
                        <label>{{ __('役割名') }}<span class="text-danger">*</span></label>
                        <input class="form-control" value="" type="text" name="title" id="edit_role_title">
                        @error('title')
                        <div class="invalid-div">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="button">{{ __('保存') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- /Edit Role Modal -->

<!-- Delete Role Modal -->
<div class="modal custom-modal fade" id="delete_role" role="dialog" data-route="{{ route('roles.destroy', ':id') }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>{{ __('役割の削除') }}</h3>
                    <p>{{ __('削除してもよろしいですか？') }}</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <a href="javascript:void(0);" id="delete_role_btn" class="btn btn-primary continue-btn">{{ __('削除') }}</a>
                        </div>
                        <div class="col-6">
                            <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">{{ __('キャンセル') }}</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Delete Role Modal -->
<script>

    function openOrCloseSwitch(e) {
        const start = parseInt($(e).val());
        const form = $(e).parents('form');
        const flag = $(e).is(':checked');
        form.find("input[name='permission["+start+"]']").prop('checked',flag);
        form.find("input[name='permission["+(start+1)+"]']").prop('checked',flag);
        if($(e).attr('name')==='invoice') form.find("input[name='permission["+(start+4)+"]']").prop('checked',flag);
    }
    function changeSwitch(e) {
        const flag = $(e).is(':checked');
        const target = $(e).data('target');
        const form = $(e).parents('form');
        form.find("input[name='"+target+"']").prop('checked',flag);
        if(!flag) $(e).parent().parent().find('input').prop('checked',false);
    }
    function addRole() {
        const modal = $('#add_role');
        const form = modal.find('form');
        $.post(form.attr('action'),form.serialize(),function (res) {
            if(res.status==='success'){
                const data = res.data;
                printSuccessMsg(res.message);
                const ul = $('div.roles-menu ul');
                const li = ul.find('li').first().clone();
                const span = li.find('a>span').clone();
                const edit_span = span.find('span.edit_role');
                let del_span = '<span class="action-circle large delete-btn" data-toggle="modal" data-target="#delete_role" data-id=":id">' +
                    '<i class="material-icons">{{ __("delete") }}</i></span>';
                edit_span.attr('data-id',data.id);
                del_span = del_span.replace(':id',data.id);
                const a = li.find('a');
                a.html(data.title);
                a.append(span);
                ul.append(li);
                ul.find('.role-action:last').append(del_span);
                $('.role-tab:last').after($('.role-tab:first').clone());
                $('.role-tab:last').attr('id','top-tab'+data.id);
                $('.role-table:last').find('input[type=radio]').removeAttr('disabled');
                $('.role-table:last').find('input[type=checkbox]').removeAttr('checked');
                $('.role-tab:last .role-title').html(data.title+'のモジュールアクセス設定');
                $('.setting-table:last tr').find('input[type=radio]:first').click();
                $('.setting-table:last').find('.removeInput').remove();
                $('.roles-setting-btn:last').removeAttr('disabled').data('id',data.id);
                ul.find('.tab-link:last').attr('tab','top-tab'+data.id).click();
                modal.modal('hide');
            }
            else printErrorMsg(res.message);
        });
    }
    function printSuccessMsg(message) {
        $.notify(message);
    }
</script>
