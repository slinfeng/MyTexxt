<div class="card-body">
    <h3 class="card-title"><strong>{{ __('銀行情報') }}</strong></h3>
    <h5 class="section-title"><strong>{{ __('') }}</strong></h5>
    <table class="w-100 employee-table">
        <tr>
            <td class="title">{{ __('銀行名') }}</td>
            <td class="history">{{$employeeBase->EmployeeBank->data_history['bank_name']}}</td>
            <td class="modify">{{ $employeeBase->EmployeeBank->bank_name}}</td>
            <td class="enter">
                <input name="bank_name" value="{{$employeeBase->EmployeeBank->bank_name}}"
                       type="text" class="form-control">
            </td>
        </tr>
        <tr>
            <td class="title">{{ __('支店名') }}</td>
            <td class="history">{{$employeeBase->EmployeeBank->data_history['branch_name']}}</td>
            <td class="modify">{{ $employeeBase->EmployeeBank->branch_name}}</td>
            <td class="enter">
                <input name="branch_name" value="{{$employeeBase->EmployeeBank->branch_name}}"
                       type="text" class="form-control">
            </td>
        </tr>
        <tr>
            <td class="title">{{ __('支店番号') }}</td>
            <td class="history">{{$employeeBase->EmployeeBank->data_history['branch_code']}}</td>
            <td class="modify">{{ $employeeBase->EmployeeBank->branch_code}}</td>
            <td class="enter">
                <input name="branch_code" value="{{$employeeBase->EmployeeBank->branch_code}}"
                       type="text" class="form-control">
            </td>
        </tr>
        <tr>
            <td class="title">{{ __('預金種類') }}</td>
            <td class="history">
                @foreach($bank_account_types as $bankAccountType)
                    {{$employeeBase->EmployeeBank->data_history['account_type']==$bankAccountType->id?$bankAccountType->account_type_name:""}}
                @endforeach
            </td>
            <td class="modify">
                @foreach($bank_account_types as $bankAccountType)
                    {{$employeeBase->EmployeeBank->account_type==$bankAccountType->id?$bankAccountType->account_type_name:""}}
                @endforeach
            </td>
            <td class="enter">
                <select class="select form-control" name="account_type">
                    @foreach($bank_account_types as $bankAccountType)
                        <option value="{{$bankAccountType->id}}" {{$employeeBase->EmployeeBank->account_type==$bankAccountType->id?'selected':''}}>{{$bankAccountType->account_type_name}}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td class="title">{{ __('口座番号') }}</td>
            <td class="history">{{$employeeBase->EmployeeBank->data_history['account_num']}}</td>
            <td class="modify">{{ $employeeBase->EmployeeBank->account_num}}</td>
            <td class="enter">
                <input name="account_num" value="{{$employeeBase->EmployeeBank->account_num}}"
                       type="text" class="form-control">
            </td>
        </tr>
        <tr>
            <td class="title">{{ __('口座名義') }}</td>
            <td class="history">{{$employeeBase->EmployeeBank->data_history['account_name']}}</td>
            <td class="modify">{{ $employeeBase->EmployeeBank->account_name}}</td>
            <td class="enter">
                <input name="account_name" value="{{$employeeBase->EmployeeBank->account_name}}"
                       type="text" class="form-control">
            </td>
        </tr>
    </table>
</div>
