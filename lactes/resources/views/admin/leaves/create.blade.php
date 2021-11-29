@section('permission_modify','leave_modify')
<style>
    .searchable-select{
        width: 100%;
    }
    .searchable-select-dropdown{
        z-index: 9999;
    }
</style>
<div class="modal-header">
        <h5 class="modal-title">{{ __('新規休暇画面') }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>{{ __('Whoops!') }}</strong> {{ __('There were some problems with your input.') }}<br><br>
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
    <form method="POST" id='add_edit_form' url-storeLeaves="{{ route('leaves.store') }}">
        @csrf
        <div class="form-group">
            <label for="employee_base_id"><span>{{ __('社員を選択:') }}</span></label>
            <select name="employee_base_id" onchange="applyUserChange(this)">
                <option>社員を選択</option>
                @foreach($employees as $employee)
                    <option value="{{$employee->id}}">{{$employee->User->name}}　[{{str_pad($employee->employee_code,4,'0',STR_PAD_LEFT)}}]</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="reason">{{ __('休暇理由（100文字）') }} </label>
            <textarea name="reason" type="text" style="resize: none;"
                      class="form-control @error('reason') invalid-input @enderror "
                      placeholder="{{__('')}}"></textarea>
            @error('reason')
            <div class="invalid-div">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="datetimes">{{ __('休暇期間') }}</label>
            <input class="form-control @error('datetimes') invalid-input @enderror " value="{{ old('datetimes') }}"
                   type="text" name="datetimes" autofocus required>
            @error('datetimes')
            <div class="invalid-div">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="form-group col-4">
                <label for="days_of_leave">{{ __('Days Of Leave') }}</label>
                <input name="days_of_leave" type="text"
                       class="form-control @error('days_of_leave') invalid-input @enderror "
                       value="1日" data-annual="0"
                       placeholder="{{__('')}}" required readonly>
                @error('days_of_leave')
                <div class="invalid-div">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-4">
                <label for="sum_days_of_leave">{{ __('本年度休暇総数') }}</label>
                <input name="sum_days_of_leave" type="text"
                       class="form-control @error('sum_days_of_leave') invalid-input @enderror "
                       value="@cannot("client_create"){{isset($sumDaysOfLeave)?$sumDaysOfLeave."日":'0 日'}}@endcannot" data-annual="0"
                       placeholder="{{__('')}}" required readonly>
                @error('sum_days_of_leave')
                <div class="invalid-div">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-4">
                <label for="annual_leave_days">{{ __('Annual leave days') }}</label>
                <input name="annual_leave_days" type="text"
                       class="form-control @error('annual_leave_days') invalid-input @enderror"
                       @can($__env->yieldContent('permission_modify'))
                       value="{{ old('annual_leave_days') }}"
                       @endcan
                       @cannot("client_create")
                       value="{{ 0 }}日"
                       @endcannot
                       placeholder="{{__('')}}" required readonly>
                @error('annual_leave_days')
                <div class="invalid-div">{{ $message }}</div>
                @enderror
            </div>
        </div>
            <div class="row">
                <div class="col-6">
                    <label>
                        <input type="radio" name="status" value="0" style="vertical-align: middle;" checked>{{__('確認中')}}
                    </label>
                </div>
                <div class="col-6">
                    <label>
                        <input type="radio" name="status" style="vertical-align: middle;" value="1">{{__('承認済')}}
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="memo">{{ __('メモ欄（200文字）') }}</label>
                <textarea name="memo" type="text" style="resize: none;"
                          class="form-control @error('reason') invalid-input @enderror "
                          placeholder="{{__('')}}"></textarea>
                @error('memo')
                <div class="invalid-div">{{ $message }}</div>
                @enderror
            </div>

            <div class="submit-section">
                @can($__env->yieldContent('permission_modify'))
                    <div name="button"><input class="hide_label btn btn-block"
                                              style="font-size:18px;width: 40%;color:white;height:50px;background-color:#FF9B44;display: inline-block;"
                                              name="hide" type="button" value="{{__('確認')}}"
                                              onclick="addEditLeave()"></div>
                @endcan
            </div>

    </form>
    <br/>
</div>

<script type="text/javascript">
    $(function(){
        $('select[name=employee_base_id]').searchableSelect();
    });
</script>
