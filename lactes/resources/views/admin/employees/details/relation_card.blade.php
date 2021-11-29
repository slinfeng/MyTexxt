@foreach($employeeDependentRelation as $key=>$relation)
        <div class="col-md-6 relationCard" data-type="{{$relation->relationship_type}}">
            <div class="card">
                <div class="card-header col-md-12 position-relative">
                    <h4 class="m-0 float-left card-title">扶養親族</h4>
                    <button type="button" class="close adminModifyAfter" onclick="relationDeleteClick(this)" data-toggle="modal"
                            data-target="#delete">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <input type="hidden" name="relation_id[]" value="{{$relation->id}}">
                <div class="card-body">
                    <table class="table-left-setting w-100">
                        <tr>
                            <td class="title">
                                {{ __('区分') }}
                            </td>
                            <td class="modify">
                                @switch($relation->relationship_type)
                                    @case(1){{"A配偶者"}}
                                    @break
                                    @case(2){{"B扶養親族(16歳以上)"}}
                                    @break
                                    @case(3){{"D他の所得者が控除を受ける扶養親族等"}}
                                    @break
                                    @case(4){{"16歳未満の扶養親族"}}
                                @endswitch
                            </td>
                            <td class="enter">
                                <select class="select form-control" name="relationship_type[]" onchange="cardChange(this)">
                                    <option value="1" {{$relation->relationship_type==1?"selected":""}}>{{"A配偶者"}}</option>
                                    <option value="2" {{$relation->relationship_type==2?"selected":""}}>{{"B扶養親族(16歳以上)"}}</option>
                                    <option value="3" {{$relation->relationship_type==3?"selected":""}}>{{"D他の所得者が控除を受ける扶養親族等"}}</option>
                                    <option value="4" {{$relation->relationship_type==4?"selected":""}}>{{"16歳未満の扶養親族"}}</option>
                                </select>
                                <br>
                                <span class="text-info sixteenAge"></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                {{ __('氏名') }}
                            </td>
                            <td class="modify">
                                {{$relation->dname}}
                            </td>
                            <td class="enter">
                                <input name="dname[]" value="{{$relation->dname}}" type="text" class="form-control" required>
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                {{ __('個人番号') }}
                            </td>
                            <td class="modify">
                                {{$relation->dependent_residence_card_num}}
                            </td>
                            <td class="enter">
                                <input name="dependent_residence_card_num[]" value="{{$relation->dependent_residence_card_num}}" type="text" class="form-control" required>
                            </td>
                        </tr>
                        <tr class="@if($relation->relationship_type==1) display-none @endif">
                            <td class="title">
                                {{ __('続柄') }}
                            </td>
                            <td class="modify">
                                {{$relation->relationship}}
                            </td>
                            <td class="enter">
                                <input name="relationship[]" value="{{$relation->relationship}}" type="text" class="form-control" required>
                            </td>
                        </tr>
                        <tr class="date-western">
                            <td class="title">{{ __('生年月日') }}</td>
                            <td class="adminModifyBefore">
                                <span class="date-val date-modify m-0">{{$relation->dependent_birthday}}</span>
                                <span class="date-japan"></span>
                            </td>
                            <td class="enter">
                                <input name="dependent_birthday[]" value="{{$relation->dependent_birthday}}"
                                       type="text" class="dateInput form-control">
                            </td>
                        </tr>
                        <tr class="@if($relation->relationship_type!=2) display-none @endif" >
                            <td class="title">
                                {{ __('同居・老親') }}
                            </td>
                            <td class="modify">
                                {{$relation->live_type==1?"同居":""}}
                                {{$relation->live_type==2?"老親":""}}
                            </td>
                            <td class="enter">
                                <select class="select form-control" name="live_type[]">
                                    <option value="0" selected>{{ __('　') }}</option>
                                    <option value="1" {{$relation->live_type==1?"selected":""}}>{{ __('同居') }}</option>
                                    <option value="2" {{$relation->live_type==2?"selected":""}}>{{ __('老親') }}</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                {{ __('住所又は居所') }}
                            </td>
                            <td class="modify">
                                {{$relation->dependent_address}}
                            </td>
                            <td class="enter">
                                <textarea type="text" name="dependent_address[]" class="form-control" style="max-height: 6em;min-height: 6em">{{$relation->dependent_address}}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="title">
                                {{ __('所得の見積額') }}
                            </td>
                            <td class="modify">
                                {{$relation->estimated}}
                            </td>
                            <td class="enter">
                                <input name="estimated[]" value="{{$relation->estimated}}" type="text" class="amount form-control">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
