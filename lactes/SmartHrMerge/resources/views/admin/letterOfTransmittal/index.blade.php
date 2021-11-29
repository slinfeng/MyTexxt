@extends('layouts.backend')
@section('title', __('LetterOfTransmittal').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('LetterOfTransmittal'))
@section('view_title', __('送付状管理画面'))
@section('permission_modify','transmittal_modify')
@section('search_period',$re_period)
@section('self_modify','transmittal_self_modify')
@section('title_delete', __('LetterOfTransmittal'))
@section('title_copy', __('LetterOfTransmittal'))
@include('layouts.pages.sections.requestmanage.batch_action')
@section('route_create', route('letteroftransmittal.create'))
@section('route_copy', route('letteroftransmittal.copy'))
@section('route_delete', route('letteroftransmittal.delete'))
@section('init_val')
    @include('layouts.pages.initval.requestmanage.input_b')
@endsection
@section('css_append')
    @include('layouts.headers.requestmanage.index_a')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/letteroftransmittal.css') }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            @include('layouts.pages.title')
                <div class="col-12 row filter-row">
                        @include('layouts.pages.searchs.period')
                        <div class="col-md-6 col-lg-3 col-xl-3 limit-search-wildcard">
                            <div class="form-group form-focus search_msg">
                                <input type="text" class="form-control floating" name="search_msg"
                                       id="search_msg">
                                <label class="focus-label" for="search_msg">{{__('送付先名称、メモ')}}</label>
                            </div>
                        </div>
                        <div class="search">
                            <a href="#" class="btn btn-success" id="client_search_btn"> {{__('Search')}} </a>
                            <a href="#" class="btn btn-secondary" id="reset_search_btn"> {{__('Reset')}} </a>
                        </div>
{{--                        @include('layouts.pages.sections.requestmanage.options_c')--}}
                        @canany([$__env->yieldContent('permission_modify'),$__env->yieldContent('self_modify')])
                            @include('layouts.pages.sections.requestmanage.options_a')
                            @include('layouts.pages.options_a')
                        @endcan
                    </div>
                <!-- Search Filter -->

                <div class="col-12">
                    <table id="letteroftransmittal-table" class="table table-striped table-fixed"
                           data-route="{{route('letteroftransmittal.getLetterOfTransmittal')}}">
                        <thead>
                        <tr>
                            @canany([$__env->yieldContent('permission_modify'),$__env->yieldContent('self_modify')])
                            <th class="select-checkbox">{{__("選択")}}</th>
                            @endcan
                            <th>{{__("送付日")}}</th>
                            <th>{{__("送付状名称・メモ")}}</th>
                            <th>{{__("取引先略称")}}</th>
                            <th>{{__("送付先")}}</th>
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
    <script>
        const EDIT_ROUTE = '{{route('letteroftransmittal.edit',':id')}}';
        let widthArr = ['3rem','5rem','', '8rem', ''];
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
                data: "delivery_date",
                name: "delivery_date",
                width: widthArr[1],
                className: 'text-center',
            },
            {
                data: "memo",
                name: "memo",
                width: widthArr[2],
                'createdCell': function(nTd, sData, oData, iRow, iCol){
                    let route = '{{route("letteroftransmittal.edit",':id')}}';
                    createdHandleWhenEditLink(nTd,oData,route);
                },
            },
            {
                data: "client_abbreviation",
                name: "client_abbreviation",
                width: widthArr[3],
            },
            {
                data: "client_name",
                name: "client_name",
                width: widthArr[4],
                'createdCell': function(nTd, sData, oData, iRow, iCol){
                    if(oData.client_id!==undefined) createdHandleWhenClient(nTd,oData);
                },
            },
        ];
        $('#search_msg').keydown(function(e){
            if(e.keyCode===13){
                $(table_selector).DataTable().draw();
            }
        });
    </script>
    <script src="{{ asset('assets/js/letteroftransmittal.view.js') }}"></script>
@endsection
