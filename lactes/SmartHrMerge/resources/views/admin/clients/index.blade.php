@extends('layouts.backend')
@section('title', __('Clients').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('Clients'))
@section('permission_modify','client_modify')
@section('title_delete', __('Clients'))
@section('function_delete', "modelDelFunc()")
@section('route_delete', route('clients.destroy','_id'))
@section('css_append')
    @include('layouts.headers.requestmanage.index_a')
    <style>
        .overflow-init {
            overflow: visible;
        }

        #delete div.modal-dialog {
            max-width: 500px;
        }

        div.modal-dialog {
            max-width: 800px;
        }

        #change_client_priority_modal div.modal-dialog {
            max-width: 500px;
        }

        .action-label > a {
            min-width: 5em !important;
        }

        .limit-search-num{
            min-width: 9em;
            max-width: 9em;
        }

        .limit-search-wildcard{
            min-width: 30em;
            max-width: 30em;
        }

        .limit-search-position{
            min-width: 8em;
            width: 8em;
        }

        .limit-search-date{
            max-width: 300px;
            min-width: 300px;
        }

        .limit-search-btn{
            width: 200px;
            min-width: 160px;
        }

        .filter-row .form-focus .focus-label {
            font-size: 0.9em;
        }

        .pd-lr-0{
            padding-left: 0!important;
            padding-right: 0!important;
        }

        body table.dataTable thead > tr > th.pd-r-18p{
            padding-right: 18px!important;
        }

        input,textarea{
            background-color: white!important;
            border: 1px solid #e3e3e3!important;
        }
        textarea{
            margin-top: 0;
        }
        input[name=calc_type]{
            vertical-align: middle;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header mb-3">
                    <div class="row align-items-center">
                        <div class="col-10">
                            <h3 class="mb-0">取引先管理画面</h3>
                        </div>
                        @can($__env->yieldContent('permission_modify'))
                            <div class="col-2 text-right">
                                <a href="#" class="btn add-btn" data-toggle="modal"
                                   data-href="{{ route('clients.create') }}" onclick="showModal(this)"
                                   title="{{ __('Add client') }}">
                                    <i class="fa fa-plus"></i> {{ __('Add client') }}
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>

                <!-- Search Filter -->
                <div class="col-12 row filter-row">
                        <div class="col-md-3 col-lg-1 col-xl-1 limit-search-num">
                            <div class="form-group form-focus">
                                <input type="text" class="form-control floating number" name="id" id="client_search_id">
                                <label class="focus-label" for="id">{{__('取引先番号')}}</label>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 col-xl-3 limit-search-wildcard">
                            <div class="form-group form-focus">
                                <input type="text" class="form-control floating" name="client_name"
                                       id="client_search_client_name">
                                <label class="focus-label" for="client_name">{{__('取引先略称、取引先名、取引先住所、電話、メモ、サイト')}}</label>
                            </div>
                        </div>
                        <div class="col-md-2 col-lg-1 col-xl-1 limit-search-position">
                            <div class="form-group form-focus select-focus">
                                <select id="client_search_position" name="position" class="select floating">
                                    <option value="0">　</option>
                                    <option value="{{$ourRole[0]->id}}">{{$ourRole[0]->our_position_type_abbr_name}}</option>
                                    <option value="{{$ourRole[1]->id}}">{{$ourRole[1]->our_position_type_abbr_name}}</option>
                                </select>
                                <label class="focus-label">立場</label>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 col-xl-3 limit-search-date">
                            <div class="form-group form-focus">
                                <div class="cal-icon">
                                    <input autocomplete="off" data-search-mode="{{$searchMode}}" style="font-size: 1em" class="form-control floating" id="client_search_date"
                                           type="text" value=""/>
                                </div>
                                <label class="focus-label">
                                    {{ __('自') }}<span style="visibility: hidden">***********</span>
                                    {{ __('至') }}</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-xl-2 limit-search-btn">
                            <a href="#" class="btn btn-success" id="client_search_btn"> {{__('Search')}} </a>
                            <a href="#" class="btn btn-secondary" id="reset_search_btn"> {{__('Reset')}} </a>
                        </div>
                    </div>
                <!-- Search Filter -->
                <div class="col-12">
                    <table class="table table-striped mb-0 datatable table-fixed" id="clients" style="width: 100%" data-route="{{route('clients.get-clients')}}">
                        <thead>
                        <tr>
                            <th>{{__('Cooperation Start')}}</th>
                            <th class="pd-lr-0 pd-r-18p">{{__('Client ID')}}</th>
                            <th>{{__('Client Abbreviation')}}</th>
                            <th>{{__('Client Name')}}</th>
                            <th class="pd-lr-0 pd-r-18p">{{__('Position')}}</th>
                            <th>{{__('Client Address')}}</th>
                            <th>{{__('Url')}}</th>
                            <th>{{__('Tel')}}</th>
                            <th>{{__('Memo')}}</th>
                            @can($__env->yieldContent('permission_modify'))
                            <th class="pd-lr-0 pd-r-18p">{{__('優先')}}</th>
                            @endcan
                            <th>{{__('Action')}}</th>
                            <th>{{__('sort')}}</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.pages.models.model_dialog')
    @include('layouts.pages.models.model_delete')
{{--    @include('layouts.pages.models.model_adjust_browser')--}}

    <div class="modal custom-modal fade" id="change_client_priority_modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>{{ __('Change Client Priority') }}</h3>
                        <p>{{ __('Are you sure want to change priority?') }}</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <a href="javascript:void(0);" id="change_client_priority_btn"
                                   class="btn btn-primary continue-btn">{{ __('Change') }}</a>
                            </div>
                            <div class="col-6">
                                <a href="javascript:void(0);" data-dismiss="modal"
                                   class="btn btn-primary cancel-btn">{{ __('Cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal custom-modal fade" id="client_bank_info" data-keyboard="false" data-backdrop="static" role="dialog">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">

            </div>
        </div>
    </div>
@endsection

@section('footer_append')
    @include('layouts.footers.requestmanage.index_a')
    <script type="text/javascript">
        let table_selector = '#clients';
        let widthArr = ['8%', '8%', '10%','', '4.8%', '', '5em', '6em', '9%', '4.2em', '3.8%'];
        let bank_action = '<a class="dropdown-item" href="#" data-toggle="modal" onclick="showBankInfoModel(_id)">';
        bank_action += '<i class="fa fa-bank m-r-5"></i>{{__('口座情報')}}</a>';
        let td_action = '<div class="dropdown dropdown-action">';
        td_action += '<a href="#" class="action-icon dropdown-toggle"';
        td_action += 'data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>';
        td_action += '<div class="dropdown-menu dropdown-menu-right">';
        @can($__env->yieldContent('permission_modify'))
        td_action += '<a class="dropdown-item edit_client" href="#" data-toggle="modal" data-href="{{route('clients.edit','_id')}}" onclick="showModal(this)">';
        td_action += '<i class="fa fa-pencil m-r-5"></i>{{__('Edit')}}</a>';
        td_action += ':bank';

        td_action +='<a class="dropdown-item" href="#" data-toggle="modal" onclick="showDelModel(_id)">';
        td_action +='<i class="fa fa-trash-o m-r-5"></i>{{__('Delete')}}</a>';
        let priority_action = '<div class="dropdown action-label"><a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">';
        priority_action += '<i class="fa fa-dot-circle-o :classA"></i>:textA</a>';
        priority_action += '<div class="dropdown-menu dropdown-menu-right">';
        priority_action += '<a class="dropdown-item" href="#" data-toggle="modal" data-target="#change_client_priority_modal" onclick="changeAlert(_id)" >';
        priority_action += '<i class="fa fa-dot-circle-o :classB"></i>:textB</a>';
        priority_action += '</div></div>';
        @else
        td_action += '<a class="dropdown-item edit_client" href="#" data-toggle="modal" data-href="{{route('clients.edit','_id')}}" onclick="showModal(this)">';
        td_action += '<i class="fa fa-eye m-r-5"></i>{{__('表示')}}</a>';
        @endcan
        td_action += '</div></div>';
        let urlPattern = /[(https:\/\/)|(http:\/\/)]+/;
        data_datatable = function (d) {
            d.id = $('#client_search_id').val();
            d.period = $('#client_search_date').val();
            d.position = $('#client_search_position').val();
        };
        let columns= [
            {data: 'cooperation_start', name: 'cooperation_start', width: widthArr[0],className: 'text-center'},
            {data: 'cid', name: 'cid', width: widthArr[1],className: 'text-center pd-lr-0'},
            {data: 'client_abbreviation', name: 'client_abbreviation', width: widthArr[2]},
            {data: 'client_name', name: 'client_name', width: widthArr[3]},
            {data: 'our_position_type.our_position_type_abbr_name', name: 'our_role', width: widthArr[4],className: 'text-center pd-lr-0'},
            {data: 'client_address', name: 'client_address', width: widthArr[5]},
            {data: 'url', name: 'url', width: widthArr[6],
                render:function (data) {
                    return data===null?'':'<a target="_blank" href="'+data+'" >'+data.replace(urlPattern,'')+'</a>';
                }},
            {data: 'tel', name: 'tel', width: widthArr[7],className: 'text-center'},
            {data: 'memo', name: 'memo', orderable: false, width: widthArr[8]},
                @can($__env->yieldContent('permission_modify'))
            {data: null, name: 'priority', width: widthArr[9], className: 'text-center overflow-init pd-lr-0',
                render:function (data) {
                    let classA = (data.priority === 0 ? 'text-success' : 'text-danger');
                    let classB = data.priority === 0 ? 'text-danger' : 'text-success';
                    let textA = data.priority === 0 ? '{{__('High')}}' : '{{__('Low')}}';
                    let textB = data.priority === 0 ? '{{__('Low')}}' : '{{__('High')}}';
                    return priority_action.replace(':classA',classA).replace(':classB',classB)
                        .replace(':textA',textA).replace(':textB',textB).replace('_id',data.id);
                }},
                @endcan
            {data: function (row) {
                    return [row.id,row.our_position_type.id];
                }, name: 'action', orderable: false, searchable: false, width: widthArr[10], className: 'overflow-init text-center',
                render:function (data) {
                    let action = td_action;
                    if(data[1]==1){
                        action = action.replace(':bank',bank_action);
                    }else{
                        action = action.replace(':bank','');
                    }
                    action = action.replace(/_id/g,data[0]);
                    return action;
                }},
        ];
        columnDefs = [
            {
                //设置不参与搜索
                targets:[0,1,4,9],
                searchable:false
            },];
        let table;
        $(function () {
            showHeadButton();
            initDatePicker('#client_search_date');
            table = initDataTable(0);
            adjustSidebarForFixedHeader();
            $('#client_search_btn').click(function () {
                let client_name = $('#client_search_client_name').val();
                table.search(client_name).draw();
            });
            $('#reset_search_btn').click(function () {
                $('#client_search_id').val('').parent('div').removeClass('focused');
                $('#client_search_client_name').val('').parent('div').removeClass('focused');
                $('#client_search_date').val('').parent('div').parent('div').removeClass('focused');
                $('#client_search_position').val('0').trigger("change");
            });
            $('#client_search_id,#client_search_client_name').keydown(function(e){
                if(e.keyCode===13) table.search($('#client_search_client_name').val()).draw();
            });

        });

        function changeAlert(id) {
            const modal = $('#change_client_priority_modal');
            modal.modal('show').find("#change_client_priority_btn").off().click(function () {
                let url = "{{ route('clients.priorityChange',':id') }}";
                url = url.replace(':id', id);
                $.ajax({
                    url: url,
                    type: "POST",
                    success: function (response) {
                        ajaxSuccessAction(response,function () {
                            table.draw();
                            modal.modal('hide');
                        });
                    },
                    error: function (jqXHR, testStatus, error) {
                        ajaxErrorAction(jqXHR, testStatus, error);
                    }
                });
            });
        }

        function addPostMark(e) {
            const val = $(e).val();
            if (val.trim() !== ''){
                if(val.length>3) $(e).val('〒' + val.substr(0,3) +'-' + val.substr(3));
                else $(e).val('〒' + val);
            }
        }

        function onPostFocus(e) {
            e.value = e.value.replace(/[〒-]/g, "");
            e.setSelectionRange(0, e.value.length);
        }

        function showBankInfoModel(id) {
            $.ajax({
                url: "{{route('client.getBankInfo')}}",
                data:{'id':id},
                type: "get",
                success: function (result) {
                    $('#client_bank_info').modal("show");
                    $('#client_bank_info .modal-content').html(result).show();
                },
                error: function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                },
            })
        }
        function bankInfoSave() {
            $.ajax({
                url: "{{route('client.saveBankInfo')}}",
                data:$('#client_bank_info_form').serialize(),
                type: "post",
                success: function (response) {
                    ajaxSuccessAction(response,function (response) {
                        $('#client_bank_info').modal("hide");
                        $('#client_bank_info .modal-content').html('');
                    });
                },
                error: function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                },
            })
        }
    </script>
@endsection
