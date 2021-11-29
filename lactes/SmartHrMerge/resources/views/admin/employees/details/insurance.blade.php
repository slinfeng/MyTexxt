<div class="card-body">
    <h3 class="card-title"><strong>{{ __('保険・年金情報') }}</strong></h3>
    <input type="hidden" name="social_insurance" value="0">
    <input type="hidden" name="employment_insurance" value="0">
    <input type="hidden" name="national_health_insurance" value="0">
    <input type="hidden" name="national_pension_insurance" value="0">
    <h5>
        <label>
            <input class="checkboxInsurance" type="checkbox" {{$employeeBase->EmployeeInsurance->social_insurance==1?'checked':''}} name="social_insurance" value="1"> {{ __('社会保険（年金・健保）') }}
        </label>
    </h5>
    <table class="w-100 employee-table">
        <tr>
            <td class="title">{{ __('基礎年金番号') }}</td>
            <td class="modify">{{ $employeeBase->EmployeeInsurance->basic_pension_number}}</td>
            <td class="enter">
                <input name="basic_pension_number" value="{{$employeeBase->EmployeeInsurance->basic_pension_number}}"
                       type="text" class="form-control">
            </td>
        </tr>
        <tr>
            <td class="title">{{ __('記号') }}</td>
            <td class="modify">{{ $employeeBase->EmployeeInsurance->sign}}</td>
            <td class="enter">
                <input name="sign" value="{{$employeeBase->EmployeeInsurance->sign}}"
                       type="text" class="form-control">
            </td>
        </tr>
        <tr>
            <td class="title">{{ __('整理番号') }}</td>
            <td class="modify">{{ $employeeBase->EmployeeInsurance->organize_number}}</td>
            <td class="enter">
                <input name="organize_number" value="{{$employeeBase->EmployeeInsurance->organize_number}}"
                       type="text" class="form-control">
            </td>
        </tr>
        <tr class="date-western">
            <td class="title">{{ __('資格取得日') }}</td>
            <td class="date-val date-modify">{{ $employeeBase->EmployeeInsurance->social_start_date}}</td>
            <td class="date-japan"></td>
            <td class="enter">
                <input name="social_start_date" value="{{$employeeBase->EmployeeInsurance->social_start_date}}"
                       type="text" class="dateInput form-control flatpickr dateInput">
            </td>
        </tr>
        <tr>
            <td class="title">{{ __('基準額') }}</td>
            <td class="modify">{{ $employeeBase->EmployeeInsurance->base_amount}}</td>
            <td class="enter">
                <input name="base_amount" value="{{$employeeBase->EmployeeInsurance->base_amount}}"
                       type="text" class="amount form-control">
            </td>
        </tr>
        <tr class="date-western">
            <td class="title">{{ __('資格喪失日') }}</td>
            <td class="date-val date-modify">{{ $employeeBase->EmployeeInsurance->social_end_date}}</td>
            <td class="date-japan"></td>
            <td class="enter">
                <input name="social_end_date" value="{{$employeeBase->EmployeeInsurance->social_end_date}}"
                       type="text" class="dateInput form-control">
            </td>
        </tr>
    </table>
    <hr>
    <h5>
        <label>
            <input class="checkboxInsurance" type="checkbox" {{$employeeBase->EmployeeInsurance->employment_insurance==1?'checked':''}} name="employment_insurance" value="1"> {{ __('雇用保険（雇用・労災）') }}
        </label>
    </h5>
    <table class="w-100 employee-table">
        <tr>
            <td class="title">{{ __('事業所番号') }}</td>
            <td class="modify">{{$hrSetting->office_number}}</td>
            <td class="enter">{{$hrSetting->office_number}}</td>
        </tr>
        <tr>
            <td class="title">{{ __('被保険者番号') }}</td>
            <td class="modify">{{ $employeeBase->EmployeeInsurance->insured_number}}</td>
            <td class="enter">
                <input name="insured_number" value="{{$employeeBase->EmployeeInsurance->insured_number}}"
                       type="text" class="form-control">
            </td>
        </tr>
        <tr class="date-western">
            <td class="title">{{ __('雇用資格取得日') }}</td>
            <td class="adminModifyBefore">
                <span class="date-val date-modify m-0">{{ $employeeBase->EmployeeInsurance->employment_start_date}}</span>
                <span class="date-japan"></span>
            </td>
            <td class="enter">
                <input name="employment_start_date" value="{{$employeeBase->EmployeeInsurance->employment_start_date}}"
                       type="text" class="dateInput form-control">
            </td>
        </tr>
        <tr class="date-western">
            <td class="title">{{ __('雇用資格喪失日') }}</td>
            <td class="adminModifyBefore">
                <span class="date-val date-modify m-0">{{ $employeeBase->EmployeeInsurance->employment_end_date}}</span>
                <span class="date-japan"></span>
            </td>
            <td class="enter">
                <input name="employment_end_date" value="{{$employeeBase->EmployeeInsurance->employment_end_date}}"
                       type="text" class="dateInput form-control">
            </td>
        </tr>
    </table>
    <hr>
    <h5>
        <label>
            <input class="checkboxInsurance" type="checkbox" {{$employeeBase->EmployeeInsurance->national_health_insurance==1?'checked':''}} name="national_health_insurance" value="1"> {{ __('国民健康保険') }}
        </label>
    </h5>
    <h5>
        <label>
            <input class="checkboxInsurance" type="checkbox" {{$employeeBase->EmployeeInsurance->national_pension_insurance==1?'checked':''}} name="national_pension_insurance" value="1"> {{ __('国民年金') }}
        </label>
    </h5>
</div>
