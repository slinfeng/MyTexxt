@section('permission_modify','asset_modify')
<style type="text/css">
    #tab > div {
        width: 500px;
        height: 200px;
        border: 1px solid gray;
        display: none;
        text-align: center;
        line-height: 200px;
        font-family: "微软雅黑";
        font-size: 24px;
    }

    #tab .content {
        display: block;
    }

    .notClick {
        pointer-events: none;
    }

    .modal-window {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1070;
        display: none;
        width: 100%;
        height: 100%;
        overflow: hidden;
        outline: 0;
    }

    .modal-open .modal {
        overflow-y: scroll;
    }

    .window-dialog {
        max-width: 400px;
        top: calc(50% - 10px);
    }

    .window-dialog .modal-body {
        padding: 10px;
    }

    .window-dialog .modal-body .form-header {
        margin-bottom: 10px;
    }

    .window-dialog-backdrop {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1060;
        width: 100%;
        height: 100%;
        background-color: #000;
    }

    .window-dialog-backdrop.show {
        opacity: 0.4;
    }

    .back-info {
        background-color: #e3e3e3;
        border: 1px solid #e3e3e3;
        box-shadow: none;
        height: 3em;
        line-height: 1.5em;
        display: flex;
        align-items: center;
        margin: 0 auto;
    }

    .back-disable {
        border: 1px solid #e3e3e3;
        box-shadow: none;
        background-color: #e3e3e3;
    }

    textarea:read-only:focus {
        outline: none;
    }

    #loan-asset input:read-only {
        background-color: #e3e3e3;
    }

    #loan-asset input:not(:read-only), #loan-asset textarea:not(:read-only) {
        background-color: #fff;
    }

    .btn {
        cursor: pointer;
    }
</style>
<div>
    <div class="row">
        <div class="col-md-2">
            {{ __('管理番号')}}<br/>
            <div class="back-info">{{$assetInfo->number.' '.$assetInfo->manage_code}}</div>
        </div>
        <div class="col-md-6" style="word-break: break-all">
            <div>
                {{ __('品名')}}<br/>
                <div class="back-info">{{$assetInfo->productName}}</div>
            </div>
        </div>
        <div class="col-md-4" style="word-break: break-all">
            {{ __('シリアル番号')}}<br/>
            <div class="back-info">{{$assetInfo->serial_number}}</div>
        </div>
    </div>
</div>
<br/>
<main>
    <ul class="nav nav-tabs">
        @can($__env->yieldContent('permission_modify'))
                <li class="nav-item">
                    <a href="#tab1" id="tab1-head" class="nav-link @if(!isset($assetRentalLog) || $assetRentalLog->status!=2)
                        active
@else
                        hide
@endif
                        " data-toggle="tab">貸出</a>
                </li>
            <li class="nav-item">
                <a href="#tab2" class="nav-link
@if(isset($assetRentalLog) && $assetRentalLog->status==2)
                    active
@endif
                    " data-toggle="tab">貸出歴史</a>
            </li>
        @endcan

    </ul>

    <div class="tab-content">
            <div id="tab1" class="tab-pane
             @if(!isset($assetRentalLog) || $assetRentalLog->status!=2)
             active
             @else
                hide
             @endif
">
                <h4>前回情報</h4>
                <div class="row">
                    <div class="col-md-3">
                        状態<br/>
                        <div class="back-disable">
                            @if(isset($assetRentalLog))
                                @switch($assetRentalLog->status)
                                    @case(0)社内@break
                                    @case(1)貸出中@break
                                    @case(2)廃棄@break
                                    @default
                                @endswitch
                            @else
                                社内
                            @endif</div>
                    </div>
                    <div class="col-md-3">
                        @if(isset($assetRentalLog))
                            @switch($assetRentalLog->status)
                                @case(0)返却日<br/>@break
                                @case(1)貸出日<br/>@break
                                @default貸出日<br/>
                            @endswitch
                        @else
                            返却日<br/>
                        @endif
                        <div
                            class="back-disable">{{isset($assetRentalLog->loan_or_return_date) && $assetRentalLog->loan_or_return_date!=''?substr($assetRentalLog->loan_or_return_date,0,10):'無'}}</div>
                    </div>
                    <div class="col-md-3">
                        利用者<br/>
                        <div class="back-disable">
                            @if(isset($assetRentalLog))
                                {{$assetRentalLog->user!=''?$assetRentalLog->user:'無'}}
                            @else
                                無
                            @endif</div>
                    </div>
                    <div class="col-md-3">
                        担当者<br/>
                        <div class="back-disable">
                            @if(isset($assetRentalLog))
                                {{$assetRentalLog->user_responsible->name}}
                            @else
                                無
                            @endif</div>
                    </div>
                </div>
                備考
                <div class="row" style="padding: 0 15px">
                    <textarea readonly class="w-100 back-disable" rows="2"
                              style="resize: none;border: none;">@if(isset($assetRentalLog)){{$assetRentalLog->remark}}@endif</textarea>
                </div>
                @can($__env->yieldContent('permission_modify'))
                    <br/>
                    <h4>@if(isset($assetRentalLog))
                            @switch($assetRentalLog->status)
                                @case(0)資産貸出 @break
                                @case(1)資産返却 @break
                                @default
                            @endswitch
                        @else
                            資産貸出
                        @endif</h4>
                    <form id="loan-or-return" data-action="{{ route('companyassets.rental',$assetInfo->id) }}"
                          method="POST">
                        @csrf
                        <input type="hidden" value="@if(isset($assetRentalLog))
                        @switch($assetRentalLog->status)
                        @case(0) 1 @break
                        @case(1) 0 @break
                        @default
                        @endswitch
                        @else
                            1
@endif" name="status">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">@if(isset($assetRentalLog))
                                            @switch($assetRentalLog->status)
                                                @case(0)貸出日@break
                                                @case(1)返却日@break
                                                @default
                                            @endswitch
                                        @else
                                            貸出日
                                        @endif</label>
                                    <input class="form-control datetime" type="text" name="loan_or_return_date"
                                           value="{{date('Y-m-d')}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">利用者</label>
                                    <input class="form-control" type="text" name="user"
                                    @if(isset($assetRentalLog) && $assetRentalLog->status==1)
                                        {{'value='.($assetRentalLog->user==''?'無':$assetRentalLog->user).' readonly'}}
                                        @endif>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remark">備考</label>
                            <textarea maxlength="100" name="remark" cols="30" rows="3" class="form-control"
                                      autofocus=""></textarea>

                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary"
                                    style="font-size:18px;width: 8em;height:40px;display: inline-block;" type="button"
                                    onclick="checkToSubmit(this)">@if(isset($assetRentalLog))
                                    @switch($assetRentalLog->status)
                                        @case(0){{ __('貸出') }}@break
                                        @case(1){{ __('返却') }}@break
                                        @default
                                    @endswitch
                                @else
                                    {{ __('貸出') }}
                                @endif</button>
                        </div>
                    </form>
                @endif

                {{--            <div class="row">--}}
                {{--                <div class="col-md-12">--}}
                {{--                    <div class="table-responsive">--}}
                {{--                        <table class="table table-striped custom-table mb-0 datatable w-100"--}}
                {{--                               id="assetInfoTable">--}}
                {{--                            <input type="hidden" value="{{$assetInfo->id}}" id="assetInfoId"/>--}}
                {{--                            <thead>--}}
                {{--                            <h4 style="text-align: left;">貸出履歴一覧:</h4>--}}
                {{--                            <tr>--}}
                {{--                                <th>{{ __('状態') }}</th>--}}
                {{--                                <th>{{ __('貸出/帰還日') }}</th>--}}
                {{--                                <th>{{ __('利用者') }}</th>--}}
                {{--                                <th>{{ __('担当者') }}</th>--}}
                {{--                                <th>{{ __('備考') }}</th>--}}
                {{--                            </tr>--}}
                {{--                            </thead>--}}
                {{--                        </table>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--            </div>--}}
            </div>

        @can($__env->yieldContent('permission_modify'))
            <div id="tab2" class="tab-pane
@if(isset($assetRentalLog) && $assetRentalLog->status==2)
                active
                @endif">
                <div id="edit-rental" class="hide">
                    <h3>貸出履歴編集</h3>
                    <form data-action="{{ route('asset.updateRental',':id') }}" method="POST">
                        @csrf
                        <div class="row" id="edit-use-able">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label id="edit-date"></label>
                                    <input class="form-control datetime" type="text" name="loan_or_return_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label id="edit-user">利用者</label>
                                    <input maxlength="20" class="form-control" type="text" name="user">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remark">備考</label>
                            <textarea maxlength="100" name="remark" cols="30" rows="3" class="form-control"
                                      autofocus=""></textarea>
                        </div>
                        <div class="submit-section">
                            <button style="font-size:18px;width: 20%;height:50px;display: inline-block;"
                                    class="btn btn-primary" type="button" onclick="updateRental()">保存
                            </button>
                            <button style="font-size:18px;width: 20%;height:50px;display: inline-block;"
                                    class="btn btn-primary" type="button" onclick="cancelEdit()">キャンセル
                            </button>
                        </div>
                    </form>
                </div>
                <h4 id="loan-title" style="text-align: left;">貸出履歴一覧:
                    @if(!isset($assetRentalLog) || $assetRentalLog->status!=2)
                        <button class="btn-option btn btn-sm btn-danger float-right" type="button"
                                data-action="{{route('companyassets.destroy',$assetInfo->id)}}"
                                onclick="toDestroyAsset(this)">廃棄
                        </button>
                    @elseif($assetRentalLog->status==2)
                        <button class="btn-option btn btn-sm btn-danger float-right" type="button"
                                data-action="{{route('companyassets.restore',$assetInfo->id)}}"
                                onclick="toRestoreAsset(this)">回復
                        </button>
                    @endif</h4>
                <div class="row w-100 m-lg-0">
                    <div class="col-md-12 p-lg-0">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0 datatable w-100"
                                   id="assetInfoTableAdmin">
                                <input type="hidden" value="{{$assetInfo->id}}" id="assetInfoId"/>
                                <thead>
                                <tr>
                                    <th>{{ __('状態') }}</th>
                                    <th>{{ __('貸出/帰還日') }}</th>
                                    <th>{{ __('利用者') }}</th>
                                    <th>{{ __('担当者') }}</th>
                                    <th>{{ __('備考') }}</th>
                                    <th>{{ __('操作') }}</th>
                                </tr>
                                </thead>
                                <div id="window-dialog-backdrop" class="window-dialog-backdrop fade hide"></div>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-window" id="confirm">
                    <div class="modal-dialog window-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="form-header">
                                    <h3></h3>
                                    <p></p>
                                    <form>
                                        @csrf
                                    </form>
                                </div>
                                <div class="modal-btn delete-action">
                                    <div class="row">
                                        <div class="col-6">
                                            <a href="javascript:void(0);" class="btn btn-primary continue-btn"></a>
                                        </div>
                                        <div class="col-6">
                                            <a href="javascript:void(0);" class="btn btn-primary cancel-btn"
                                               onclick="cancelConfirm('#confirm')">{{__('Cancel')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </div>
</main>
<script>
    $(function () {
        // let table_loan = $('#assetInfoTable');
        let table_admin = $('#assetInfoTableAdmin');
        // table_loan.dataTable().fnDestroy();
        {{--table_loan.DataTable({--}}
        {{--    columnDefs: [{"defaultContent": "", "targets": "_all"}],--}}
        {{--    searching: false,--}}
        {{--    ordering: false,--}}
        {{--    serverSide: true,--}}
        {{--    paging: false,--}}
        {{--    "ajax": {--}}
        {{--        "url": "{{route('asset.getAssetRentalLogs')}}",--}}
        {{--        "type": "POST",--}}
        {{--        "datatype": "json",--}}
        {{--        "data": function (d) {--}}
        {{--            d.asset_info_id = $('#assetInfoId').val();--}}
        {{--        }--}}
        {{--    },--}}
        {{--    oLanguage: {--}}
        {{--        sInfoEmpty: '総件数:0 件',--}}
        {{--        sInfo: '総件数:_TOTAL_ 件',--}}
        {{--        sZeroRecords: "表示するデータがありません",--}}
        {{--    },--}}
        {{--    "columns": [--}}
        {{--        {data: 'status', name: 'status'},--}}
        {{--        {data: 'loan_or_return_date', name: 'loan_or_return_date'},--}}
        {{--        {data: 'user', name: 'user'},--}}
        {{--        {data: 'responsible_person', name: 'responsible_person'},--}}
        {{--        {data: 'remark', name: 'remark', className: 'remark'},--}}
        {{--    ],--}}
        {{--});--}}
        table_admin.dataTable().fnDestroy();
        table_admin.DataTable({
            columnDefs: [{"defaultContent": "", "targets": "_all"}],
            searching: false,
            ordering: false,
            serverSide: true,
            paging: false,
            "ajax": {
                "url": "{{route('asset.getAssetRentalLogs')}}",
                "type": "POST",
                "datatype": "json",
                "data": function (d) {
                    d.asset_info_id = $('#assetInfoId').val();
                }
            },
            oLanguage: {
                sInfoEmpty: '総件数:0 件',
                sInfo: '総件数:_TOTAL_ 件',
                sZeroRecords: "表示するデータがありません",
            },
            "columns": [
                {data: 'status', name: 'status'},
                {data: 'loan_or_return_date', name: 'loan_or_return_date'},
                {data: 'user', name: 'user'},
                {data: 'responsible_person', name: 'responsible_person'},
                {data: 'remark', name: 'remark', className: 'remark'},
                {data: 'action', name: 'action'},
            ],
        });
        initSingleDatePicker('.datetime');
        $('.sorting_asc').removeClass('sorting_asc');
    })

    function checkToSubmit() {
        const form = $('#loan-or-return');
        const obj = lockAjax();
        $.ajax({
            url: form.data('action'),
            type: 'post',
            data: form.serialize(),
            success: function (res) {
                ajaxSuccessAction(res, function () {
                    $('#loan-asset').modal("hide");
                    table.draw();
                });
            }, error: function (jqXHR, testStatus, error) {
                ajaxErrorAction(jqXHR, testStatus, error);
            }, complete: function () {
                unlockAjax(obj);
            }
        });
    }

    function confirm(id, t1, t2, t3, action, func) {
        let header = $('#confirm div.form-header');
        header.find('h3').html(t1);
        header.find('p').html(t2);
        header.find('form').attr('action', action);
        let btn = $('#confirm div.delete-action').find('a').first();
        btn.html(t3);
        btn.attr('onclick', func);
        $('#window-dialog-backdrop').removeClass('hide').addClass('show');
        $(id).show();
    }

    function cancelConfirm(id) {
        $('#window-dialog-backdrop').removeClass('show').addClass('hide');
        $(id).hide();
    }

    function toDestroyAsset(e) {
        const t1 = '{{ __('資産の廃棄') }}';
        const t2 = '{{ __('当該資産を廃棄してもよろしいでしょうか?') }}';
        const t3 = '{{ __('廃棄') }}';
        const action = $(e).data('action');
        const func = 'destroyAsset()';
        confirm('#confirm', t1, t2, t3, action, func);
    }

    function destroyAsset() {
        const action = $('#confirm form').attr('action');
        const obj = lockAjax();
        $.ajax({
            url: action,
            type: 'post',
            data: {_method: 'DELETE'},
            success: function (res) {
                ajaxSuccessAction(res, function () {
                    $('#loan-asset').modal("hide");
                    $('#AssetInfo').DataTable().draw();
                });
            }, error: function (jqXHR, testStatus, error) {
                ajaxErrorAction(jqXHR, testStatus, error);
            }, complete: function () {
                unlockAjax(obj);
            }
        })
    }

    function toRestoreAsset(e) {
        const t1 = '{{ __('資産の回復') }}';
        const t2 = '{{ __('当該資産を回復してもよろしいでしょうか?') }}';
        const t3 = '{{ __('回復') }}';
        const action = $(e).data('action');
        const func = 'restoreAsset()';
        confirm('#confirm', t1, t2, t3, action, func);
    }

    function restoreAsset() {
        const obj = lockAjax();
        $.ajax({
            url: $('#confirm form').attr('action'),
            type: "get",
            success: function (res) {
                ajaxSuccessAction(res, function () {
                    $('#loan-asset').modal("hide");
                    $('#AssetInfo').DataTable().draw();
                });
            }, error: function (jqXHR, testStatus, error) {
                ajaxErrorAction(jqXHR, testStatus, error);
            }, complete: function () {
                unlockAjax(obj);
            }
        })
    }

    function toEditRental(rental_id) {
        let url = "{{route('asset.editRental',':rental_id')}}";
        url = url.replace(':rental_id', rental_id);
        $.ajax({
            url: url,
            type: "get",
            datatype: "json",
            success: function (res) {
                switch (res.status) {
                    case 0:
                        if (res.date_str != '') {
                            $('#edit-use-able').removeClass('hide');
                            $('#edit-date').html('帰還日');
                        } else $('#edit-use-able').addClass('hide');
                        break;
                    case 1:
                        $('#edit-use-able').removeClass('hide');
                        $('#edit-date').html('貸出日');
                        break;
                    case 2:
                        $('#edit-use-able').addClass('hide');
                        break;
                }
                $('#edit-rental input[name=loan_or_return_date]').val(res.date_str);
                initSingleDatePicker('.datetime');
                $('#edit-rental input[name=user]').val(res.user);
                $('#edit-rental textarea[name=remark]').html(res.remark);
                let action = $('#edit-rental form').data('action');
                action = action.replace(':id', res.id);
                $('#edit-rental form').attr('action', action);
                $('#edit-rental').removeClass('hide');
            }
        });
    }

    function cancelEdit() {
        $('#edit-rental').addClass('hide');
    }

    function updateRental() {
        let form = $('#edit-rental form');
        let url = form.attr('action');
        $.ajax({
            url: url,
            data: form.serialize(),
            type: 'post',
            success: function (res) {
                ajaxSuccessAction(res, function () {
                    cancelEdit();
                    $('#assetInfoTableAdmin').DataTable().draw();
                    $('#AssetInfo').DataTable().draw();
                });
            }, error: function (jqXHR, testStatus, error) {
                ajaxErrorAction(jqXHR, testStatus, error);
            }
        })
    }

    function toDelRental(e) {
        const t1 = '{{ __('貸出情報の削除') }}';
        const t2 = '{{ __('当該貸出情報を削除してもよろしいでしょうか？') }}';
        const t3 = '{{ __('削除') }}';
        const action = $(e).data('action');
        const func = 'delRental()';
        confirm('#confirm', t1, t2, t3, action, func);
    }

    function delRental() {
        $.ajax({
            url: $('#confirm form').attr('action'),
            type: "get",
            success: function (res) {
                ajaxSuccessAction(res, function () {
                    cancelConfirm('#confirm');
                    $('#assetInfoTableAdmin').DataTable().draw();
                    $('#AssetInfo').DataTable().draw();
                    let btn = $('#loan-title button');
                    if (res.asset_status === 2) {
                        btn.html('回復');
                        btn.attr('onclick', 'toRestoreAsset(this)');
                        btn.attr('data-action', "{{route('companyassets.restore',$assetInfo->id)}}");
                        $('#tab1').addClass('hide');
                        $('#tab1-head').addClass('hide');
                    } else {
                        console.log(btn.html());
                        if(btn.html().trim()==='回復') {
                            console.log(btn.html());
                            $('#loan-asset').modal("hide");
                        }
                        btn.html('廃棄');
                        btn.attr('onclick', 'toDestroyAsset(this)');
                        btn.attr('data-action', "{{route('companyassets.destroy',$assetInfo->id)}}");
                        $('#tab1').removeClass('hide');
                        $('#tab1-head').removeClass('hide');


                        {{--alert(res);--}}
                        {{--var route='{{ route('companyassets.rental',':id') }}';--}}
                        {{--route=route.replace(':id',res.id);--}}
                        {{--$('#loan-or-return').attr('data-action',route);--}}
                        {{--$('#loan-or-return input[name=status]').val(res.asset_status);--}}

                        {{--if(res.asset_status === 0){--}}
                        {{--    $('#loan-or-return button').text('貸出');--}}
                        {{--}else{--}}
                        {{--    $('#loan-or-return button').text('返却');--}}
                        {{--}--}}

                    }
                });
            }, error: function (jqXHR, testStatus, error) {
                ajaxErrorAction(jqXHR, testStatus, error);
            }
        })
    }
</script>
