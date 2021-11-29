<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
{{--    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">--}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="{{ $gcon->description }}">
    <meta name="author" content="{{ $gcon->author }}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title',config('app.name', 'Lactes'))</title>

    <!-- Open Graph Meta -->
    <meta property="og:title" content="{{ $gcon->title }}">
    <meta property="og:site_name" content="{{ $gcon->description }}">
    <meta property="og:description" content="{{ config('app.description', '') }}">
    <meta property="og:type" content="website">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <style type="text/css">
        td{
            word-break: keep-all;
            white-space:nowrap;
            border: 1px solid black;
            line-height: 25px;
        }
    </style>

    <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.media.js') }}"></script>
    <script src="{{ asset('assets/js/xlsx.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script>

        function loadModal(filename,url) {
            let myModal =
                '<div class="modal fade tab-pane active" id="preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">' +
                '<div class="modal-dialog modal-xl" style="border-radius:5px" role="document">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<h5 class="modal-title">' + filename + '</h5>' +
                '</div>' +
                '<ul class="nav nav-tabs" id="sheets"></ul>' +
                '<div id="tab-content" class="tab-content"></div>' +
                '</div></div></div>';
            $('#handout_wrap_inner').prepend(myModal);
            $('#preview').on('shown.bs.modal', function (e) {
                loadRemoteFile(url);
            })
        }
        function readWorkbookFromRemoteFile(url, callback) {
            let xhr = new XMLHttpRequest();
            xhr.open('get', url, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.responseType = 'arraybuffer';
            xhr.onload = function (e) {
                if (xhr.status == 200) {
                    let data = new Uint8Array(xhr.response)
                    let workbook = XLSX.read(data, { type: 'array' });
                    if (callback) callback(workbook);
                }
            };
            xhr.send();
        }
        function readWorkbook(workbook) {
            let sheetNames = workbook.SheetNames;
            let li,a;
            let tabHtml = '';
            for (let index = 0; index < sheetNames.length; index++) {
                let sheetName = sheetNames[index];
                let worksheet = workbook.Sheets[sheetName];
                let sheetHtml = XLSX.utils.sheet_to_html(worksheet);
                let tabid = "tab-" + index;
                let xlsid = "xlsid-" + index;
                let active = index === 0 ? "active" : '';
                li = $('<li class="nav-item"></li>');
                a = $('<a class="nav-link ' + active + '" href="#" data-target="#' + tabid + '" data-toggle="tab">' + sheetName + '</a>');
                tabHtml = '<div id="' + tabid + '" class="tab-pane ' + active + '" style="padding:10px;overflow:auto;height:600px">' +
                    '<div id="' + xlsid + '">' + sheetHtml + ' </div></div>';
                li.append(a);
                $("#sheets").append(li);
                $("#tab-content").append($(tabHtml));
            }
            $("#tab-content table").addClass("table table-bordered table-hover");
        }
        function loadRemoteFile(fileUrl) {
            readWorkbookFromRemoteFile(fileUrl, function (workbook) {
                readWorkbook(workbook);
            });
        }
        let localAccessFlag = false;
        let path='';
        let getFilePath='';
        let getCloudFilePath='';
        window.onload = function () {
            let find = '{!!$find!!}';
            find=find.replaceAll("%","%25");
            find=find.replaceAll("&","%26");
            find=find.replaceAll("+","%2B");
            find=find.replaceAll("#","%23");
            getCloudFilePath='{{route('getFileSource').'?path='}}'+find+'{{'&_='.time()}}';
            path='http://'+'{!!$ipAddr!!}'+'/getfile?path='+'{!!$find!!}'+'&type='+'{!!$type!!}'+'&_='+'{!!$time!!}';
            getFilePath = 'http://'+'{!!$ipAddr!!}'+'/getfile?path='+find+'&type='+'{!!$type!!}'+'&_='+'{!!$time!!}';
            @if(isset($status))
            printErrorMsg('ファイルが見つかりませんでした。');
            throw false;
            @endif
            @if(!\Illuminate\Support\Facades\Storage::disk('local')->exists($find))
            testForLocal();
            setTimeout(function (){
                if(localAccessFlag){
                    exist('{{$find}}');
                    getFile(getFilePath);
                }else{
                    printErrorMsg('ローカルサーバーへの接続が失敗しました！');
                }
            },300);
            @else
            getFile(getCloudFilePath);
            @endif
        }
        function getFile(path){
            @if($type == 'png' || $type == 'jpg' || $type == 'jpeg' || $type == 'svg')
            $('img').attr('src',path);
            @elseif($type == 'pdf')
            $('#handout_wrap_inner').media({
                width: '100%',
                height: '100%',
                autoplay: true,
                src:path,
            });
            @else
            let url = path;
            let filename=url.substr(url.lastIndexOf('/')+1);
            loadModal(filename,url);
            $('#preview').modal('show');
            @endif
        }
        @if(isset($ipAddr))
        function testForLocal(){
            let action = 'http://'+ '{{$ipAddr}}'+'/access/index.php';
            $.ajax({
                url: action,
                type: "get",
                success: function (res) {
                    localAccessFlag = true;
                },
                error: function (jqXHR, testStatus, error) {
                    if (jqXHR.status === 0) {
                        printErrorMsg('ローカルサーバーへの接続が失敗しました！');
                        localAccessFlag = false;
                    }
                },
                timeout: 200
            });
        }
        function exist(path){
            let flag = false;
            let action = 'http://'+ '{{$ipAddr}}'+'/exist/index.php';
            path=path.replaceAll('&amp;','&');
            $.ajax({
                url: action,
                type: "post",
                data: {path:path},
                async:false,
                success: function (res) {
                    if (res==='existed'){
                        flag = true;
                    }else if(res==='notfound'){
                        printErrorMsg('ファイルが見つかりませんでした。');
                        throw false;
                    }
                },
                error: function (jqXHR, testStatus, error) {
                    if (jqXHR.status === 0) {
                        printErrorMsg('ローカルサーバーへの接続が失敗しました！');
                        localAccessFlag = false;
                    }
                },
            });
            return flag;
        }
        @endif
        function printErrorMsg(msg){
            $('body').html('<p style="height: 100%;line-height: 100vh;text-align: center;">'+msg+'</p>');
            throw false;
        }
    </script>
</head>
@include('layouts.headers.head_end')
    <body class="h-100">
    <div id="handout_wrap_inner" class="w-100 h-100">
        <img style="margin: 0 auto" src=""/>
    </div>
    </body>
</html>
