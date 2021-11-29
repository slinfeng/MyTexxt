@extends('layouts.backend')
@section('title', __('OrderConfirmation').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('OrderConfirmation'))
@section('view_title', __('注文請書管理画面'))
@section('permission_modify','orderconfirm_modify')
@section('action','create')
@section('title_delete', __('OrderConfirmation'))
@section('title_copy', __('OrderConfirmation'))
@section('function_create','showModal(this)')
@section('function_delete', "modelDelFunc()")
@section('route_delete', route('confirmations.destroy','_id'))
@section('route_create', route('confirmations.create'))
@include('layouts.pages.sections.requestmanage.position_a')
@section('position',$position)
@section('search_period',date('Y-m-01').'～'.date('Y-m-t'))
@section('css_append')
    @include('layouts.headers.requestmanage.index_a')
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.searchableSelect.css')}}">
    <style>
        #dialog-modal input[name=period]{
            background-color: white;
            border: 1px solid #e3e3e3;
        }
        div.searchable-select{
            display: block;
            height: 44px;
            line-height: 32px;
        }
        div.searchable-select-dropdown{
            z-index: 100;
        }
        div.searchable-select-items{
            max-height: 300px;
        }
        div.searchable-select-holder{
            height: 44px;
            overflow: hidden;
        }
    </style>
@endsection
@section('init_val')
    @include('layouts.pages.initval.requestmanage.input_b')
@endsection
@section('content')
    @include('layouts.pages.data.data_file_route')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                @include('layouts.pages.title')
                <div class="col-12 row filter-row">
                    @include('layouts.pages.searchs.requestmanage.search')
                </div>
            <!-- /Search Filter -->
            <div class="col-12">
                <table id="order-confirmations-table" class="table table-striped row-border stripe" data-route="{{route('confirmations.search')}}"
                        style="width:100%">
                            <thead>
                            <tr>
                                <th>{{ __('開始日') }}</th>
                                <th>{{ __('注文請書番号') }}</th>
                                <th class="text-center">{{ __('ファイル名') }}</th>
                                <th class="text-center">{{ __('取引先') }}</th>
                                <th>{{ __('操作') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @include('layouts.pages.models.model_delete')
    @include('layouts.pages.models.model_dialog')
{{--    @include('layouts.pages.models.model_adjust_browser')--}}
@endsection
@section('footer_append')
    @include('layouts.footers.requestmanage.index_a')
    <script src="{{ asset('assets/js/jquery.searchableSelect.js') }}"></script>
    <script>
        const file_index=4;
        let widthArr = ['5rem','7.5rem','','','4rem'];
        const action_html = '<div class="dropdown dropdown-action">'
                +'<a href=" " class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'
                +'<i class="material-icons">more_vert</i></a>'
                +'<div class="dropdown-menu dropdown-menu-right">'
                +'<a href="#" class="dropdown-item" data-toggle="modal" id="edit" data-href="{{route('confirmations.edit',':id')}}" onclick="showModal(this)">'
                +'<i class="fa fa-pencil m-r-5"></i>編集 </a>'
                +'<a href="#" class="dropdown-item" data-toggle="modal" id="confirm" onclick="showDelModel(:id)">'
                +'<i class="fa fa-trash-o m-r-5"></i>削除 </a></div></div>';


        let columns = [
            {
                data: "period",
                name: "period",
                width: widthArr[0],
                className: 'text-center',
            },
            {
                data: "order_manage_code",
                name: "order_manage_code",
                width: widthArr[1],
                className: 'text-center',
            },
            {
                data: "project_name_or_file_name",
                name: "project_name_or_file_name",
                width: widthArr[2],
                'createdCell':function(nTd, sData, oData, iRow, iCol){
                    createdHandleWhenFile(nTd,oData);
                },
            },
            {
                data: 'client_name',
                name: 'cname',
                width: widthArr[3],
                'createdCell': function(nTd, sData, oData, iRow, iCol){
                    createdHandleWhenClient(nTd,oData);
                },
            },
           @can($__env->yieldContent('permission_modify'))
            {
                data: 'id',
                orderable: false,
                className: "text-center pd-tb-5",
                width: widthArr[4],
                render:function (data) {
                    return action_html.replace(/:id/g,data);
                }
            },
            @endcan
        ];
    </script>
    <script src="{{ asset('assets/js/account.order.confirmation.js') }}"></script>
@endsection








