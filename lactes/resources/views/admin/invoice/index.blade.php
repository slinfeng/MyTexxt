@extends('layouts.backend')
@section('title', __('Invoice').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('Invoice'))
@section('permission_modify','invoice_modify')
@section('self_modify','invoice_self_modify')
@section('title_delete', __('Invoice'))
@section('title_copy', __('Invoice'))
@section('function_delete', "batchDeleteForLocalServer('#delete')")
@section('document_format',isset($client)?$client['document_format']:'')
@include('layouts.pages.sections.requestmanage.batch_action')
@section('view_title', __('請求書管理画面'))
@section('route_create', route('invoice.create'))
@section('route_copy', route('invoice.copyToCreate'))
@section('route_delete', route('invoice.delete'))
@section('position_a_html', '甲（受領）')
@section('position_a_val', '1')
@section('position_b_html', '乙（提出）')
@section('position_b_val', '2')
@section('position', $position)
@section('search_period',$re_period)
@include('layouts.pages.sections.requestmanage.options_b')
@section('css_append')
    @include('layouts.headers.requestmanage.index_a')
    <link rel="stylesheet" href="{{ asset('assets/css/invoice.style.css') }}">
@endsection
@section('init_val')
    @include('layouts.pages.initval.requestmanage.input_a')
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                @include('layouts.pages.title')
                <div class="col-12 row filter-row @can($__env->yieldContent('self_modify'))filter-row-extra @endcan">
                    @include('layouts.pages.searchs.requestmanage.search')
                </div>
                <div class="col-12">
                    <table id="invoices-table" class="table table-striped table-fixed" data-route="{{route('invoice.getInvoices')}}"
                           style="width: 100%">
                        <thead>
                        <tr>
                            @can($__env->yieldContent('permission_modify'))
                            <th class="select-checkbox"></th>
                            @endcan
                            <th>{{ __('請求日') }}</th>
                            <th>{{ __('請求番号') }}</th>
                            <th>{{ __('案件名/ファイル名') }}</th>
                            @can($__env->yieldContent('self_modify'))
                                <th class="text-center">{{ __('請求額') }}<span style="font-size: 0.7em">{{ __('（税込）') }}</span></th>
                                <th>{{ __('支払期限日') }}</th>
                                <th>{{ __('確認状態') }}</th>
                                <th>{{ __('操作') }}</th>
                            @else
                                <th>{{ __('取引先') }}</th>
                                <th class="text-center">{{ __('請求額') }}<span style="font-size: 0.7em">{{ __('（税込）') }}</span></th>
                                <th>@if($position == $__env->yieldContent('position_a_val'))支払期限日 @else入金予定日 @endif</th>
                                <th class="text-center">@if($position == $__env->yieldContent('position_a_val'))支払額 @else入金額 @endif</th>
                                <th>{{ __('確認状態') }}</th>
                                <th>{{ __('確認者') }}</th>
                            @endcan
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            @can($__env->yieldContent('permission_modify'))
                                <th></th>
                            @endcan
                            <th></th>
                            <th></th>
                            <th></th>
                            @can($__env->yieldContent('self_modify'))
                                <th class="text-right"></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            @else
                                <th></th>
                                <th class="text-right"></th>
                                <th></th>
                                <th class="text-right"></th>
                            <th></th>
                            <th></th>
                            @endcan
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- copy modal -->
    @include('layouts.pages.models.model_copy')
    <!-- /copy modal -->
    <!-- delete modal -->
    @include('layouts.pages.models.model_delete')
    <!-- /delete modal -->
    @include('layouts.pages.models.model_exclude')
    <!-- Browser Size Adjust Alert -->
{{--    @include('layouts.pages.models.model_adjust_browser')--}}
    <!-- /Browser Size Adjust Alert -->
@endsection
@section("footer_append")
    @include('layouts.footers.requestmanage.index_a')
    <script>
        const printTd=[3,4,5];
        const file_index=4;
        const pdf_name = '請求書「一括」';
        let ONLY_VIEW = false;
        const EDIT_ROUTE = '{{route('invoice.edit',':id')}}';
        @canany([$__env->yieldContent('self_modify'),$__env->yieldContent('permission_modify')]) @else table_no_selector.push('#invoices-table'); ONLY_VIEW = true; @endcanany
        @can($__env->yieldContent('self_modify'))
        const ACTION_START = '<div class="dropdown dropdown-action">'
            +'<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>'
            +'<div class="dropdown-menu dropdown-menu-right">';
        const ACTION_END = '</div></div>';
        const ACTION_EDIT = '<a class="dropdown-item" href="javascript:void(0)" data-href="{{route('invoice.edit',':id')}}" onclick="toCreate(this)">'
        +'<i class="fa fa-pencil m-r-5"></i> 編集</a>';
        const ACTION_CONFIRM = '<a class="dropdown-item" href="javascript:void(0)" data-href="{{route('invoice.approveRequest',':id')}}" onclick="approveRequest(this)">'
        +'<i class="fa fa-pencil m-r-5"></i> 承認要請</a>';
        const ACTION_DEL = '<a class="dropdown-item" href="#" data-href="{{route('invoice.delInvoiceClient',':id')}}" onclick="delInvoiceClient(this)">'
        +'<i class="fa fa-trash-o m-r-5"></i> 削除</a>';
        const ACTION_CANCEL = '<a class="dropdown-item" href="javascript:void(0)" data-href="{{route('invoice.requestCallBack',':id')}}" onclick="requestCallBack(this)">'
        +'<i class="fa fa-pencil m-r-5"></i> 却下</a>';
        @endcan
        let widthArr = ['1rem','5rem','8rem','','','7.5rem','5.5rem','7.5rem','5rem','4rem','8rem','3rem'];
        const INVOICE_TOTAL = {
            data: "invoice_total",
            name: 'invoice_total',
            className: 'text-right',
            width: widthArr[5],
        };
        const PAY_DEADLINE = {
            data: "pay_deadline",
            name: 'pay_deadline',
            width: widthArr[10],
            className: "text-center",
        };
        const STATUS = {
            data: "status",
            name: 'status',
            width: widthArr[8],
            className: 'text-center status-mark',
        };
        let columns = [
            @can($__env->yieldContent('permission_modify'))
            {
                data: "select",
                name: "id",
                orderable: false,
                className: 'position-relative select-checkbox',
                width: widthArr[0],
            },
            @endcan
            {
                data: "created_date",
                name: "created_date",
                width: widthArr[1],
            },
            {
                data: "invoice_manage_code",
                name: "invoice_manage_code",
                width: widthArr[2],
                className: 'text-center',
                'createdCell': function(nTd, sData, oData, iRow, iCol){
                    let route = '{{route("invoice.edit",':id')}}';
                    createdHandleWhenEditLink(nTd,oData,route);
                },
            },
            {
                data: "project_name_or_file_name",
                name: "project_name_or_file_name",
                width: widthArr[3],
                'createdCell': function(nTd, sData, oData, iRow, iCol){
                    createdHandleWhenFile(nTd,oData);
                },
            }, @can($__env->yieldContent('self_modify'))
                INVOICE_TOTAL,PAY_DEADLINE,STATUS,{
                data: null,
                name: 'action',
                width: widthArr[11],
                className: 'text-center',
                orderable: false,
                render:function (data) {
                    if(data.status==='承認済'){
                        return '';
                    }
                   let action_edit=ACTION_EDIT.replace(':id',data.id);
                   let action_confirm=ACTION_CONFIRM.replace(':id',data.id);
                   let action_del=ACTION_DEL.replace(':id',data.id);
                   let action_cancel=ACTION_CANCEL.replace(':id',data.id);
                   let action = ACTION_START;
                    data.status==='作成中'?(action+=action_edit+action_confirm+action_del):action+=action_cancel;
                    action+=ACTION_END;
                    action = action.replace(/:id/g,data.id);
                    return action;
                }
            }, @endcan
            @cannot($__env->yieldContent('self_modify'))
            {
                data: 'client_name',
                name: 'cname',
                width: widthArr[4],
                'createdCell': function(nTd, sData, oData, iRow, iCol){
                    createdHandleWhenClient(nTd,oData);
                },
            },
            INVOICE_TOTAL, PAY_DEADLINE,
            {
                data: "paid_total",
                name: 'paid_total',
                className: 'text-right',
                width: widthArr[7],
            }, STATUS,
            {
                data: "user_name",
                name: 'user.name',
                width: widthArr[9],
                className: 'text-center',
            }
            @endcan
        ];
        function matchWidth() {
            let widthArray=['1rem','5rem','8rem','','','7.5rem','8rem','7.5rem','5rem','5rem'];
            @cannot($__env->yieldContent('permission_modify'))
                widthArray=['5rem','8rem','','','7.5rem','8rem','7.5rem','5rem','5rem'];
            @endcannot
            @can($__env->yieldContent('self_modify'))
                widthArray=['5rem','8rem','','7.5rem','8rem','5rem','3rem'];
            @endcan
            $(table_selector).DataTable().fixedHeader.adjust();

            $('table.fixedHeader-floating th').each(function (index) {
                $(this).width(widthArray[index]);
            });
            $(table_selector + ' th').each(function (index) {
                $(this).width(widthArray[index]);
            });
        }
        function appendedFuncOnDatatable(){
            @can($__env->yieldContent('permission_modify'))
                tableSettingInfo.order = [[1, 'asc'],[2,'asc']];
            @else
                tableSettingInfo.order = [[0, 'asc'],[1,'asc']];
            @endcan
            allSelectOnDatatable();
        }
    </script>
    <script src="{{ asset('assets/js/invoice.view.js') }}"></script>
@endsection
