@extends('layouts.backend')
@section('title', __('Leaves').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('Leaves'))
@section('permission_modify','leave_modify')
@section('css_append')
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select.dataTables.min.css') }}">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.searchableSelect.css')}}">

    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/css/datatables/fixedHeader.dataTables.min.css') }}">

    <!-- daterangepicker CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
    <style type="text/css">
        .modal-dialog {
            width: 1000px;
            max-width: 1000px;
            margin: 1.75rem auto;
        }
        .custom-modal .modal-header {
            padding-top: 30px!important;
        }
        .dataTable{
            table-layout: fixed;
        }
        div.content {
            font-family: P Gothic !important;
        }

        .dataTables_scrollHeadInner {
            width: 100% !important;
        }
        .th{
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header mb-3">
                    <div class="row align-items-center">
                        <div class="col-5">
                            <h3 class="mb-0">{{ __('休暇管理画面') }}</h3>
                        </div>
                        <div class="col-7">
                            <div class="row">
                                <div class="col-4">
                                    <div class="layui-input-inline">
                                        <input type="text" value="{{\Carbon\Carbon::now()->year}}年度"
                                               class="btn btn-block hide" style="border-color: gray;" onchange="initTable()"
                                               id="yearSelector">
                                    </div>
                                </div>
                                <div class="col-4" style="padding-top: 5px;">
                                    <div class="row col-auto float-right ml-auto">
                                        <span style="padding-top: 4px;font-size: 16px;">{{__('退職者：')}}</span>
                                        <div class="status-toggle" style="padding-top: 5px;">
                                            <input type="checkbox" id="switch_annual" value="1" name="employeeType" class="check" onclick="initTable()">
                                            <label for="switch_annual" class="checktoggle">checkbox</label>
                                        </div>
{{--                                        <div class="onoffswitch">--}}
{{--                                            <input type="checkbox" name="employeeType" class="onoffswitch-checkbox"--}}
{{--                                                   id="switch_annual" onclick="initTable()">--}}
{{--                                            <label class="onoffswitch-label" for="switch_annual">--}}
{{--                                                <span class="onoffswitch-inner"></span>--}}
{{--                                                <span class="onoffswitch-switch" checked></span>--}}
{{--                                            </label>--}}
{{--                                        </div>--}}
                                    </div>
                                </div>
                                @can($__env->yieldContent('permission_modify'))
                                <div class="col-4">
                                    <a href="#" class="btn add-btn" id="showLeaveModalBtn"
                                       data-url="{{ route('leaves.create') }}"
                                       title="{{ __('Add leave') }}">
                                        <i class="fa fa-plus"></i> {{ __('新規作成') }}
                                    </a>
                                </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Leave Statistics -->
                <div class="col-12">
                    <!-- /Leave Statistics -->
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
                </div>
                <!-- /Search Filter -->
                <div class="col-12">
                    <input type="hidden" id="url" url-getLeaves="{{route('leaves.getLeaves')}}"
                           url-statusChange="{{ route('leaves.statusChange',':id') }}"
                           url-editLeaves="{{route('leaves.edit',':id')}}"
                           url-history="{{route('leaves.getEmployeeLeaves')}}"
                           url-delLeaves="{{route('leaves.destroy',':id')}}"
                           url-editLeave="{{route('leaves.edit',':id')}}"
                           url-editOne="{{route('leaves.getLeaveOne',':id')}}">
                    <table class="table table-striped custom-table mb-0 datatable dataTable no-footer"
                           style="margin-top: 0!important;margin-bottom: 0!important;width: 100%;table-layout: fixed;" id='leave-table'>
                        <thead>
                        <tr>
                            <th>{{__('社員番号')}}</th>
                            <th>{{__('氏名')}}</th>
                            <th>{{__('勤務年数')}}</th>
                            <th>{{__('年休累計日数')}}</th>
                            <th>{{__('年休残日数')}}</th>
                            <th>{{__('連絡状態')}}</th>
                            <th>{{__('Action')}}</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- leave Modal -->
    <div id="leave_modal" url-leaveDateValidate="{{route('leaves.leaveDateValidate')}}" url-getAnnualLeaveHasDays="{{route('leaves.getAnnualLeaveHasDays')}}" class="modal custom-modal fade"
         role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content" id="leave_modal_content">

            </div>
        </div>
    </div>

    <!-- Change leave Status Modal -->
    <div class="modal custom-modal fade" id="change_leave_status_modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>{{ __('Change Leave Status') }}</h3>
                        <p>{{ __('Are you sure want to change status?') }}</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <a href="javascript:void(0);" id="change_leave_status_btn"
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
    <!-- /Change leave Status Modal -->
@endsection

@section('footer_append')
    <!-- Datatable JS -->
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Slimscroll JS -->
    <script src="{{asset('assets/js/jquery.slimscroll.min.js')}}"></script>

    <!-- Select2 JS -->
    <script src="{{asset('assets/js/select2.min.js')}}"></script>
    <script src="{{ asset('assets/js/jquery.searchableSelect.js') }}"></script>

    <!-- daterangepicker JS -->
    <script src="{{asset('assets/js/moment.min.js')}}"></script>
    <script src="{{asset('assets/js/daterangepicker.min.js')}}"></script>
    <script src="{{asset('assets/js/laydate.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>

    <script src="{{ asset('assets/js/dataTables.select.min.js') }}"></script>

    <script src="{{ asset('assets/js/datatables/dataTables.fixedHeader.min.js') }}"></script>

    <script src="{{ asset('assets/js/bootstrap-notify-3.1.3/bootstrap-notify.min.js') }}"></script>

    <script src="{{asset('assets/js/lactes.js')}}"></script>
    <script src="{{asset('assets/js/leaves.js')}}"></script>
@endsection
