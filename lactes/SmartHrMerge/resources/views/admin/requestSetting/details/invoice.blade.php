<div class="tab-content">
    <div id="emp_profile" class="pro-overview tab-pane fade show active ">
        <div class="card" style="">
            <form action="{{route('requestSetting.update',4)}}" method="post">
                @csrf
                @method('put')
                <div class="row m-0">
                    <div class="card-header col-md-12">
                        <h4 class="m-0">{{ __('請求書初期値設定') }}</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="card-body">
                            <table class="table-left-setting w-100">
                                <tr>
                                    <td>
                                        {{ __('我社立場') }}
                                    </td>
                                    <td>
                                        <label style="width: 30% ">
                                            <input type="radio" value="1" name="position_type4"  {{$requestSettingGlobal[4]->position_type==1?"checked":""}}> 甲
                                        </label>
                                        <label>
                                            <input type="radio" value="2" name="position_type4"  {{$requestSettingGlobal[4]->position_type==2?"checked":""}}> 乙
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('カレンダー検索範囲') }}
                                    </td>
                                    <td>
                                        <label style="width: 30% ">
                                            <input type="radio" value="0" name="calendar_search_unit4" {{$requestSettingGlobal[4]->calendar_search_unit==0?"checked":""}}> 日単位
                                        </label>
                                        <label>
                                            <input type="radio" value="1" name="calendar_search_unit4" {{$requestSettingGlobal[4]->calendar_search_unit==1?"checked":""}}> 月単位
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('初期値使用') }}
                                    </td>
                                    <td>
                                        <input type="checkbox" name="use_init_val4" value="1" id="use_init_val4" class="check" {{$requestSettingGlobal[4]->use_init_val==1?"checked":""}}>
                                        <label for="use_init_val4" class="checktoggle">checkbox</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('初期値(会社宛情報)') }}
                                    </td>
                                    <td>
                                        <textarea name="company_info4" cols="30" rows="5" type="text" class="form-control" required oninput="changeLength(this)">{{$requestSettingGlobal[4]->company_info}}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('初期値(説明文)') }}
                                    </td>
                                    <td>
                                        <textarea name="remark_start4" cols="30" rows="5" type="text" class="form-control" placeholder="" required="" oninput="changeLength(this)">{{$requestSettingGlobal[4]->remark_start}}</textarea>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('初期値(補充説明文)') }}
                                    </td>
                                    <td>
                                        <textarea name="remark_end4" cols="30" rows="5" type="text" class="form-control" placeholder="" required="" oninput="changeLength(this)">{{$requestSettingGlobal[4]->remark_end}}</textarea>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('電子印鑑使用') }}
                                    </td>
                                    <td>
                                        <input type="checkbox" id="use_seal4" value="1" name="use_seal4" class="check" {{$requestSettingGlobal[4]->use_seal==1?"checked":""}}>
                                        <label for="use_seal4" class="checktoggle">checkbox</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('電子印鑑アプロード') }}
                                    </td>
                                    <td>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail img-thumbnail" style="width: 160px; height: 160px;">
                                                <img class="electronicSeal" src="{{isset($requestSettingGlobal[4]->seal_file)?$requestSettingGlobal[4]->seal_file:url('assets/img/profiles/150-150.png')}}" style="width: 150px; height: 150px;">
                                            </div>
                                            <div>
                                                <span class="btn btn-file file-btns">
                                                    <span class="fileinput-new file-select-btn"> 画像を選択 </span>
                                                    <input type="hidden"><input type="file" name="seal_file4" onchange="showImg(this)">
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>

                    <div class="col-md-6 p-0">
                        <div class="card-body p-0">
                            <table class="table-right-setting w-100">
                                <tr>
                                    <td>
                                        {{ __('業務名') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="project_name_radio4" value="0" onchange="radioInputIsEnter(this,false)" {{$requestSettingGlobal[4]->project_name==''?"checked":""}}>
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="project_name_radio4" type="radio" value="1" onchange="radioInputIsEnter(this,true)" {{$requestSettingGlobal[4]->project_name!=''?"checked":""}}>
                                                </label>
                                            </div>
                                            <input type="text" name="project_name4" class="form-control radioInput" value="{{$requestSettingGlobal[4]->project_name}}" {{$requestSettingGlobal[4]->project_name==''?"readonly":""}}>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('契約形態') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <select class=" form-control radioSelect" name="contract_type" style="width:8em!important;display: inline-block" onchange="ableOther(this)">
                                                    @foreach($contractTypes as $contractType)
                                                        <option {{$requestSettingExtra->contract_type==$contractType->id?'selected':''}}
                                                                value="{{$contractType->id}}">{{$contractType->contract_type_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <input type="text" class="form-control radioInput" name="contract_type_other_remark" value="{{$requestSettingExtra->contract_type_other_remark}}" {{$requestSettingExtra->contract_type_other_remark==''?"readonly":""}}>

                                        </div>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('請求日') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input type="radio" name="create_month_radio4" value="0" onchange="radioSelectIsEnter(this,false)" {{$requestSettingGlobal[4]->create_month==0?"checked":""}}>&nbsp;本日&nbsp;
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input type="radio" name="create_month_radio4" value="1" onchange="radioSelectIsEnter(this,true)" {{$requestSettingGlobal[4]->create_month!=0?"checked":""}}>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <select class=" form-control radioSelect" name="create_month4" style="width:76px;display: inline-block" {{$requestSettingGlobal[4]->create_month==0?"disabled":""}}>
                                                    <option selected disabled="disabled"  style='display: none'>{{ __('　') }}</option>
                                                    <option value="1" {{$requestSettingGlobal[4]->create_month==1?"selected":""}}>{{ __('当月') }}</option>
                                                    <option value="2" {{$requestSettingGlobal[4]->create_month==2?"selected":""}}>{{ __('翌月') }}</option>
                                                </select>
                                            </div>
                                            <div class="input-group-prepend">
                                                <select class=" form-control radioSelect" name="create_day4" style="width: 66px;display: inline-block" {{$requestSettingGlobal[4]->create_month==0?"disabled":""}}>
                                                    <option selected disabled="disabled"  style='display: none'>{{ __('　') }}</option>
                                                    @for($i=1;$i<32;$i++)
                                                        <option value="{{$i}}" {{$requestSettingGlobal[4]->create_day==$i?"selected":""}}>{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('期間') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="period4" value="0" {{$requestSettingGlobal[4]->period==0?"checked":""}}>
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="period4" value="1" {{$requestSettingGlobal[4]->period==1?"checked":""}}>
                                                    <span>&nbsp;当月&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="period4" value="2" {{$requestSettingGlobal[4]->period==2?"checked":""}}>
                                                    <span>&nbsp;翌月&nbsp;</span>
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('作業場所') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="work_place_radio4" value="0" {{$requestSettingGlobal[4]->work_place==''?"checked":""}} onchange="radioInputIsEnter(this,false)">
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="work_place_radio4" type="radio" {{$requestSettingGlobal[4]->work_place!=''?"checked":""}} onchange="radioInputIsEnter(this,true)">
                                                </label>
                                            </div>
                                            <input type="text" name="work_place4" class="form-control radioInput" value="{{$requestSettingGlobal[4]->work_place}}" {{$requestSettingGlobal[4]->work_place==''?"readonly":""}}>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('口座選択') }}
                                    </td>
                                    <td class="status-toggle">
                                        <select class=" form-control bankInfo" name="bank_account_id4">
                                            <option selected disabled="disabled"  style='display: none'>{{ __('　') }}</option>
                                            @foreach($bankAccount as $bank)
                                                <option value="{{$bank->id}}" {{$requestSettingGlobal[4]->bank_account_id==$bank->id?'selected':''}}>{{$bank->bank_name}}　{{$bank->branch_name}}　（{{$bank->branch_code}}）　{{$bank->account_num}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('支払条件') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="payment_contract_radio4" onchange="radioInputIsEnter(this,false)" {{$requestSettingGlobal[4]->payment_contract==''?"checked":""}}>
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="payment_contract_radio4" type="radio" onchange="radioInputIsEnter(this,true)" {{$requestSettingGlobal[4]->payment_contract!=''?"checked":""}}>
                                                </label>
                                            </div>
                                            <input type="text" class="form-control radioInput" name="payment_contract4" value="{{$requestSettingGlobal[4]->payment_contract}}" {{$requestSettingGlobal[4]->payment_contract==''?"readonly":""}}>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('振込期限日') }}<br>
                                        {{ __('/支払期限日') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="request_pay_month_radio" value="0" onchange="radioSelectIsEnter(this,false)" {{$requestSettingExtra->request_pay_month==0?"checked":""}}>
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input type="radio" name="request_pay_month_radio" value="1" onchange="radioSelectIsEnter(this,true)"{{$requestSettingExtra->request_pay_month!=0?"checked":""}}>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <select class=" form-control radioSelect" name="request_pay_month"  style="width:76px;display: inline-block" {{$requestSettingExtra->request_pay_month==0?"disabled":""}}>
                                                    <option selected disabled="disabled"  style='display: none'>{{ __('　') }}</option>
                                                    <option value="1" {{$requestSettingExtra->request_pay_month==1?"selected":""}}>{{ __('当月') }}</option>
                                                    <option value="2" {{$requestSettingExtra->request_pay_month==2?"selected":""}}>{{ __('翌月') }}</option>
                                                    <option value="3" {{$requestSettingExtra->request_pay_month==3?"selected":""}}>{{ __('翌々月') }}</option>
                                                </select>
                                            </div>
                                            <div class="input-group-prepend">
                                                <select class=" form-control radioSelect" name="request_pay_date" style="width: 66px;display: inline-block" {{$requestSettingExtra->request_pay_month==0?"disabled":""}}>
                                                    <option selected disabled="disabled"  style='display: none'>{{ __('　') }}</option>
                                                    @for($i=1;$i<32;$i++)
                                                        <option value="{{$i}}" {{$requestSettingExtra->request_pay_date==$i?"selected":""}}>{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
{{--                                <tr>--}}
{{--                                    <td>--}}
{{--                                        {{ __('印刷') }}--}}
{{--                                    </td>--}}
{{--                                    <td class="status-toggle">--}}
{{--                                        <input type="checkbox" name="print_num4[]" value="0" checked style="display: none">--}}
{{--                                        <label><input type="checkbox" name="print_num4[]" value="1" {{substr($requestSettingGlobal[4]->print_num,0,1)==1?"checked":""}}> 請求書 </label>　--}}
{{--                                        <label><input type="checkbox" name="print_num4[]" value="2" {{substr($requestSettingGlobal[4]->print_num,1,1)==1?"checked":""}}> 送付状 </label>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
                            </table>
                        </div>
                    </div>
                </div>
                @can($__env->yieldContent('permission_modify'))
                <div class="card-footer bg-whitesmoke text-md-right">
                    <button class="btn btn-primary" type="submit" onclick="requestSettingSubmit(this);return false">保存</button>
                </div>
                @endcan
            </form>
        </div>
    </div>
</div>
