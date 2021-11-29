<div class="tab-content">
    <div id="emp_profile" class="pro-overview tab-pane fade show active ">
        <div class="col-xl-6 row pr-0">
            <div class="card w-100">
                <form action="{{route('requestSetting.update',0)}}" method="post">
                    @csrf
                    @method('put')
                    <div class="row m-0">
                        <div class="card-header w-100">
                            <h4 class="m-0">{{ __('取引先初期値設定') }}</h4>
                        </div>
                        <div class="card-body">
                            <table class="table-left-setting w-100">
                                <tr>
                                    <td>
                                        {{ __('カレンダー検索範囲') }}
                                    </td>
                                    <td>
                                        <label class="w-30">
                                            <input type="radio" value="0" name="calendar_search_unit0" {{$requestSettingGlobal[0]->calendar_search_unit==0?"checked":""}}> 日単位
                                        </label>
                                        <label>
                                            <input type="radio" value="1" name="calendar_search_unit0" {{$requestSettingGlobal[0]->calendar_search_unit==1?"checked":""}}> 月単位
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('取引先並び順') }}
                                    </td>
                                    <td>
                                        <label class="w-30">
                                            <input type="radio" value="0" name="client_sort_type" {{$requestSettingExtra->client_sort_type==0?"checked":""}}> 番号順
                                        </label>
                                        <label>
                                            <input type="radio" value="1" name="client_sort_type" {{$requestSettingExtra->client_sort_type==1?"checked":""}}> 略称順
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('我社立場') }}
                                    </td>
                                    <td>
                                        <label class="w-30">
                                            <input type="radio" value="1" name="our_position_type" {{$requestSettingGlobal[0]->position_type==1?"checked":""}}> 甲
                                        </label>
                                        <label>
                                            <input type="radio" value="2" name="our_position_type" {{$requestSettingGlobal[0]->position_type==2?"checked":""}}> 乙
                                        </label>
                                    </td>
                                </tr>
{{--                                <tr>--}}
{{--                                    <td>--}}
{{--                                        {{ __('取引先導入') }}--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        <div class="fileinput fileinput-new" data-provides="fileinput">--}}
{{--                                            <div>--}}
{{--                                                <span class="btn btn-file file-btns">--}}
{{--                                                    <span class="fileinput-new file-select-btn"> ファイルを選択 </span>--}}
{{--                                                    <input type="hidden"><input type="file" name="client_file" onchange="showFileName(this);">--}}
{{--                                                </span>--}}
{{--                                                <span class="files-info"></span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
                            </table>
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
</div>
