@extends('layouts.backend')
@section('title', __('Estimates').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('Estimates'))
@section('view_title', __('見積書管理画面'))
@section('permission_modify','estimate_modify')
@section('title_delete', __('Estimates'))
@section('title_copy', __('Estimates'))
@section('function_delete', "batchDeleteForLocalServer('#delete')")
@include('layouts.pages.sections.requestmanage.batch_action')
@section('route_create', route('estimates.create'))
@section('route_copy', route('estimates.copyToCreate'))
@section('route_delete', route('estimates.delete'))
@include('layouts.pages.sections.requestmanage.position_a')
@section('position', $position)
@section('search_period',$re_period)
@include('layouts.pages.sections.requestmanage.options_a')
@section('css_append')
    @include('layouts.headers.requestmanage.index_a')
    <style>
        #estimates-table th:nth-of-type(4){
            width: -moz-calc( 50% - 10.65em );
            width: -webkit-calc( 50% - 10.65em );
        }
    </style>
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
                <div class="col-12 row filter-row">
                    @include('layouts.pages.searchs.requestmanage.search')
                </div>
                <div class="col-12">
                    <table id="estimates-table" class="table table-striped table-fixed" style="width:100%;" data-route="{{route('estimates.getEstimates')}}">
                        <thead>
                        <tr>
                            @can($__env->yieldContent('permission_modify'))
                            <th class="select-checkbox">{{__("選択")}}</th>
                            @endcan
                            <th>{{__("開始日")}}</th>
                            <th>{{__("見積番号")}}</th>
                            <th>{{__("業務名/ファイル名")}}</th>
                            <th>{{__("取引先")}}</th>
                            <th>{{__("見積額")}}{{$tax_type}}</th>
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
        const printTd=[3,4,5];
        const file_index=4;
        const EDIT_ROUTE = '{{route('estimates.edit',':id')}}';
        let widthArr = ['2.2em','5.5em','8em','','calc( 50% - 10.65em )','7.5em']
        @cannot($__env->yieldContent('permission_modify')) table_no_selector.push('#estimates-table'); @endcannot
        let columns = [
                @can($__env->yieldContent('permission_modify'))
                {
                    orderable: false,
                    data: 'name',
                    name: "id",
                    className: 'position-relative select-checkbox',
                    width:widthArr[0],
                },@endcan
                {
                    data: 'period',
                    name: 'period',
                    width:widthArr[1],
                },
                {
                    data: 'est_manage_code',
                    name: 'est_manage_code',
                    width:widthArr[2],
                    'createdCell': function(nTd, sData, oData, iRow, iCol){
                        let route = '{{route("estimates.edit",':id')}}';
                        createdHandleWhenEditLink(nTd,oData,route);
                    },
                },
                {
                    data: 'project_name_or_file_name',
                    name: 'project_name_or_file_name',
                    width:widthArr[3],
                    'createdCell': function(nTd, sData, oData, iRow, iCol){
                        createdHandleWhenFile(nTd,oData);
                    },
                },
                {
                    data: 'client_name',
                    name: 'cname',
                    width:widthArr[4],
                    'createdCell': function(nTd, sData, oData, iRow, iCol){
                        createdHandleWhenClient(nTd,oData);
                    },
                },
                {
                    data: 'estimate_subtotal',
                    name: 'estimate_subtotal',
                    className: 'text-right',
                    width:widthArr[5],
                },
            ];
        let table_selector = '#estimates-table';
        $(function () {
            showHeadButton();
            adjustSidebarForFixedHeader();
            initDataTable();
            initDatePicker('#startAndEndDate',function () {
                $(table_selector).DataTable().draw();
            });
        });
        const pdf_name = '見積書「一括」';
        @cannot($__env->yieldContent('permission_modify'))
        function matchWidth() {
            let widthArray = ['5.5em','8em','','calc( 50% - 10.65em )','7.5em'];
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
        function handleAfterPrint() {

        }
    </script>
@endsection
