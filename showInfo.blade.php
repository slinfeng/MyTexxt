@extends('layouts.backend')
@section('title', __('showInfo').' | '.config('app.name', __('Dashboard')))
@section('page_title', __('表示情報'))
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
    <div id="showInfo">
        <div class="row">
            <div class="col-lg-6 text-center">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">社内ニュース</h3>
                        <div class="news-area">
                            {!! $news !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title"> 社内報『SEED』</h3>
                        <div class="docs-area">
                            {!! $docs !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer_append')
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/morris/morris.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('assets/js/lactes.js') }}"></script>
    <script src="{{ asset('assets/js/showInfo.js') }}"></script>
@endsection
