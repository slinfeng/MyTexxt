@section('permission_modify','leave_modify')

<div class="modal-header">
    <h3>{{__('休暇編集・承認画面') }}</h3>
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
    <form method="POST" id='add_edit_form' url-updateLeaves="{{route('leaves.update',':id')}}">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{isset($leave)?$leave->id:''}}">
        <input type="hidden" name="employee_base_id" value="{{isset($employee)?$employee->id:0}}">
        <div class="form-group">
            <div class="row">
                <div
                    class="col-6">{{__('社員番号:')}}{{isset($employee)?str_pad($employee->employee_code, 4, '0', STR_PAD_LEFT):''}}</div>
                <div
                    class="col-6">{{__('氏名:')}}{{isset($employee)?$employee->user->name.(isset($employee->date_retire)?'(退職)':''):''}}</div>
            </div>
        </div>
        <div class="form-group">
            <label for="reason">{{ __('休暇理由（100文字）') }}</label>
            <textarea name="reason" type="text" style="resize: none;"
                      class="form-control @error('reason') invalid-input @enderror "
                      readonly
                      placeholder="{{__('')}}">{{ old('reason',isset($leave)?$leave->reason:'') }}</textarea>
            @error('reason')
            <div class="invalid-div">{{ $message }}</div>
            @enderror
        </div>

        {{--        @if(($leave->status==1)||($employee->id!=\Illuminate\Support\Facades\Auth::id()))--}}
        <div class="form-group">
            <label for="datetimes">{{ __('休暇期間') }}</label>
            <input class="form-control @error('datetime') invalid-input @enderror "
                   value="{{isset($leave)?(date('Y-m-d H:00',strtotime($leave->leave_from))."～".date('Y-m-d H:00',strtotime($leave->leave_to))):''}}"
                   type="text"
                   @if(isset($leave))
                   name="datetime"
                   @else
                   name="datetimes"
                   @endif
                   autofocus required
                   readonly>
            @error('datetime')
            <div class="invalid-div">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="form-group col-4">
                <label for="days_of_leave">{{ __('Days Of Leave') }}</label>
                <input name="days_of_leave" type="text"
                       class="form-control @error('days_of_leave') invalid-input @enderror "
                       value="{{isset($leave)?old('days_of_leave',$leave->days_of_leave):'1'}}日"
                       data-annual="{{isset($leave)?($leave->leave_type==1?$leave->days_of_leave:'0'):0}}"
                       placeholder="{{__('')}}" required readonly>
                @error('days_of_leave')
                <div class="invalid-div">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-4">
                <label for="sum_days_of_leave">{{ __('本年度休暇総数') }}</label>
                <input name="sum_days_of_leave" type="text"
                       class="form-control @error('sum_days_of_leave') invalid-input @enderror "
                       value="{{isset($sumDaysOfLeave)?old('sum_days_of_leave',$sumDaysOfLeave):0}}日" data-annual="0"
                       placeholder="{{__('')}}" required readonly>
                @error('sum_days_of_leave')
                <div class="invalid-div">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-4">
                <label for="annual_leave_days">{{ __('Annual leave days') }}</label>
                <input name="annual_leave_days" type="text"
                       class="form-control @error('annual_leave_days') invalid-input @enderror"
                       value="{{isset($annualLeaveHasDays)?$annualLeaveHasDays."日":"0日"}}"
                       placeholder="{{__('')}}" required readonly>
                @error('annual_leave_days')
                <div class="invalid-div">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-4">
                    <label>
                        <input type="radio" name="status" value="0" style="vertical-align: middle;"
                               @if(isset($leave)&&($leave->status==0))
                               checked
                               @else
                               disabled="true"
                            @endif>{{__(' 確認中')}}
                    </label>
                </div>
                <div class="col-4">
                    <label>
                        <input type="radio" name="status" value="1" style="vertical-align: middle;"
                               @if(isset($leave)&&($leave->status==1))
                               disabled="true"
                               checked
                               @elseif(isset($leave)&&($leave->status==2))
                               disabled="true"
                            @endif>{{__(' 承認済')}}
                    </label>
                </div>
                <div class="col-4">
                    <label>
                        <input type="radio" name="status" value="2" style="vertical-align: middle;"
                               @if(isset($leave)&&($leave->status==2))
                               disabled="true"
                               checked
                               @elseif(isset($leave)&&($leave->status==1))
                               disabled="true"
                            @endif>{{__(' 拒否')}}
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="memo">{{ __('メモ欄（200文字）') }} </label>
            <textarea name="memo" type="text" style="resize: none;"
                      class="form-control @error('reason') invalid-input @enderror "
                      placeholder="{{__('')}}">{{ isset($leave)?old('memo',$leave->memo) :''}}</textarea>
            @error('memo')
            <div class="invalid-div">{{ $message }}</div>
            @enderror
        </div>
        <div class="submit-section">
            @can($__env->yieldContent('permission_modify'))
                <div name="button"><input class="hide_label btn btn-block"
                                          style="font-size:18px;width: 40%;color:white;height:50px;background-color:#FF9B44;display: inline-block;" id="edit_btn"
                                          name="hide" type="button" value="{{__('保存')}}"
                                          data-id="{{isset($leave)?$leave->id:''}}"
                                          @if(isset($leave))
                                          onclick="addEditLeave()"
                                          @endif
                    ></div>
            @endcan
        </div>
    </form>
    <br/>
    @include('admin.leaves.history')
</div>
