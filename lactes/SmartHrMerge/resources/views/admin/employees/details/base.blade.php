<div class="col-md-6">
    <div class="card-body">
{{--      profile-info-left  右边框虚线--}}
        <div class="row m-0">
            <div class="profile-img-div col-md-2 position-relative p-0">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="fileinput-new thumbnail img-thumbnail" style="@if($employeeBase->icon!=$employeeBase->data_history['icon']) border-color:orange; @endif">
                        <div class="cutIconShow w-100" style="overflow: hidden" onclick="photoShow($(this).find('img')[0])">
                            <img>
                        </div>
                    </div>
                    <div class="w-100 mt-1 text-center adminModifyAfter position-absolute">
                    <span class="btn btn-file file-btns">
                        <span class="file-select-btn" onclick="$('input[name=icon-temporary]').click()"> 画像を選択 </span>
                    </span>
                        <div style="display: none">
                            <input type="file" class="iconFile hidden" name="icon-temporary" oninput="photoCut(this)">
                            <input type="file" class="iconFile hidden" name="icon" oninput="photoCut(this)">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body col-md-10">
                <div class="">
                    <table class="w-100 employee-table">
                        <tr class="name-phonetic">
                            <td class="title adminModifyAfter">{{ __('フリガナ') }}</td>
                            <td class="history">{{$employeeBase->data_history['name_phonetic']}}</td>
                            <td class="modify">{{ $employeeBase->name_phonetic}}</td>
                            <td class="enter">
                                <input name="name_phonetic" value="{{$employeeBase->name_phonetic}}" onchange="value=value.replace(/[^\u30A0-\u30FF\u3000 ]/g,'')"
                                       type="text" class="form-control">
                            </td>
                        </tr>
                        <tr class="name">
                            <td class="title adminModifyAfter">{{ __('氏名') }}</td>
                            <td class="history">{{ $user->name}}</td>
                            <td class="modify" colspan="2">{{ $user->name}}</td>
                            <td class="enter">
                                <input name="" value="{{ $user->name}}"
                                       type="text" class="form-control" readonly>
                            </td>
                        </tr>
                        <tr class="name-roman">
                            <td class="title adminModifyAfter">{{ __('ローマ字') }}</td>
                            <td class="history">{{$employeeBase->data_history['name_roman']}}</td>
                            <td class="modify">{{ $employeeBase->name_roman}}</td>
                            <td class="enter">
                                <input name="name_roman" value="{{$employeeBase->name_roman}}" onchange="value=value.replace(/[^a-zA-Z\u3000 ]/g,'')"
                                       type="text" class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <td class="title">{{ __('社員番号') }}</td>
                            <td class="history">{{ $employeeBase->employee_code}}</td>
                            <td class="modify">{{ $employeeBase->employee_code}}</td>
                            <td class="enter">
                                <input name="employee_code" value="{{$employeeBase->employee_code}}"
                                       type="text" class="form-control number" onblur="employeeCodeCheck(this)">
                            </td>
                        </tr>
                        <tr>
                            <td class="title">{{ __('国籍') }}</td>
                            <td class="history">{{$employeeBase->data_history['nationality']}}</td>
                            <td class="modify">{{ $employeeBase->nationality}}</td>
                            <td class="enter">
                                <input name="nationality" value="{{$employeeBase->nationality}}"
                                       type="text" class="form-control">
                            </td>
                        </tr>
                        <tr class="date-western">
                            <td class="title" rowspan="2">{{ __('生年月日') }}</td>
                            <td class="adminModifyBefore">
                                <span class="date-val date-history m-0">{{$employeeBase->data_history['birthday']}}</span>
                                <span class="date-japan"></span>
                            </td>
                        </tr>
                        <tr class="date-western">
                            <td class="adminModifyBefore">
                                <span class="date-val date-modify m-0">{{ $employeeBase->birthday}}</span>
                                <span class="date-japan"></span>
                            </td>
                            <td class="enter">
                                <input name="birthday" value="{{$employeeBase->birthday}}"
                                       type="text" class="dateInput form-control">
                            </td>
                        </tr>
                        <tr class="adminModifyBefore">
                            <td class="title">{{ __('年齢') }}</td>
                            <td class="ageVal color-grey">0歳</td>
                        </tr>
                        <tr>
                            <td class="title">{{ __('Sex') }}</td>
                            <td class="history">
                                {{$employeeBase->data_history['sex']==0 ? __('Male') :($employeeBase->data_history['sex']==1 ? __('Female') :($employeeBase->data_history['sex']==2 ? __('Unisex') :""))}}
                            </td>
                            <td class="modify">{{$employeeBase->sex==0 ? __('Male') :($employeeBase->sex==1 ? __('Female') :($employeeBase->sex==2 ? __('Unisex') :""))}}</td>
                            <td class="enter">
                                <select class="select form-control" name="sex">
                                    <option value="0" {{$employeeBase->sex==0 ?  "selected" : ""}}>{{ __('Male') }}</option>
                                    <option value="1" {{$employeeBase->sex==1 ?  "selected" : ""}}>{{ __('Female') }}</option>
                                    <option value="2" {{$employeeBase->sex==2 ?  "selected" : ""}}>{{ __('Unisex') }}</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{--<div class="profile-basic">--}}
{{--    <div class="row">--}}
{{--<div class="col-md-1"></div>--}}
<div class="col-md-6">
    <div class="card-body">
        <div class="card-body">
            <table class="w-100 employee-table">
                <tr>
                    <td class="title">{{ __('部門') }}</td>
                    <td class="modify">
                        @foreach ($departments as $department)
                            {{$employeeBase->department_type_id==$department['id'] ?  $department['department_name'] : ""}}
                        @endforeach
                    </td>
                    <td class="enter">
                        <select class="select form-control" name="department_type_id" required>
                            @foreach ($departments as $department)
                                <option value="{{$department['id']}}"  {{$employeeBase->department_type_id==$department['id'] ?"selected":""}}  >{{$department['department_name']}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="title">{{ __('契約形態') }}</td>
                    <td class="modify">
                        @foreach ($hireType as $hire_type)
                            {{$employeeBase->hire_type_id==$hire_type['id'] ?  $hire_type['hire_type'] : ""}}
                        @endforeach
                    </td>
                    <td class="enter">
                        <select class="select form-control" name="hire_type_id" required>
                            @foreach ($hireType as $hire_type)
                                <option value="{{$hire_type['id']}}"  {{$employeeBase->hire_type_id==$hire_type['id'] ?  "selected" : ""}}  >{{$hire_type['hire_type']}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="title">{{ __('役職') }}</td>
                    <td class="modify">
                        @foreach ($positionType as $position)
                            {{$employeeBase->position_type_id==$position['id'] ?  $position['position_type'] : ""}}
                        @endforeach
                    </td>
                    <td class="enter">
                        <select class="select form-control" name="position_type_id" required>
                            @foreach ($positionType as $position)
                                <option value="{{$position['id']}}"  {{$employeeBase->position_type_id==$position['id'] ?  "selected" : ""}}  >{{$position['position_type']}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="title">{{ __('在職区分') }}</td>
                    <td class="modify">
                        @foreach ($retireType as $retire_type)
                            {{$employeeBase->retire_type_id==$retire_type['id'] ?  $retire_type['retire_type'] : ""}}
                        @endforeach
                    </td>
                    <td class="enter">
                        <select class="select form-control" name="retire_type_id">
                            @foreach ($retireType as $retire_type)
                                <option value="{{$retire_type['id']}}"  {{$employeeBase->retire_type_id==$retire_type['id']?"selected":""}}  >{{$retire_type['retire_type']}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr class="date-western">
                    <td class="title">{{ __('入社日') }}</td>
                    <td class="adminModifyBefore">
                        <span class="date-val date-modify m-0">{{ $employeeBase->date_hire}}</span>
                        <span class="date-japan"></span>
                    </td>
                    <td class="enter">
                        <input name="date_hire" value="{{ $employeeBase->date_hire}}"
                               type="text" class="dateInput form-control">
                    </td>
                </tr>
                <tr class="date-western">
                    <td class="title">{{ __('退職日') }}</td>
                    <td class="adminModifyBefore">
                        <span class="date-val date-modify m-0">{{ $employeeBase->date_retire}}</span>
                        <span class="date-japan"></span>
                    </td>
                    <td class="enter">
                        <input name="date_retire" value="{{ $employeeBase->date_retire}}"
                               type="text" class="dateInput form-control">
                    </td>
                </tr>
                <tr class="adminModifyBefore">
                    <td class="title">{{ __('勤続年数') }}</td>
                    <td class="workYear color-grey">0年</td>
                </tr>
                <tr>
                    <td class="title">{{ __('Remark') }}</td>
                    <td class="modify">
                        {{ $employeeBase->remark}}
                    </td>
                    <td class="enter">
                        <textarea type="text" name="remark" rows="5" class="form-control" oninput="changeLength(this)">{{$employeeBase->remark}}</textarea>
                    </td>
                </tr>

            </table>
        </div>
    </div>
</div>

{{--    </div>--}}
{{--</div>--}}
