<!-- Payroll Additions Table -->
<div class="tab-content">
    <div id="emp_profile" class="pro-overview tab-pane fade show active">
        <div class="card">
            <form action="{{route('HrSetting.update',1)}}" method="post">
                @csrf
                @method('put')
                <div class="row m-0">
                    <div class="card-header w-100">
                        <h4 class="m-0">{{ __('社員設定') }}</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="card-body">
                            <table class="table-left-setting w-100">
                                <tr>
                                    <td>
                                        {{ __('事業所番号') }}
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control text-right" name="office_number" value="{{$hrSetting->office_number}}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('勤続年数の表示') }}
                                    </td>
                                    <td>

                                        <label style="width: 40%">
                                            <input type="radio" name="calculate_work_years" value="0" {{$hrSetting->calculate_work_years==0?'checked':''}}>

                                            <span>{{ __('××.××年') }}</span>
                                        </label>
                                        <label style="width: 40%">
                                            <input type="radio" name="calculate_work_years" value="1" {{$hrSetting->calculate_work_years==1?'checked':''}}>
                                            <span>{{ __('××年××月') }}</span>
                                        </label>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('勤続月数の計算') }}
                                        <br>
                                        <span class="text-info" style="font-size: 12px">{{ __('(1ヶ月不満の場合)') }}</span>

                                    </td>
                                    <td>
                                        <label style="width: 40%">
                                            <input type="radio" name="calculate_work_months" value="0" {{$hrSetting->calculate_work_months==0?'checked':''}}>
                                            <span>{{ __('加算する') }}</span>
                                        </label>
                                        <label style="width: 40%">
                                            <input type="radio" name="calculate_work_months" value="1" {{$hrSetting->calculate_work_months==1?'checked':''}}>
                                            <span>{{ __('切り捨て') }}</span>
                                        </label>

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

                                    </td>
                                    <td>

                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                @can($__env->yieldContent('permission_modify'))
                    <div class="card-footer bg-whitesmoke text-md-right">
                        <button class="btn btn-primary" type="button" onclick="hrSettingSubmit(this)">保存</button>
                    </div>
                @endcan
            </form>
        </div>
        <div class="faq-card">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <a class="collapsed" data-toggle="collapse" href="#collapseOne" aria-expanded="false">部門</a>
                    </h4>
                </div>
                <div id="collapseOne" class="card-collapse collapse">
                    <div class="card-body">
                        @include('admin.HrSetting.details.employee-department')
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false">契約形態</a>
                    </h4>
                </div>
                <div id="collapseTwo" class="card-collapse collapse">
                    <div class="card-body">
                        @include('admin.HrSetting.details.employee-hire')
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <a class="collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="false">役職</a>
                    </h4>
                </div>
                <div id="collapseThree" class="card-collapse collapse">
                    <div class="card-body">
                        @include('admin.HrSetting.details.employee-position')
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <a class="collapsed" data-toggle="collapse" href="#collapseFour" aria-expanded="false">在職区分</a>
                    </h4>
                </div>
                <div id="collapseFour" class="card-collapse collapse">
                    <div class="card-body">
                        @include('admin.HrSetting.details.employee-retire')
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <a class="collapsed" data-toggle="collapse" href="#collapseFive" aria-expanded="false">在留資格種類</a>
                    </h4>
                </div>
                <div id="collapseFive" class="card-collapse collapse">
                    <div class="card-body">
                        @include('admin.HrSetting.details.employee-residence')
                    </div>
                </div>
            </div>
            @include('admin.HrSetting.details.modal')
        </div>
    </div>
</div>
