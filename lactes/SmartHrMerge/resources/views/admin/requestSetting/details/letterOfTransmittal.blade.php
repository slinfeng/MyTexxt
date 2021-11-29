<div class="tab-content">
    <div id="emp_profile" class="pro-overview tab-pane fade show active ">
        <div class="card" style="">
            <form action="{{route('requestSetting.update',5)}}" method="post">
                @csrf
                @method('put')
                <div class="row m-0">
                    <div class="card-header col-md-12">
                        <h4 class="m-0">{{ __('送付状初期値設定') }}</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="card-body">
                            <table class="table-left-setting w-100">
                                <tr>
                                    <td>
                                        {{ __('縦距離') }}
                                    </td>
                                    <td>
                                        <div class="input-group">
{{--                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">--}}
{{--                                                <label class="input-group-text">--}}
{{--                                                    <input type="radio" name="vertical_distance_radio" value="0" onchange="radioInputIsEnter(this,false)" {{$requestSettingExtra->vertical_distance==''?"checked":""}}>--}}
{{--                                                    <span>&nbsp;空欄&nbsp;</span>--}}
{{--                                                </label>--}}
{{--                                            </div>--}}
{{--                                            <div class="input-group-prepend">--}}
{{--                                                <label class="input-group-text">--}}
{{--                                                    <input name="vertical_distance_radio" type="radio" value="1" onchange="radioInputIsEnter(this,true)" {{$requestSettingExtra->vertical_distance!=''?"checked":""}}>--}}
{{--                                                </label>--}}
{{--                                            </div>--}}
                                            <input type="text" name="vertical_distance" class="form-control radioInput float" value="{{$requestSettingExtra->vertical_distance}}" {{$requestSettingExtra->vertical_distance==''?"readonly":""}}>
                                            <div class="input-group-append">
                                                <span class="input-group-text">mm</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('横距離') }}
                                    </td>
                                    <td>
                                        <div class="input-group">
{{--                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">--}}
{{--                                                <label class="input-group-text">--}}
{{--                                                    <input type="radio" name="horizontal_distance_radio" value="0" onchange="radioInputIsEnter(this,false)" {{$requestSettingExtra->horizontal_distance==''?"checked":""}}>--}}
{{--                                                    <span>&nbsp;空欄&nbsp;</span>--}}
{{--                                                </label>--}}
{{--                                            </div>--}}
{{--                                            <div class="input-group-prepend">--}}
{{--                                                <label class="input-group-text">--}}
{{--                                                    <input name="horizontal_distance_radio" type="radio" value="1" onchange="radioInputIsEnter(this,true)" {{$requestSettingExtra->horizontal_distance!=''?"checked":""}}>--}}
{{--                                                </label>--}}
{{--                                            </div>--}}
                                            <input type="text" name="horizontal_distance" class="form-control radioInput float" value="{{$requestSettingExtra->horizontal_distance}}" {{$requestSettingExtra->horizontal_distance==''?"readonly":""}}>
                                            <div class="input-group-append">
                                                <span class="input-group-text">mm</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('初期値使用') }}
                                    </td>
                                    <td>
                                        <input type="checkbox" name="use_init_val5" value="1" id="use_init_val5" class="check" {{$requestSettingGlobal[5]->use_init_val==1?"checked":""}}>
                                        <label for="use_init_val5" class="checktoggle">checkbox</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('初期値(会社宛情報)') }}
                                    </td>
                                    <td>
                                        <textarea name="company_info5" cols="30" rows="5" type="text" class="form-control" required="" oninput="changeLength(this)">{{$requestSettingGlobal[5]->company_info}}</textarea>

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
                                        {{ __('枠付け') }}
                                    </td>
                                    <td class="status-toggle">
                                        <input type="checkbox" id="use_seal5" value="1" name="use_seal5" class="check" {{$requestSettingGlobal[5]->use_seal==1?"checked":""}}>
                                        <label for="use_seal5" class="checktoggle">checkbox</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('タイトル') }}
                                    </td>
                                    <td class="status-toggle">
                                        <input name="project_name5" type="text" class="form-control" value="{{$requestSettingGlobal[5]->project_name}}" required="">

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('正文') }}
                                    </td>
                                    <td class="status-toggle">
                                        <textarea name="remark_start5" cols="30" rows="5" type="text" class="form-control" placeholder="" required="" oninput="changeLength(this)">{{$requestSettingGlobal[5]->remark_start}}</textarea>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('送付書類') }}
                                    </td>
                                    <td class="status-toggle">
                                        <textarea name="remark_end5" cols="30" rows="5" type="text" class="form-control" placeholder="" required="" oninput="changeLength(this)">{{$requestSettingGlobal[5]->remark_end}}</textarea>
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
