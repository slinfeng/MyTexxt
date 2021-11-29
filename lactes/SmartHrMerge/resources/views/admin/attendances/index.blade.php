@extends('layouts.backend')
@section('title', __('Attendance').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('Attendance'))
@section('permission_modify','attendance_modify')
@section('css_append')
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables/select/select.dataTables.min.css') }}">
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.searchableSelect.css')}}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css') }}"/>

    <!-- Datetimepicker CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css')}}">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css')}}">
    <style>
        span.files-info{
            padding: 0;
        }
        .file-header{
            display: flex;
        }
        .file-header span {
            font-size: 20px;
            font-weight: 600;
            text-transform: capitalize;
            background-color: #fff;
            color: #324148;
            display: flex;
            height: 72px;
            padding: 0 15px;
            border-bottom: 1px solid #e0e3e4;
            align-items: center;
        }
        .file-search{
            display: flex;
        }
        .view-icons{
            margin-left: auto;
        }
        tbody .pd-tb-5{
            padding-top: 5px!important;
            padding-bottom: 5px!important;
        }
        tbody .material-icons{
            margin-top: 4px;
        }
        .dataTables_scrollHeadInner{
            width: 100%!important;
        }
        #upload_confirm_modal .form-header p{
            text-align: left;
        }
        table.dataTable thead th.select-checkbox {
            position: relative;
        }
        #attendance-info thead th.select-checkbox {
            visibility: hidden;
        }
        table.dataTable thead th.select-checkbox:before {
            content: ' ';
            margin-top: -6px;
            margin-left: -6px;
            border: 1px solid black;
            border-radius: 3px;
        }
        table.dataTable thead th.select-checkbox:before, table.dataTable thead th.select-checkbox:after {
            display: block;
            position: absolute;
            top: 1.2em;
            left: 50%;
            width: 12px;
            height: 12px;
            box-sizing: border-box;
        }
        table.dataTable thead th.select-checkbox:before {
            top: 50% !important;
        }
        table.dataTable tbody td.select-checkbox:before {
            top: 50% !important;
        }
        table.dataTable thead th.select-checkbox:after {
            top: 50% !important;
        }
        table.dataTable tbody td.select-checkbox:after {
            top: 50% !important;
        }
        table.dataTable tbody tr {
            background-color: white!important;
        }
        .view-icons .btn{
            margin-top: 4px;
        }
        table input{
            border: none;
            background-color: #E8F0FE;
        }
        .color-red{
            color: red!important;
        }
        .disable-input {
            border: none;
            pointer-events: none;
            background-color: transparent;
        }
        div.searchable-select{
            width: 100%;
            outline: none;
        }
        div.searchable-select-holder{
            line-height: 32px;
        }
        .dropdown-menu{
            min-width: 70px;
            transform: translate3d(-46px, 24px, 0px);
        }
        #update .submit-btn{
            min-width: 132px;
        }
        .modal-dialog{
            max-width: 400px;
        }
        @media (max-width: 1400px){
            #card div.col-6 {
                -ms-flex: 0 0 33.33%;
                flex: 0 0 33.33%;
                max-width: 33.33%;
            }
        }
        .card-file h6 a[name=file_name]{
            color: #007bff;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="file-header">
                <span class="w-100" name="title">勤務管理</span>
                <div>
                    @include('layouts.pages.initval.hrmanage.input_file')
{{--                    <span class="btn-file">--}}
{{--                        <form data-url="{{route('attendances.uploadFile')}}" id="file">--}}
{{--                            <input name="file[]" type="file" multiple="true" onchange="uploadFile()" class="upload">--}}
{{--                            <i class="fa fa-upload"></i>--}}
{{--                            <input type="hidden" name="year_and_month">--}}
{{--                            <input type="hidden" name="file_name_str">--}}
{{--                        </form></span>--}}
                    <span class="btn-file">
                        @can($__env->yieldContent('permission_modify'))
                        <a style="color: grey;" href="#" type="file" multiple="true" data-toggle="modal" class="upload" data-target="#upload_modal">
                            <i class="fa fa-upload"></i>
                        </a>
                        @endcan
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="file-wrap">
                <div class="file-sidebar">
                    <div class="file-search">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <i class="fa fa-search"></i>
                            </div>
                            <input id="search-year" onkeydown="if(event.keyCode===13) searchYear();" onchange="searchYear()" maxlength="4" oninput="value=value.replace(/[^0-9]/g,'')" type="text" class="form-control" placeholder="過去の年数を検索">
                        </div>
                    </div>
                    <div class="file-pro-list">
                        <div class="file-scroll">
                            <ul class="file-menu">
                            </ul>
                            <div class="show-more">
                                <a href="#">更に表示</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="file-cont-wrap w-100">
                    <div class="file-cont-inner">
                        <div class="file-content">
                            <form class="file-search">
                                <div class="input-group w-50">
                                    <div class="input-group-prepend">
                                        <i class="fa fa-search"></i>
                                    </div>
                                    <input name="file_name" onkeydown="searchFileName()" type="text" class="form-control" placeholder="ファイル名を検索">
                                </div>
                                <div class="view-icons">
                                    <a href="#list" onclick="showList()" class="list-view btn btn-link @if(Auth::user()->user_view_attendance==0)active @endif"><i class="fa fa-bars"></i></a>
                                    <a href="#card" onclick="showCard()" class="grid-view btn btn-link @if(Auth::user()->user_view_attendance==1)active @endif"><i class="fa fa-th"></i></a>
                                </div>
                                <div id="options" class="col-auto float-right">
                                    <span class="h-100 invisible float-right" style="margin-right: 20px">
                                        <button id="list-confirm-btn" class="h-100 btn-option btn btn-sm btn-success" type="button" data-route="{{route('attendances.confirmWorkingTime')}}" onclick="confirmWorkingTime(this)"> 承認</button>
                                        <button id="list-reject-btn" class="h-100 btn-option btn btn-sm btn-danger" type="button" data-toggle="modal" onclick="confirmReject()"> 拒否</button>
                                        <button class="h-100 btn-option btn btn-sm btn-primary" type="button" onclick="saveCheck()"> {{ __('保存') }}</button>
                                        <button id="list-delete-btn" class="h-100 btn-option btn btn-sm btn-danger" type="button" data-toggle="modal" onclick="confirmDelete()"> 削除</button>
                                    </span>
                                </div>
                            </form>
                            <div class="file-body">
                                <div class="file-scroll">
                                    <div class="file-content-inner">
                                        <tab id="card" data-url="{{route('attendances.getCardInfo')}}">
                                        <div class="row row-sm">
                                            <div hidden name="file_info_card" class="col-6 col-sm-4 col-md-3 col-lg-4 col-xl-3">
                                                <div class="card card-file">
                                                    @can($__env->yieldContent('permission_modify'))
                                                    <div class="dropdown-file">
                                                        <a href="" class="dropdown-link" data-toggle="dropdown"><i
                                                                class="fa fa-ellipsis-v"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a href="#" class="dropdown-item confirm-card" data-route="{{route('attendances.confirmWorkingTime')}}">承認</a>
                                                            <a href="#" class="dropdown-item reject-card">拒否</a>
                                                            <a href="#" class="dropdown-item delete-card">削除</a>
                                                        </div>
                                                    </div>
                                                    @endcan
                                                    <div class="card-file-thumb">
                                                        <i class="fa fa-file-image-o"></i>
                                                    </div>
                                                    <div class="card-body">
                                                        <h6><a name="file_name" href="javascript:void(0)"></a></h6>
                                                        <span>勤務時間数：<span name="working_time"></span>
                                                            @can($__env->yieldContent('permission_modify'))
                                                            　<span class="cursor-point"><i class="fa fa-pencil m-r-5"></i></span>
                                                            @endcan
                                                        </span><br/>
                                                        <span>精算費用：<span name="transportation_expense"></span>
                                                            @can($__env->yieldContent('permission_modify'))
                                                            　<span class="cursor-point"><i class="fa fa-pencil m-r-5"></i></span>
                                                            @endcan
                                                        </span><br/>
                                                        <span>確認状態：<span name="status"></span></span>
                                                    </div>
                                                    <div class="card-footer">
                                                        <span name="created_time"></span><br>
                                                        <span name="created_user"></span>&nbsp;&nbsp;&nbsp;アップロードしました。
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </tab>
                                        @include('layouts.pages.data.data_file_route')
                                        <tab id="list">
                                            <p id="list-date"></p>
                                            <table id="attendance-info" class="w-100 table datatable bg-white"
                                                   data-route="{{route('attendances.getTableInfo')}}">
                                                <thead>
                                                    <tr>
                                                        @can($__env->yieldContent('permission_modify'))
                                                        <th class="select-checkbox"></th>
                                                        @endcan
                                                        <th class="text-center">ファイル名</th>
                                                        <th>勤務時間数</th>
                                                        <th>精算費用</th>
                                                        <th>確認状態</th>
                                                        <th>アップロード者</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </tab>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div id="update" class="modal custom-modal fade" role="dialog"
        data-route="{{route('attendances.modify')}}">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group" name="working_time">
                        <label for="working_time">{{ __('勤務時間数:') }}</label>
                        <input type="text" name="working_time" class="form-control float" value="" maxlength="6">
                    </div>
                    <div class="form-group" name="transportation_expense">
                        <label for="transportation_expense">{{ __('精算費用:') }}</label>
                        <input type="text" name="transportation_expense" class="form-control amount" value="" maxlength="6">
                    </div>

                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="button" name="save">保存</button>
                        <button class="btn btn-primary submit-btn" type="button" name="cancel" data-dismiss="modal" aria-label="Close">キャンセル</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Modal -->

    <!--Upload Modal -->
    <div id="upload_modal" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" id="upload_modal_content" >
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('勤務表をアップロード') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form data-url="{{route('attendances.uploadFile')}}" id="file">
                    <div class="form-group">
                        <label for="employee_id">{{ __('社員選択:') }}</label>
                        <select name="employee_id">
                            <option>社員を選択</option>
                            @foreach($employees as $employee)
                                <option value="{{$employee->id}}">{{$employee->User->name}}　[{{str_pad($employee->employee_code,4,'0',STR_PAD_LEFT)}}]</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="year_and_month">{{ __('勤務日付:') }}</label>
                        <select name="year_and_month" class="form-control">
                            <option value="{{date('Y年m月')}}">{{date('Y年m月')}}</option>
                            @for($i=1;$i<=2;$i++)
                            <option value="{{date('Y年m月',strtotime(date('Y-m-01') . '-'.$i.' month'))}}">{{date('Y年m月',strtotime(date('Y-m-01') . '-'.$i.' month'))}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="working_time">{{ __('勤務時間数:') }}</label>
                        <input type="text" name="working_time" class="form-control float" value="">
                    </div>
                    <div class="form-group">
                        <label for="transportation_expense">{{ __('精算費用:') }}</label>
                        <input type="text" name="transportation_expense" class="form-control amount" value="">
                    </div>
                    <div class="form-group">
                        <input type="button" class="btn btn-success btn-file-border" value="勤務表選択" onclick="uploadFile(this)">
                        <input class="w-100" onchange="showFileName(this)" hidden type="file" name="file">
                        <span class="files-info"></span>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="button" onclick="uploadFileSubmit();return false">保存</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Modal -->

    <!-- Delete Modal -->
    <div class="modal custom-modal fade" id="delete_modal" role="dialog" data-route="{{route('attendances.delete')}}">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>勤務表の拒否</h3>
                        <p></p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <a href="javascript:void(0);" id="delete_btn" class="btn btn-primary continue-btn">拒否</a>
                            </div>
                            <div class="col-6">
                                <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">{{ __('Cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Delete Modal -->
@endsection
@section('footer_append')
    <!-- Datatable JS -->
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable-language.jp.js') }}"></script>
    <script src="{{ asset('assets/js/datatables/select/dataTables.select.min.js') }}"></script>
    <!-- Select2 JS -->
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.searchableSelect.js') }}"></script>

    <!-- Datetimepicker JS -->
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <!-- Bootstrap Notify -->
    <script src="{{ asset('assets/js/bootstrap-notify-3.1.3/bootstrap-notify.min.js') }}"></script>
    <!-- flatpickr JS -->
    <script src="{{asset('assets/plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{asset('assets/plugins/flatpickr/l10n/ja.js') }}"></script>
    <script src="{{asset('assets/js/lactes.js') }}"></script>
    <script>
        const vh = window.innerHeight;
        const table_selector = '#attendance-info';
        let data_datatable = function(d) {
            d.year_and_month = currDate;
            d.file_name = $('input[name=file_name]').val();
        };
        let columns = [@can($__env->yieldContent('permission_modify'))
        {data:'id',name:'id',orderable:false,className:'select-checkbox',width:'3em'},@endcan
            {data:'file_name',name:'file_name'},
            {data:'working_time',name:'working_time',className: 'text-center',width:'8em'},
            {data:'transportation_expense',name:'transportation_expense',className: 'text-center',width:'8em'},
            {data:'status',name:'status',className: 'text-center',width:'8em'},
            {data:'update_user',name:'update_user',className:'text-center',width:'8em'}];
        let columnDefs = [];
        let tableSettingInfo = initTableSettingInfo();
        tableSettingInfo.columns.push({data: "id", name: "id",orderable:true,visible:false});
        tableSettingInfo.scrollY = vh-396 + 'px';
        batchSelectOnDatatable();
        allSelectOnDatatable();
        let date = moment();
        let div_file = $('div[name=file_info_card]');
        let ul = $('ul.file-menu');
        // let currDate = moment().format('YYYYMM');
        let currDate = '';
        $(function(){
            showMore(true);
            $('select[name=employee_id]').searchableSelect();
            $(document).on('click','ul.file-menu li',function () {
                $('ul.file-menu li.active').removeClass('active');
                $(this).addClass('active');
                currDate = $(this).text().split('(')[0];
                $('tr.selected').removeClass('selected');
                changeDate();
            });
            $('div.show-more a').on('click',function () {
                showMore();
            });
        });
        function whenNotSelected(i,shift_flag) {
            ableInput($(table_selector+' tbody').find('tr').eq(i).find('td.select-checkbox').first(),shift_flag,true);
        }
        function whenSelected(i,shift_flag) {
            ableInput($(table_selector+' tbody').find('tr').eq(i).find('td.select-checkbox').first(),shift_flag);
        }
        function whenClicked(obj) {
            showOptions(obj,true);
        }
        function showOptions(e,able=false) {
            const parent = $(e).parent();
            $('.return_btn').addClass('return_btn_change');
            $("#options").removeClass("hide");
            if ($("tbody tr.selected").length === 1 && parent.hasClass('selected')) {
                $("#options span").addClass("invisible");
                $('thead tr').removeClass('selected');
            } else {
                const span = $("#options span");
                if (span.hasClass("invisible")) {
                    span.removeClass("invisible");
                }
                let flag;
                if(!parent.hasClass('selected')) {
                    if(parent.find('td:nth-of-type(5)').html()==='確認済') flag=1;
                    else flag=0;
                }
                btnMode(flag);
            }
            if(able){
                ableInput(e);
            }
        }
        function btnMode(flag){
            let selector = '.selected';
            if(flag===undefined) selector = '';
            try{
                $('#attendance-info').DataTable().rows(selector).every(function () {
                    const data = this.data();
                    if(this.node()===parent[0]) return;
                    if ((data.status !== '確認済' && flag === 1) || (data.status === '確認済' && flag === 0)) {
                        flag = 2;
                        throw 'jump out';
                    } else if (data.status === '確認済' && flag === undefined) {
                        flag = 1;
                    } else if (data.status !== '確認済' && flag === undefined) {
                        flag = 0;
                    }
                });
            }catch(e){}
            const confirm = $('#list-confirm-btn');
            const reject = $('#list-reject-btn');
            const del = $('#list-delete-btn');
            switch(flag){
                case 0:
                    confirm.show();
                    reject.show();
                    del.hide();
                    break;
                case 1:
                    confirm.hide();
                    reject.hide();
                    del.show();
                    break;
                case 2:
                    confirm.hide();
                    reject.hide();
                    del.hide();
                    break;
            }
        }
        function datatableComplete(){
            hideModal();
            if($('a.list-view').hasClass('active')){
                trMap.clear();
                $('tr.selected').removeClass('selected');
                $("#options span").addClass("invisible");
            }
        }

        function hideModal(){
            $('#upload_modal').modal('hide');
            $('#delete_modal').modal('hide');
        }

        let len = Math.floor((vh - 319)/42);
        function showMore(init=false) {
            const endDate=date.format('YYYY-MM-DD HH:mm');
            const startDate=date.add(-len,'months').format('YYYY-MM-DD HH:mm');
            date.add(len,'months');
            $.ajax({
                url:'{{route('attendances.getAttendanceCountInMonth')}}',
                data:{
                    startDate:startDate,
                    endDate:endDate
                },
                type: 'post',
                success:function (res) {
                    for(let i=0;i<len;i++){
                        let active = '';
                        if(date.format('YYYYMM') === currDate){
                            active = 'active';
                        }
                        ul.append('<li class='+active+'><a href="#">'+res[i]+'</a></li>');
                        date.add(-1,'months');
                    }
                    if(init){
                        ul.find('li:eq(1)').click();
                    }
                },error:function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error)
                }
            });
        }

        function uploadFileSubmit() {
            const form = $('#file');
            const url = form.data('url');
            const formData = new FormData(form.get(0));
            const obj = lockAjax();
            $.ajax({
                url:url,
                data:formData,
                type: 'post',
                processData: false,
                contentType : false,
                success:function (res) {
                    ajaxSuccessAction(res,function () {
                        changeDate();
                        form[0].reset();
                        form.find('span.files-info').html('');
                        $('select[name=employee_id]').data('searchableSelect').selectItem($('div.searchable-select-item').first());
                        searchYear();
                    });
                },error:function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error)
                },complete:function () {
                    unlockAjax(obj);
                }
            });
        }

        function showList() {
            if($('div.dataTables_info').length===0){
                $('#attendance-info').dataTable(tableSettingInfo);
            }else
                $('#attendance-info').DataTable().draw();
            $('#card').hide();
            $('#list').show();
            $('a.list-view').addClass('active');
            $('a.grid-view').removeClass('active');
        }

        function showCard(year_month) {
            const url = $('#card').data('url');
            $.ajax({
                url:url,
                data:{
                    year_and_month:year_month===undefined?$('ul.file-menu li.active a').text().split('(')[0]:year_month,
                    file_name:$('input[name=file_name]').val(),
                },
                type:'get',
                datatype: 'json',
                success:function (cards) {
                    $('#card>div').empty();
                    cards.forEach(function (card,index) {
                        let div_temp = div_file.clone();
                        div_temp.find('a[name=file_name]').html(card.file.basename).attr('onclick','openFile('+card.file.id+',\''+card.file.type+'\')');
                        let working_time = card.working_time+'時間';
                        div_temp.find('span[name=working_time]').html(working_time);
                        let expense = card.transportation_expense;
                        div_temp.find('span[name=transportation_expense]').html(expense);
                        let status = '';
                        if(card.status !== 0){
                            status = '確認済';
                            div_temp.find('a.confirm-card').hide();
                            div_temp.find('a.reject-card').hide();
                            div_temp.find('a.delete-card').attr('onclick','confirmDelete('+card.id+')');
                        }else{
                            status = '<span class="color-red">未確認</span>';
                            div_temp.find('a.delete-card').hide();
                            div_temp.find('a.confirm-card').attr('onclick','confirmWorkingTimeWhenCard(this,'+card.id+')').show();
                            div_temp.find('a.reject-card').attr('onclick','confirmReject('+card.id+')').show();
                        }
                        div_temp.find('span[name=status]').html(status);
                        let created_at = moment(card.created_at);
                        div_temp.find('span[name=created_time]').html(created_at.format("YYYY-MM-DD hh:mm:ss"));
                        div_temp.find('span[name=created_user]').html(card.file.user.name);
                        div_temp.prop('hidden',false);
                        div_temp.attr('name',div_temp.attr('name')+index);
                        div_temp.attr('pos-id',card.id);
                        div_temp.find('span[name=working_time]').next().attr('onclick','showUpdateWhenCard('+card.id+',"'+card.working_time+'",1)');
                        div_temp.find('span[name=transportation_expense]').next().attr('onclick','showUpdateWhenCard('+card.id+',"'+card.transportation_expense+'",2)');
                        $('#card>div').append(div_temp);
                    });
                    $('#card').show();
                    $('#list').hide();
                    $('a.grid-view').addClass('active');
                    $('a.list-view').removeClass('active');
                    hideModal();
                },error:function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error)
                }
            });
        }

        function changeDate() {
            const year_month = $('ul.file-menu li.active a').text().split('(')[0];
            setTimeout(function () {
                if($('a.list-view').hasClass('active'))
                    showList();
                else
                    showCard(year_month);
            },100);
        }
        function confirmDelete(id) {
            const tagID = '#delete_modal';
            $(tagID).find('div.form-header h3').html('勤務表の削除');
            if(id===undefined){
                $(tagID).find('div.form-header p').html('選択された勤務表を削除してよろしいでしょうか?');
                $(tagID).find("#delete_btn").html('削除').off().click(function () {
                    batchAction(tagID,'delete');
                });
            }else{
                $(tagID).find('div.form-header p').html('当該勤務表を削除してよろしいでしょうか?');
                $(tagID).find("#delete_btn").html('削除').off().click(function () {
                    delAttendance(id);
                });
            }
            $(tagID).modal('show');
        }
        function delAttendance(id) {
            $.ajax({
                url: $('#delete_modal').data('route'),
                type: 'delete',
                data: {
                    idArr: [id],
                }, success: function (res) {
                    ajaxSuccessAction(res,function () {
                        changeDate();
                        searchYear();
                    });
                }, error: function (jqXHR,testStatus,error) {
                    ajaxErrorAction(jqXHR,testStatus,error);
                }
            });
        }
        function confirmReject(id) {
            const tagID = '#delete_modal';
            let idArr = [];
            $(tagID).find('div.form-header h3').html('勤務表の拒否');
            if(id===undefined){
                $(tagID).find('div.form-header p').html('選択された勤務表を拒否してよろしいでしょうか?');
                idArr = getIdArr();
            }else{
                $(tagID).find('div.form-header p').html('当該勤務表を拒否してよろしいでしょうか?');
                idArr.push(id);
            }
            $(tagID).find("#delete_btn").html('拒否').off().click(function () {
                rejectAttendance(idArr);
            });
            $(tagID).modal('show');
        }
        function rejectAttendance(idArr) {
            $.ajax({
                url: '{{route('attendances.rejection')}}',
                type: 'post',
                data: {
                    idArr: idArr,
                }, success: function (response) {
                    ajaxSuccessAction(response,function () {
                        changeDate();
                        searchYear();
                    });
                }, error: function (jqXHR,testStatus,error) {
                    ajaxErrorAction(jqXHR,testStatus,error);
                }
            });
        }
        function successWhenBatchAction() {
            trMap.clear();
            $(table_selector).DataTable().draw();
            $('tr.selected').removeClass('selected');
            $("#options span").addClass("invisible");
            searchYear();
        }
        function searchYear() {
            const e = $('#search-year');
            let year = $(e).val();
            date=moment();
            if(year >= moment().year()){
                $(e).val(moment().year());
            }else if(year === ''){
                $(e).val('');
            }else{
                date.year(year);
                date.month(11);
            }
            ul.empty();
            showMore();
        }

        function searchFileName() {
            if(event.keyCode === 13){
                event.preventDefault();
                changeDate();
            }
        }

        let trMap = new Map();

        function whenAllSelected(){
            $(table_selector+' tbody tr').each(function () {
                const index = $(this).index();
                if(!trMap.has(index)){
                    trMap.set(index,$(this).clone());
                }
                $(this).find('input').removeClass('disable-input');
                let input = $(this).find('input[name=working_time]');
                input.val(input.val().replace(/[^0-9\.]/g,''));
            });
            $("#options span").removeClass("invisible");
            btnMode();
        }

        function whenAllNotSelected() {
            $("#options span").addClass("invisible");
            $(table_selector+' tbody tr.selected').each(function () {
                const index = $(this).index();
                recoveryWith($(this),trMap.get(index));
            });
            trMap.clear();
            $(table_selector+' input').addClass('disable-input');
        }

        function recoveryWith(tr,trClone) {
            tr.find('input[name=working_time]').val(trClone.find('input[name=working_time]').val());
            tr.find('input[name=transportation_expense]').val(trClone.find('input[name=transportation_expense]').val());
        }

        function ableInput(e,shift_flag,clear_flag=false) {
            let tr = $(e).parent();
            const index = tr.index();
            if (tr.hasClass('selected') && (!shift_flag || clear_flag)) {
                recoveryWith(tr,trMap.get(index));
                trMap.delete(index);
                tr.find('input').addClass('disable-input');
            } else if(!tr.hasClass('selected')) {
                trMap.set(index,tr.clone());
                tr.find('input').removeClass('disable-input');
                let input = tr.find('input[name=working_time]');
                input.val(input.val().replace(/[^0-9\.]/g,''));
            }
        }

        function confirmWorkingTime(el) {
            let route = $(el).data('route');
            let idArr = getIdArr();
            changeStatus(idArr,route);
        }

        function confirmWorkingTimeWhenCard(el,id) {
            let route = $(el).data('route');
            let idArr = [id];
            changeStatus(idArr,route);
        }

        function changeStatus(idArr,route) {
            $.ajax({
                url:route,
                data:{
                    idArr:idArr,
                },
                type:'post',
                success:function (res) {
                    ajaxSuccessAction(res,function () {
                        if(!$('a.list-view').hasClass('active')){
                            const id = res.idArr[0];
                            const div_temp = $('div[pos-id='+id+']');
                            div_temp.find('span[name=status]').html('確認済');
                            div_temp.find('a.confirm-card').hide();
                            div_temp.find('a.reject-card').hide();
                            div_temp.find('a.delete-card').attr('onclick','confirmDelete('+id+')').show();
                            $('#update').modal('hide');
                        }else{
                            $(table_selector).DataTable().rows('.selected').every(function () {
                                this.data().status = '確認済';
                            });
                            $(table_selector).find('tbody tr.selected td:nth-of-type(5)').html('確認済');
                            $('#list-confirm-btn').hide();
                            $('#list-reject-btn').hide();
                            $('#list-delete-btn').show();
                            // changeDate();
                        }
                    });
                    searchYear();
                },error:function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error)
                }
            });
        }

        function saveCheck() {
            let modUrl = $('#update').data('route');
            let workingTimeArr = [];
            let expenseArr = [];
            let idArr = [];
            $("tbody tr.selected").each(function () {
                idArr.push($(this).find("input[name=id]").val());
                let working_time = $(this).find("input[name=working_time]").val();
                workingTimeArr.push(working_time);
                let expense = $(this).find("input[name=transportation_expense]").val();
                expenseArr.push(expense);
            });
            saveModify(modUrl,idArr,workingTimeArr,expenseArr);
        }

        function showUpdateWhenCard(id,val,type) {
            const modal = $('#update');
            modal.find('button[name=save]').attr('onclick','updateWhenCard('+id+')');
            if(type==1){
                modal.find('div[name=working_time]').show();
                modal.find('div[name=transportation_expense]').hide();
                modal.find('input[name=working_time]').val(val);
                modal.find('input[name=transportation_expense]').val('-1');
            }else{
                modal.find('div[name=working_time]').hide();
                modal.find('div[name=transportation_expense]').show();
                modal.find('input[name=working_time]').val('0');
                modal.find('input[name=transportation_expense]').val(val);
            }
            modal.modal('show');
        }

        function updateWhenCard(id) {
            const modal = $('#update');
            let modUrl = modal.data('route');
            let workingTimeArr = [modal.find('input[name=working_time]').val()];
            let expenseArr = [modal.find('input[name=transportation_expense]').val()]
            let idArr = [id];
            saveModify(modUrl,idArr,workingTimeArr,expenseArr);
        }

        /**
         * 編集の保存
         * @param modUrl
         * @param idArr
         * @param workingTimeArr
         */
        function saveModify(modUrl,idArr,workingTimeArr,expenseArr) {
            $.ajax({
                url: modUrl,
                type: "put",
                async: true,
                data: {
                    idArr: idArr,
                    workingTimeArr: workingTimeArr,
                    expenseArr:expenseArr,
                },
                success: function (result) {
                    ajaxSuccessAction(result,function () {
                        if(!$('a.list-view').hasClass('active')){
                            $('#update').modal('hide');
                        }
                        changeDate();
                    });
                }, error:function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error)
                }
            });
        }
    </script>

@endsection
