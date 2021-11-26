@extends('layouts.backend')
@section('title', __('Search').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('Search'))

@section('css_append')
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="search-result">
                <h3>Search Result Found For: <u>{{$result['keyword']}}</u></h3>
                <p>{{$result['sum']}} Results found</p>
            </div>
            <div class="search-lists">
                <ul class="nav nav-tabs nav-tabs-solid">
                    <li class="nav-item"><a class="nav-link active" href="#results_estimates" data-toggle="tab">{{__('Estimates')}}</a></li>
                    <li class="nav-item"><a class="nav-link" href="#results_invoices" data-toggle="tab">{{__('Invoices')}}</a></li>
                    <li class="nav-item"><a class="nav-link" href="#results_expenses" data-toggle="tab">{{__('Expenses')}}</a></li>
                    <li class="nav-item"><a class="nav-link" href="#results_clients" data-toggle="tab">{{__('Clients')}}</a></li>
                    <li class="nav-item"><a class="nav-link" href="#results_employees" data-toggle="tab">{{__('Employees')}}</a></li>
                    <li class="nav-item"><a class="nav-link" href="#results_confirmations" data-toggle="tab">{{__('OrderConfirmation')}}</a></li>
                    <li class="nav-item"><a class="nav-link" href="#results_assets" data-toggle="tab">{{__('Assets')}}</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="results_estimates">
                        <div class="row">
                            @if(sizeof($result['estimates'])==0)
                                <h4 class="text-center w-100">条件に満たす結果は見つかりませんでした。</h4>
                            @endif
                            @if(sizeof($result['estimates'])>0)
                                @foreach($result['estimates'] as $estimate)
                                    <div class="col-lg-4 col-sm-6 col-md-4 col-xl-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="invoice-code"><a href="{{route('estimates.edit',$estimate->id)}}">{{$estimate->est_manage_code}}</a></h4>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        作成日:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{mb_substr($estimate->created_date,0,10)}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        業務名/ファイル名:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$estimate->project_name_or_file_name}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        取引先名:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$estimate->client->client_name}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        我社立場:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$estimate->OurPositionType->our_position_type_opp_name}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        見積額（税抜）:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$estimate->estimate_subtotal}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane" id="results_invoices">
                        <div class="row">
                            @if(sizeof($result['invoices'])==0)
                                <h4 class="text-center w-100">条件に満たす結果は見つかりませんでした。</h4>
                            @endif
                            @if(sizeof($result['invoices'])>0)
                            @foreach($result['invoices'] as $invoice)
                            <div class="col-lg-4 col-sm-6 col-md-4 col-xl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="invoice-code"><a href="{{route('invoice.edit',$invoice->id)}}">{{$invoice->invoice_manage_code}}</a></h4>
                                        <div class="client-name m-b-15">
                                            <div class="sub-title">
                                                請求日:
                                            </div>
                                            <div class="text-muted">
                                                {{mb_substr($invoice->created_date,0,10)}}
                                            </div>
                                        </div>
                                        <div class="client-name m-b-15">
                                            <div class="sub-title">
                                                プロジェクト/ファイル名:
                                            </div>
                                            <div class="text-muted">
                                                {{$invoice->project_name_or_file_name}}
                                            </div>
                                        </div>
                                        <div class="client-name m-b-15">
                                            <div class="sub-title">
                                                我社立場:
                                            </div>
                                            <div class="text-muted">
                                                {{$invoice->our_position_types->our_position_type_opp_name}}
                                            </div>
                                        </div>
                                        <div class="client-name m-b-15">
                                            <div class="sub-title">
                                                取引先名:
                                            </div>
                                            <div class="text-muted">
                                                {{$invoice->client->client_name}}
                                            </div>
                                        </div>
                                        <div class="client-name m-b-15">
                                            <div class="sub-title">
                                                入金状態:
                                            </div>
                                            <div class="text-muted">
                                                @if($invoice->status == 0)未入金@endif
                                                @if($invoice->status == 1)確認済@endif
                                                @if($invoice->status == 2)要確認@endif
                                            </div>
                                        </div>
                                        <div class="client-name m-b-15">
                                            <div class="sub-title">
                                                確認者:
                                            </div>
                                            <div class="text-muted">
                                                {{$invoice->approved_by_user_id!=null?$invoice->user->name:"無"}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane" id="results_expenses">
                        <div class="row">
                            @if(sizeof($result['expenses'])==0)
                                <h4 class="text-center w-100">条件に満たす結果は見つかりませんでした。</h4>
                            @endif
                            @if(sizeof($result['expenses'])>0)
                                @foreach($result['expenses'] as $expense)
                                    <div class="col-lg-4 col-sm-6 col-md-4 col-xl-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="invoice-code"><a href="{{route('expense.edit',$expense->id)}}">{{$expense->project_manage_code}}</a></h4>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        作成日:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{mb_substr($expense->created_date,0,10)}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        業務名/ファイル名:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$expense->project_name_or_file_name}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        取引先名:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$expense->client->client_name}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        我社立場:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$expense->OurPositionType->our_position_type_name}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        見積額（税抜）:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$expense->estimate_subtotal}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane" id="results_confirmations">
                        <div class="row">
                            @if(sizeof($result['accountOrderConfirmations'])==0)
                                <h4 class="text-center w-100">条件に満たす結果は見つかりませんでした。</h4>
                            @endif
                            @if(sizeof($result['accountOrderConfirmations'])>0)
                            @foreach($result['accountOrderConfirmations'] as $accountOrderConfirmations)
                            <div class="col-lg-4 col-sm-6 col-md-4 col-xl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="invoice-code"><a href="{{route('confirmations.edit',$accountOrderConfirmations->id)}}">{{$accountOrderConfirmations->order_manage_code}}</a></h4>
                                        <div class="client-name m-b-15">
                                            <div class="sub-title">
                                                作成日:
                                            </div>
                                            <div class="text-muted">
                                                {{mb_substr($accountOrderConfirmations->created_date,0,10)}}
                                            </div>
                                        </div>
                                        <div class="client-name m-b-15">
                                            <div class="sub-title">
                                                プロジェクト/ファイル名:
                                            </div>
                                            <div class="text-muted">
                                                {{$accountOrderConfirmations->project_name_or_file_name}}
                                            </div>
                                        </div>
                                        <div class="client-name m-b-15">
                                            <div class="sub-title">
                                                取引先名:
                                            </div>
                                            <div class="text-muted">
                                                {{$accountOrderConfirmations->client->client_name}}
                                            </div>
                                        </div>
                                        <div class="client-name m-b-15">
                                            <div class="sub-title">
                                                我社立場:
                                            </div>
                                            <div class="text-muted">
                                                {{$accountOrderConfirmations->our_position_types->our_position_type_opp_name}}
                                            </div>
                                        </div>
                                        <div class="client-name m-b-15">
                                            <div class="sub-title">
                                                発注額（税抜）:
                                            </div>
                                            <div class="text-muted">
                                                {{$accountOrderConfirmations->estimate_subtotal}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane" id="results_clients">
                        <div class="row">
                            @if(sizeof($result['clients'])==0)
                                <h4 class="text-center w-100">条件に満たす結果は見つかりませんでした。</h4>
                            @endif
                            @if(sizeof($result['clients'])>0)
                                @foreach($result['clients'] as $client)
                                    <div class="col-lg-4 col-sm-6 col-md-4 col-xl-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="invoice-code"><a href="{{route('clients.edit',$client->id)}}">{{$client->client_name}}</a></h4>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        提携開始日:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{mb_substr($client->cooperation_start,0,10)}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        会社住所:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$client->client_address}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        契約形態:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$client->ContractType->contract_type_name}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        我社立場:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$client->OurPositionType->our_position_type_name}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        備考:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$client->remark}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane" id="results_employees">
                        <div class="row">
                            @if(sizeof($result['employees'])==0)
                                <h4 class="text-center w-100">条件に満たす結果は見つかりませんでした。</h4>
                            @endif
                            @if(sizeof($result['employees'])>0)
                                @foreach($result['employees'] as $employee)
                                    <div class="col-lg-4 col-sm-6 col-md-4 col-xl-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="invoice-code"><a href="{{route('employees.edit',$employee->id)}}">{{$employee->name}}</a></h4>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        性別:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$employee->sex}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        参加日:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{mb_substr($employee->date_hire,0,10)}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        メール:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$employee->mail}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        携帯電話:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$employee->tel}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane" id="results_assets">
                        <div class="row">
                            @if(sizeof($result['assets'])==0)
                                <h4 class="text-center w-100">条件に満たす結果は見つかりませんでした。</h4>
                            @endif
                            @if(sizeof($result['assets'])>0)
                                @foreach($result['assets'] as $asset)
                                    <div class="col-lg-4 col-sm-6 col-md-4 col-xl-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="invoice-code"><a href="{{route('companyassets.edit',$asset->id)}}">{{$asset->manage_code}}</a></h4>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        資産分類コード:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$asset->number}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        種類:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$asset->type}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        メーカー:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$asset->maker}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        型番:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$asset->model}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        シリアル番号:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$asset->serial_number}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        納品日:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{mb_substr($asset->delivery_date,0,10)}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        その他情報:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$asset->infos}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        保管場所:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$asset->storage}}
                                                    </div>
                                                </div>
                                                <div class="client-name m-b-15">
                                                    <div class="sub-title">
                                                        備考:
                                                    </div>
                                                    <div class="text-muted">
                                                        {{$asset->remark}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
