<div class="tab-content">
    <div id="emp_profile" class="pro-overview tab-pane fade show active ">
        <div class="card" style="">
            <form action="{{route('requestSetting.update',3)}}" method="post">
                @csrf
                @method('put')
                <div class="row m-0">
                    <div class="card-header col-md-12">
                        <h4 class="m-0">{{ __('注文請書初期値設定') }}</h4>
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
                                            <input type="radio" value="1" name="position_type3"  {{$requestSettingGlobal[3]->position_type==1?"checked":""}}> 甲
                                        </label>
                                        <label>
                                            <input type="radio" value="2" name="position_type3"  {{$requestSettingGlobal[3]->position_type==2?"checked":""}}> 乙
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('カレンダー検索範囲') }}
                                    </td>
                                    <td>
                                        <label style="width: 30% ">
                                            <input type="radio" value="0" name="calendar_search_unit3" {{$requestSettingGlobal[3]->calendar_search_unit==0?"checked":""}}> 日単位
                                        </label>
                                        <label>
                                            <input type="radio" value="1" name="calendar_search_unit3" {{$requestSettingGlobal[3]->calendar_search_unit==1?"checked":""}}> 月単位
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('初期値(説明文)') }}
                                    </td>
                                    <td>
                                        <textarea name="remark_start3" cols="30" rows="5" type="text" class="form-control" placeholder="" required="" oninput="changeLength(this)">{{$requestSettingGlobal[3]->remark_start}}</textarea>
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
                                        {{ __('作業期間') }}
                                    </td>
                                    <td class="status-toggle">
                                        <div class="input-group">
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="period3" value="0" {{$requestSettingGlobal[3]->period==0?"checked":""}}>
                                                    <span>&nbsp;空欄&nbsp;</span>
                                                </label>
                                            </div>
                                            <div class="input-group-prepend" style="line-height: 100%;vertical-align: bottom">
                                                <label class="input-group-text">
                                                    <input type="radio" name="period3" value="2" {{$requestSettingGlobal[3]->period==2?"checked":""}}>
                                                    <span>&nbsp;翌月&nbsp;</span>
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
{{--                                <tr>--}}
{{--                                    <td>--}}
{{--                                        {{ __('我社名称') }}--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        <input name="company_info3" type="text" class="form-control" required="" value="{{$requestSettingGlobal[3]->company_info}}">--}}
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
