<!-- Payroll Additions Table -->
<div class="tab-content">
    <div id="emp_profile" class="pro-overview tab-pane fade show active">
        <div class="card">
            <form action="{{route('HrSetting.update',3)}}" method="post">
                @csrf
                @method('put')
                <div class="row m-0">
                    <div class="card-header w-100">
                        <h4 class="m-0">{{ __('休暇設定') }}</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="card-body">
                            <table class="table-left-setting w-100">
                                <tr>
                                    <td>
                                        {{ __('年休起算日数') }}
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control text-right" name="first_year_leave" onchange="annualLeaveTableInit();" oninput="value=value.replace(/[^0-9]/g,'')" value="{{$hrSetting->first_year_leave}}">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">日</span>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('年毎に増加日数') }}
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control text-right" name="grow_leave" onchange="annualLeaveTableInit();" oninput="value=value.replace(/[^0-9]/g,'')" value="{{$hrSetting->grow_leave}}">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">日</span>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card-body">
                            <table class="table-left-setting w-100">
                                <tr>
                                    <td>
                                        {{ __('年休の加算期間') }}
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control text-right" name="cumulative_years" oninput="value=value.replace(/[^0-9]/g,'')" value="{{$hrSetting->cumulative_years}}">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">年</span>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('年休の上限日数') }}
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control text-right" name="max_annual_leave" onchange="annualLeaveTableInit();" oninput="value=value.replace(/[^0-9]/g,'')" value="{{$hrSetting->max_annual_leave}}">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">日</span>
                                            </div>
                                        </div>

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

        <div class="card">
            <form action="{{route('HrSetting.update',4)}}" method="post" id="annual_leave_form">
                @csrf
                @method('put')
            <div class="card-header">
                <div class="card-title">
                    <h4 class="m-0">{{ __('休暇設定') }}</h4>
                </div>
            </div>
                <div class="card-body">
                    <table class="table table-bordered w-100" id="annual_leave_table">
                        <thead>
                        <tr>
                            <th style="width: 15%;">社員</th>
                            <th style="width: 70px;">年休スイッチ</th>
                            <th style="width: 100px">{{date('Y')+1}}(プレビュー値)</th>
                            <th style="width: 200px;">{{date('Y')}}(本年度年休残り日数/本年度年休総日数)</th>
                            @for($i=1;$i<10;$i++)
                                <th>{{date('Y')-$i}}</th>
                            @endfor
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($employees as $key=>$employee)
                        <tr>
                            <td>{{$employee->User->name}}
                                <input type="hidden" name="date_hire" value="{{$employee->date_hire}}">
                                <input type="hidden" name="date_retire" value="{{$employee->date_retire}}">
                            </td>
                            <td><input type="hidden" name="employee_base_id[]" value="{{$employee->id}}">
                                    <div class="row col-auto ml-auto p-0 m-0">
                                        <div class="status-toggle p-0 m-0">
                                            <input type="checkbox" id="annual_leave_type{{$key}}" value="1" name="annual_leave_type[{{$key}}]" class="check" onclick="employeeAnnualLeaveChange(this);"
                                                @if($employee->annual_leave_type==1)
                                                   checked
                                                @endif>
                                            <label for="annual_leave_type{{$key}}" class="checktoggle">checkbox</label>
                                        </div>
                                    </div>
                            </td>
{{--                            <td><input type="hidden" name="idEmployeeIdYear[]" value="{{$employee->EmployeeAnnualLeave->where('year',date('Y')+1)->pluck('id')->first()}}{{'_'.$employee->id.'_'.(date('Y')+1)}}"><input name="days[]" value="{{$employee->EmployeeAnnualLeave->where('year',date('Y')+1)->pluck('days')->first()??''}}"></td>--}}
{{--                            <td>{{$employee->annual_leave_type==1?($employee->EmployeeAnnualLeave->where('year',date('Y'))->pluck('days')->first()?(((($employee->EmployeeAnnualLeave->where('year',date('Y'))->pluck('days')->first())+$hrSetting->grow_leave)<$hrSetting->max_annual_leave)?(($employee->EmployeeAnnualLeave->where('year',date('Y'))->pluck('days')->first())+$hrSetting->grow_leave):$hrSetting->max_annual_leave):''):''}}</td>--}}
                            <td></td>
                            <td><input type="hidden" name="idEmployeeIdYear[]" value="{{$employee->EmployeeAnnualLeave->where('year',date('Y'))->pluck('id')->first()}}{{'_'.$employee->id.'_'.(date('Y'))}}"><input type="text" oninput="value=value.replace(/[^0-9]/g,'')" name="has_days[]" value="{{$employee->annual_leave_type==1?($employee->EmployeeAnnualLeave->where('year',date('Y'))->pluck('has_days')->first()??''):''}}" style="width: 40%;">/<input style="width: 40%;" type="text" oninput="value=value.replace(/[^0-9]/g,'')" name="days[]" value="{{$employee->annual_leave_type==1?($employee->EmployeeAnnualLeave->where('year',date('Y'))->pluck('days')->first()??''):''}}" onchange="preValChange(this);"></td>
                            @for($i=1;$i<10;$i++)
                                <td>{{$employee->EmployeeAnnualLeave->where('year',date('Y')-$i)->pluck('has_days')->first()?$employee->EmployeeAnnualLeave->where('year',date('Y')-$i)->pluck('has_days')->first().'/'.$employee->EmployeeAnnualLeave->where('year',date('Y')-$i)->pluck('days')->first():''}}</td>
                            @endfor
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @can($__env->yieldContent('permission_modify'))
                <div class="card-footer bg-whitesmoke text-md-right">
                    <button class="btn btn-primary" id="annual_leave_btn" type="button" onclick="hrSettingSubmit(this)">保存</button>
                </div>
            @endcan
            </form>
        </div>
    </div>

</div>
