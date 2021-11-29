@if($type_id==0)
    <div id="employee_user_type_0" style="width: 100%;" >
        <div class="w-100 text-center p-1">
            <h4>{{ __('Please Select A User') }}</h4>
        </div>
        <div class="form-group">
            <label for="employee_user_selected">{{ __('Registered User but not employee') }}<span class="text-danger">*</span></label>
            <select class="select form-control" name="employee_user_selected" value="{{ old('employee_user_selected') }}"
                    placeholder="{{__('Please Select A User')}}" required >
                @foreach ($notemployees as $notemployee)
                    <option value="{{$notemployee['id']}}" data-name="{{$notemployee['name']}}">{{$notemployee['name']}}({{$notemployee['email']}})</option>
                @endforeach
            </select>
        </div>
    </div>
    <script>
        $('select[name=employee_user_selected]').searchableSelect();
    </script>
@elseif($type_id=1)
    <div id="employee_user_type_1" style="width: 100%;"  >
        <div class="w-100 text-center p-1">
            <h4>{{ __('Please register a new user') }}</h4>
        </div>
        <div id="register_form_error_msg" class="alert alert-danger" style="display:none">
            <ul class="mb-0"></ul>
        </div>
        <form method="POST"  id="register_form" >
            <input type="hidden" name="roles" value="7">
            @csrf
            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name" >{{ __('ユーザー名') }}</label>
                        <input id="name" type="text" class="form-control " name="name" value="{{ old('name') }}" required autocomplete="name" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email" >{{ __('E-Mail Address') }}</label>
                        <input id="email" type="email" class="form-control " name="email" value="{{ old('email') }}" required autocomplete="email">
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password" class="">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control " name="password" required autocomplete="new-password">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password-confirm" class="">{{ __('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>
            </div>
        </form>
    </div>
@endif


