<div class="card-body">
    <h3 class="card-title"><strong>{{ __('滞在情報') }}</strong></h3>
{{--        <a href="#" class="edit-icon" data-toggle="modal" data-target="#stay_modal"><i class="fa fa-pencil"></i></a></h3>--}}
        <h5></h5>
    <table class="w-100 employee-table">
        <tr>
            <td class="title">{{ __('在留カード番号') }}</td>
            <td class="history">{{$employeeBase->EmployeeStay->data_history['residence_card_num']}}</td>
            <td class="modify">{{ $employeeBase->EmployeeStay->residence_card_num}}</td>
            <td class="enter">
                <input name="residence_card_num" value="{{$employeeBase->EmployeeStay->residence_card_num}}"
                       type="text" class="form-control">
            </td>
        </tr>

        <tr>
            <td class="title">{{ __('在留資格種類') }}</td>
            <td class="history">
                @foreach($residenceType as $residence)
                    @if($employeeBase->EmployeeStay->data_history['residence_type']==$residence->id)
                        {{$residence->residence_type}}
                    @endif
                @endforeach
            </td>
            <td class="modify">
                @foreach($residenceType as $residence)
                    @if($employeeBase->EmployeeStay->residence_type==$residence->id)
                        {{$residence->residence_type}}
                    @endif
                @endforeach
            </td>
            <td class="enter">
                <select class="select form-control" name="residence_type"  value="{{$employeeBase->EmployeeStay->residence_type}}"
                        required>
                    @foreach($residenceType as $residence)
                        <option value="{{$residence->id}}" @if($employeeBase->EmployeeStay->residence_type==$residence->id) selected @endif>{{$residence->residence_type}}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr class="date-western">
            <td class="title" rowspan="2">{{ __('在留満了年月日') }}</td>
            <td class="adminModifyBefore">
                <span class="date-val date-history m-0">{{$employeeBase->EmployeeStay->data_history['residence_deadline']}}</span>
                <span class="date-japan"></span>
            </td>
{{--            <td class="date-val date-history">{{$employeeBase->EmployeeStay->data_history['residence_deadline']}}</td>--}}
{{--            <td class="date-japan"></td>--}}
        </tr>
        <tr class="date-western">
            <td class="adminModifyBefore">
                <span class="date-val date-modify m-0">{{ $employeeBase->EmployeeStay->residence_deadline}}</span>
                <span class="date-japan"></span>
            </td>
{{--            <td class="date-val date-modify">{{ $employeeBase->EmployeeStay->residence_deadline}}</td>--}}
{{--            <td class="date-japan"></td>--}}
            <td class="enter">
                <input name="residence_deadline" value="{{ $employeeBase->EmployeeStay->residence_deadline}}"
                       type="text" class="dateInput form-control">
            </td>
        </tr>
        <tr>
            <td class="title">{{ __('個人番号') }}</td>
            <td class="history">{{$employeeBase->EmployeeStay->data_history['personal_num']}}</td>
            <td class="modify">{{ $employeeBase->EmployeeStay->personal_num}}</td>
            <td class="enter">
                <input name="personal_num" value="{{$employeeBase->EmployeeStay->personal_num}}"
                       type="text" class="form-control">
            </td>
        </tr>
        <tr>
            <td class="title">{{ __('在留カードの表') }}</td>
            @if($employeeBase->EmployeeStay->residence_card_front!=$employeeBase->EmployeeStay->data_history['residence_card_front'])
                <td class="historyImg p-1" style="position: relative;width: 35%">
                    <img class="w-100 cursor-point" onclick="photoShow(this)" src="{{$employeeBase->EmployeeStay->data_history['residence_card_front']==''?url('assets/img/residence_card_front.png'):$employeeBase->EmployeeStay->data_history['residence_card_front']}}">
                </td>
            @endif
            <td class="modifyImg p-1 text-left" style=" @if($employeeBase->EmployeeStay->residence_card_front!=$employeeBase->EmployeeStay->data_history['residence_card_front']) border:2px orange solid;width: 35% @endif ">
                <img id="residenceCardFront" class=" @if($employeeBase->EmployeeStay->residence_card_front!=$employeeBase->EmployeeStay->data_history['residence_card_front'])  w-100 @else w-50 @endif cursor-point" onclick="photoShow(this)" src="{{$employeeBase->EmployeeStay->residence_card_front==''?url('assets/img/residence_card_front.png'):$employeeBase->EmployeeStay->residence_card_front}}">
                <div class="m-1 adminModifyAfter">
                    <span class="btn btn-file file-btns">
                        <span class="fileinput-new file-select-btn"> 画像を選択 </span>
                        <input type="file" name="residence_card_front" onchange="showImg(this)">
                    </span>
                </div>
            </td>

        </tr>
        <tr>
            <td class="title">{{ __('在留カードの裏') }}</td>
            @if($employeeBase->EmployeeStay->residence_card_back!=$employeeBase->EmployeeStay->data_history['residence_card_back'])
                <td class="historyImg p-1" style="position: relative;width: 35%">
                    <img class="w-100 cursor-point" onclick="photoShow(this)" src="{{$employeeBase->EmployeeStay->data_history['residence_card_back']==''?url('assets/img/residence_card_back.png'):$employeeBase->EmployeeStay->data_history['residence_card_back']}}">
                </td>
            @endif
            <td class="modifyImg p-1 text-left" style="@if($employeeBase->EmployeeStay->residence_card_back!=$employeeBase->EmployeeStay->data_history['residence_card_back']) border:2px orange solid;width: 35% @endif ">
                <img id="residenceCardBack" class=" @if($employeeBase->EmployeeStay->residence_card_front!=$employeeBase->EmployeeStay->data_history['residence_card_front'])  w-100 @else w-50 @endif  cursor-point" href="javascript:void(0)" onclick="photoShow(this)" src="{{$employeeBase->EmployeeStay->residence_card_back==''?url('assets/img/residence_card_back.png'):$employeeBase->EmployeeStay->residence_card_back}}">
                <div class="m-1 adminModifyAfter">
                    <span class="btn btn-file file-btns">
                        <span class="fileinput-new file-select-btn"> 画像を選択 </span>
                        <input type="file" name="residence_card_back" onchange="showImg(this)">
                    </span>
                </div>
            </td>
        </tr>

    </table>
</div>
