@extends('layouts.backend')
@section('title', __('初期設定').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('初期設定画面'))
@section('permission_modify','hrsetting_modify')
@section('css_append')
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/hr_setting.css') }}">
@endsection
@section('content')

    <div class="content container-fluid">

    <!-- Page Tab -->
    <div class="page-menu">
        <div class="row">
            <div class="col-sm-12">
                <ul class="nav nav-tabs nav-tabs-bottom">
                    <li class="nav-item">
{{--                        <a class="nav-link active" data-toggle="tab" href="#tab_common">共通</a>--}}
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tab_employee">社員</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab_attendances">勤務</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab_leaves" onclick="annualLeaveTableInit();">休暇</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Tab -->

    <!-- Tab Content -->
    <div class="tab-content" id="request_setting">

        <!-- common Tab -->
{{--        <div class="tab-pane show active" id="tab_common">--}}
{{--            @include('admin.HrSetting.details.common')--}}
{{--        </div>--}}
        <!-- common Tab -->

        <!-- employee Tab -->
        <div class="tab-pane show active" id="tab_employee">
            @include('admin.HrSetting.details.employee')
        </div>
        <!-- /employee Tab -->

        <!-- attendances Tab -->
        <div class="tab-pane" id="tab_attendances">
            @include('admin.HrSetting.details.attendance')
        </div>
        <!-- /attendances Tab -->

        <!-- leaves Tab -->
        <div class="tab-pane" id="tab_leaves">
            @include('admin.HrSetting.details.leaves')
        </div>
        <!-- /leaves Tab -->
    </div>
    <!-- Tab Content -->
</div>
@include('layouts.pages.models.model_delete')
@endsection
@section('footer_append')
    @include('layouts.footers.requestmanage.index_a')
    <script src="{{ asset('assets/js/Hr_setting.js') }}" defer></script>
    @yield('footer_department')
    @yield('footer_hire_type')
    @yield('footer_position_type')
    @yield('footer_retire_type')
    @yield('footer_residence_type')
@endsection
