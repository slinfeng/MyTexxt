<!-- Payroll Additions Table -->
<div class="tab-content">
    <div id="emp_profile" class="pro-overview tab-pane fade show active">
        <div class="card">
            <form action="{{route('scheduleSetting.update',0)}}" method="post">
                @csrf
                @method('put')
                <div class="row m-0">
                    <div class="card-header w-100">
                        <h4 class="m-0">{{ __('全般設定') }}</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="card-body">
                            <table class="table-left-setting w-100">
                                <tr>
                                    <td>
                                        {{ __('予約制限') }}
                                    </td>
                                    <td>
                                        <label style="width: 20%">
                                            <input type="radio" name="reservation_restrictions_type" value="0" {{$scheduleSetting->reservation_restrictions_type==0?'checked':''}}>
                                            <span>{{ __('なし') }}</span>
                                        </label>
                                        <label style="width: 75%">
                                            <input type="radio" name="reservation_restrictions_type" value="1" {{$scheduleSetting->reservation_restrictions_type==1?'checked':''}}>
                                            <span>{{ __('あり（予約登録した本人以外による、予約の変更·削除を禁止）') }}</span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('匿名予約の許可') }}
                                    </td>
                                    <td>
                                        <input type="hidden" name="anonymous_type" value="0">
                                        <label style="width: 20%">
                                            <input type="checkbox" name="anonymous_type" value="1" {{$scheduleSetting->anonymous_type==1?'checked':''}}>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('予約者名表示（初期値）') }}
                                    </td>
                                    <td>
                                        <input type="hidden" name="display_reservation_type" value="0">
                                        <label style="width: 20%">
                                            <input type="checkbox" name="display_reservation_type" value="1" {{$scheduleSetting->display_reservation_type==1?'checked':''}}>
                                        </label>
                                        <label style="width: 20%">
                                            <span>{{ __('例：山本｜定例会議') }}</span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('重複予約可（初期値）') }}
                                    </td>
                                    <td>
                                        <input type="hidden" name="duplicate_reservation_type" value="0">
                                        <label style="width: 20%">
                                            <input type="checkbox" name="duplicate_reservation_type" value="1" {{$scheduleSetting->duplicate_reservation_type==1?'checked':''}}>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('表示時間帯') }}
                                    </td>
                                    <td>
                                        <div class="input-group" style="width: 300px">
                                            <input type="text" maxlength="2" class="form-control text-right" name="display_time_start" value="{{explode("～",$scheduleSetting->display_time)[0]}}">
                                            <div class="input-group-append">
                                                <span class="input-group-text border-right-0" id="basic-addon2">時～</span>
                                            </div>
                                            <input type="text" maxlength="2" class="form-control text-right" name="display_time_end" value="{{explode("～",$scheduleSetting->display_time)[1]}}">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">時</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ __('ドラッグ精度') }}
                                    </td>
                                    <td>
                                        <label style="width: 20%">
                                            <select name="drag_accuracy_type" class="form-control select w-100">
                                                <option value="0" {{$scheduleSetting->drag_accuracy_type==0?'selected':''}}>1分</option>
                                                <option value="1" {{$scheduleSetting->drag_accuracy_type==1?'selected':''}}>5分</option>
                                                <option value="2" {{$scheduleSetting->drag_accuracy_type==2?'selected':''}}>10分</option>
                                                <option value="3" {{$scheduleSetting->drag_accuracy_type==3?'selected':''}}>15分</option>
                                            </select>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                {{--        @can($__env->yieldContent('permission_modify'))--}}
                <div class="card-footer bg-whitesmoke text-md-right">
                    <button class="btn btn-primary" type="button" onclick="scheduleSubmit(this)">保存</button>
                </div>
                {{--        @endcan--}}
            </form>
        </div>
    </div></div>
