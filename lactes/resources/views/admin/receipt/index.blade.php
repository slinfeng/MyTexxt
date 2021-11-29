@extends('layouts.backend')
@section('title', __('receipt').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('receipt'))
@section('permission_modify','receipt_modify')
@section('self_modify','receipt_self_modify')
@section('title_delete', __('receipt'))
@section('title_copy', __('receipt'))
@section('function_delete', "batchAction('#delete')")
@section('function_copy', "batchAction('#copy')")
@section('view_title', __('領収書管理画面'))
@section('route_create', route('receipt.create'))
@section('route_copy', route('receipt.copy'))
@section('route_delete', route('receipt.delete'))
@section('function_create','toCreate(this)')

@section('css_append')
    @include('layouts.headers.requestmanage.index_a')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/letteroftransmittal.css') }}">
@endsection
@section('content')
{{--    @include('layouts.pages.data.data_file_route')--}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header mb-3">
                    <input hidden name="init_val" data-currency-symbol="{{$currency}}">
                    <div class="row align-items-center">
                        <div class="col-7">
                            <h3 class="mb-0">
                                <a name="index" class="pointer-events-none" onclick="returnInit();">@yield('view_title')</a>
                                <span name="showed-by-client" class="invisible"></span>
                            </h3>
                        </div>
                        @canany([$__env->yieldContent('permission_modify'),$__env->yieldContent('self_modify')])
                        <div  class="col-auto float-right ml-auto">
                            <a href="javascript:void(0)" data-href="@yield('route_create')" class="btn add-btn"
                               onclick="@yield('function_create')">
                                <i class="fa fa-plus"></i> {{ __('新規作成') }}
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
                <div class="col-12 row filter-row">
                        <div class="search" style="width: 300px;">
                            <div class="form-group form-focus">
                                <div class="cal-icon">
                                    <input class="form-control floating" data-search-mode="{{$searchMode}}" autocomplete="off" id="startAndEndDate" type="text" value="{{isset($_GET['re_period'])?$_GET['re_period']:date('Y-m-01',time()).'～'.date('Y-m-t',time())}}"/>
                                </div>
                                <label class="focus-label">
                                    {{ __('自') }}<span class="invisible">****-**-** ～</span>{{ __('至') }}</label>
                            </div>
                        </div>
                        <div style="width: 300px;">
                            <div class="form-group form-focus search_msg">
                                <input type="text" class="form-control floating" name="search_msg"
                                       id="search_msg">
                                <label class="focus-label" for="search_msg">{{__('送付先名称、メモ')}}</label>
                            </div>
                        </div>

                        <div class="search" style="width: 200px;">
                            <a href="#" class="btn btn-success" id="receipt_search_btn"> {{__('Search')}} </a>
                            <a href="#" class="btn btn-secondary" id="reset_search_btn"> {{__('Reset')}} </a>
                        </div>

                        @canany([$__env->yieldContent('permission_modify'),$__env->yieldContent('self_modify')])
                        <div id="options" class="" style="width:calc(100% - 810px);min-width: 200px;">
                            <span class="invisible float-right" style="margin-right: 0;">
                                <button class="btn-option btn btn-sm btn-primary" data-toggle="modal"
                                    data-target="#copy"> {{ __('コピーして作成') }}</button>
                                <button class="btn-option btn btn-sm btn-danger" data-toggle="modal"
                                    data-target="#delete"> {{ __('削除') }}</button>
                            </span>
                        </div>
                        @endcan
                    </div>
                <!-- Search Filter -->

                <div class="col-12">
                    <table id="receipt-table" class="table table-striped table-fixed"
                           data-route="{{route('receipt.getReceipt')}}">
                        <thead>
                        <tr>
                            @canany([$__env->yieldContent('permission_modify'),$__env->yieldContent('self_modify')])
                            <th class="select-checkbox">{{__("選択")}}</th>
                            @endcan
                            <th>{{__("領収書番号")}}</th>
                            <th>{{__("領収日")}}</th>
                            <th>{{__("領収書名称・メモ")}}</th>
                            <th>{{__("取引先")}}</th>
                            <th>{{__("領収金額")}}</th>
                        </tr>
                        </thead>
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

@section('footer_append')
    @include('layouts.footers.requestmanage.index_a')
    <script>
        const EDIT_ROUTE = '{{route('receipt.edit',':id')}}';
        let widthArr = ['3rem', '7rem', '6rem', '', '', '7rem'];
        let columns = [
                @canany([$__env->yieldContent('permission_modify'),$__env->yieldContent('self_modify')])
            {
                data: "edit_id",
                name: "edit_id",
                orderable: false,
                className: 'position-relative select-checkbox',
                width: widthArr[0],
            },
            @endcan
            {
                data: "receipt_code",
                name: "receipt_code",
                width: widthArr[1],
            },
            {
                data: "receipt_date",
                name: "receipt_date",
                width: widthArr[2],
            }, {
                data: "name_or_memo",
                name: "name_or_memo",
                width: widthArr[3],
            },
            {
                data: "client_name",
                name: "client_name",
                width: widthArr[4],
                'createdCell': function(nTd, sData, oData, iRow, iCol){
                    createdHandleWhenClient(nTd,oData);
                },
            },
            {
                data: "receipt_amount",
                name: "receipt_amount",
                width: widthArr[5],
            }
        ];
        $('#search_msg').keydown(function(e){
            if(e.keyCode===13) $(table_selector).DataTable().draw();
        });
    </script>
    <script src="{{ asset('assets/js/receipt.view.js') }}"></script>
@endsection
