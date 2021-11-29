@section('permission_modify','leave_modify')
<style type="text/css">
    #tab > div {
        width: 500px;
        height: 200px;
        border: 1px solid gray;
        display: none;
        text-align: center;
        line-height: 200px;
        font-size: 24px;
    }

    #tab .content {
        display: block;
    }

    .notClick {
        pointer-events: none;
    }

    .modal-window{
        position: absolute;
        top: 185px;
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
    .window-dialog{
        max-width: 400px;
        top:calc( 50% - 10px );
    }
    .window-dialog .modal-body{
        padding: 10px;
    }
    .window-dialog .modal-body .form-header{
        margin-bottom: 10px;
    }
    .window-dialog-backdrop{
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1060;
        width: 100%;
        height: 100%;
        background-color: #000;
    }
    .window-dialog-backdrop.show{
        opacity: 0.4;
    }
    .back-info{
        background-color: #e3e3e3;
        border: 1px solid #e3e3e3;
        box-shadow: none;
        height: 3em;
        line-height: 1.5em;
        display:flex;
        align-items:center;
        margin:0 auto;
    }
    .back-disable{
        border: 1px solid #e3e3e3;
        box-shadow: none;
        background-color: #e3e3e3;
    }
    textarea:read-only:focus{
        outline: none;
    }
    .btn{
        cursor: pointer;
    }
</style>
<div class="row w-100 m-lg-0">
    <div class="col-md-12 p-lg-0">
        <div class="table-responsive">
    <table class="table table-striped custom-table mb-0 datatable no-footer w-100" id="leave-history">
        <thead>
        <tr>
            <th>休暇期間</th>
            <th>休暇日数</th>
            <th>理由</th>
            <th>承認状態</th>
            <th>承認日</th>
            <th>承認者</th>
            <th>メモ</th>
            @can($__env->yieldContent('permission_modify'))
            <th>操作</th>
            @endcan
        </tr>
        </thead>
        <div id="window-dialog-backdrop" class="window-dialog-backdrop fade hide"></div>
    </table>
        </div>
    </div>
</div>

<!-- Delete leave Modal -->
<div class="modal-window" id="delete_leave" style="display: none;">
    <div class="modal-dialog window-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>{{ __('休暇の削除') }} </h3>
                    <p>{{ __('休暇を削除しますか?') }}</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <a href="javascript:void(0);" onclick="deleteLeaveAlert(this);"
                               class="btn btn-primary continue-btn">{{ __('削除') }}</a>
                        </div>
                        <div class="col-6">
                            <a href="javascript:void(0);" class="btn btn-primary cancel-btn" onclick="cancelConfirm('#delete_leave')">{{__('Cancel')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Delete leave Modal -->

<script type="text/javascript">

    var historyTable = $('#leave-history').DataTable({
        scrollCollapse: true,
        paging: false,
        searching: false,
        serverSide: true,
        bAutoWidth: false,
        scrollY: '500px',
        oLanguage: {
            sInfoEmpty: '総件数:0 件',
            sInfo: '総件数:_TOTAL_ 件',
            sZeroRecords: "表示するデータがありません",
            select: {
                rows: {
                    _: "%d行を選択しました",
                    0: ""
                }
            }
        },
        ajax: {
            url: $('#url').attr('url-history'),
            'data': function (d) {
                d.id ={{isset($employee)?$employee->id:0}};
            },
            type: "get",
            dataType: "json"
        },
        columns: [
            {data: 'leave_date', name: 'leave_date', width: '8rem', className: 'nowrap'},
            {data: 'days_of_leave', name: 'days_of_leave', width: '5rem', className: 'nowrap'},
            {data: 'reason', name: 'reason', className: 'nowrap'},
            {data: 'status_action', name: 'status_action', width: '5rem', className: 'nowrap text-center'},
            {data: 'approved_date', name: 'approved_date', width: '4rem', className: 'nowrap'},
            {data: 'approved_by_user', name: 'approved_by_user', width: '4rem', className: 'nowrap'},
            {data: 'memo', name: 'memo', className: 'nowrap'},
            @can($__env->yieldContent('permission_modify'))
            {
                data: 'action', name: 'action', width: '2.2rem', orderable: false, className: 'text-center'
            },
            @endcan
        ],
        select: {
            style: 'multi',
            selector: 'td:first-child',
        },
        order: [
            [0, 'desc']
        ]
    });
</script>
