@extends('layouts.backend')
@section('page_title', '休暇管理')
@section('css_append')
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select.dataTables.min.css') }}">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">

    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/css/datatables/fixedHeader.dataTables.min.css') }}">

    <!-- daterangepicker CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lactes.mobile.css') }}">
    <style type="text/css">
        .content{
            padding: 30px 0 10px!important;
        }
        .back-color-white{
            border-radius: 5px;
        }
        .svg-plus{
            height: 50px;
            width: 50px;
            display: block;
            margin: 5px;
            pointer-events: none;
        }
        td{
            padding: 3px 10px;
        }
        .leave-info{
            border-radius: 5px;
            padding: 4px 0;
            font-family: 'Meiryo', serif !important;
            width: 100%;
            margin: 10px auto;
            background-color: white;
        }
        .leaveCard{
            margin: 4px 0;
        }
        .leaveCard table{
            height: 100%;
        }
        .leaveCard th{
            padding: 0 0.75em;
            /*width: 100px!important;*/
            text-align: justify;
            text-align-last: justify;
        }
        .leaveCard td{
            text-align: left;
            width: 220px!important;
            word-break: break-word;
        }
        .leave-btn{
            margin-left: auto;
            margin-right: 4px;
            text-align: center;
        }
        .leave-btn button{
            width: 100%;
            margin: 7px 0;
            height: 30px;
        }
        @media only screen and (max-width: 440px){
            .leave-btn{
                width: 100%;
            }
            .leave-btn button{
                width: calc(50% - 6px);
            }
        }
    </style>
@endsection
@section('content')
<input type="hidden" id="url"
       url-statusChange="{{ route('leaves.statusChange',':id') }}"
       url-delLeaves="{{route('leaves.destroy',':id')}}">

@if(sizeof($leaves)>0)
@foreach($leaves as $leave)
<div class="row leave-info">
{{--    <div class="alert alert-danger"><span class="err-msg"></span></div>--}}
    <div class="leaveCard" id="leaveCard{{$leave->id}}">
        <table>
            <tr>
                <th>休暇期間：</th>
                <td>{{$leave->leave_from}}から、<br>
                    {{$leave->leave_to}}まで</td>
            </tr>
            <tr>
                <th>休暇理由：</th>
                <td>{{$leave->reason}}</td>
            </tr>
            <tr>
                <th>状態：</th>
                <td>@if($leave->status==0)
                        確認中&nbsp;&nbsp;&nbsp;&nbsp;
                    @elseif($leave->status==2)
                        拒否
                    @else
                        承認済
                    @endif</td>
            </tr>
        </table>
    </div>
    @if($leave->status==0)
    <div class="leave-btn">
        <button class="btn btn-success" onclick="window.location.href=href='{{route('leaves.edit',$leave->id)}}'">編集</button>
        <button class="btn btn-danger" onclick="toDeletedAlert({{$leave->id}});">削除</button>
    </div>
    @endif
</div>
@endforeach
@else
    <div class="m-2 back-color-white">
        <table class="w-100">
            <tr>
                <td class="text-center" style="height: 60px;padding: 10px;width: 0">
                    休暇情報がありません。
                </td>
            </tr>
        </table>
    </div>
@endif

<div class="modal custom-modal fade" id="deleted" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>{{ __('休暇の削除') }} </h3>
                    <p>{{ __('休暇を削除しますか?') }}</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <a href="javascript:void(0);" onclick="deleteLeave(this);"
                               class="btn btn-primary continue-btn">{{ __('削除') }}</a>
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

@cannot('leave_mobile_audit')
<div><a href="{{route('leaves.create')}}"><div style="display: inline-block;box-sizing: border-box;position: fixed;right: 5px;bottom: 70px;"><embed class="svg-plus" src="{{ asset('assets/svg/plus.svg') }}" type="image/svg+xml" /></div></a></div>
@endcan
@include('mobile.personal.footer')
@endsection
@section('footer_append')
    <!-- Datatable JS -->
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Slimscroll JS -->
    <script src="{{asset('assets/js/jquery.slimscroll.min.js')}}"></script>
    <!-- Select2 JS -->
    <script src="{{asset('assets/js/select2.min.js')}}"></script>
    <!-- daterangepicker JS -->
    <script src="{{asset('assets/js/moment.min.js')}}"></script>
    <script src="{{asset('assets/js/daterangepicker.min.js')}}"></script>
    <script src="{{asset('assets/js/laydate.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{ asset('assets/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-notify-3.1.3/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/lactes.mobile.js') }}"></script>
    <script type="text/javascript">
        function deleteLeave(e) {
            var delete_url = $('#url').attr('url-delLeaves');
            var id=$(e).attr('data-id');
            delete_url = delete_url.replace(':id', id);
            $.ajax({
                url: delete_url,
                type: 'delete',
                success: function (response) {
                    ajaxSuccessAction(response,function () {
                        $('#leaveCard'+id).parent().remove();
                        $('#deleted').modal('hide');
                    })
                },
                error: function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error)
                }
            });
        }

        function changeAlert(id, status) {
                var url = $('#url').attr('url-statusChange');
                url = url.replace(':id', id);
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        status: status
                    },
                    success: function (response) {
                        ajaxSuccessAction(response,function () {
                            if(status==1) $('#leaveCard'+id).find('td').last().html('状態：承認済');
                            if(status==2) $('#leaveCard'+id).find('td').last().html('状態：拒否');
                        })
                    },
                    error: function (jqXHR, testStatus, error) {
                        ajaxErrorAction(jqXHR, testStatus, error)
                    }
                });
        }

        function toDeletedAlert(id){
            $('#deleted').find('a').first().attr('data-id',id);
            $('#deleted').modal('show');
        }
    </script>
@endsection
