@extends('layouts.backend')
@section('title', __('Employees').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('Employees'))
@section('view-list', 'active')
@section('view-card', '')
@section('permission_modify','employee_modify')
@section('css_append')
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.searchableSelect.css') }}">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.min.css') }}">
    <!-- Datetimepicker CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables/fixedHeader.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">

    <!-- Tagsinput CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css') }}"/>
    <style>
        .searchable-select-item{
            position: relative;
            z-index: 10;
        }
        .searchable-select{
            width: 100%;
            z-index: 10000;
        }
        .select-checkbox *{
            width: 3em;
            vertical-align: middle!important;

        }
        table td{
            height: 22px!important;
        }
        table.dataTable thead th.select-checkbox:before{
            content: ' ';
            margin-top: -6px;
            margin-left: -6px;
            border: 1px solid #333;
            border-radius: 3px;
        }
        table.dataTable thead th.select-checkbox:before, table.dataTable thead th.select-checkbox:after{
            display: block;
            position: absolute;
            top: 1.2em;
            left: 50%;
            width: 12px;
            height: 12px;
            box-sizing: border-box;
        }
        table.dataTable tbody tr:nth-of-type(2n+1){
            background-color: #F6F6F6!important;
        }
        table.dataTable tbody tr:nth-of-type(2n){
            background-color: #ffffff!important;
        }
        .employee_code_input{
            display: none;
            max-width: 5em;
            height: 22px;
            padding: 0;
            background-color: #E8F0FE;
            border: none;
        }
        .employee_code{
            max-width: 8em;
        }
        .blueFont{
            color: #1F8FEF;
            cursor: pointer;
        }

    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                @include('admin.employees.index')
                <div class="col-12">
                    <table class="table table-striped custom-table mb-0 datatable" id="employees" style="width: 100%">
                        <thead>
                        <tr>
                            @can($__env->yieldContent('permission_modify'))
                            <th class="select-checkbox" onclick="selectAll(this)"></th>
                            @endcan
                            <th style="word-wrap:break-word;">{{__('社員番号')}}</th>
                            <th style="word-wrap:break-word;" >{{__('氏名')}}</th>
                            <th style="word-wrap:break-word;" >{{__('Sex')}}</th>
                            <th style="word-wrap:break-word;" >{{__('生年月日')}}</th>
                            <th style="word-wrap:break-word;" >{{__('Age')}}</th>
                            <th style="word-wrap:break-word;" >{{__('Date Hire')}}</th>
                            <th style="word-wrap:break-word;" >{{__('Work Year')}}</th>
                            <th style="word-wrap:break-word;" >{{__('Hire Type')}}</th>
                            <th style="word-wrap:break-word;" >{{__('役職')}}</th>
                            <th style="word-wrap:break-word;" >{{__('Nearest Station')}}</th>
                            <th style="word-wrap:break-word;" >{{__('Mail Address')}}</th>
                            <th style="word-wrap:break-word;" >{{__('携帯番号')}}</th>
{{--                            <th style="width: 30px;" >{{__('Action')}}</th>--}}
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('admin.employees.indexModal')
@endsection
@section('footer_append')
    <!-- Datatable JS -->
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable-language.jp.js') }}"></script>
    <!-- Select2 JS -->
    <script src="{{ asset('assets/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.searchableSelect.js') }}"></script>
    <!-- Bootstrap Notify -->
    <script src="{{ asset('assets/js/bootstrap-notify-3.1.3/bootstrap-notify.min.js') }}"></script>
    <!-- flatpickr JS -->
    <script src="{{asset('assets/plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{asset('assets/plugins/flatpickr/l10n/ja.js') }}"></script>
    <!-- Tagsinput JS -->
    <script src="{{ asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables/dataTables.fixedHeader.min.js') }}"></script>
    <script src="https://www.jqueryscript.net/demo/easy-wizard-control/jq-wizard.js"></script>
    <script src="{{ asset('assets/js/lactes.js') }}"></script>
    <script type="text/javascript">
        let widthArr = ['','','','','','','','','','','','',''];
        let table_selector="#employees"
        $('#toggle_btn').on('click',function () {
            setTimeout(function () {
                $('#employees').DataTable().draw();
            },100);
            setTimeout(function () {
                const table_temp = $('table.fixedHeader-floating');
                const sidebar_temp = $('#sidebar');
                if(sidebar_temp.width() < 100) table_temp.css('left',106);
                else if(sidebar_temp.length()===0) table_temp.css('left',0);
                else table_temp.css('left',276);
                $('table.fixedHeader-floating th').each(function (index) {
                    $(this).width($('#employees thead th').eq(index).width());
                });
                table_temp.width($('#employees').width());
            },300);
        });
        $dataTable = $('#employees');
        let table = $dataTable.DataTable({
            "autoWidth": false,
            "searching" : false,
            "responsive": true,
            "bAutoWidth": true,
            "serverSide": true,
            columnDefs:[{
                orderable:false,
                className:'select-checkbox',
                targets:0
            }],
            select:{
              selector:'td:first-child'
            },
            fixedHeader: {
                header: true,
                headerOffset:145,
            },
            paging:         false,
            language: {
                sInfoEmpty :'総件数:0 件',
                sInfo:"総件数:  _TOTAL_ 件",
                select:{
                    rows:{
                        _:"%d行を選択しました",
                        0:"",
                    }
                }
            },
            "order": [
                [1, "asc"]
            ],
            'iDisplayLength': 10,
            "bLengthChange": true,
            "ajax": {
                "url": "{{route('employees.get-employees')}}",
                "type": "GET",
                "datatype": "json",
                "data": function(d) {
                    d.id = $('#employee_search_id').val();
                    d.employee_name = $('#employee_search_employee_name').val();
                    if(!$('#switch_annual').is(':checked')) d.employee_retire='true';
                },
            },
            "columns": [
                @can($__env->yieldContent('permission_modify'))
                {data: "id", name: "id", orderable: false, className: 'position-relative select-checkbox',width:widthArr[0]},
                @endcan
                {data: 'employee_code',name: 'employee_code' ,className: 'employee_code',width:widthArr[1]},
                {data: 'name', name: 'name',width:widthArr[2]},
                {data: 'sex', name: 'sex',width:widthArr[3]},
                {data: 'birthday', name: 'birthday',width:widthArr[4]},
                {orderable: false,data: 'age', name: 'age',width:widthArr[5]},
                {data: 'date_hire',name: 'date_hire',width:widthArr[6]},
                {data: 'work_year', name: 'work_year',width:widthArr[7]},
                {data: 'hire_type', name: 'hire_type',width:widthArr[8]},
                {data: 'position', name: 'position',width:widthArr[9]},
                {data: 'nearest_station', name: 'nearest_station',width:widthArr[10]},
                {orderable: false,data: 'mail',name: 'mail' ,width:widthArr[11]},
                {orderable: false,data: 'tel', name: 'tel',width:widthArr[12]},
                // {data: 'action',name: 'action', orderable: false, searchable: false}
            ],
            "oLanguage": {
                "sProcessing": '<div class="overlay"><div class="cssload-speeding-wheel"></div></div>'
            }
        });
        $(function() {
            showHeadButton();
            initPageContent();
            initAddModel();
            $(document).on('click','tr',function () {
                trSelected(this);
            })
            $(table_selector).on('order.dt',function () {
                whenAllNotSelected()
            })
        });
        function trSelected(e) {
            if($('.selected').length==0){
                whenAllNotSelected();
            }else{
                $("#options").show();
            }
            if($('.selected').length<$('tr').length && $('tr').first().hasClass('selected')){
                $('tr').first().removeClass('selected');
            }else if($('.selected').length==$('tr').length-1 && !$('tr').first().hasClass('selected')){
                $('tr').first().addClass('selected');
            }
            $('.selected').find('.employee_code_input').show();
            $('.selected').find('.employee_code_span').hide();
            $('tr:not(.selected)').each(function () {
                $(this).find('.employee_code_input').hide();
                $(this).find('.employee_code_span').show();
                let employee_code = $(this).find('.employee_code_span').data('code');
                $(this).find('.employee_code_input').val(employee_code);
            })
        }
        function whenAllSelected() {

        }
        function whenAllNotSelected() {
            $("#options").hide();
            $('tr').first().removeClass('selected');
        }
        function saveCheck() {
            const obj = lockAjax();
            let idArr=[];
            let codeArr=[];
            let index=0
            $('.selected').each(function () {
                if($(this).find('.employee_code_span').length==1){
                    idArr[index]=$(this).find('.employee_code_span').data('id');
                    codeArr[index]=$(this).find('.employee_code_input').val();
                    index++;
                }
            })
            $.ajax({
                url: '{{route('employees.codeSave')}}',
                type: 'post',
                data: {
                    idArr: idArr,
                    codeArr:codeArr,
                },
                success: function (res) {
                    ajaxSuccessAction(res,function (res) {
                        table.draw();
                        $('tr').first().removeClass('selected');
                        $('.fixedHeader-floating tr').removeClass('selected');
                        $("#options").hide();
                    });
                }, error: function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                },complete:function () {
                    unlockAjax(obj);
                }
            });
        }

        function deleteCheck() {
            $('#delete_employee_modal').modal('show').find("#delete_employee_btn").off().click(function () {
                let idArr=[];
                $('.selected').each(function (index) {
                    idArr[index]=$(this).find('.employee_code_span').data('id');
                })
                $.ajax({
                    url:"{{route('employees.deleteCheck')}}",
                    type:"post",
                    data: {
                        idArr: idArr,
                    },
                    success:function(res){
                        ajaxSuccessAction(res,function (res) {
                            table.draw();
                            $('tr').first().removeClass('selected');
                            $('.fixedHeader-floating tr').removeClass('selected');
                            $("#options").hide();
                            $('#delete_employee_modal').modal('hide')
                        });
                    },
                    error: function(jqXHR, testStatus, error) {
                        ajaxErrorAction(jqXHR, testStatus, error);
                    }
                });

            });
        }
    </script>
@endsection
