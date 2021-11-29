<div class="tab-content">
    <div id="" class="pro-overview tab-pane fade show active">
        <div class="card">
            <form action="{{route('HrSetting.update',2)}}" method="post">
                @csrf
                @method('put')
                <div class="row m-0">
                    <div class="card-header w-100">
                        <h4 class="m-0">{{ __('休暇設定') }}</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="card-body">
                            <table class="table-left-setting w-100">
                                <tr>
                                    <td>
                                        {{ __('クラウドで勤務表保存期間') }}
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <select name="cloud_attendance_period" class="form-control w-100">
                                                <option value="3" {{$hrSetting->cloud_attendance_period==3?'selected':''}}>三ヶ月</option>
                                                <option value="6" {{$hrSetting->cloud_attendance_period==6?'selected':''}}>六ヶ月</option>
                                                <option value="12" {{$hrSetting->cloud_attendance_period==12?'selected':''}}>一年</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                @can($__env->yieldContent('permission_modify'))
                    <div class="card-footer bg-whitesmoke text-md-right">
                        <button class="btn btn-primary" type="button" onclick="hrSettingSubmit(this)">保存</button>
                    </div>
                @endcan
            </form>
        </div>
    </div>
</div>
