<div class="tab-content">
    <div id="emp_profile" class="pro-overview tab-pane fade show active ">
        <div class="card" style="">
            <form action="{{route('requestSetting.update',6)}}" method="post">
                @csrf
                @method('put')
                <div class="row m-0">
                    <div class="card-header col-md-12">
                        <h4 class="m-0">{{ __('請求書初期値設定') }}</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="card-body">
                            <table class="table-left-setting w-100">
                                @if(isset($client) && $client['document_format']!=1)
                                <tr>
                                    <td>
                                        {{ __('初期値使用')}}
                                    </td>
                                    <td>
                                        <input type="checkbox" id="use_init_val_outside" name="use_init_val_outside" value="1" class="check" {{$requestSettingClient->use_init_val==1?"checked":""}}>
                                        <label for="use_init_val_outside" class="checktoggle">checkbox</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('初期値(会社宛情報)') }}
                                    </td>
                                    <td>
                                        <textarea name="company_info_outside" cols="30" rows="5" type="text" class="form-control" required oninput="changeLength(this)">{{$requestSettingClient->company_info}}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('初期値(説明文)') }}
                                    </td>
                                    <td>
                                        <textarea name="remark_start_outside" cols="30" rows="5" type="text" class="form-control" placeholder="" required="" oninput="changeLength(this)">{{$requestSettingClient->remark_start}}</textarea>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('初期値(補充説明文)') }}
                                    </td>
                                    <td>
                                        <textarea name="remark_end_outside" cols="30" rows="5" type="text" class="form-control" placeholder="" required="" oninput="changeLength(this)">{{$requestSettingClient->remark_end}}</textarea>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('電子印鑑使用') }}
                                    </td>
                                    <td>
                                        <input type="checkbox" id="use_seal_outside" value="1" name="use_seal_outside" class="check" {{$requestSettingClient->use_seal==1?"checked":""}}>
                                        <label for="use_seal_outside" class="checktoggle">checkbox</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('電子印鑑アプロード') }}
                                    </td>
                                    <td>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail img-thumbnail" style="width: 160px; height: 160px;">
                                                <img class="electronicSeal" src="{{isset($requestSettingClient->seal_file)?$requestSettingClient->seal_file:url('assets/img/profiles/150-150.png')}}" style="width: 150px; height: 150px;">
                                            </div>
                                            <div>
                                                <span class="btn btn-file file-btns">
                                                    <span class="fileinput-new file-select-btn"> 画像を選択 </span>
                                                    <input type="hidden"><input type="file" name="seal_file_outside" onchange="showImg(this)">
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
                                                    <input type="radio" name="project_name_radio_outside" value="0" onchange="radioInputIsEnter(this,false)" {{$requestSettingClient->project_name==''?"checked":""}}>
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="project_name_radio_outside" type="radio" value="1" onchange="radioInputIsEnter(this,true)" {{$requestSettingClient->project_name!=''?"checked":""}}>
                                                </label>
                                            </div>
                                            <input type="text" name="project_name_outside" class="form-control radioInput" value="{{$requestSettingClient->project_name}}" {{$requestSettingClient->project_name==''?"readonly":""}}>
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
                                                <select class=" form-control radioSelect" name="contract_type_outside" style="width:8em!important;display: inline-block" onchange="ableOther(this)">
                                                    @foreach($contractTypes as $contractType)
                                                        <option {{$requestSettingClient->contract_type==$contractType->id?'selected':''}}
                                                                value="{{$contractType->id}}">{{$contractType->contract_type_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <input type="text" class="form-control radioInput" name="contract_type_other_remark_outside" value="{{$requestSettingClient->contract_type_other_remark}}" {{$requestSettingClient->contract_type_other_remark==''?"readonly":""}}>

                                        </div>

                                    </td>
                                </tr>
                                @endif

                                <tr>
                                    <td>
                                        {{ __('請求日') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input type="radio" name="create_month_radio_outside" value="0" onchange="radioSelectIsEnter(this,false)" {{$requestSettingClient->create_month==0?"checked":""}}>&nbsp;本日&nbsp;
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input type="radio" name="create_month_radio_outside" value="1" onchange="radioSelectIsEnter(this,true)" {{$requestSettingClient->create_month!=0?"checked":""}}>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <select class=" form-control radioSelect" name="create_month_outside" style="width:76px;display: inline-block" {{$requestSettingClient->create_month==0?"disabled":""}}>
                                                    <option selected disabled="disabled"  style='display: none'>{{ __('　') }}</option>
                                                    <option value="1" {{$requestSettingClient->create_month==1?"selected":""}}>{{ __('当月') }}</option>
                                                    <option value="2" {{$requestSettingClient->create_month==2?"selected":""}}>{{ __('翌月') }}</option>
                                                </select>
                                            </div>
                                            <div class="input-group-prepend">
                                                <select class=" form-control radioSelect" name="create_day_outside" style="width: 66px;display: inline-block" {{$requestSettingClient->create_month==0?"disabled":""}}>
                                                    <option selected disabled="disabled"  style='display: none'>{{ __('　') }}</option>
                                                    @for($i=1;$i<32;$i++)
                                                        <option value="{{$i}}" {{$requestSettingClient->create_day==$i?"selected":""}}>{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @if(isset($client) && $client['document_format']!=1)
                                <tr>
                                    <td>
                                        {{ __('期間') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="period_outside" value="0" {{$requestSettingClient->period==0?"checked":""}}>
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="period_outside" value="1" {{$requestSettingClient->period==1?"checked":""}}>
                                                    <span>&nbsp;当月&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="period_outside" value="2" {{$requestSettingClient->period==2?"checked":""}}>
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
                                                    <input type="radio" name="work_place_radio_outside" value="0" {{$requestSettingClient->work_place==''?"checked":""}} onchange="radioInputIsEnter(this,false)">
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="work_place_radio_outside" type="radio" {{$requestSettingClient->work_place!=''?"checked":""}} onchange="radioInputIsEnter(this,true)">
                                                </label>
                                            </div>
                                            <input type="text" name="work_place_outside" class="form-control radioInput" value="{{$requestSettingClient->work_place}}" {{$requestSettingClient->work_place==''?"readonly":""}}>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('口座情報') }}
                                    </td>
                                    <td class="status-toggle">
                                        <input class="form-control text-left" disabled readonly value="{{$requestSettingClient->bank_name.'　'.$requestSettingClient->branch_name.'（'.$requestSettingClient->branch_code}}）">
                                        <input class="form-control text-left" disabled readonly value="{{$requestSettingClient->BankAccountType->account_type_name.'　'.$requestSettingClient->account_num}}">
                                        <input class="form-control text-left" disabled readonly value="{{$requestSettingClient->account_name}}">
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
                                                    <input type="radio" name="payment_contract_radio_outside" onchange="radioInputIsEnter(this,false)" {{$requestSettingClient->payment_contract==''?"checked":""}}>
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input name="payment_contract_radio_outside" type="radio" onchange="radioInputIsEnter(this,true)" {{$requestSettingClient->payment_contract!=''?"checked":""}}>
                                                </label>
                                            </div>
                                            <input type="text" class="form-control radioInput" name="payment_contract_outside" value="{{$requestSettingClient->payment_contract}}" {{$requestSettingClient->payment_contract==''?"readonly":""}}>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td>
                                        {{ __('振込期限日') }}<br>
                                        {{ __('/支払期限日') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="request_pay_month_radio_outside" value="0" onchange="radioSelectIsEnter(this,false)" {{$requestSettingClient->request_pay_month==0?"checked":""}}>
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input type="radio" name="request_pay_month_radio_outside" value="1" onchange="radioSelectIsEnter(this,true)"{{$requestSettingClient->request_pay_month!=0?"checked":""}}>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend">
                                                <select class=" form-control radioSelect" name="request_pay_month_outside"  style="width:76px;display: inline-block" {{$requestSettingClient->request_pay_month==0?"disabled":""}}>
                                                    <option selected disabled="disabled"  style='display: none'>{{ __('　') }}</option>
                                                    <option value="1" {{$requestSettingClient->request_pay_month==1?"selected":""}}>{{ __('当月') }}</option>
                                                    <option value="2" {{$requestSettingClient->request_pay_month==2?"selected":""}}>{{ __('翌月') }}</option>
                                                    <option value="3" {{$requestSettingClient->request_pay_month==3?"selected":""}}>{{ __('翌々月') }}</option>
                                                </select>
                                            </div>
                                            <div class="input-group-prepend">
                                                <select class=" form-control radioSelect" name="request_pay_date_outside" style="width: 66px;display: inline-block" {{$requestSettingClient->request_pay_month==0?"disabled":""}}>
                                                    <option selected disabled="disabled"  style='display: none'>{{ __('　') }}</option>
                                                    @for($i=1;$i<32;$i++)
                                                        <option value="{{$i}}" {{$requestSettingClient->request_pay_date==$i?"selected":""}}>{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                @can($__env->yieldContent('permission_modify_outside'))
                    <div class="card-footer bg-whitesmoke text-md-right">
                        <button class="btn btn-primary" type="submit" onclick="requestSettingSubmit(this);return false">保存</button>
                    </div>
                @endcan
            </form>
        </div>
    </div>
</div>
