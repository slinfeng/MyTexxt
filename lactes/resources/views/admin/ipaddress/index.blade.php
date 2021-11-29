<div class="tab-content">
    <div class="pro-overview tab-pane fade show active">
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header mb-3">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="mb-0">{{ __('IPAddress') }}</h4>
                    </div>
                    @can($__env->yieldContent('permission_modify'))
                    <div class="col-4">
                        <button class="btn btn-primary float-right" type="button"  onclick="addEditIPAddress();return false" >{{ __('保存') }}</button>
                    </div>
                    @endcan
                </div>
            </div>


            <div class="col-12">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
            </div>

            <div class="card-body">
                <input type="hidden" name="delId[]" value="">
                <form method="POST" id='ipAddress_add_edit_form'>
                    @csrf
                <table id="IPAddress" class="table table-bordered w-100">
                    <thead>
                    <tr>
                        <th rowspan="2" class="align-text-top">{{__('No.')}}</th>
                        <th rowspan="2" class="align-text-top">{{__('タイトル')}}</th>
                        <th colspan="8">IPアドレス(0~255の半角数字)</th>
                    </tr>
                    <tr>
                        <th colspan="2">第1オクテット</th>
                        <th colspan="2">第2オクテット</th>
                        <th colspan="2">第3オクテット</th>
                        <th colspan="2">第4オクテット</th>
                    </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td class="align-text-top">(入力例)</td>
                            <td class="align-text-top">本社</td>
                            <td>123</td>
                            <td>.</td>
                            <td>123</td>
                            <td>.</td>
                            <td>123</td>
                            <td>.</td>
                            <td>0</td>
                            <td>(~100)</td>
                        </tr>
                        @if(count($ipAddressArr)>0)
                            @foreach($ipAddressArr as $ipAddress)
                        <tr>
                            <input type="hidden" name="id[]" value="{{$ipAddress[2]}}">
                            <input name="sort_num[]" type="hidden" value="{{$ipAddress[3]}}">
                            <td class="align-text-top">{{$ipAddress[3]}}</td>
                            <td class="align-text-top"><input type="text" name="ipAddressName[]" class="form-control" value="{{$ipAddress[4]}}" placeholder="IPアドレス名"></td>
                            <td><input name="IPAddress00[]" class="form-control" type="text" oninput="inputCheck(this);" onchange="" value="{{$ipAddress[0][0]}}" autofocus required></td>
                            <td>.</td>
                            <td><input name="IPAddress01[]" class="form-control" type="text" oninput="inputCheck(this);" onchange="" value="{{$ipAddress[0][1]}}" autofocus required></td>
                            <td>.</td>
                            <td><input name="IPAddress02[]" class="form-control" type="text" oninput="inputCheck(this);" onchange="" value="{{$ipAddress[0][2]}}" autofocus required></td>
                            <td>.</td>
                            <td><input name="IPAddress03[]" class="form-control" type="text" oninput="inputCheck(this);" onchange="lastInputCheck(this);" value="{{$ipAddress[0][3]}}" autofocus required></td>
                            <td>(~<input  name="IPAddress13[]" class="ip-input form-control" type="text" oninput="inputCheck(this);" onchange="lastInputCheck(this);" value="{{$ipAddress[1][3]}}" autofocus required>)
                                @can($__env->yieldContent('permission_modify'))
                                <span name="addAndDelete" data-print="false" style="position: absolute;right: 5px;line-height: 18px;font-size: 18px">
                                <a href="javascript:void(0);" onclick="delIPAddress(this)" onmousemove="showLinePoint(this)" onmouseleave="hideLinePoint(this)">⊝</a>
                            <span class="linePoint" style="display: none;">行削除</span>
                            <br>
                                <a href="javascript:void(0);" onclick="addIPAddress(this)" onmousemove="showLinePoint(this)" onmouseleave="hideLinePoint(this)">⊕</a>
                            <span class="linePoint" style="display: none;">行追加</span>
                            </span>
                                @endcan
                            </td>
                        </tr>
                            @endforeach
                        @else
                            <tr>
                                <input type="hidden" name="id[]" value="">
                                <input name="sort_num[]" type="hidden" value="1">
                                <td class="align-text-top">1</td>
                                <td class="align-text-top"><input type="text" name="ipAddressName[]" class="form-control" value="" placeholder="IPアドレス名"></td>
                                <td><input name="IPAddress00[]" class="form-control" type="text" oninput="inputCheck(this);" onchange="" value="" autofocus required></td>
                                <td>.</td>
                                <td><input name="IPAddress01[]" class="form-control" type="text" oninput="inputCheck(this);" onchange="" value="" autofocus required></td>
                                <td>.</td>
                                <td><input name="IPAddress02[]" class="form-control" type="text" oninput="inputCheck(this);" onchange="" value="" autofocus required></td>
                                <td>.</td>
                                <td><input name="IPAddress03[]" class="form-control" type="text" oninput="inputCheck(this);" onchange="lastInputCheck(this);" value="" autofocus required></td>
                                <td>(~<input  name="IPAddress13[]" class="ip-input form-control" type="text" oninput="inputCheck(this);" onchange="lastInputCheck(this);" value="" autofocus required>)
                                    <span name="addAndDelete" data-print="false" style="position: absolute;right: 5px;line-height: 18px;font-size: 18px">
                                <a href="javascript:void(0);" onclick="delIPAddress(this)" onmousemove="showLinePoint(this)" onmouseleave="hideLinePoint(this)">⊝</a>
                            <span class="linePoint" style="display: none;">行削除</span>
                            <br>
                                <a href="javascript:void(0);" onclick="addIPAddress(this)" onmousemove="showLinePoint(this)" onmouseleave="hideLinePoint(this)">⊕</a>
                            <span class="linePoint" style="display: none;">行追加</span>
                            </span>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                </form>
            </div>
        </div>
    </div>
</div>

    </div></div>

