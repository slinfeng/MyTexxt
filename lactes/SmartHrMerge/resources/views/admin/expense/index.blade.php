@extends('layouts.backend')
@section('title', __('Expense').' | '.config('app.name', 'ダッシュボード'))
@section('permission_modify','expense_modify')
@section('self_modify','invoice_self_modify')
@section('view_title', __('注文書管理画面'))
@section('title_delete', __('Expense'))
@section('title_copy', __('Expense'))
@section('function_delete', "batchDeleteForLocalServer('#delete')")
@include('layouts.pages.sections.requestmanage.batch_action')
@section('route_create', route('expense.create'))
@section('route_copy', route('expense.copy'))
@section('route_delete', route('expense.delete'))
@section('position_a_html', '甲（提出）')
@section('position_a_val', '1')
@section('position_b_html', '乙（受領）')
@section('position_b_val', '2')
@section('position', $position)
@section('search_period',$re_period)
@include('layouts.pages.sections.requestmanage.options_c')
@section('css_append')
    @include('layouts.headers.requestmanage.index_a')
@endsection
@section('init_val')
    @include('layouts.pages.initval.requestmanage.input_a')
@endsection
@section('content')
    <!-- /Page Header -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                @include('layouts.pages.title')
                <div class="col-12 row filter-row @can($__env->yieldContent('self_modify'))filter-row-extra @endcan">
                    @include('layouts.pages.searchs.requestmanage.search')
                </div>

                <div class="col-12">
                    <table id="expenses-table" class="table table-striped table-fixed" data-route="{{route('expense.getExpense')}}"
                           style="width:100%;">
                        <thead>
                        <tr>
                            @can($__env->yieldContent('permission_modify'))
                            <th class="select-checkbox">{{__("選択")}}</th>
                            @endcan
                            <th style="width: 80px">{{__("開始日")}}</th>
                            <th style="width: 120px">{{__("注文番号")}}</th>
                            <th style="width: 120px">{{__("期間")}}</th>
                            <th style="width: 25%">{{__("業務名/ファイル名")}}</th>
                            <th>{{__("取引先")}}</th>
                            <th class="estimateSubtotal">{{__("発注額（税抜）")}}</th>
                            <th class="expense_status">{{__("発注状態")}}</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.pages.models.model_copy')
    @include('layouts.pages.models.model_delete')
    @include('layouts.pages.models.model_exclude')
{{--    @include('layouts.pages.models.model_adjust_browser')--}}
@endsection

@section('footer_append')
    @include('layouts.footers.requestmanage.index_a')
    <script type="text/javascript">
        const printTd=[3,5,6];
        const file_index=5;
        const EDIT_ROUTE = '{{route('expense.edit',':id')}}';
        let widthArr = ['2.2em','5.5em','8.5em','5em','calc( 50% - 17em )','','7.5em','5em'];
        @cannot($__env->yieldContent('permission_modify')) table_no_selector.push('#expenses-table'); @endcannot
        let columns = [
            @can($__env->yieldContent('permission_modify'))
            {
                orderable: false,
                data: 'name',
                name: "id",
                className: 'position-relative select-checkbox',
                width:widthArr[0],
            },
            @endcan
            {
                data: 'period',
                name: 'period',
                width:widthArr[1],
            },
            {
                data: 'project_manage_code',
                name: 'project_manage_code',
                width:widthArr[2],
                'createdCell': function(nTd, sData, oData, iRow, iCol){
                    let route = '{{route("expense.edit",':id')}}';
                    createdHandleWhenEditLink(nTd,oData,route);
                },
            },
            {
                data: 'month_sum',
                name: 'month_sum',
                width:widthArr[3],
                searchable: false,
                className: 'text-right',
            },
            {
                data: 'project_name_or_file_name',
                name: 'project_name_or_file_name',
                width:widthArr[4],
                'createdCell': function(nTd, sData, oData, iRow, iCol){
                    createdHandleWhenFile(nTd,oData);
                },
            },
            {
                data: 'client_name',
                name: 'cname',
                width:widthArr[5],
                'createdCell': function(nTd, sData, oData, iRow, iCol){
                    createdHandleWhenClient(nTd,oData);
                },
            },
            {
                data: 'estimate_subtotal',
                name: 'estimate_subtotal',
                className: 'text-right',
                width:widthArr[6],
            },
            {
                data: 'expense_status',
                name: 'expense_status',
                className: 'text-center expense_status',
                width:widthArr[7],
            },
        ];
        let table_selector = '#expenses-table';
        $(function () {
            showHeadButton();
            adjustSidebarForFixedHeader();
            initDataTable();
            initDatePicker('#startAndEndDate',function () {
                $(table_selector).DataTable().draw();
            });
            @can($__env->yieldContent('permission_modify'))
            $(table_selector).on('draw.dt', function () {
                let positionVal = $('select[name=position]').val();
                let boo=false;
                if(positionVal==1){
                    if($('.expense_status:hidden').length>0){
                        boo=true;
                        $('.expense_status').show();
                    }
                }else{
                    if($('.expense_status:visible').length>0){
                        boo=true;
                        $('.expense_status').hide();
                    }

                }
                if(boo){
                    $(table_selector).DataTable().draw();
                }
            });
            @endcan
        });
        const pdf_name = '注文書「一括」';
        @cannot($__env->yieldContent('permission_modify'))
        function matchWidth() {
            let widthArray = ['5.5em','8.5em','calc( 50% - 12em )','','7.5em','5em'];
            $(table_selector).DataTable().fixedHeader.adjust();
            $('table.fixedHeader-floating th').each(function (index) {
                $(this).width(widthArray[index]);
            });
            $(table_selector + ' th').each(function (index) {
                $(this).width(widthArray[index]);
            });

        }
        @endcannot
        function appendedFuncOnDatatable(){
            @can($__env->yieldContent('permission_modify'))
                tableSettingInfo.order = [[1, 'desc'],[2,'asc']];
            @else
                tableSettingInfo.order = [[0, 'desc'],[1,'asc']];
            @endcan
        }

        /**
         * 操作項目を表示
         * @param e
         * @param able
         */
        function showOptions(e,able=false) {
            var trSelected=$("tbody tr.selected");
            $('#expenseHacchuuCheck').addClass('hide');
            if((trSelected.length===1 && $(e).parent().hasClass('selected')) || (trSelected.length===0 && e==undefined)){
                $("#options").addClass("hide");
                $("#options span").addClass("invisible");
            }else{
                var checkBea=true;
                var statusKey;
                $("#options").removeClass("hide");
                $("#options span").removeClass("invisible");
                $('#expenseHacchuuCheck').addClass('hide')
                if(!$(e).parent().hasClass('selected')){
                    statusKey=$(e).parent('tr').find('td:nth-of-type(8)').text();
                    if(statusKey!=='発注待ち'){
                        checkBea=false;
                    }
                }

                trSelected.each(function () {
                    statusKey=$(this).find('td:nth-of-type(8)').text();
                    if($(this).index()!==$(e).parent('tr').index()){
                        if(statusKey!=='発注待ち'){
                            checkBea=false;
                        }
                    }
                });
                if(checkBea){
                    $('#expenseHacchuuCheck').removeClass('hide');
                }
            }
        }

        function expensePost(e) {
            var idArr=[];
            $("tbody tr.selected").each(function () {
                const id=$(this).find("input[name=id]").val();
                idArr.push(id);
            });
            const obj = lockAjax();
            $.ajax({
                url: $(e).data('route'),
                type: "post",
                data: {
                    idArr: idArr,
                    action:$(obj).html(),
                },
                success: function (response) {
                    ajaxSuccessAction(response,function (response) {
                        $(table_selector).DataTable().draw();
                        // let positionVal = $('select[name=position]')
                        // if(positionVal=="1"){
                        //     $('.expense_status').show()
                        // }else{
                        //     $('.expense_status').hide();
                        // }
                    });
                }, error: function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                },complete: function () {
                    unlockAjax(obj);
                }
            });
        }

    </script>
@endsection
