@extends('layouts.backend')
@section('title', __('Dashboard').' | '.config('app.name', __('Dashboard')))
@section('page_title', __('Dashboard'))
@section('css_append')
    <link rel="stylesheet" href="{{ asset('assets/plugins/morris/morris.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-icons/bootstrap-icons.css') }}">
    <style>
        #statistical .progress{
            height: 8px;
        }
        #statistical-graph .card-body{
            overflow-x: auto;
            padding-right: 0;
            padding-left: 0;
        }
        .graph-select{
            font-family: 'bootstrap-icons', 'sans-serif';
            font-size: 0.8em;
            position: absolute;
            right: 20px;
            padding: 0.25em;
            height: 2em;
        }
        .news-area{
            text-align: left;
            height: 176px;
            overflow-y: scroll;
        }
        .news_each{
            text-align: left;
        }
        .news_each_time{
            display: inline-block;
            color: #ff0000;
            margin-right: 1em;
        }
        .news_each_link{
            display: inline-block;
        }
        .content{
            color: #464547;
        }
        .content a{
            color: #145b7d;
            text-decoration: none;
            margin: 0;
            padding: 0;
            vertical-align: baseline;
            background: transparent;
            line-height: 1.2em;
            font-family: Arial,sans-serif;
        }
        .content a:hover {
            font-family: Arial, sans-serif;
            color: #ff0000;
            text-decoration: underline;
        }
        .docs-area{
            text-align: left;
            height: 176px;
            overflow-y: scroll;
        }
        .docs-area img{
            margin-right: 0.5em;
        }
        .news-yahoo ul{
            text-decoration: none;
        }
        .news-yahoo li{
            float: left;
            list-style: none;
        }
        .news-yahoo .jdPwFv {
            display: flex;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            justify-content: center;
            flex-direction: column;
            overflow: hidden;
            position: relative;
            background-color: rgb(239, 239, 239);
            height: 60px;
            width: 60px;
        }
        .news-yahoo .jidbQj {
            vertical-align: middle;
            user-select: none;
            max-height: calc(61px);
            max-width: calc(61px);
        }
        .news-yahoo .newsFeed_item_thumbnail{
            margin-right: 0.5em;
        }
        .news-yahoo .newsFeed_item_text {
            -ms-flex-preferred-size: 0;
            flex-basis: 0%;
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            -ms-flex-negative: 1;
            flex-shrink: 1;
        }
        .news-yahoo .ckdsaO {
            display: flex;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            justify-content: center;
            flex-direction: column;
            overflow: hidden;
            position: relative;
            background-color: rgb(0, 0, 0);
            height: 60px;
            width: 60px;
        }
        .news-yahoo .newsFeed_item_link {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            padding: 12px;
            text-decoration: none;
        }
        .news-yahoo img{
            width: 60px;
        }
        #div-coins{
            overflow: auto;
            min-height: 30em;
            max-height: 34em;
        }
        #coin-id{
            float: right;
            width: 10em;
        }
        #coin-period{
            outline: none;
            border: none;
            height: 100%;
        }
        #coins td,#coins th{
            padding: 0.25em 0.5em;
        }
        #coins td:first-child,#coins td:nth-child(2){
            text-align: left;
        }
        #coins td:nth-child(n+3){
            text-align: right;
        }
        #coins td:nth-child(1){
            width: 12em;
        }
        #coins td:nth-child(2){
            width: 4em;
        }
        #coins td:nth-child(3){
            width: 10em;
        }
        #coins td:nth-child(4){
            width: 12em;
        }
        #coins td:nth-child(5){
            width: 10em;
        }
        #coins td:nth-child(6){
            text-align: left;
            width: 25em;
        }
        #stocks{
            max-width: 90em;
            min-width: 90em;
            margin: 0 auto;
        }
        #stock td{
            border-right: 1px dashed;
            border-left: 1px dashed;
            border-color: lightgrey;
        }
        #stock dl{
            margin-bottom: 0;
        }
        #stock strong a{
            font-size: 13px;
        }
        #stock-profile{
            width: 60em;
            text-align: left;
            position: relative;
        }
        #stock h2{
            font-size: 1em;
            padding-left: 1em;
        }
        #stock dt{
            padding: 0.5em 0;
        }
        #stock td{
            width: 25%;
            padding: 0.25em 0.5em;
        }
        #fx-info{
            text-align: left;
            padding-left: 2em;
            width: calc( 100% - 60em );
        }
        #fx-info .rateup,#fx-info .ratedown,#fx-info .rateListArea a,#fx-info .moreBox a{
            float: left;
        }
        #fx-info .ratedown,#fx-info .rateListArea a{
            margin-left: 2em;
        }
        #fx-info .moreBox a{
            margin-top: -2em;
        }
        #fx-info .chartInner{
            margin-bottom: 2em;
        }
        #fx-info .chartInner h2,#fx-info .marketInner h2,#fx-info .fundInner h2{
            font-size: 20px;
        }
        .ymuiTitle:nth-of-type(1){
            display: none;
        }
        .floatL{
            float: left;
        }
        .floatR{
            float: right;
        }
        .greenFin{
            color: #3a932e;
            font-family: "MS PGothic";
        }
        .redFin{
            color: #e46062;
            font-family: "MS PGothic";
        }
        #profile-info{
            bottom: 0;
            position: absolute;
        }
        #profile-info ul{
            padding: 0;
        }
        #profile-info li{
            list-style: none;
            text-align: left;
            padding: 0.5em 0;
        }
    </style>
@endsection
@section('content')
    @canany(['in-house_information_statistics','annual_change_statistics','sales_information_statistics','business_information','cryptocurrency','stock_information','economic_news','international_news'])
        @can('in-house_information_statistics')
            <div class="row">
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget">
                        <div class="card-body">
                    <span class="dash-widget-icon">
                        <i class="fa fa-user"></i>
                    </span>
                            <div class="dash-widget-info">
                                <h3>{{$client_alliance['sum']}}</h3>
                                <span>取引先（提携）</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <span class="dash-widget-icon"><i class="fa fa-user"></i></span>
                            <div class="dash-widget-info">
                                <h3>{{$client_cooperation['sum']}}</h3>
                                <span>取引先（協力）</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget">
                        <div class="card-body">
                    <span class="dash-widget-icon">
                        <svg t="1628038547866" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1218" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30"><defs><style type="text/css"></style></defs><path d="M958.122667 854.869333a266.24 266.24 0 0 0-115.029334-164.693333 227.84 227.84 0 0 0 111.445334-198.314667 221.866667 221.866667 0 0 0-182.442667-223.573333A282.794667 282.794667 0 0 0 512 86.698667a282.453333 282.453333 0 0 0-260.266667 181.76A221.866667 221.866667 0 0 0 69.290667 491.861333a228.352 228.352 0 0 0 111.786666 198.485334 264.874667 264.874667 0 0 0-115.2 164.693333 27.818667 27.818667 0 0 0 19.456 34.133333 31.232 31.232 0 0 0 6.826667 0A27.477333 27.477333 0 0 0 119.466667 869.205333c21.333333-85.333333 85.333333-147.114667 155.989333-151.722666h8.362667a27.648 27.648 0 0 0 27.136-28.16 27.648 27.648 0 0 0-27.306667-28.16h-9.557333a165.888 165.888 0 0 1-149.674667-169.301334 168.448 168.448 0 0 1 110.592-161.450666 319.317333 319.317333 0 0 0-4.266667 51.2A296.96 296.96 0 0 0 392.533333 649.557333a343.893333 343.893333 0 0 0-168.277333 225.109334 28.672 28.672 0 0 0 19.626667 34.133333 29.866667 29.866667 0 0 0 6.826666 0.853333 27.306667 27.306667 0 0 0 26.112-21.162666c29.866667-119.466667 118.442667-204.8 220.501334-211.968h13.824c155.306667 0 281.770667-132.437333 281.770666-295.253334a324.266667 324.266667 0 0 0-4.266666-51.2 168.277333 168.277333 0 0 1 110.592 161.621334A165.546667 165.546667 0 0 1 750.933333 660.992h-9.728a27.648 27.648 0 0 0-27.136 27.989333 27.477333 27.477333 0 0 0 7.68 19.797334 26.794667 26.794667 0 0 0 19.285334 8.704L750.933333 716.8c70.997333 5.12 133.802667 65.877333 154.965334 151.210667a27.136 27.136 0 0 0 26.282666 21.504 28.16 28.16 0 0 0 26.112-35.328zM512 620.714667c-125.44 0-227.328-107.178667-227.328-238.933334s99.84-236.544 223.232-238.933333H512c125.44 0 227.498667 107.178667 227.498667 238.933333s-102.058667 238.933333-227.498667 238.933334z" fill="#ff9b44" p-id="1219"></path><path d="M670.549333 674.986667a26.794667 26.794667 0 0 0-20.650666-4.949334 27.306667 27.306667 0 0 0-17.066667 11.605334 29.184 29.184 0 0 0 6.485333 39.082666 296.789333 296.789333 0 0 1 107.349334 167.936 27.648 27.648 0 0 0 26.282666 21.333334h7.168a28.330667 28.330667 0 0 0 19.114667-34.133334 354.986667 354.986667 0 0 0-128.682667-200.874666zM637.098667 465.749333a17.066667 17.066667 0 0 0-13.141334-3.584 17.066667 17.066667 0 0 0-11.776 6.997334 129.194667 129.194667 0 0 1-83.968 51.2 131.925333 131.925333 0 0 1-16.213333 1.194666 125.098667 125.098667 0 0 1-51.2-11.264 120.32 120.32 0 0 1-41.642667-32.426666 17.066667 17.066667 0 0 0-25.429333 0 18.602667 18.602667 0 0 0-1.194667 24.405333 156.842667 156.842667 0 0 0 72.704 48.298667l14.336 3.072a136.533333 136.533333 0 0 0 29.354667 3.242666H512q10.410667 0 20.992-1.536a165.034667 165.034667 0 0 0 107.349333-64.682666 18.432 18.432 0 0 0-3.242666-24.917334z" fill="#ff9b44" p-id="1220"></path></svg>
                    </span>
                            <div class="dash-widget-info">
                                <h3>{{$employee['sum']}}</h3>
                                <span>社員</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <span class="dash-widget-icon"><i class="fa fa-diamond"></i></span>
                            <div class="dash-widget-info">
                                <h3>{{$asset['sum']}}</h3>
                                <span>設備</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        @can('annual_change_statistics')
            <select id="fiscal-year" onchange="drawStatistical(this)">
                @foreach($fiscal_year_arr as $temp)
                    <option value="{{$temp}}">{{$temp}}年度</option>
                @endforeach
            </select>
            <div class="row" id="statistical">
                <div class="col-md-12">
                    <div class="card-group m-b-30">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <span class="d-block">新規取引先（提携）</span>
                                    </div>
                                    <div>
                                        <span class="text-success" id="per-add1">+{{$client_alliance['per_add']}}%</span>
                                    </div>
                                </div>
                                <h3 class="mb-3" id="add1">{{$client_alliance['add']}}</h3>
                                <p class="mb-0">高位優先<span class="float-right text-success" id="high1">{{$client_alliance['high']}}</span></p>
                                <div class="progress mb-2">
                                    <div class="progress-bar bg-success" id="per-high1" role="progressbar" style="width: {{$client_alliance['per_high']}}%;" data-toggle="tooltip" data-original-title="{{$client_alliance['per_high']}}%"></div>
                                </div>
                                <p class="mb-0">取引先（提携）合計：<span id="sum1">{{$client_alliance['sum']}}</span></p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <span class="d-block">新規取引先（協力）</span>
                                    </div>
                                    <div>
                                        <span class="text-success" id="per-add2">+{{$client_cooperation['per_add']}}%</span>
                                    </div>
                                </div>
                                <h3 class="mb-3" id="add2">{{$client_cooperation['add']}}</h3>
                                <p class="mb-0">高位優先<span class="float-right text-success" id="high2">{{$client_cooperation['high']}}</span></p>
                                <div class="progress mb-2">
                                    <div class="progress-bar bg-success" id="per-high2" role="progressbar" style="width: {{$client_cooperation['per_high']}}%;" data-toggle="tooltip" data-original-title="{{$client_cooperation['per_high']}}%"></div>
                                </div>
                                <p class="mb-0">取引先（協力）合計：<span id="sum2">{{$client_cooperation['sum']}}</span></p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <span class="d-block">新入社員</span>
                                    </div>
                                    <div>
                                        <span class="text-success" id="per-add3">+{{$employee['per_add']}}%</span>
                                    </div>
                                </div>
                                <h3 class="mb-3" id="add3">{{$employee['add']}}</h3>
                                <p class="mb-0">在職<span class="float-right text-success" id="office">{{$employee['office']}}</span></p>
                                <div class="progress mb-2">
                                    <div class="progress-bar bg-success" id="per-office" role="progressbar" style="width: {{$employee['per_office']}}%;" data-toggle="tooltip" data-original-title="{{$employee['per_office']}}%"></div>
                                </div>
                                <p class="mb-0">社員合計：<span id="sum3">{{$employee['sum']}}</span></p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <span class="d-block">新規設備</span>
                                    </div>
                                    <div>
                                        <span class="text-success" id="per-add4">+{{$asset['per_add']}}%</span>
                                    </div>
                                </div>
                                <h3 class="mb-3" id="add4">{{$asset['add']}}</h3>
                                <p class="mb-0">未使用<span class="float-right text-success" id="available">{{$asset['available']}}</span></p>
                                <div class="progress mb-2">
                                    <div class="progress-bar bg-success" role="progressbar" id="per-available" style="width: {{$asset['per_available']}}%;" data-toggle="tooltip" data-original-title="{{$asset['per_available']}}%"></div>
                                </div>
                                <p class="mb-0">設備合計：<span id="sum4">{{$asset['sum']}}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        @can('sales_information_statistics')
            <div id="statistical-graph" class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 col-lg-6 text-center">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">十年間売上
                                        <select class="graph-select" onchange="changeGraph(0,this)">
                                            <option value="1" @if(\Illuminate\Support\Facades\Auth::user()->home_chart_type[0]==1) selected @endif>&#xf3f2;</option>
                                            <option value="2" @if(\Illuminate\Support\Facades\Auth::user()->home_chart_type[0]==2) selected @endif>&#xf17a;</option>
                                        </select></h3>
                                    <div id="bar-charts"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6 text-center">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">年度売上
                                        <select class="graph-select" onchange="changeGraph(1,this)">
                                            <option value="1" @if(\Illuminate\Support\Facades\Auth::user()->home_chart_type[1]==1) selected @endif>&#xf3f2;</option>
                                            <option value="2" @if(\Illuminate\Support\Facades\Auth::user()->home_chart_type[1]==2) selected @endif>&#xf17a;</option>
                                        </select></h3>
                                    <div id="line-charts"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        @can('business_information')
            <div id="showInfo">{!! $showInfo !!}</div>
        @endcan

        {{--    <div class="row">--}}
        {{--        <div class="col-md-12">--}}
        {{--            <div class="row">--}}
        {{--                <div class="col-lg-6 text-center">--}}
        {{--                    <div class="card">--}}
        {{--                        <div class="card-body">--}}
        {{--                            <h3 class="card-title">社内ニュース</h3>--}}
        {{--                            <div class="news-area">--}}
        {{--                                {!! $news !!}--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--                <div class="col-lg-6 text-center">--}}
        {{--                    <div class="card">--}}
        {{--                        <div class="card-body">--}}
        {{--                            <h3 class="card-title"> 社内報『SEED』</h3>--}}
        {{--                            <div class="docs-area">--}}
        {{--                                {!! $docs !!}--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--    </div>--}}



        @can('cryptocurrency')
            <div class="row">
                <div class="col-12 text-center">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title" style="margin-left: 10em;">仮想通貨
                                <select id="coin-id" onchange="redrawCoinYear(this)">
                                    <option value="bitcoin">Bitcoin</option>
                                    <option value="ethereum">Ethereum</option>
                                    <option value="xrp">XRP</option>
                                    <option value="polkadot">Polkadot</option>
                                    <option value="bitcoin-cash">Bitcoin Cash</option>
                                    <option value="litecoin">Litecoin</option>
                                    <option value="ethereum-classic">Ethereum Classic</option>
                                    <option value="stellar">Stellar</option>
                                    <option value="eos">EOS</option>
                                    <option value="tezos">Tezos</option>
                                    <option value="nem">NEM</option>
                                    <option value="basic-attention-token">Basic Attention Token</option>
                                    <option value="lisk">Lisk</option>
                                    <option value="monacoin">MonaCoin</option>
                                </select></h3>
                            <div class="row">
                                <div id="div-coins" class="col-8">
                                    <table id="coins">
                                        <thead><tr>
                                            <th class="text-left">名前</th>
                                            <th class="text-left">略称</th>
                                            <th class="text-right">価格（USD）</th>
                                            <th class="text-right">出来高（24時間）</th>
                                            <th class="text-right">変動（24時間）</th>
                                            <th>サイト</th>
                                        </tr></thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="col-4 text-center">
                                    <h3 class="card-title"><select onchange="redrawCoinYear()" id="coin-period">
                                            <option value="0">一年間</option>
                                            <option value="1">半年間</option>
                                            <option value="2">三か月間</option>
                                        </select>変動</h3>
                                    <canvas height="250" id="coin-year"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        @can('stock_information')
            <div class="row">
                <div class="col-12 text-center">
                    <div class="card">
                        <div class="card-body" style="overflow: auto;">
                            <h3 class="card-title">株式情報（YAHOO）</h3>
                            <div id="stocks" class="row">
                                <div id="stock-profile">
                                    <div id="stock"></div>
                                    <div id="profile-info"></div>
                                </div>
                                <div id="fx-info"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        @can('economic_news')
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title text-center">経済ニュース「YAHOO」</h3>
                            <div class="news-yahoo">
                                {!! $news_economy !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        @can('international_news')
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title text-center">国際ニュース「YAHOO」</h3>
                            <div class="news-yahoo">
                                {!! $news_international !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    @else
        <div class="text-center font-24" style="padding-top: 35vh">
            Lactes v1.0へ、ようこそ！
        </div>
    @endcanany

    @include('layouts.pages.models.model_adjust_browser')
@endsection
@section('footer_append')
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/morris/morris.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('assets/js/lactes.js') }}"></script>
    <script>
        @can('cryptocurrency')
        drawCoinYear('bitcoin');
        drawCoins();
        @endcan
        @can('stock_information')
        drawStockInfo();
        @endcan
        $(document).ready(function () {
            @can('business_information')
            $.ajax({
                url:'//lactes.jp/assets/js/showInfo.js',
                type:"GET",
                dataType:"jsonp",
                jsonp: false,
                jsonpCallback: "showjson", //这里的值需要和回调函数名一样
                success:function(data){
                    console.log("Script loaded and executed.");
                },
                error:function (textStatus) { //请求失败后调用的函数
                    console.log(JSON.stringify(textStatus));
                }
            });
            @endcan
            makeTargetBlank('.content');
            $('.news-yahoo li.newsFeed_item').addClass('col-xl-3 col-lg-4 col-md-6 col-sm-12');
            @can('cryptocurrency')
            refresh();
            @endcan
            $(".graph-select").trigger("change");
        });
        @can('sales_information_statistics')
        const elArr = ['bar-charts','line-charts'];
        const common_options = {
            parseTime: false,
            xLabelMargin: 10,
            xLabelAngle: 50,
            barColors: ['#55ce63','#fc6075'],
            lineColors: ['#55ce63','#fc6075'],
            lineWidth: '3px',
            smooth: false,
            xkey: 'x',
            ykeys: ['y1', 'y2'],
            labels: ['請求入金額', '請求支払額'],
            resize: false,
            redraw: false,
            yLabelFormat:function(y){
                return new Intl.NumberFormat().format(y/10000)+'万円';
            },
        };
        const init_data_bar = [@foreach($year_sales['x_arr'] as $index=>$x)
        {x:'{{$x}}', y1:{{$year_sales['y1_arr'][$index]}}, y2:{{$year_sales['y2_arr'][$index]}}},
            @endforeach];
        let graph_bar = Morris.Bar($.extend({
                element: 'bar-charts',
                data: init_data_bar,
                xLabelFormat:function(x){
                    return x.label+'年度';
                },
            },common_options));
        const init_data_line = [
                @foreach($month_sales['x_arr'] as $index=>$x)
            {x:'{{$x}}', @if(str_replace(['年','月'],'',$x)<=date('Ym'))y1:{{$month_sales['y1_arr'][$index]}}, y2:{{$month_sales['y2_arr'][$index]}}@endif},
            @endforeach
        ];
        let graph_line = Morris.Line($.extend({
            element: 'line-charts',
            data: init_data_line,
            hoverCallback: function(index,options,content,row){
                if(typeof row.y1!=='undefined') return content;
            },
            common_options
        },common_options));
        const graphObjArr = [graph_bar,graph_line];
        @endcan

        const fiscal_year = '{{$fiscal_year}}';
        function makeTargetBlank(selector) {
            $(selector+' a:not([target=_blank])').attr('target','_blank');
        }
        function drawStatistical(e) {
            let year = $(e).val();
            if(year === fiscal_year){
                graphObjArr[0].setData(init_data_bar);
                graphObjArr[1].setData(init_data_line);
                year = 'curr';
            }
            $.ajax({
                url:'{{route('home.getStatistical')}}',
                data:{year:year},
                success:function (data) {
                    drawProgress(data);
                    drawGraph(data);
                },error:function (jqXHR,testStatus,error) {
                    ajaxErrorAction(jqXHR,testStatus,error);
                }
            });
        }
        function drawProgress(data) {
            $('#per-add1').html('+'+data['client_alliance']['per_add']+'%');
            let per = data['client_alliance']['per_high']+'%';
            $('#per-high1').width(per).attr('data-original-title',per);
            $('#add1').html(data['client_alliance']['add']);
            $('#sum1').html(data['client_alliance']['sum']);
            $('#high1').html(data['client_alliance']['high']);
            $('#per-add2').html('+'+data['client_cooperation']['per_add']+'%');
            per = data['client_cooperation']['per_high']+'%';
            $('#per-high2').width(per).attr('data-original-title',per);
            $('#add2').html(data['client_cooperation']['add']);
            $('#sum2').html(data['client_cooperation']['sum']);
            $('#high2').html(data['client_cooperation']['high']);
            $('#per-add3').html('+'+data['employee']['per_add']+'%');
            per = data['employee']['per_office']+'%';
            $('#per-office').width(per).attr('data-original-title',per);
            $('#add3').html(data['employee']['add']);
            $('#sum3').html(data['employee']['sum']);
            $('#office').html(data['employee']['office']);
            $('#per-add4').html('+'+data['asset']['per_add']+'%');
            per = data['asset']['per_available']+'%';
            $('#per-available').width(per).attr('data-original-title',per);
            $('#add4').html(data['asset']['add']);
            $('#sum4').html(data['asset']['sum']);
            $('#available').html(data['asset']['available']);
        }
        function drawGraph(data) {
            const month_sales = data['month_sales'];
            const year_sales = data['year_sales'];
            let line_data = [];
            let bar_data = [];
            for(let i=0;i<month_sales['x_arr'].length;i++){
                line_data.push({x:month_sales['x_arr'][i],y1:month_sales['y1_arr'][i],y2:month_sales['y2_arr'][i]});
            }
            for(let i=0;i<year_sales['x_arr'].length;i++){
                bar_data.push({x:year_sales['x_arr'][i],y1:year_sales['y1_arr'][i],y2:year_sales['y2_arr'][i]});
            }
            graphObjArr[0].setData(bar_data);
            graphObjArr[1].setData(line_data);
        }
        function changeGraph(index,el) {
            $('#'+elArr[index]).empty();
            if(el.value==='1') graphObjArr[index] = Morris.Line(graphObjArr[index].options);
            else graphObjArr[index] = Morris.Bar(graphObjArr[index].options);
            $.ajax({
                url:'{{route("setChartType")}}',
                type:"GET",
                data:{index:index,val:$(el).val()},
                success:function (data) {
                },error:function (jqXHR,testStatus,error) {
                }
            })
        }
        let myChart;
        function drawCoinYear(id) {
            const route = '{{route('home.yearCoins',':id')}}';
            $.ajax({
                url:route.replace(':id',id),
                dataType:'json',
                data:{mode:$('#coin-period').val()},
                success:function (data) {
                    const ctx = document.getElementById('coin-year');
                    if(typeof myChart !== 'undefined') myChart.destroy();
                    myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            datasets: [{
                                label: "USD",
                                backgroundColor: 'rgb(45, 180, 130)',
                                borderColor: 'rgb(45, 180, 130)',
                                data: data,
                                lineTension: 0,
                                fill: false,
                                pointRadius: 1,
                                parsing: {
                                    xAxisKey: 'date',
                                    yAxisKey: 'priceUsd'
                                },
                            }]
                        },
                        options: {
                            layout: {
                                padding: {left: 0,right: 0,top: 0,bottom: 0}},
                            legend: {display: false},
                            scales:{
                                xAxes:[{
                                    ticks: {fontColor: "white",},
                                    gridLines:{color:'rgba(255,255,255,0.5)'}
                                }],
                                yAxes:[{
                                    ticks: {fontColor: "white",},
                                    gridLines:{color:'rgba(255,255,255,0.5)'}}],
                            }
                        }
                    });
                },error:function (jqXHR,testStatus,error) {
                    ajaxErrorAction(jqXHR,testStatus,error);
                }
            });
        }
        function drawCoins() {
            $.ajax({
                url:'{{route('home.coins')}}',
                dataType:'json',
                success:function (data) {
                    const target = $('#coins tbody');
                    target.empty();
                    let tr;
                    const td = $('<td></td>');
                    data.data.forEach(function (item) {
                        tr = $('<tr></tr>');
                        td.clone().html(item.name).appendTo(tr);
                        td.clone().html(item.symbol).appendTo(tr);
                        td.clone().html(toFixed(item.priceUsd,2)+'　').appendTo(tr);
                        td.clone().html(getSum(item.volumeUsd24Hr)+'　').appendTo(tr);
                        const per = item.changePercent24Hr;
                        let html = toFixed(per,2)+'%　';
                        let css = {'color':'#3a932e'};
                        if (per>0) {
                            html = '+'+html;
                        }else{
                            css = {'color':'#e46062'};
                        }
                        td.clone().html(html).css(css).appendTo(tr);
                        td.clone().html('<a target="_blank" href="'+item.explorer+'">'+item.explorer+'</a>').appendTo(tr);
                        target.append(tr);
                    });
                    makeTargetBlank('#coins');
                },error:function (jqXHR,testStatus,error) {
                    ajaxErrorAction(jqXHR,testStatus,error);
                }
            });
        }
        function redrawCoinYear() {
            drawCoinYear($('#coin-id').val());
        }
        function toFixed(f,n) {
            const per = 10 ** n;
            return Math.round(f*per)/per;
        }
        function getSum(sum) {
            if(sum>10 ** 7) return toFixed(sum/(10 ** 6),2)+' 百万';
            else if(sum>10 ** 5) return toFixed(sum/(10 ** 4),2)+' 　万';
            else return toFixed(sum,2);
        }

        function drawStockInfo() {
            $.ajax({
                url:'{{route('home.stocks')}}',
                dataType:'json',
                success:function (data) {
                    $('#stock').append($(data.stock));
                    $('.ymuiTitle').first().css({
                        'display': 'block',
                        'font-size': '17px',
                        'font-weight': '700',
                    }).show();
                    $('#fx-info').append($(data.fx));
                    let tag = $('#fx-info .moreBox');
                    tag.eq(1).hide();
                    tag.eq(2).hide();
                    $('#profile-info').append($(data.profile));
                    makeTargetBlank('#stocks');
                },error:function (jqXHR,testStatus,error) {
                    ajaxErrorAction(jqXHR,testStatus,error);
                }
            });
        }

        function refresh() {
            setTimeout(function () {
                drawCoins();
                refresh();
            },30000);
        }
    </script>
@endsection
