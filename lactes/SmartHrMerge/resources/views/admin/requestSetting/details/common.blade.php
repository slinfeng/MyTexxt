@section('title_delete', __('口座情報'))
@section('function_delete', __('bankCardDelete()'))
<!-- Payroll Additions Table -->
<div class="tab-content">
    <div id="emp_profile" class="pro-overview tab-pane fade show active">
        <div class="card">
                <form action="{{route('requestSetting.update',-1)}}" method="post">
                    @csrf
                    @method('put')
                    <div class="row m-0">
                        <div class="card-header w-100">
                            <h4 class="m-0">{{ __('共通設定') }}</h4>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body">
                                <table class="table-left-setting w-100">
                                    <tr>
                                        <td>
                                            {{ __('税率') }}
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="text" class="form-control text-right float" name="tax_rate" value="{{$requestSettingExtra->tax_rate}}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">%</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ __('管理サーバIPアドレス') }}
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="local_ip_addr" value="{{$requestSettingExtra->local_ip_addr}}">
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6 p-0">
                            <div class="card-body p-0">
                                <table class="table-left-setting w-100" style="margin-top: 20px">
                                    <tr style="height: 72.8px">
                                        <td>
                                            {{ __('印刷フォント') }}
                                        </td>
                                        <td>
                                            @foreach($fontFamilyTypes as $fontFamilyType)
                                                <label class="w-30"><input type="radio" name="font_family_type_id" value="{{$fontFamilyType->id}}" data-fontfamily="{{$fontFamilyType->font_family}}" {{$requestSettingExtra->font_family_type_id==$fontFamilyType->id?'checked':''}}><span style="font-family: {{$fontFamilyType->font_family}}"> {{$fontFamilyType->font_family_name}} </span></label>
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ __('クラウドで請求保存期間') }}
                                        </td>
                                        <td>
                                            <div class="input-group pr-25">
                                                <select name="cloud_request_period" class="form-control w-100">
                                                    <option value="0" {{$requestSettingExtra->cloud_request_period==0?'selected':''}}>保存しない</option>
                                                    <option value="3" {{$requestSettingExtra->cloud_request_period==3?'selected':''}}>三ヶ月</option>
                                                    <option value="6" {{$requestSettingExtra->cloud_request_period==6?'selected':''}}>六ヶ月</option>
                                                    <option value="12" {{$requestSettingExtra->cloud_request_period==12?'selected':''}}>一年</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    @can($__env->yieldContent('permission_modify'))
                    <div class="card-footer bg-whitesmoke text-md-right">
                        <button class="btn btn-primary" type="submit" onclick="requestSettingSubmit(this);return false">保存</button>
                    </div>
                    @endcan
                </form>
            </div>

        <input type="hidden" name="bankInfoGet" data-url="{{route('requestSetting.bankInfoGet')}}">
        <div class="row">
            @foreach($bankAccount as $key=>$bank)
                <div class="col-md-6 bankCard">
                    <div class="card profile-box flex-fill">
                        <div class="card-header col-md-12 position-relative">
                            <h4 class="m-0">{{ __('口座情報')}} {{($key+1)}}</h4>
                            @can($__env->yieldContent('permission_modify'))
                            <button type="button" class="close" onclick="bankCardDeleteData(this)" data-toggle="modal"
                                    data-target="#delete" data-url="{{route('requestSetting.destroy',':id')}}">
                                <span aria-hidden="true">×</span>
                            </button>
                            @endcan
                        </div>
                        <form action="{{route('requestSetting.bankInfoAddOrEdit')}}" method="post">
                            <input name="id" value="{{$bank->id}}" type="hidden">
                            <div class="card-body" id="employee-address-container" >
                                <table class="table-left-setting w-100">
                                    <tr>
                                        <td>
                                            {{ __('銀行名') }}
                                        </td>
                                        <td>
                                            <input name="bank_name" value="{{$bank->bank_name}}" type="text" class="form-control" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ __('支店名') }}
                                        </td>
                                        <td>
                                            <input name="branch_name" value="{{$bank->branch_name}}" type="text" class="form-control" required>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ __('支店番号') }}
                                        </td>
                                        <td>
                                            <input name="branch_code" value="{{$bank->branch_code}}" type="text" class="form-control number" required>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ __('預金種類') }}
                                        </td>
                                        <td>
                                            <select class="select form-control" name="account_type">
                                                @foreach($bank_account_types as $bankAccountType)
                                                    <option value="{{$bankAccountType->id}}" {{$bank->account_type==$bankAccountType->id?'selected':''}}>{{$bankAccountType->account_type_name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ __('口座番号') }}
                                        </td>
                                        <td>
                                            <input name="account_num" value="{{$bank->account_num}}" type="text" class="form-control number" required>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ __('口座名義') }}
                                        </td>
                                        <td>
                                            <input name="account_name" value="{{$bank->account_name}}" type="text" class="form-control" required>

                                        </td>
                                    </tr>
                                </table>

                            </div>
                            @can($__env->yieldContent('permission_modify'))
                            <div class="card-footer bg-whitesmoke text-md-right">
                                <button class="btn btn-primary" type="button" onclick="bankInfoSave(this);">保存</button>
                            </div>
                            @endcan
                        </form>

                    </div>
                </div>
            @endforeach
                <div class="col-md-6 bankCard" style="display: none!important;">
                    <div class="card profile-box flex-fill">
                        <div class="card-header col-md-12 position-relative">
                            <h4 class="m-0">{{ __('口座情報')}}</h4>
                            <button type="button" class="close" onclick="bankCardDeleteData(this)" data-toggle="modal"
                                    data-target="#delete" data-url="{{route('requestSetting.destroy',':id')}}">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form action="{{route('requestSetting.bankInfoAddOrEdit')}}" method="post">
                            <input name="id" value="" type="hidden">
                            <div class="card-body" id="employee-address-container" >
                                <table class="table-left-setting w-100">
                                    <tr>
                                        <td>
                                            {{ __('銀行名') }}
                                        </td>
                                        <td>
                                            <input name="bank_name" value="" type="text" class="form-control" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ __('支店名') }}
                                        </td>
                                        <td>
                                            <input name="branch_name" value="" type="text" class="form-control" required>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ __('支店番号') }}
                                        </td>
                                        <td>
                                            <input name="branch_code" value="" type="text" class="form-control" required>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ __('預金種類') }}
                                        </td>
                                        <td>
                                            <select class="form-control" name="account_type">
                                                @foreach($bank_account_types as $bankAccountType)
                                                    <option value="{{$bankAccountType->id}}" {{$bankAccountType->id==1?'selected':''}}>{{$bankAccountType->account_type_name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ __('口座番号') }}
                                        </td>
                                        <td>
                                            <input name="account_num" value="" type="text" class="form-control" required>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ __('口座名義') }}
                                        </td>
                                        <td>
                                            <input name="account_name" value="" type="text" class="form-control" required>

                                        </td>
                                    </tr>
                                </table>

                            </div>
                            <div class="card-footer bg-whitesmoke text-md-right">
                                @can($__env->yieldContent('permission_modify'))
                                    <button class="btn btn-primary" type="button" onclick="bankInfoSave(this)">保存</button>
                                @endcan
                            </div>
                        </form>

                    </div>
                </div>
            <div class="col-md-12 text-center">
                @can($__env->yieldContent('permission_modify'))
                <div class="text-center mb-4 clearfix">
                    <button class="btn btn-primary add-btn" type="button" onclick="bankCardAdd()">口座情報追加</button>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
