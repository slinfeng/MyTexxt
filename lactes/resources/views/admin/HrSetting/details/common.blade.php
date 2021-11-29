{{--<div class="tab-content">--}}
{{--    <div id="emp_profile" class="pro-overview tab-pane fade show active">--}}
{{--        <div class="card">--}}
{{--            <form action="{{route('requestSetting.update',-1)}}" method="post">--}}
{{--                @csrf--}}
{{--                @method('put')--}}
{{--                <div class="row m-0">--}}
{{--                    <div class="card-header w-100">--}}
{{--                        <h4 class="m-0">{{ __('共通設定') }}</h4>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6">--}}
{{--                        <div class="card-body">--}}
{{--                            <table class="table-left-setting w-100">--}}
{{--                                <tr>--}}
{{--                                    <td>--}}
{{--                                        {{ __('クラウドで勤務表保存期間') }}--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        <div class="input-group">--}}
{{--                                            <select name="cloud_attendance_period" class="form-control w-100">--}}
{{--                                                <option value="0" {{5==0?'selected':''}}>三ヶ月</option>--}}
{{--                                                <option value="1" {{6==1?'selected':''}}>六ヶ月</option>--}}
{{--                                                <option value="2" {{7==2?'selected':''}}>一年</option>--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6 p-0">--}}
{{--                        <div class="card-body p-0">--}}
{{--                            <table class="table-left-setting w-100" style="margin-top: 20px">--}}
{{--                                <tr style="height: 72.8px">--}}
{{--                                    <td>--}}

{{--                                    </td>--}}
{{--                                    <td>--}}

{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6">--}}
{{--                        <div class="card-body">--}}
{{--                            <table class="table-left-setting w-100">--}}
{{--                                <tr>--}}
{{--                                    <td>--}}

{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        <div class="input-group">--}}

{{--                                        </div>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                @can($__env->yieldContent('permission_modify'))--}}
{{--                    <div class="card-footer bg-whitesmoke text-md-right">--}}
{{--                        <button class="btn btn-primary" type="submit" onclick="requestSettingSubmit(this);return false">保存</button>--}}
{{--                    </div>--}}
{{--                @endcan--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
