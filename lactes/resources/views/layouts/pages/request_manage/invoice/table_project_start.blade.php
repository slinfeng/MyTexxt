<div class="layer-two w-100">
    <input type="hidden" name="calc_type" value="@yield('calc_type')">
    <textarea rows="1" readonly name="remark_start"
              data-initial="{{$requestSettingGlobal['remark_start']}}"
              oninput="changeLength(this)">@if(isset($data)){{$data->request_setting->remark_start}}@else{{$requestSettingGlobal['remark_start']}}@endif</textarea><br>
    <div class="project-info">
        <table class="w-100 h-100 bolder-border">
            <tr>
                <th class="width-project-th">{{ __('業務名') }}</th>
                <td class="input-able"><label>
                        <input name="project_name_or_file_name" value="@if(isset($data)){{$data->project_name_or_file_name}}@else{{$requestSettingGlobal['project_name']}}@endif" type="text">
                    </label></td>
            </tr>
            <tr>
                <th class="width-project-th">{{ __('契約形態') }}</th>
                <td>
                    <select class="select-focus" onchange="ableOther(this)" name="contract_type">
                        @foreach($contractTypes as $contractType)
                            @if(isset($data))
                                <option value="{{$contractType->id}}"
                                    {{$data->contract_type==$contractType->id?'selected':''}}>{{$contractType->contract_type_name}}</option>

                            @else
                                <option {{$requestSettingExtra['contract_type']==$contractType->id?'selected':''}}
                                        value="{{$contractType->id}}">{{$contractType->contract_type_name}}</option>
                            @endif
                        @endforeach
                    </select>
                    <label class="width-other h-100">
                        <input class="w-100" type="text" oninput="maxBytesCanInput(this,34)"
                               readonly value="@if(isset($data)){{$data->contract_type_other_remark}}@else{{$requestSettingExtra['contract_type_other_remark']}}@endif"
                               name="contract_type_other_remark"/></label>
                </td>
            </tr>
            <tr>
                <th class="width-project-th">{{ __('期間') }}</th>
                <td><input class="w-100" name="period" type="text" maxlength="23" autocomplete="off"
                           value="@if(isset($data)){{$data->period}}@endif"
                    @if(!isset($data))data-month="{{$requestSettingGlobal['period']}}"@endif/></td>
            </tr>
            <tr>
                <th class="width-project-th">{{ __('作業場所') }}</th>
                <td class="input-able"><label>
                        <input type="text" class="w-100" name="work_place" value="@if(isset($data)){{$data->work_place}}@else{{$requestSettingGlobal['work_place']}}@endif">
                    </label></td>
            </tr>
            <tr>
                <th class="width-project-th">{{ __('支払条件') }}</th>
                <td class="textarea-able"><label>
                        <textarea rows="1" oninput="changeLength(this)"
                                  name="payment_contract">@if(isset($data)){{$data->payment_contract}}@else{{$requestSettingGlobal['payment_contract']}}@endif</textarea>
                    </label></td>
            </tr>
        </table>
    </div>
    @include('layouts.pages.request_manage.company_info_and_init_switch')
</div>
