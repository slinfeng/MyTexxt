<div class="modal-header">
    <h5 class="modal-title">{{ __('Add User') }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>{{ __('Whoops!') }}</strong>{{ __('There were some problems with your input.') }}<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="alert alert-danger print-error-msg" style="display:none">
        <ul class="mb-0"></ul>
    </div>
    <form method="POST" id='add_edit_form' >
        @csrf
        <div class="form-group">
            <label for="client_id">{{ __('取引先（社外担当者使用）') }}</label>
            <select name="client_id">
                <option value="0">本社</option>
                @foreach($clients as $client)
                    <option value="{{$client->id}}">{{'('.substr(('000'.$client->id),-4).')'.$client->client_name.'('.$client->client_abbreviation.')'}}</option>
                @endforeach
            </select>
            @error('client_id')
            <div class="invalid-div">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="name">{{ __('User Name') }}<span class="text-danger">*</span></label>
            <input class="form-control @error('name') invalid-input @enderror " value="{{ old('name') }}"
                   type="text" name="name" placeholder="{{__('')}}" autofocus required >
            @error('name')
            <div class="invalid-div">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">{{ __('User Email') }}<span class="text-danger">*</span></label>
            <input name="email" value="{{ old('email') }}"
                      class="form-control  @error('email') invalid-input @enderror " placeholder="{{__('')}}" autofocus>
            @error('email')
            <div class="invalid-div">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="password" class="">{{ __('Password') }}<span class="text-danger">*</span></label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    @error('password')
                    <div class="invalid-div">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                        <label for="password-confirm" class="">{{ __('Confirm Password') }}<span class="text-danger">*</span></label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    @error('password-confirm')
                    <div class="invalid-div">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

{{--        <div class="form-group">--}}
{{--            <label >{{__('Roles')}}<span class="text-danger">*</span> <span class="text-info">{{ __('Press the Ctrl key at the same time to select multiple') }}</span></label>--}}
{{--            <select class="select form-control"  name="roles[]" multiple="multiple" autofocus required>--}}
{{--                @foreach ($roles as $role)--}}
{{--                    <option value="{{$role['id']}}">{{$role['title']}}</option>--}}
{{--                @endforeach--}}

{{--            </select>--}}
{{--            @error('roles')--}}
{{--            <div class="invalid-div">{{ $message }}</div>--}}
{{--            @enderror--}}
{{--        </div>--}}
        <div class="form-group leave-duallist">
            <label>{{__('Roles')}}</label>
            <div class="row">
                <div class="col-lg-12">
                    <span>{{ __('▼選択されている役割')}}</span>
                </div>
                <div class="col-lg-5 col-sm-5">
                    <select name="roles[]" id="edit_roles_select" class="form-control" size="4" multiple="multiple">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" style="display: none">{{ $role->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="multiselect-controls col-lg-2 col-sm-2">
                    <button type="button" onclick="rolesSelectLeft()" class="btn btn-block btn-white"><i class="fa fa-chevron-left"></i></button>
                    <button type="button" onclick="rolesSelectRight()" class="btn btn-block btn-white"><i class="fa fa-chevron-right"></i></button>
                    <button type="button" onclick="rolesSelectRightAll()" class="btn btn-block btn-white"><i class="fa fa-forward"></i></button>
                    {{--                    <button type="button" id="edit_roles_select_leftAll" class="btn btn-block btn-white"><i class="fa fa-backward"></i></button>--}}
                </div>
                <div class="col-lg-5 col-sm-5">
                    <select name="" id="edit_roles_select_to" class="form-control" size="8" multiple="multiple">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="font-size: 12px">
                <br>
                <span class="text-info"> {{ __('Ctrlキーを同時に押して、複数を選択します。') }}</span>
                <br>
                <span class="text-info"> {{ __('Shiftキーを押しながら、終点のセルをクリックすると範囲選択されます。 ') }}</span>
            </div>
        </div>

        <div class="submit-section">
            <button class="btn btn-primary submit-btn" type="submit"  onclick="addEditUser();return false" >{{ __('追加') }}</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(function(){
        $('select[name=client_id]').searchableSelect();
        $('.searchable-select-dropdown').css('display','none');
    });
</script>
