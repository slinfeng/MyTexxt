@extends('layouts.backend')
@section('title', __('初期設定').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('初期設定画面'))

@section('css_append')
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <style>
        .table-setting tr td:nth-of-type(1) {
            padding-right: 20px;
            text-align: right;
            width: 13em;
        }
        .table-setting tr td{
            padding: 15px 5px;
        }
        .w-30{
            width: 30%;
        }
    </style>
@endsection
@section('content')
    <div class="content container-fluid">
        <!-- Page Tab -->
        <div class="page-menu">
            <div class="row">
                <div class="col-sm-12">
                    <ul class="nav nav-tabs nav-tabs-bottom">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab_common">設備管理</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab_clients" onclick="textareaHeight()">領収書</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Tab -->

        <!-- Tab Content -->
        <div class="tab-content" id="request_setting">
            <!-- common Tab -->
            <div class="tab-pane show active" id="tab_common">
                @include('admin.assetSetting.details.equipment')
            </div>
            <!-- common Tab -->

            <!-- clients Tab -->
            <div class="tab-pane" id="tab_clients">
                @include('admin.assetSetting.details.receipt')
            </div>
            <!-- /clients Tab -->
        </div>
        <!-- Tab Content -->
    </div>
    @include('layouts.pages.models.model_delete')
@endsection
@section('footer_append')
    <script src="{{ asset('assets/js/bootstrap-notify-3.1.3/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/lactes.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click','.edit_asset_type',function(){
                var id=$(this).attr('data-id');
                let modal_edit = $('#edit_asset_type');
                let edit_action_url=$(this).data('edit');
                let route = modal_edit.data('route');
                route = route.replace(':id',id);
                $.get(edit_action_url, function (data) {
                    $('#edit_asset_type_code').val(data.assettype.asset_type_code);
                    $('#edit_asset_type_name').val(data.assettype.asset_type_name);
                    $('#edit_asset_type_form').attr('action',route);
                    $('#edit_asset_type').modal('show');
                })
            });
            const modalAdd = $("#add_asset_type");
            modalAdd.on("show.bs.modal", function() {
                modalAdd.find('input').val('');
            });
            const modalDelete = $("#delete_asset_type");
            modalDelete.on("show.bs.modal", function(e) {
                let route = modalDelete.data('route');
                route = route.replace(':id',$(e.relatedTarget).data('id'));
                modalDelete.find('form').attr('action',route);
            });
            modalDelete.find('#delete_asset_type_btn').on('click', function(){
                const form = modalDelete.find('form');
                $.ajax({
                    url:form.attr('action'),
                    type:'post',
                    data:form.serialize(),
                    success:function (res) {
                        ajaxSuccessAction(res,function () {
                            updateAssetTypes(res);
                            $('#delete_asset_type').modal('hide');
                        });
                    },error:function (jqXHR, testStatus, error) {
                        ajaxErrorAction(jqXHR, testStatus, error);
                    }
                })
            });
        });

        function addOrUpdateAssetType(form_selector) {
            const form = $(form_selector);
            const obj = lockAjax();
            $.ajax({
                url:form.attr('action'),
                type:'post',
                data:form.serialize(),
                dataType:'json',
                success:function (data) {
                    ajaxSuccessAction(data,function () {
                        updateAssetTypes(data);
                        $('#edit_asset_type').modal('hide');
                        $('#add_asset_type').modal('hide');
                    });
                },error:function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                },complete:function () {
                    unlockAjax(obj);
                }
            })
        }

        function updateAssetTypes(res) {
            const datas = res.data;
            const table = $('#asset-types');
            table.find('tbody').empty();
            $.each(datas,function (index,data) {
                const tr = $('#tr_template').clone().removeAttr('hidden');
                tr.attr('id','type_'+data.id);
                const tds = tr.find('td');
                tds.eq(0).html(index+1);
                tds.eq(1).html(data.asset_type_code);
                tds.eq(2).html(data.asset_type_name);
                tds.eq(3).html(tds.eq(3).html().replace(/:id/g,data.id));
                tr.appendTo(table);
            });
        }

        function saveSetting() {
            const form = $('#receive-setting');
            $.ajax({
                url: form.attr('action'),
                type:'post',
                data: new FormData(form.get(0)),
                contentType: false,
                processData: false,
                success: function (res) {
                    ajaxSuccessAction(res);
                },error: function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error)
                }
            });
        }

        function textareaHeight() {
            setTimeout(function () {
                initTextarea()
            },1)
        }
    </script>
@endsection
