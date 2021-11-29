@extends('layouts.backend')
@section('title', __('Employees').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('Employees'))
@section('view-card', 'active')
@section('css_append')
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.searchableSelect.css') }}">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables/fixedHeader.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css') }}" />
    <style>
        .searchable-select-item{
            position: relative;
            z-index: 10;
        }
        .searchable-select{
            width: 100%;
            z-index: 10000;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                @include('admin.employees.index')
                <div class="row staff-grid-row" id="index-list" style="padding: 0 20px">
                </div>
            </div>
        </div>
    </div>
    @include('admin.employees.indexModal')
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
    <!-- flatpickr JS -->
    <script src="{{asset('assets/js/moment.min.js')}}"></script>
    <script src="{{asset('assets/plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{asset('assets/plugins/flatpickr/l10n/ja.js') }}"></script>
    <!-- Tagsinput JS -->
    <script src="{{ asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables/dataTables.fixedHeader.min.js') }}"></script>
    <script src="https://www.jqueryscript.net/demo/easy-wizard-control/jq-wizard.js"></script>
    <script src="{{ asset('assets/js/lactes.js') }}"></script>
    <script type="text/javascript">
        window.onload=function () {
            cardShow();
        };
        function cardShow() {
            let employee_retire = null;
            if(!$('#switch_annual').is(':checked')) employee_retire ='true';
            $.ajax({
                url:"{{route('employees.get-employees')}}",
                type:"GET",
                data:{
                    id : $('#employee_search_id').val(),
                    employee_name : $('#employee_search_employee_name').val(),
                    type  : 'true',
                    employee_retire:employee_retire,
                },
                success:function(result){
                    $('#index-list').html(result);
                }, error:function(jaXHR,testStatus,error){
                    ajaxErrorAction(jaXHR,testStatus,error)
                }
            });
        }
        $(function() {
            initPageContent();
            initAddModel();
        });
    </script>
@endsection
