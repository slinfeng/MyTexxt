<div class="tab-content">
    <div id="emp_profile" class="pro-overview tab-pane fade show active ">
        <div class="card" style="">
            <form action="{{route('requestSetting.update',1)}}" method="post">
                    @csrf
                    @method('put')
                <div class="row m-0">
                    <div class="card-header col-md-12">
                        <h4 class="m-0">{{ __('見積書初期値設定') }}</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="card-body">
                            <table class="table-left-setting w-100">
                                <tr>
                                    <td>
                                        {{ __('我社立場') }}
                                    </td>
                                    <td>
                                        <label class="w-30">
                                            <input type="radio" value="1" name="position_type1"  {{$requestSettingGlobal[1]->position_type==1?"checked":""}}> 甲
                                        </label>
                                        <label>
                                            <input type="radio" value="2" name="position_type1"  {{$requestSettingGlobal[1]->position_type==2?"checked":""}}> 乙
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('カレンダー検索範囲') }}
                                    </td>
                                    <td>
                                        <label class="w-30">
                                            <input type="radio" value="0" name="calendar_search_unit1" {{$requestSettingGlobal[1]->calendar_search_unit==0?"checked":""}}> 日単位
                                        </label>
                                        <label>
                                            <input type="radio" value="1" name="calendar_search_unit1" {{$requestSettingGlobal[1]->calendar_search_unit==1?"checked":""}}> 月単位
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('御見積金額') }}
                                    </td>
                                    <td>
                                        <label class="w-30">
                                            <input type="radio" value="0" name="tax_type1" {{$requestSettingGlobal[1]->tax_type==0?"checked":""}}> 税拔
                                        </label>
                                        <label>
                                            <input type="radio" value="1" name="tax_type1" {{$requestSettingGlobal[1]->tax_type==1?"checked":""}}> 税込
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('初期値使用') }}
                                    </td>
                                    <td>
                                        <input type="checkbox" name="use_init_val1" value="1" id="use_init_val1" class="check" {{$requestSettingGlobal[1]->use_init_val==1?"checked":""}}>
                                        <label for="use_init_val1" class="checktoggle">checkbox</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('初期値(会社宛情報)') }}
                                    </td>
                                    <td>
                                        <textarea name="company_info1" cols="30" rows="5" type="text" class="form-control" placeholder="" required="" oninput="changeLength(this)">{{$requestSettingGlobal[1]->company_info}}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('初期値(説明文)') }}
                                    </td>
                                    <td>
                                        <textarea name="remark_start1" cols="30" rows="5" type="text" class="form-control" placeholder="" required="" oninput="changeLength(this)">{{$requestSettingGlobal[1]->remark_start}}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('電子印鑑使用') }}
                                    </td>
                                    <td>
                                        <input type="checkbox" id="use_seal1" value="1" name="use_seal1" class="check" {{$requestSettingGlobal[1]->use_seal==1?"checked":""}}>
                                        <label for="use_seal1" class="checktoggle">checkbox</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('電子印鑑アプロード') }}
                                    </td>
                                    <td>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail img-thumbnail">
                                                <img class="electronicSeal" src="{{isset($requestSettingGlobal[1]->seal_file)?$requestSettingGlobal[1]->seal_file:url('assets/img/profiles/150-150.png')}}" style="width: 150px; height: 150px;">
                                            </div>
                                            <div>
                                                <span class="btn btn-file file-btns">
                                                    <span class="fileinput-new file-select-btn"> 画像を選択 </span>
                                                    <input type="hidden"><input type="file" name="seal_file1" onchange="showImg(this)">
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
                                        {{ __('案件名') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="project_name_radio1" value="0" onchange="radioInputIsEnter(this,false)" {{$requestSettingGlobal[1]->project_name==''?"checked":""}}>
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="project_name_radio1" type="radio" value="1" onchange="radioInputIsEnter(this,true)" {{$requestSettingGlobal[1]->project_name!=''?"checked":""}}>
                                                </label>
                                            </div>
                                            <input type="text" name="project_name1" class="form-control radioInput" value="{{$requestSettingGlobal[1]->project_name}}" {{$requestSettingGlobal[1]->project_name==''?"readonly":""}}>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('作成日') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">

                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input type="radio" name="create_month_radio1" value="0" onchange="radioSelectIsEnter(this,false)" {{$requestSettingGlobal[1]->create_month==0?"checked":""}}>&nbsp;本日&nbsp;
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input type="radio" name="create_month_radio1" value="1" onchange="radioSelectIsEnter(this,true)" {{$requestSettingGlobal[1]->create_month!=0?"checked":""}}>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <select class="form-control radioSelect" name="create_month1" style="width:76px;display: inline-block" {{$requestSettingGlobal[1]->create_month==0?"disabled":""}}>
                                                    <option selected disabled="disabled"  style='display: none'>{{ __('　') }}</option>
                                                    <option value="1" {{$requestSettingGlobal[1]->create_month==1?"selected":""}}>{{ __('当月') }}</option>
                                                    <option value="2" {{$requestSettingGlobal[1]->create_month==2?"selected":""}}>{{ __('翌月') }}</option>
                                                </select>
                                            </div>
                                            <div class="input-group-prepend">
                                                <select class="form-control radioSelect" name="create_day1" style="width: 66px;display: inline-block" {{$requestSettingGlobal[1]->create_month==0?"disabled":""}}>
                                                    <option selected disabled="disabled"  style='display: none'>{{ __('　') }}</option>
                                                    @for($i=1;$i<32;$i++)
                                                        <option value="{{$i}}" {{$requestSettingGlobal[1]->create_day==$i?"selected":""}}>{{ $i }}</option>
                                                    @endfor
                                                </select>
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
                                                    <input type="radio" name="work_place_radio1" value="0"  {{$requestSettingGlobal[1]->work_place==''?"checked":""}} onchange="radioInputIsEnter(this,false)">
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="work_place_radio1" type="radio" {{$requestSettingGlobal[1]->work_place!=''?"checked":""}} onchange="radioInputIsEnter(this,true)">
                                                </label>
                                            </div>
                                            <input type="text" name="work_place1" class="form-control radioInput" value="{{$requestSettingGlobal[1]->work_place}}" {{$requestSettingGlobal[1]->work_place==''?"readonly":""}}>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('カスタム') }}<br>{{ __('タイトル') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="custom_title_radio1" value="0"  {{$requestSettingGlobal[1]->custom_title==''?"checked":""}} onchange="radioInputIsEnter(this,false)">
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="custom_title_radio1" type="radio" {{$requestSettingGlobal[1]->custom_title!=''?"checked":""}} onchange="radioInputIsEnter(this,true)">
                                                </label>
                                            </div>
                                            <input type="text" name="custom_title1" class="form-control radioInput" value="{{$requestSettingGlobal[1]->custom_title}}" {{$requestSettingGlobal[1]->custom_title==''?"readonly":""}}>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('カスタム') }}<br>{{ __('コンテンツ') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="custom_content_radio1" value="0"  {{$requestSettingGlobal[1]->custom_content==''?"checked":""}} onchange="radioInputIsEnter(this,false)">
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="custom_content_radio1" type="radio" {{$requestSettingGlobal[1]->custom_content!=''?"checked":""}} onchange="radioInputIsEnter(this,true)">
                                                </label>
                                            </div>
                                            <input type="text" name="custom_content1" class="form-control radioInput" value="{{$requestSettingGlobal[1]->custom_content}}" {{$requestSettingGlobal[1]->custom_content==''?"readonly":""}}>
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
                                                    <input type="radio" name="period1" value="0" value="0"  {{$requestSettingGlobal[1]->period==0?"checked":""}}>
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="period1" value="2" {{$requestSettingGlobal[1]->period==2?"checked":""}}>
                                                    <span>&nbsp;翌月&nbsp;</span>
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('納品場所') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="acceptance_place_radio1" value="0"  {{$requestSettingGlobal[1]->acceptance_place==''?"checked":""}} onchange="radioInputIsEnter(this,false)">
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="acceptance_place_radio1" type="radio" {{$requestSettingGlobal[1]->acceptance_place!=''?"checked":""}} onchange="radioInputIsEnter(this,true)">
                                                </label>
                                            </div>
                                            <input type="text" class="form-control radioInput" name="acceptance_place1" value="{{$requestSettingGlobal[1]->acceptance_place}}" {{$requestSettingGlobal[1]->acceptance_place==''?"readonly":""}}>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('備考') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="estimate_remark_radio" value="0" onchange="radioInputIsEnter(this,false)" {{$requestSettingExtra->estimate_remark==''?"checked":""}} >
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>

                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="estimate_remark_radio" type="radio" onchange="radioInputIsEnter(this,true)" {{$requestSettingExtra->estimate_remark!=''?"checked":""}}>
                                                </label>
                                            </div>
                                            <textarea name="estimate_remark" cols="30" rows="1" type="text" class="form-control radioInput" required="" {{$requestSettingExtra->estimate_remark==''?"readonly":""}} oninput="changeLength(this)">{{$requestSettingExtra->estimate_remark}}</textarea>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('口座選択') }}
                                    </td>
                                    <td class="status-toggle">
                                        <select class="form-control bankInfo" name="bank_account_id1">
                                            <option value="0" selected disabled="disabled"  style='display: none'>{{ __('　') }}</option>
                                            @foreach($bankAccount as $bank)
                                                <option value="{{$bank->id}}" {{$requestSettingGlobal[1]->bank_account_id==$bank->id?'selected':''}}>{{$bank->bank_name}}　{{$bank->branch_name}}（{{$bank->branch_code}}）{{$bank->account_num}}  {{mb_substr($bank->account_name,0,12)}}</option>
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
                                                    <input type="radio" name="payment_contract_radio1" value="0" onchange="radioInputIsEnter(this,false)" {{$requestSettingGlobal[1]->payment_contract==''?"checked":""}}>
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="payment_contract_radio1" type="radio" onchange="radioInputIsEnter(this,true)" {{$requestSettingGlobal[1]->payment_contract!=''?"checked":""}}>
                                                </label>
                                            </div>
                                            <input type="text" class="form-control radioInput" name="payment_contract1" value="{{$requestSettingGlobal[1]->payment_contract}}" {{$requestSettingGlobal[1]->payment_contract==''?"readonly":""}}>
                                        </div>
                                    </td>
                                </tr>
{{--                                <tr>--}}
{{--                                    <td>--}}
{{--                                        {{ __('印刷') }}--}}
{{--                                    </td>--}}
{{--                                    <td class="status-toggle">--}}
{{--                                        <input type="checkbox" name="print_num1[]" value="0" checked style="display: none">--}}
{{--                                        <label><input type="checkbox" name="print_num1[]" value="1" {{substr($requestSettingGlobal[1]->print_num,0,1)==1?"checked":""}}> 見積書 </label>　--}}
{{--                                        <label><input type="checkbox" name="print_num1[]" value="2" {{substr($requestSettingGlobal[1]->print_num,1,1)==1?"checked":""}}> 送付状 </label>--}}
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
