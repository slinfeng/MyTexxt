@extends('layouts.backend')
@section('title', __('Users').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('Users'))
@section('permission_modify','user_modify')
@section('css_append')
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.searchableSelect.css')}}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables/fixedHeader.dataTables.min.css') }}">
    <style type="text/css">
        .filter-row .form-focus .focus-label {
            font-size: 0.9em;
        }
        input, select, textarea, h1, h2, h3, h4, h5, h6, th {
            color: #333;
        }
        th{
            text-align: center;
        }
        input{
            border: 1px solid grey;
        }
        .dataTable{
            table-layout: fixed;
        }
        .ip-input{
            width: 40px;
            margin: 1px;
        }
        #add_edit_form select.form-control[size] {
            height: 150px;
        }
        .searchable-select{
            width: 100%;
        }
        .searchable-select-dropdown{
            z-index: 9999;
        }
        .searchable-select-holder{
            background-color: lightgrey;
        }
    </style>

@endsection
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header mb-3">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">{{ __('Users') }}</h3>
                        </div>
                        @can($__env->yieldContent('permission_modify'))
                            <div class="col-auto float-right ml-auto">
                                <a href="javascript:void(0);" class="btn add-btn" data-toggle="modal" id="showUserModalBtn"
                                   data-url="{{ route('users.create') }}" title="{{ __('新規作成') }}">
                                    <i class="fa fa-plus"></i> {{ __('新規作成') }}
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>

                <!-- Search Filter -->
                <div class="col-12 row filter-row">
                        <div class="col-sm-6 col-md-2">
                            <div class="form-group form-focus">
                                <input type="text" class="form-control floating" name="id" id="user_search_id" >
                                <label class="focus-label" for="id" >{{__('User ID')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-2">
                            <div class="form-group form-focus">
                                <input type="text" class="form-control floating" name="user_name" id="user_search_user_name"  >
                                <label class="focus-label" for="user_name">{{__('User Name')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <a href="javascript:void(0);" class="btn btn-success" id="user_search_btn" > {{__('Search')}} </a>
                            <a href="javascript:void(0);" class="btn btn-secondary" id="reset_search_btn" > {{__('Reset')}} </a>
                        </div>

                    </div>
                <!-- Search Filter -->

                <div class="col-12">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                </div>

                <div class="col-12">
                    <table class="table table-striped custom-table mb-0 datatable" id="users" data-route="{{route('users.get-users')}}">
                        <thead>
                        <tr>
                            <th>{{__('User ID')}}</th>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Email')}}</th>
                            <th>{{__('Register Date')}}</th>
                            <th>{{__('Role')}}</th>
                            <th>{{__('取引先')}}</th>
                            @can($__env->yieldContent('permission_modify'))
                                <th class="text-right no-sort">{{__('Action')}}</th>
                            @endcan
                        </tr>

                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- User Modal -->
    <div id="user_modal" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document" style="width: 700px">
            <div class="modal-content" id="user_modal_content" >

            </div>
        </div>
    </div>
    <!-- /User Modal -->


    <!-- Delete User Modal -->
    <div class="modal custom-modal fade" id="delete_user_modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>{{ __('Delete User') }}</h3>
                        <p>{{ __('Are you sure want to delete?') }}</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <a href="javascript:void(0);" id="delete_user_btn" class="btn btn-primary continue-btn">{{ __('Delete') }}</a>
                            </div>
                            <div class="col-6">
                                <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">{{ __('Cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Delete User Modal -->
    <!-- Change User Role Modal -->
    <div class="modal custom-modal fade" id="change_user_priority_modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>{{ __('Change User Role') }}</h3>
                        <p>{{ __('Are you sure want to change priority?') }}</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <a href="javascript:void(0);" id="change_user_priority_btn" class="btn btn-primary continue-btn">{{ __('Change') }}</a>
                            </div>
                            <div class="col-6">
                                <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">{{ __('Cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Change User Status Modal -->
@endsection

@section('footer_append')
    <!-- Datatable JS -->
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable-language.jp.js') }}"></script>
    <!-- Select2 JS -->
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.searchableSelect.js') }}"></script>
    <!-- Bootstrap Notify -->
    <script src="{{ asset('assets/js/bootstrap-notify-3.1.3/bootstrap-notify.min.js') }}"></script>

    <script src="{{ asset('assets/js/datatables/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('assets/js/lactes.js') }}"></script>
    <script type="text/javascript">
        let table_selector = '#users';
        let widthArr = ['6em','10em','14em','7.5em','','','2.5em'];
        let columns = [
            {data: 'id',name: 'id',className: 'text-center',width: widthArr[0]},
            {data: 'name', name: 'name',width: widthArr[1]},
            {data: 'email', name: 'email',width: widthArr[2]},
            {data: 'registered_date', name: 'registered_date',width: widthArr[3],className: 'text-center'},
            {data: 'roles', name: 'roles',width: widthArr[4]},
            {data: 'client_name', name: 'client_name',width: widthArr[5]},
            @can($__env->yieldContent('permission_modify'))
            {data: 'action',name: 'action', orderable: false, searchable: false,width: widthArr[6],className: 'text-center'}
            @endcan
        ];
        let columnDefs = [
            {
                //设置不参与搜索
                targets:[0,2,3,4,5,6],
                searchable:false
            },
        ];
        let data_datatable = function (d){
            d.id = $('#user_search_id').val();
            d.user_name = $('#user_search_user_name').val();
        };
        let table;
        function initDataTable() {
            unSizeForFixedHeader();
            tableSettingInfo = initTableSettingInfo(0, 'asc');
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

        function showModalForm(url){
            $.ajax({
                url: url,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#user_modal_content').html(result).show();
                    $('#user_modal').modal("show");
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        }

        // Show Edit Modal
        function editModal(id) {
            var url  ="{{route('users.edit',':id')}}";
            url      = url.replace(':id',id);
            showModalForm(url);
        }

        // add or Update User Function ajax request
        function addEditUser(id) {
            if(typeof id!='undefined'){
                var url  ="{{route('users.update',':id')}}";
                url      = url.replace(':id',id);
            }else{
                url = "{{ route('users.store') }}";
            }
            $('#edit_roles_select option:selected').prop('selected',false);
            $("#edit_roles_select option:visible").prop('selected',true);
            $("#edit_roles_select option:hidden").prop('selected',false);
            const obj = lockAjax();
            $.ajax({
                url:url,
                type:"POST",
                data: $('#add_edit_form').serialize(),
                success:function(response){
                    ajaxSuccessAction(response,function () {
                        $('#user_modal').modal('hide');
                        table.draw();
                    });
                },
                error: function(jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                },complete:function () {
                    unlockAjax(obj);
                }
            });

        }
        // Show Delete Modal and set delete btn method
        function deleteAlert(id) {
            $('#delete_user_modal').modal('show');
            $('#delete_user_modal').find("#delete_user_btn").off().click(function () {
                var url = "{{ route('users.destroy',':id') }}";
                url = url.replace(':id', id);

                $.ajax({
                    url:url,
                    type:"delete",
                    success:function(response){
                        ajaxSuccessAction(response,function () {
                            $('#delete_user_modal').modal('hide');
                            table.draw();
                        });
                    },
                    error: function(jqXHR, testStatus, error) {
                        ajaxErrorAction(jqXHR, testStatus, error);
                    }
                });

            });
        }

        $(document).ready(function() {
            showHeadButton();
            initDataTable();
            adjustSidebarForFixedHeader();
            table = $(table_selector).DataTable();
            // search
            $('#user_search_btn').click(function(){
                table.search($('#user_search_user_name').val()).draw();
            });
            // create
            $(document).on('click', '#showUserModalBtn', function(event) {
                event.preventDefault();
                let url = $(this).attr('data-url');
                showModalForm(url);
            });

            $('#reset_search_btn').click(function(){
                $('#user_search_id').val('');
                $('#user_search_user_name').val('');
                //$('#user_search_priority').val('');
                //$dataTable.destroy();
                table.draw();
            });
            $('#user_search_id,#user_search_user_name').keydown(function(e){
                if(e.keyCode===13){
                    table.search($('#user_search_user_name').val()).draw();
                }
            });
        });

        function rolesSelectRightAll() {
            $('#edit_roles_select>*').hide();
            $('#edit_roles_select_to>*').show();
            roleCheck();
        }
        function rolesSelectRight() {
            let val = $('#edit_roles_select').val();
            $('#edit_roles_select option:selected').hide();
            $.each(val, function(i, item){
                $('#edit_roles_select_to option[value='+item+']').show();
            });
            roleCheck();
        }
        function rolesSelectLeft() {
            let val = $('#edit_roles_select_to').val();
            $('#edit_roles_select_to option:selected').hide();
            $.each(val, function(i, item){
                $('#edit_roles_select option[value='+item+']').show();
            });
            roleCheck();
        }

        function roleCheck() {
            if($('#edit_roles_select option[value=8]').is(':visible')){
                $('.searchable-select-dropdown').css('display','');
                $('.searchable-select-holder').css('background-color','white');
                return;
            }
            $('.searchable-select-dropdown').css('display','none');
            $('.searchable-select-holder').css('background-color','lightgrey');
        }
    </script>


@endsection
