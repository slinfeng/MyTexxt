@extends('layouts.backend')
@section('title', __('設備管理').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('設備管理'))
@section('permission_modify','asset_modify')
@section('css_append')
{{--    <link rel="stylesheet" href="{{ asset('assets/css/newcss.css') }}">--}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables/fixedHeader.dataTables.min.css') }}">--}}
<link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">

<!-- dateTable CSS -->
{{--    <link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.min.css')}}">--}}
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.min.css') }}">
<!-- Datetimepicker CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables/fixedHeader.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">
<style type="text/css">
    .m-w-5em{
        min-width: 5em;
    }
    .max-w-10em{
        max-width: 10em;
    }
    .dataTable{
        table-layout: fixed;
    }
    .datatable th, .datatable td {
        text-align: center;
    }
    .operating{
        width: 30px;
    }
    th:focus{
        outline: none;
    }
    #loan-asset>div.modal-dialog{
        width: 800px;
        max-width: 800px;
    }

    .custom-modal .modal-header {
        padding-top: 10px!important;
    }

    div.dataTables_scrollHeadInner,div.dataTables_scrollHeadInner table{
        width: 100%!important;
    }
</style>
@endsection
@section('content')
    <input name="init_val" hidden data-currency-symbol="{{$currency}}">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header mb-3">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">{{ __('設備管理') }}</h3>
                        </div>
                        @can($__env->yieldContent('permission_modify'))
                        <div class="col-auto float-right ml-auto">
                            <a id="smallButton" href="#" class="btn add-btn edit-link" data-toggle="modal"
                               data-target="#add_asset"
                               data-href="{{ route('companyassets.create') }}"><i
                                    class="fa fa-plus"></i> {{ __('新規作成') }}</a>
                        </div>
                        @endcan
                    </div>
                </div>
                <!-- /Page Header -->
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
            @endif
            <!-- Search Filter -->
                <div class="col-12 row filter-row">
                        <div class="col-sm-4 col-md-2">
                            <div class="form-group form-focus select-focus">
                                <select onchange="ableOtherSearch(this)" class="select floating" name="number" id="number_search_status">
                                    <option value=''>　</option>
                                    <option value="1">設備(B010)</option>
                                    <option value="2">食器(B020)</option>
                                    <option value="3">その他(B030)</option>
                                </select>
                                <label class="focus-label">{{__('設備分類')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-2 col-md-2">
                            <div class="form-group form-focus">
                                <input onchange="ableOtherSearch(this)" type="text" class="form-control floating" name="manage_code"
                                       id="asset_search_code">
                                <label class="focus-label" for="id">{{__('管理番号')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-3">
                            <a href="javascript:void(0);" style="width: 30%" class="btn btn-success"
                               id="asset_search_btn"> {{__('Search')}} </a>
                            <a href="javascript:void(0);" style="width: 60%" class="btn btn-secondary"
                               id="reset_search_btn"> {{__('Reset')}} </a>
                        </div>
                    </div>
                <!-- Search Filter -->
                <div class="col-12">
                    <table id="AssetInfo" class="table table-striped mb-0 datatable" data-route="{{route('asset.getAssetInfo')}}"
                           style="width: 100%">
                        <thead>
                        <tr>
                            <th class="text-center">{{ __('管理番号') }}</th>
                            <th class="text-center">{{ __('種類/メーカー/型番') }}</th>
                            <th class="text-center">{{ __('シリアル番号') }}</th>
                            <th class="text-center">{{ __('分類') }}</th>
                            <th class="text-center">{{ __('納品日') }}</th>
                            <th class="text-center">{{ __('金額') }}</th>
                            <th class="text-center">{{ __('保管場所/利用者') }}</th>
                            <th class="text-center">{{ __('その他情報') }}</th>
                            <th class="text-center">{{ __('状態') }}</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- Add/edit Asset Modal -->
            <div id="add-edit-asset" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true">{{ __('x') }}</span>
                            </button>
                        </div>
                        <div class="modal-body" id="add-edit-asset-body">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add//edit Asset Modal -->
            <!-- 貸出モーダル　Loan Modal-->
            <div id="loan-asset" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                @can($__env->yieldContent('permission_modify'))
                                {{ __('貸出管理') }}
                                @else
                                {{ __('貸出詳細情報') }}
                                @endcan
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">{{ __('x') }}</span>
                            </button>
                        </div>
                        <div class="modal-body" id="loan-asset-body">
                        </div>
                    </div>
                </div>
            </div>
            <!-- 貸出モーダル　Loan Modal -->　　　　　 　　　　　　　
        </div>
    </div>
@endsection
@section('footer_append')
<!-- Datatable JS -->
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable-language.jp.js') }}"></script>
    <!-- Select2 JS -->
    <script src="{{ asset('assets/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <!-- Bootstrap Notify -->
    <script src="{{ asset('assets/js/bootstrap-notify-3.1.3/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('assets/js/lactes.js') }}"></script>

    <script>
        let table_selector = '#AssetInfo';
        let clientStatus = '';
        let widthArr = ['5em','','10%','2.5em','5em','7.5em','10%','10%','2.5em'];
        let columns = [
            {data: 'manage_code', name: 'manage_code',className: 'text-center',width: widthArr[0]},
            {data: 'type_maker_model', name: 'type_maker_model',className: 'text-left m-w-5em',width: widthArr[1]},
            {data: 'serial_number', name: 'serial_number',className: 'text-left',width: widthArr[2]},
            {data: 'asset_type', name: 'asset_type',className: 'text-center',width: widthArr[3]},
            {data: 'delivery_date', name: 'delivery_date',className: 'text-center',width: widthArr[4]},
            {data: 'amount', name: 'amount',className: 'text-right',width: widthArr[5]},
            {data: 'storage', name: 'storage',className: 'text-left m-w-5em',width: widthArr[6]},
            {data: 'infos', name: 'infos',className: 'text-left m-w-5em',width: widthArr[7],orderable:false},
            {data: 'status', name: 'status',className: 'text-center',width: widthArr[8],
                'createdCell':function (nTd, sData, oData) {
                    const route = "{{route('companyassets.loan',':id')}}";
                    createdHandleWhenEditLink(nTd,oData,route);
                }},
        ];
        let data_datatable = function (d){
            d.number = $('#number_search_status').val();
            d.manage_code = $('#asset_search_code').val();
            d.status = $('#remark_search_status').val();
        }
        let columnDefs = [];
        let table;
        $(function () {
            showHeadButton();
            initDataTable();
            table = $(table_selector).DataTable();
            adjustSidebarForFixedHeader();
            $('#asset_search_btn').click(function () {
                table.draw();
            });
            $('#reset_search_btn').click(function () {
                $('#number_search_status').prop('disabled',false).select2("val", [""]);
                $("#number_search_status option").eq(1).prev("selected",true);
                $('#asset_search_code').prop('disabled',false).val('');
                $('#remark_search_status').select2("val", [""]);
                table.draw();
            });
            $('#asset_search_code').keydown(function (e) {
                if(e.keyCode==13){
                    $('#asset_search_btn').click();
                }
            });
        })
        function initDataTable() {
            unSizeForFixedHeader();
            tableSettingInfo = initTableSettingInfo();
            tableSettingInfo.columns.push({data: "id", name: "id",orderable:true,visible:false});
            initTable();
        }
        let ajaxLock = false;
        function initTable(){
            if(!ajaxLock){
                ajaxLock = true;
                if($('div.dataTables_info').length===0){
                    $(table_selector).DataTable(tableSettingInfo);
                }else{
                    $(table_selector).DataTable().draw();
                }
            }
        }
        function datatableComplete(){}
        $(document).on('click', 'a.edit-link', function (event) {
            event.preventDefault();
            let href = $(this).data('href');
            let id = href.indexOf('loan')>=0?'#loan-asset':'#add-edit-asset';
            $.ajax({
                url: href,
                success: function (result) {
                    $(id+'-body').html(result).show();
                },
                complete: function () {
                    $(id).modal("show");
                    $('#loader').hide();
                },
                error: function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        });

        function ableOtherSearch(e) {
            if($(e).attr('name')==='manage_code')
                if($(e).val().trim()==='') $('#number_search_status').prop('disabled',false);
                else $('#number_search_status').prop('disabled',true);
            if($(e).attr('name')==='number')
                if($(e).val()==='') $('#asset_search_code').prop('disabled',false);
                else $('#asset_search_code').prop('disabled',true);
        }

        function formSubmit(e) {
            const obj = lockAjax();
            $.ajax({
                url:'{{ route('companyassets.store') }}',
                type:'post',
                data:$(e).parents('form').serialize(),
                success:function (res) {
                    ajaxSuccessAction(res,function () {
                        $('#add-edit-asset').modal("hide");
                        table.draw();
                    });
                },error:function (jqXHR,testStatus,error) {
                    ajaxErrorAction(jqXHR,testStatus,error);
                },complete:function () {
                    unlockAjax(obj);
                }
            });
        }
    </script>
@endsection


