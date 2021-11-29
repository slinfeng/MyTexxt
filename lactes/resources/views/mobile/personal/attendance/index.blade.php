@extends('layouts.backend')
@section('page_title', '勤務表提出')
@section('css_append')
    <link rel="stylesheet" href="{{ asset('assets/css/mobileSelect.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lactes.mobile.css') }}">
    <style>
        .attendance-history>div{
            flex-shrink: 0;
        }
        .download-file{
            font-size: 60px;
            margin-right: 0!important;
            width: 100% !important;
            text-align: center;
        }
        .download-file a{
            color: darkgrey!important;
        }
        .content {
            max-width: 500px!important;
            margin: 0 auto!important;
            display: block!important;
        }
        .content-body>*:first-of-type>button,.content-body>*:nth-of-type(4)>input{
            height: 2.5em;
            border-radius: 5px;
            background-color: #f7f7f7;
            border: 1px solid #e3e3e3;
            box-shadow: 0 0 1px 1px grey;
            color: #1f1f1f;
        }
    </style>
@endsection
@section('content')
    <input hidden name="init_val" data-currency-symbol="{{$currency}}">
    <form action="{{route('attendances.uploadFile')}}" enctype="multipart/form-data" method="post">
        @csrf
    <div class="content-body back-color-white">
        <div>
            <label>勤務年月</label>
            <button onfocus="clearErr(this)" type="button" class="attendance-selector">{{old('year_and_month','')==''?'':old('year_and_month','')}}</button>
            <input name="year_and_month" hidden value="{{old('year_and_month','')}}">
            <span class="error-message">{{$errors->getBag('default')->has('year_and_month')?$errors->getBag('default')->first('year_and_month'):''}}</span>
        </div>
        <div>
            <label>勤務表ファイル</label>
            <input type="button" class="btn btn-success btn-file-border" value="ファイルを選択" onfocus="clearErr(this)" onclick="uploadFile(this)">
{{--            <button type="button" onclick="uploadFile(this)">勤務表写真選択</button>--}}
            <input type="file" hidden name="file" onchange="showFileName(this)">
            <p class="file-name"><a href="#"></a></p>
            <span class="error-message">{{$errors->getBag('default')->has('file')?$errors->getBag('default')->first('file'):''}}</span>
            <span class="error-message">{{$errors->getBag('default')->has('confirm')?$errors->getBag('default')->first('confirm'):''}}</span>
        </div>
        <div>
            <label>勤務時間数</label>
            <input onfocus="clearErr(this)" maxlength="6"  name="working_time" class="text-center float" value="{{old('working_time','')}}" autocomplete="off">
            <span class="error-message">{{$errors->getBag('default')->has('working_time')?$errors->getBag('default')->first('working_time'):''}}</span>
        </div>
        <div>
            <label>精算費用</label>
            <input onfocus="clearErr(this)" name="transportation_expense" class="text-center amount" value="{{old('transportation_expense','')}}" autocomplete="off">
            <span class="error-message">{{$errors->getBag('default')->has('transportation_expense')?$errors->getBag('default')->first('transportation_expense'):''}}</span>
        </div>
        <div class="content-btn">
            <button style="color: white;line-height: 100%;">勤務表送信</button>
        </div>
    </div>
    </form>
    <div class="attendance-history">
        @foreach($attendances as $data)
            <div>
                <span>{{$data->year_and_month}}　</span>
                <span>{{$data->working_time}}時間</span><br/>
                @if(in_array(strtolower($data->file->type),['png','jpg','jpeg','gif']))
                    <img src="/getAttendance?url={{$data->file->path}}&_={{time()}}" alt="ファイル無" title="{{$data->file->basename}}"/>
                @elseif(strtolower($data->file->type=='txt'))
                    <div class="download-file">
                        <a href="/getAttendance?url={{$data->file->path}}&_={{time()}}"><i class="fa fa-file-text-o"></i></a>
                    </div>
                @elseif(in_array(strtolower($data->file->type),['doc','docs']))
                    <div class="download-file">
                        <a href="/getAttendance?url={{$data->file->path}}&_={{time()}}"><i class="fa fa-file-word-o"></i></a>
                    </div>
                @elseif(in_array(strtolower($data->file->type),['xls','xlsx']))
                    <div class="download-file">
                        <a href="/getAttendance?url={{$data->file->path}}&_={{time()}}"><i class="fa fa-file-excel-o"></i></a>
                    </div>
                @elseif(strtolower($data->file->type=='pdf'))
                    <div class="download-file">
                        <a href="/getAttendance?url={{$data->file->path}}&_={{time()}}"><i class="fa fa-file-pdf-o"></i></a>
                    </div>
                @endif
                <br/><span>{{$data->status==1?'承認済':($data->status==0?'承認待':'拒否された')}}　</span>
            </div>
        @endforeach
    </div>
    @include('layouts.pages.models.model_preview')
    @include('mobile.personal.footer')
@endsection
@section('footer_append')
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/mobileSelect.min.js') }}"></script>
    <script src="{{ asset('assets/js/lactes.mobile.js') }}"></script>
@endsection
<script>

</script>
