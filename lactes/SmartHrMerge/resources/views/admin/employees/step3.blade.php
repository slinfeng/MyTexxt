<div class="w-100 text-center p-1">
{{--    <h4>{{ __('Please enter the employee base info') }}</h4>--}}
</div>
<form method="POST" id='add_employee_base_info_form' >
    @csrf
    <div id="add_employee_base_info_form_error_msg" class="alert alert-danger" style="display:none">
        <ul class="mb-0"></ul>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name">{{ __('氏名') }}<span class="text-danger">*</span></label>
                <input name="name" readonly value="{{$user_name}}" class="form-control autofocus  required">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name_roman">{{ __('Roman Name') }}<span class="text-danger">*</span></label>
                <input name="name_roman" class="form-control  @error('name_roman') invalid-input @enderror " onchange="value=value.replace(/[^a-zA-Z\u3000 ]/g,'')"  required>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name_phonetic">{{ __('Phonetic Name') }}<span class="text-danger">*</span></label>
                <input name="name_phonetic" class="form-control  @error('name_phonetic') invalid-input @enderror " onchange="value=value.replace(/[^\u30A0-\u30FF\u3000 ]/g,'')" required>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="department_type_id">{{ __('部門') }}<span class="text-danger">*</span></label>
                <select class="select form-control" name="department_type_id" required>
                    <option value="">　</option>
                    @foreach ($departments as $department)
                        <option value="{{$department['id']}}">{{$department['department_name']}}</option>
                    @endforeach
                </select>
            </div>
        </div><div class="col-sm-6">
            <div class="form-group">
                <label for="hire_type_id">{{ __('契約形態') }}<span class="text-danger">*</span></label>
                <select class="select form-control" name="hire_type_id" required>
                    <option value="">　</option>
                    @foreach ($hireType as $hire_type)
                        <option value="{{$hire_type['id']}}">{{$hire_type['hire_type']}}</option>
                    @endforeach
                </select>
            </div>
        </div><div class="col-sm-6">
            <div class="form-group">
                <label for="position_type_id">{{ __('役職') }}<span class="text-danger">*</span></label>
                <select class="select form-control" name="position_type_id" required>
                    <option value="">　</option>
                    @foreach ($positionType as $position)
                        <option value="{{$position['id']}}">{{$position['position_type']}}</option>
                    @endforeach
                </select>
            </div>
        </div><div class="col-sm-6">
            <div class="form-group">
                <label for="sex">{{ __('Sex') }}<span class="text-danger">*</span></label>
                <select class="select form-control" name="sex"
                        placeholder="{{__('Please select sex')}}"
                        required>
                    <option value="0">{{ __('Male') }}</option>
                    <option value="1">{{ __('Female') }}</option>
                    <option value="2">{{ __('Unisex') }}</option>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="tel">{{ __('Telphone number') }}</label>
                <input name="phone" oninput="value=value.replace(/[^0-9]/g,'')"
                       class="form-control  @error('tel') invalid-input @enderror "  required>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="birthday">{{ __('生年月日') }}<span class="text-danger">*</span></label>
                <input class="form-control dateInput @error('birthday') invalid-input @enderror "
                       type="text" name="birthday"  required>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label for="date_hire">{{ __('入社日') }}<span class="text-danger">*</span></label>
                <input class="form-control dateInput @error('date_hire') invalid-input @enderror "
                       type="text" name="date_hire" required>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="remark">{{ __('Remark') }}</label>
        <textarea type="text" name="remark" rows="1" class="form-control" oninput="changeLength(this)"></textarea>
    </div>
</form>
<script src="{{asset('assets/js/laydate.js')}}"></script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
<script type="text/javascript">
    dateTimePicker();
    function dateTimePicker() {
        $('.dateInput').each(function () {
            initSingleDatePicker(this);
        })
    }
</script>
