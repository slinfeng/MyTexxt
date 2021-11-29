<div class="modal-header">
    <div class="row"><h3>{{__('休暇履歴画面') }}</h3></div>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-6">&nbsp;&nbsp;&nbsp;&nbsp;{{"社員ID: ".str_pad($employee->id, 4, '0', STR_PAD_LEFT)}} </div>
        <div class="col-6">{{"氏名:".$employee->name.(isset($employee->date_retire)?'(退職)':'')}}</div>
    </div>
    <div class="col-12">
        <table class="table table-striped mb-0 datatable no-footer w-100" id="leave-history"
               url-history="{{route('leaves.getLeaves')}}">
            <thead>
            <tr>
                <th>休暇期間</th>
                <th>休暇日数</th>
                <th>理由</th>
                <th>承認日</th>
                <th>承認者</th>
                <th>メモ</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
<script type="text/javascript">
    $('#leave-history').DataTable({
        scrollCollapse: true,
        paging: false,
        searching: false,
        serverSide: true,
        bAutoWidth:false,
        scrollY:'500px',
        oLanguage:{
            sInfoEmpty : '総件数:0 件',
            sInfo :'総件数:_TOTAL_ 件',
            sZeroRecords : "表示するデータがありません",
            select:{
                rows:{
                    _: "%d行を選択しました",
                    0: ""
                }
            }
        },
        ajax: {
            url: $('#leave-history').attr('url-history'),
            'data': function(d) {
                d.historyId= {{$employee->id}};
            },
            type: "get",
            dataType: "json"
        },
        columns: [
            {data: 'leave_date', name: 'leave_date', width: '30%', className: 'nowrap'},
            {data: 'days_of_leave', name: 'days_of_leave', width: '15%', className: 'nowrap'},
            {data: 'reason', name: 'reason', width: '15%', className: 'nowrap'},
            {data: 'approved_date', name: 'approved_date', width: '15%', className: 'nowrap'},
            {data: 'approved_by_user', name: 'approved_by_user', width: '15%', className: 'nowrap'},
            {data: 'memo', name: 'memo', width: '10%', className: 'nowrap'},
            // {data: 'action', name: 'action', width: '8%',orderable: false, className: 'width-action nowrap'},
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
