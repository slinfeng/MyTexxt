@extends('layouts.backend')
@section('title', __('初期設定').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('初期設定画面'))
@section('permission_modify','requestsetting_modify')
@section('permission_modify_outside','invoice_self_modify')
@section('css_append')
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/hr_setting.css') }}">
    <style>
        .select-receiver+.select2{
            width: 100%!important;
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
                    @can($__env->yieldContent('permission_modify_outside'))
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tab_outsideInvoice" onclick="textareaHeight()">請求書</a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tab_common" onclick="textareaHeight()">共通</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab_clients" onclick="textareaHeight()">取引先</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab_estimates" onclick="textareaHeight()">見積書</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab_expense" onclick="textareaHeight()">注文書</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab_accountsOrder" onclick="textareaHeight()">注文請書</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab_invoice" onclick="textareaHeight()">請求書</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab_letterOfTransmittal" onclick="textareaHeight()">送付状</a>
                    </li>
                    @if(count($mails)>=4)
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab_mail" onclick="textareaHeight()">メール</a>
                    </li>
                    @endif
                    @endcan
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab_createSeal" onclick="textareaHeight()">createSeal</a>
                        </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Tab -->

    <!-- Tab Content -->
    <div class="tab-content" id="request_setting">
    @can($__env->yieldContent('permission_modify_outside'))
            <div class="tab-pane show active" id="tab_common">
                @include('admin.requestSetting.details.outsideInvoice')
            </div>
    @else
        <!-- common Tab -->
            <div class="tab-pane show active" id="tab_common">
                @include('admin.requestSetting.details.common')
            </div>
            <!-- common Tab -->

            <!-- clients Tab -->
            <div class="tab-pane" id="tab_clients">
                @include('admin.requestSetting.details.clients')
            </div>
            <!-- /clients Tab -->

            <!-- estimates Tab -->
            <div class="tab-pane" id="tab_estimates">
                @include('admin.requestSetting.details.estimates')
            </div>
            <!-- /estimates Tab -->

            <!-- expense Tab -->
            <div class="tab-pane" id="tab_expense">
                @include('admin.requestSetting.details.expense')
            </div>
            <!-- /expense Tab -->

            <!-- accountsOrder Tab -->
            <div class="tab-pane" id="tab_accountsOrder">
                @include('admin.requestSetting.details.accountsOrder')
            </div>
            <!-- /accountsOrder Tab -->

            <!-- invoice Tab -->
            <div class="tab-pane" id="tab_invoice">
                @include('admin.requestSetting.details.invoice')
            </div>
            <!-- /invoice Tab -->
            <!-- letterOfTransmittal Tab -->
            <div class="tab-pane" id="tab_letterOfTransmittal">
                @include('admin.requestSetting.details.letterOfTransmittal')
            </div>
            <!-- /letterOfTransmittal Tab -->
        @if(count($mails)>=4)
            <!-- mail Tab -->
                <div class="tab-pane" id="tab_mail">
                    @include('admin.requestSetting.details.mail')
                </div>
                <!-- /mail Tab -->
        @endif
            <!-- createSeal Tab -->
            <div class="tab-pane" id="tab_createSeal">
                @include('admin.requestSetting.details.createSeal')
            </div>
            <!-- /createSeal Tab -->
    @endcan

    </div>
    <!-- Tab Content -->
</div>
@include('layouts.pages.models.model_delete')

@endsection
@section('footer_append')
    @include('layouts.footers.requestmanage.index_a')
    <script src="{{ asset('assets/ttf/insyo.ttf') }}" defer></script>
    <script src="{{ asset('assets/js/request_setting.js') }}" defer></script>

@endsection
