<div class="tab-content">
    <div id="emp_profile" class="pro-overview tab-pane fade show active ">
        <div class="card" style="">
            <form action="{{route('requestSetting.update',2)}}" method="post">
                @csrf
                @method('put')
                <div class="row m-0">
                    <div class="card-header col-md-12">
                        <h4 class="m-0">{{ __('注文書初期値設定') }}</h4>
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
                                            <input type="radio" value="1" name="position_type2"  {{$requestSettingGlobal[2]->position_type==1?"checked":""}}> 甲
                                        </label>
                                        <label>
                                            <input type="radio" value="2" name="position_type2"  {{$requestSettingGlobal[2]->position_type==2?"checked":""}}> 乙
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('カレンダー検索範囲') }}
                                    </td>
                                    <td>
                                        <label style="width: 30% ">
                                            <input type="radio" value="0" name="calendar_search_unit2" {{$requestSettingGlobal[2]->calendar_search_unit==0?"checked":""}}> 日単位
                                        </label>
                                        <label>
                                            <input type="radio" value="1" name="calendar_search_unit2" {{$requestSettingGlobal[2]->calendar_search_unit==1?"checked":""}}> 月単位
                                        </label>
                                    </td>
                                </tr>
{{--                                <tr>--}}
{{--                                    <td>--}}
{{--                                        {{ __('発注金額') }}--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        <label style="width: 30%">--}}
{{--                                            <input type="radio" value="0" name="tax_type2" {{$requestSettingGlobal[2]->tax_type==0?"checked":""}}> 税拔--}}
{{--                                        </label>--}}
{{--                                        <label>--}}
{{--                                            <input type="radio" value="1" name="tax_type2" {{$requestSettingGlobal[2]->tax_type==1?"checked":""}}> 税込--}}
{{--                                        </label>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
                                <tr>
                                    <td>
                                        {{ __('初期値使用') }}
                                    </td>
                                    <td>
                                        <input type="checkbox" name="use_init_val2" value="1" id="use_init_val2" class="check" {{$requestSettingGlobal[2]->use_init_val==1?"checked":""}}>
                                        <label for="use_init_val2" class="checktoggle">checkbox</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('初期値(会社宛情報)') }}
                                    </td>
                                    <td>
                                        <textarea name="company_info2" cols="30" rows="5" type="text" class="form-control" required="" oninput="changeLength(this)">{{$requestSettingGlobal[2]->company_info}}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('初期値(説明文)') }}
                                    </td>
                                    <td>
                                        <textarea name="remark_start2" cols="30" rows="5" type="text" class="form-control" placeholder="" required="" oninput="changeLength(this)">{{$requestSettingGlobal[2]->remark_start}}</textarea>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('初期値(補充説明文)') }}
                                    </td>
                                    <td>
                                        <textarea name="remark_end2" cols="30" rows="5" type="text" class="form-control" placeholder="" required="" oninput="changeLength(this)">{{$requestSettingGlobal[2]->remark_end}}</textarea>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('電子印鑑使用') }}
                                    </td>
                                    <td>
                                        <input type="checkbox" id="use_seal2" value="1" name="use_seal2" class="check" {{$requestSettingGlobal[2]->use_seal==1?"checked":""}}>
                                        <label for="use_seal2" class="checktoggle">checkbox</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('電子印鑑アプロード') }}
                                    </td>
                                    <td>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail img-thumbnail" style="width: 160px; height: 160px;">
                                                <img class="electronicSeal" src="{{isset($requestSettingGlobal[2]->seal_file)?$requestSettingGlobal[2]->seal_file:url('assets/img/profiles/150-150.png')}}" style="width: 150px; height: 150px;">
                                            </div>
                                            <div>
                                                <span class="btn btn-file file-btns">
                                                    <span class="fileinput-new file-select-btn"> 画像を選択 </span>
                                                    <input type="hidden"><input type="file" name="seal_file2" onchange="showImg(this)">
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
                                        {{ __('業務名称') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="project_name_radio2" value="0" onchange="radioInputIsEnter(this,false)" {{$requestSettingGlobal[2]->project_name==''?"checked":""}}>
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="project_name_radio2" type="radio" value="1" onchange="radioInputIsEnter(this,true)" {{$requestSettingGlobal[2]->project_name!=''?"checked":""}}>
                                                </label>
                                            </div>
                                            <input type="text" name="project_name2" class="form-control radioInput" value="{{$requestSettingGlobal[2]->project_name}}" {{$requestSettingGlobal[2]->project_name==''?"readonly":""}}>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('業務内容範囲') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" value="0" name="project_content_radio" onchange="radioInputIsEnter(this,false)" {{$requestSettingExtra->project_content==''?"checked":""}}>
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="project_content_radio" value="1" type="radio" onchange="radioInputIsEnter(this,true)" {{$requestSettingExtra->project_content!=''?"checked":""}}>
                                                </label>
                                            </div>
                                            <input type="text" name="project_content" class="form-control radioInput" value="{{$requestSettingExtra->project_content}}" {{$requestSettingExtra->project_content==''?"readonly":""}}>
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
                                                    <input type="radio" name="create_month_radio2" value="0" onchange="radioSelectIsEnter(this,false)" {{$requestSettingGlobal[2]->create_month==0?"checked":""}}>&nbsp;本日&nbsp;
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input type="radio" name="create_month_radio2" value="1" onchange="radioSelectIsEnter(this,true)" {{$requestSettingGlobal[2]->create_month!=0?"checked":""}}>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <select class=" form-control radioSelect" name="create_month2" style="width:76px;display: inline-block" {{$requestSettingGlobal[2]->create_month==0?"disabled":""}}>
                                                    <option selected disabled="disabled"  style='display: none'>{{ __('　') }}</option>
                                                    <option value="1" {{$requestSettingGlobal[2]->create_month==1?"selected":""}}>{{ __('当月') }}</option>
                                                    <option value="2" {{$requestSettingGlobal[2]->create_month==2?"selected":""}}>{{ __('翌月') }}</option>
                                                </select>
                                            </div>
                                            <div class="input-group-prepend">
                                                <select class=" form-control radioSelect" name="create_day2" style="width: 66px;display: inline-block" {{$requestSettingGlobal[2]->create_month==0?"disabled":""}}>
                                                    <option selected disabled="disabled"  style='display: none' >{{ __('　') }}</option>
                                                    @for($i=1;$i<32;$i++)
                                                        <option value="{{$i}}" {{$requestSettingGlobal[2]->create_day==$i?"selected":""}}>{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('作業期間') }}
                                    </td>
                                    <td class="status-toggle">
                                    <div class="input-group">
                                        <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                            <label class="input-group-text">
                                                <input type="radio" name="period2" value="0" {{$requestSettingGlobal[2]->period==0?"checked":""}}>
                                                <span>&nbsp;空欄&nbsp;</span>
                                            </label>
                                        </div>
                                        <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                            <label class="input-group-text">
                                                <input type="radio" name="period2" value="2" {{$requestSettingGlobal[2]->period==2?"checked":""}}>
                                                <span>&nbsp;翌月&nbsp;</span>
                                            </label>
                                        </div>
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
                                                    <input type="radio" name="custom_title_radio2" value="0"  {{$requestSettingGlobal[2]->custom_title==''?"checked":""}} onchange="radioInputIsEnter(this,false)">
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="custom_title_radio2" type="radio" {{$requestSettingGlobal[2]->custom_title!=''?"checked":""}} onchange="radioInputIsEnter(this,true)">
                                                </label>
                                            </div>
                                            <input type="text" name="custom_title2" class="form-control radioInput" value="{{$requestSettingGlobal[2]->custom_title}}" {{$requestSettingGlobal[2]->custom_title==''?"readonly":""}}>
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
                                                    <input type="radio" name="custom_content_radio2" value="0"  {{$requestSettingGlobal[2]->custom_content==''?"checked":""}} onchange="radioInputIsEnter(this,false)">
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="custom_content_radio2" type="radio" {{$requestSettingGlobal[2]->custom_content!=''?"checked":""}} onchange="radioInputIsEnter(this,true)">
                                                </label>
                                            </div>
                                            <input type="text" name="custom_content2" class="form-control radioInput" value="{{$requestSettingGlobal[2]->custom_content}}" {{$requestSettingGlobal[2]->custom_content==''?"readonly":""}}>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="2">
                                        {{ __('支払条件') }}
                                    </td>
                                    <td class="status-toggle pb-0">
                                        検収：
                                        <div class="input-group">
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="payment_contract_radio21" value="0" onchange="radioInputIsEnter(this,false)" {{$requestSettingGlobal[2]->payment_contract[0]==''?"checked":""}}>
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="payment_contract_radio21" type="radio" onchange="radioInputIsEnter(this,true)" {{$requestSettingGlobal[2]->payment_contract[0]!=''?"checked":""}}>
                                                </label>
                                            </div>
                                            <input type="text" name="payment_contract2[]" class="form-control radioInput" {{$requestSettingGlobal[2]->payment_contract[0]==''?"readonly":""}} value="{{($requestSettingGlobal[2]->payment_contract)[0]}}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="display: none"></td>
                                    <td class="status-toggle text-left pt-0 pr-25">
                                        支払：
                                        <div class="input-group">
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="payment_contract_radio22" value="0" onchange="radioInputIsEnter(this,false)" {{$requestSettingGlobal[2]->payment_contract[1]==''?"checked":""}}>
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="payment_contract_radio22" type="radio" onchange="radioInputIsEnter(this,true)" {{$requestSettingGlobal[2]->payment_contract[1]!=''?"checked":""}}>
                                                </label>
                                            </div>
                                            <input name="payment_contract2[]" type="text" class="form-control radioInput" {{$requestSettingGlobal[2]->payment_contract[1]==''?"readonly":""}} value="{{($requestSettingGlobal[2]->payment_contract)[1]}}">
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
                                                    <input type="radio" name="work_place_val" value="1" {{$requestSettingGlobal[2]->work_place_val==1?"checked":""}}>
                                                    <span>&nbsp;客先&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="work_place_val" type="radio" value="0" {{$requestSettingGlobal[2]->work_place_val==0?"checked":""}}>
                                                </label>
                                            </div>
                                            <input type="text" name="work_place2" class="form-control" value="{{$requestSettingGlobal[2]->work_place}}">

                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('納入物件') }}
                                    </td>
                                    <td class="status-toggle">
                                        <input type="hidden" name="expense_delivery_files[]" value="0">
                                        <label><input type="checkbox" name="expense_delivery_files[]" value="1" {{substr($requestSettingExtra->expense_delivery_files,0,1)==1?"checked":""}}> 勤務表 </label>　
                                        <label><input type="checkbox" name="expense_delivery_files[]" value="2" {{substr($requestSettingExtra->expense_delivery_files,1,1)==1?"checked":""}}> 作業実績報告書 </label>　
                                        <label><input type="checkbox" name="expense_delivery_files[]" value="3" {{substr($requestSettingExtra->expense_delivery_files,2,1)==1?"checked":""}}> その他成果物一式 </label>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('納入場所') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input type="radio" name="acceptance_place_val" value="1"  {{$requestSettingGlobal[2]->acceptance_place_val==1?"checked":""}}>
                                                    <span>&nbsp;発注者指定場所&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="acceptance_place_val" type="radio" value="0" {{$requestSettingGlobal[2]->acceptance_place_val==0?"checked":""}}>
                                                </label>
                                            </div>
                                            <input type="text" class="form-control" name="acceptance_place2" value="{{$requestSettingGlobal[2]->acceptance_place}}">

                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('交通・通勤費') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input type="radio" name="expense_traffic_expence_paid_by_val" value="1" {{$requestSettingExtra->expense_traffic_expence_paid_by_val==1?"checked":""}}>
                                                    <span>&nbsp;協同会社負担&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="expense_traffic_expence_paid_by_val" type="radio" value="0" {{$requestSettingExtra->expense_traffic_expence_paid_by_val==0?"checked":""}}>
                                                </label>
                                            </div>
                                            <input type="text" class="form-control" name="expense_traffic_expence_paid_by" value="{{$requestSettingExtra->expense_traffic_expence_paid_by}}">

                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('経費清算') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="expense_outlay_radio" value="0" onchange="radioInputIsEnter(this,false)" {{$requestSettingExtra->expense_outlay==''?"checked":""}} >
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>

                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="expense_outlay_radio" type="radio" onchange="radioInputIsEnter(this,true)" {{$requestSettingExtra->expense_outlay!=''?"checked":""}}>
                                                </label>
                                            </div>
                                            <textarea name="expense_outlay" cols="30" rows="1" type="text" class="form-control radioInput" required="" {{$requestSettingExtra->expense_outlay==''?"readonly":""}} oninput="changeLength(this)">{{$requestSettingExtra->expense_outlay}}</textarea>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('特記事項	') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="expense_remark_radio" value="0" onchange="radioInputIsEnter(this,false)" {{$requestSettingExtra->expense_remark==''?"checked":""}} >
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>

                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="expense_remark_radio" type="radio" onchange="radioInputIsEnter(this,true)" {{$requestSettingExtra->expense_remark!=''?"checked":""}}>
                                                </label>
                                            </div>
                                            <textarea name="expense_remark" cols="30" rows="1" type="text" class="form-control radioInput" required="" {{$requestSettingExtra->expense_remark==''?"readonly":""}} oninput="changeLength(this)">{{$requestSettingExtra->expense_remark}}</textarea>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('印刷') }}
                                    </td>
                                    <td class="status-toggle">
                                        <input type="hidden" name="print_num2[]" value="0">
                                        <label><input type="checkbox" name="print_num2[]" value="1" {{substr($requestSettingGlobal[2]->print_num,0,1)==1?"checked":""}}> 注文書 </label>　
                                        <label><input type="checkbox" name="print_num2[]" value="2" {{substr($requestSettingGlobal[2]->print_num,1,1)==1?"checked":""}}> 注文請書 </label>　
{{--                                        <label><input type="checkbox" name="print_num2[]" value="3" {{substr($requestSettingGlobal[2]->print_num,2,1)==1?"checked":""}}> 送付状 </label>--}}

                                    </td>
                                </tr>
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
