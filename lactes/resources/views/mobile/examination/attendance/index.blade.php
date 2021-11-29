@extends('layouts.backend')
@section('page_title', '勤務表管理')
@section('css_append')
    <link rel="stylesheet" href="{{ asset('assets/css/lactes.mobile.css') }}">
    <style>
        .content{
            padding: 30px 0 10px!important;
        }
        .back-color-white{
            border-radius: 5px;
        }
        .attendance-info{
            border-radius: 5px;
            padding: 4px 0;
            font-family: 'Meiryo', serif !important;
            width: 100%;
            margin: 10px auto;
            background-color: white;
        }
        .attendance-file{
            text-align: center;
            width: 88px;
        }
        .download-file{
            margin: 4px;
        }
        .download-file i{
            width: 80px;
            font-size: 60px;
            color: darkgrey;
            margin: 10px 0;
        }
        img{
            height: 80px;
            width: 80px;
            margin: 4px;
        }
        .attendance-confirm{
            height: 110px;
            margin: 4px 0;
        }
        .attendance-confirm table{
            height: 100%;
        }
        .attendance-confirm th{
            padding: 0 0.75em;
            text-align: justify;
            text-align-last: justify;
        }
        .attendance-confirm td{
            text-align: left;
            width: 6em;
        }
        .attendance-btn{
            margin-left: auto;
            margin-right: 4px;
        }
        .attendance-btn button{
            width: 100%;
            margin: 7px 0;
            height: 30px;
        }
        @media only screen and (max-width: 355px){
            .attendance-btn{
                width: 100%;
            }
            .attendance-btn button{
                width: calc(50% - 6px);
            }
        }
        @media only screen and (min-width: 710px){
            div.attendance-info{
                float: left;
                width: calc( 50% - 20px );
                margin: 10px;
            }
        }
        @media only screen and (min-width: 1175px){
            div.attendance-info{
                width: calc( 33% - 20px );
            }
        }
        #confirm{
            font-size: 13px;
        }
        #confirm p{
            margin-bottom: 0.25em;
        }
        #confirm input{
            width: auto;
            display: inline;
            line-height: 1.5em;
            height: 1.5em;
            padding: 0;
            max-width: 10em;
            min-width: 5em;
        }
        #confirm .form-group{
            margin-bottom: 0;
        }
        #confirm .modal-dialog,#delete .modal-dialog{
            max-width: 350px!important;
        }
        #confirm .modal-btn{
            margin-top: 10px;
        }
        #confirm .modal-body{
            width: 301px;
            margin: 0 auto;
        }
        #confirm .btn,#delete .btn{
            width: 100%;
            font-size: 13px;
            padding: 6px 12px;
        }
    </style>
@endsection
@section('content')
    <input type="hidden" name=init_val data-currency-symbol="{{$currency}}">
    @if(sizeof($datas)==0)
        <div class="m-2 back-color-white">
            <table class="w-100">
                <tr>
                    <td class="text-center" style="height: 60px;padding: 10px;width: 0">
                        審査待ちの勤務表はありません。
                    </td>
                </tr>
            </table>
        </div>
    @endif
    @foreach($datas as $data)
        <div class="row attendance-info">
            <div class="attendance-file">
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
            </div>
            <div class="attendance-confirm">
                <table>
                    <tr>
                        <th>提出者番号</th>
                        <td>{{isset($data->employee)?$data->employee->employee_code:'なし'}}</td>
                    </tr>
                    <tr>
                        <th>提出者</th>
                        <td>{{isset($data->employee)?$data->employee->user->name:'なし'}}</td>
                    </tr>
                    <tr>
                        <th>勤務表年月</th>
                        <td>{{$data->year_and_month}}</td>
                    </tr>
                    <tr>
                        <th>勤務時間数</th>
                        <td>{{$data->working_time}}</td>
                    </tr>
                    <tr>
                        <th>精算費用</th>
                        <td>{{$data->transportation_expense}}</td>
                    </tr>
                </table>
            </div>
            <div class="attendance-btn">
                <button class="btn btn-success" onclick="toConfirmAttendance({{$data->id}},this)">承認</button>
                <button class="btn btn-danger" onclick="toDelete({{$data->id}},this)">拒否</button>
            </div>
        </div>
    @endforeach
    @include('layouts.pages.models.model_preview')
    <div class="modal custom-modal fade" id="delete" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>勤務表の拒否</h3>
                        <p>拒否してもよろしいでしょうか?</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <a href="javascript:void(0);" onclick="rejectAttendance()"
                                   class="btn btn-primary continue-btn">拒否</a>
                            </div>
                            <div class="col-6">
                                <a href="javascript:void(0);" data-dismiss="modal"
                                   class="btn btn-primary cancel-btn">{{__('Cancel')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="confirm" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group">
                        <p>提出者番号：　<span id="confirm-code"></span></p>
                        <p>提出者氏名：　<span id="confirm-name"></span></p>
                        <p>　提出年月：　<span id="confirm-yearmonth"></span></p>
                    </div>
                    <div class="form-group">
                        <label for="working_time">{{ __('勤務時間数：　') }}<input autocomplete="off" type="text" name="working_time" class="form-control float" value="" maxlength="6"></label>
                        <span class="error-message"></span>
                    </div>
                    <div class="form-group">
                        <label for="transportation_expense">{{ __('　精算費用：　') }}<input autocomplete="off" type="text" name="transportation_expense" class="form-control amount" value="" maxlength="6"></label>
                        <span class="error-message"></span>
                    </div>
                    <div class="modal-btn">
                        <div class="row">
                            <div class="col-6">
                                <button class="btn btn-success continue-btn" type="button" name="save" onclick="confirmAttendance()">承認</button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-primary cancel-btn" type="button" name="cancel" data-dismiss="modal" aria-label="Close">キャンセル</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('mobile.examination.footer')
@endsection
@section('footer_append')
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/lactes.mobile.js') }}"></script>
    <script>
        $(document).on('img','click',function (ev) {
            const obj = ev.target;
            $('#preview img').attr('src',obj.src);
        });
        let actionID = 0;
        let actionTag;
        function toDelete(id,e) {
            updateActionObj(id,e);
            $('#delete').modal('show');
        }
        function rejectAttendance() {
            $.ajax({
                url: '{{route('attendances.rejection')}}',
                type: 'post',
                data: {
                    idArr: [actionID],
                }, success: function (response) {
                    ajaxSuccessAction(response,function () {
                        removeActionObj();
                        $('#delete').modal('hide');
                    });
                }, error: function (jqXHR,testStatus,error) {
                    ajaxErrorAction(jqXHR,testStatus,error);
                }
            });
        }
        function toConfirmAttendance(id,e) {
            const table = $(e).parents('.attendance-info').find('.attendance-confirm table');
            $('#confirm-code').html(table.find('td').first().html());
            $('#confirm-name').html(table.find('td').eq(1).html());
            $('#confirm-yearmonth').html(table.find('td').eq(2).html());
            $('input[name=working_time]').val(table.find('td').eq(3).html());
            $('input[name=transportation_expense]').val(table.find('td').eq(4).html());
            $('#confirm').modal('show');
            updateActionObj(id,e);
        }
        function confirmAttendance() {
            const modal = $('#confirm');
            $.ajax({
                url: '{{route('attendances.confirmWhenMobile')}}',
                type: 'post',
                data: {
                    idArr: [actionID],
                    timeArr: [modal.find('input[name=working_time]').val()],
                    expenseArr: [modal.find('input[name=transportation_expense]').val()],
                }, success: function (response) {
                    ajaxSuccessAction(response,function () {
                        removeActionObj();
                        modal.modal('hide');
                    });
                }, error: function (jqXHR,testStatus,error) {
                    ajaxErrorAction(jqXHR,testStatus,error);
                }
            });
        }
        function updateActionObj(id,e) {
            actionID = id;
            actionTag = e;
        }
        function removeActionObj() {
            $(actionTag).parents('.attendance-info').remove();
        }
    </script>
@endsection
