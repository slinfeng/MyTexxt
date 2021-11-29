@extends('layouts.backend')
@section('title', __('schedule').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('schedule'))
@section('css_append')
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.sileaf.schedule.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/schedule.css') }}">
    <style>
        #show-event{
            padding: 0.5em 0;
        }
        #show-event p{
            padding: 0 0.5em;
            margin: 0;
            line-height: 2em;
        }
        .select-members+.select2{
            width: 100%!important;
        }
    </style>
@endsection
@section('content')
    <div class="content container-fluid">

        <input hidden name="schedule_init_val" id="schedule_init_val"
               data-reservation-restrictions-type="{{$setting['reservation_restrictions_type']}}"
               data-anonymous-type="{{$setting['anonymous_type']}}"
               data-display-reservation-type="{{$setting['display_reservation_type']}}"
               data-duplicate-reservation-type="{{$setting['duplicate_reservation_type']}}"
               data-drag-accuracy-type="{{$setting['drag_accuracy_type']}}"
               data-palette-type="{{$setting['palette_type']}}"
               data-display-time="{{$setting['display_time']}}"
        >

    <!-- Page Tab -->
    <div class="page-menu">
        <div class="row">
            <div class="col-sm-12">
                <ul class="nav nav-tabs nav-tabs-bottom">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tab_schedule" data-member-id="" onclick="initMemberId(this,function (){scheduleDraw();});">一覧</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="item-show" data-toggle="tab" href="#tab_schedule" data-member-id="{{sizeof($members)>0?$members[0]->id:0}}" onclick="initMemberId(this,function (){scheduleDraw();});">アイテム別</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab_calendar" data-member-id="{{sizeof($members)>0?$members[0]->id:0}}" onclick="initMemberId(this,function (){calendarDraw();});">カレンダー</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Tab -->

        <!-- Tab Content -->
        <div class="tab-content" id="request_setting">
            <!-- employee Tab -->
            <div class="tab-pane show active" id="tab_schedule">
                @include('admin.schedule.details.schedule')
            </div>
            <!-- /employee Tab -->

{{--            <!-- attendances Tab -->--}}
{{--            <div class="tab-pane" id="tab_item_schedule">--}}
{{--                @include('admin.schedule.details.itemSchedule')--}}
{{--            </div>--}}
{{--            <!-- /attendances Tab -->--}}

            <!-- leaves Tab -->
            <div class="tab-pane" id="tab_calendar">
                @include('admin.schedule.details.calendar')
            </div>
            <!-- /leaves Tab -->
        </div>
        <!-- Tab Content -->
    </div>

    <div id="edit-event" class="modal custom-modal fade" role="dialog" style="display: none;" data-backdrop="static" data-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" id="edit-event-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('スケジュールを編集') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="position: relative">
                    <form data-action="{{route('schedules.saveSchedule')}}">
                        <input type="hidden" name="id" value="">
                        <div class="form-group">
                            <label for="schedule_date_arr">{{ __('日付') }}<span class="text-danger">*</span></label>
                            <input class="form-control" type="text"
                                   name="schedule_date_arr" value="{{date('Y-m-d')}}" onchange="dateSetFormat(this);" onclick="calendarSelect(this);" autofocus required>
                        </div>
                        <div class="form-group">
                            <label for="schedule_time">{{ __('時刻') }}<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="schedule_time" value="09:00 - 18:00">
                        </div>
                        <div class="form-group">
                            <label for="member">{{ __('対象アイテム') }}<span class="text-danger">*</span></label>
                            <select class="select-members form-control"
                                    name="member" multiple="multiple"></select>
                        </div>
                        <div class="form-group">
                            <label for="title">{{ __('テキスト') }}<span class="text-danger">*</span></label>
                            <input class="form-control" type="text"
                                   name="title" autofocus required >
                        </div>
                        <div class="form-group">
                            <div class="row ml-0 mr-0 mb-3">
                                <label for="user_id">{{ __('予約者（登録者）:') }}</label>
                                <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                                <span name="user_name">{{auth()->user()->name}}</span>
                                <div class="ml-auto user-checkbox">
                                    <label><input type="checkbox" name="private_type" value="1">プライベート</label>
                                    <label class="anonymous_type"><input type="checkbox" name="anonymous_type" value="1">匿名</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group color-btn">
                            <div class="row ml-0 mb-3">
                            <label for="color_id">{{ __('色:') }}</label>
                            <input name="color_id" type="hidden" value="1">
                                <button class="btn color-show" onclick="return false" style="background-color: #f13b09;margin: -5px 0 0 18px"></button>
                                <div class="col-3 color-name" style="padding-left: 5px;">#f13b09</div>
                            </div>
                            @foreach ($scheduleColorTypes as $scheduleColorType)
                                <button class="btn" onclick="changeColor(this);return false" data-id="{{$scheduleColorType->id}}" data-color="{{$scheduleColorType->css_name}}" style="background-color:{{$scheduleColorType->css_name}};"></button>
                            @endforeach
                        </div>
                        <div class="form-group">
                            <label for="remark">{{ __('備考') }}</label>
                            <textarea class="form-control" name="remark" rows="5" style=""></textarea>
                        </div>
                        <div class="submit-section m-0">
                            <button class="btn btn-primary submit-btn" type="button" onclick="saveAppendEvent(this);" data-dismiss="modal">{{ __('保存') }}</button>
                        </div>
                    </form>
                    <div id="calendar-select" style="">
                        <p>クリックで選択、再度クリックで選択解除されます。</p>
                        <div class="row m-0">
                            <table class="text-center">
                                <tr class="first-tr">
                                    <td class="text-right p-0">
                                        <span onclick="selectLastMonth()"><img src="{{ asset('assets/img/left.png') }}"></span>
                                    </td>
                                    <td colspan="5" class="year-month" data-year="" data-month="">

                                    </td>
                                    <td class="text-left p-0">
                                        <span onclick="selectNextMonth()"><img src="{{ asset('assets/img/right.png') }}"></span>
                                    </td>
                                </tr>
                                <tr class="week-name">
                                    <td>日</td>
                                    <td>月</td>
                                    <td>火</td>
                                    <td>水</td>
                                    <td>木</td>
                                    <td>金</td>
                                    <td>土</td>
                                </tr>
                            </table>
                            <div class="select-calendar-show" style="">
                                <p>▼選択されている日付</p>
                                <textarea rows="5" readonly style="width: 160px;resize: none;border: 1px grey solid;"></textarea>
                            </div>
                        </div>
                        <div class="row m-0">
                            <button type="button" class="btn-success" onclick="selectDatePush();" style="width: 85px">応用</button>
                            <button type="button" class="btn-dark" onclick="selectDateCancel();">キャンセル</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="context-menu" style="display: none;">
        <ul style="margin: 0;padding: 0;list-style: none;width: 100%;">
            <li>
                <a href="javascript:void(0);">編集</a>
            </li>
            <li>
                <a href="javascript:void(0);" data-toggle="modal"
                   data-target="#delete">削除</a>
            </li>
        </ul>
    </div>
    <div class="modal custom-modal fade" id="delete" role="dialog" data-route="{{route('schedules.deleteSchedule')}}" data-id="">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>{{ __('スケジュールの削除') }} </h3>
                        <p>{{ __('削除してもよろしいでしょうか?') }}</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <a href="javascript:void(0);" onclick="deleteSchedule();"
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
{{--    @include('admin.schedule.details.summary')--}}
@endsection
@section('footer_append')
    @yield('footer_append_schedule')
    @yield('footer_append_itemSchedule')
    @yield('footer_append_calendar')
    @include('layouts.footers.requestmanage.index_a')
    @yield('footer_append_item')
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.sileaf.schedule.js') }}"></script>
    <script src="{{ asset('assets/js/schedule.js') }}"></script>
    <script type="text/javascript">
        const id={{$id}};
        const hours=[];
        let hourCellWidth=8;
        const schedule_init_val=$('#schedule_init_val');
        let drag_accuracy=1;
        const drag_accuracy_type=schedule_init_val.data('drag-accuracy-type');
        let adjustmentBoo=false;

        $(function () {
            let hoursRange = schedule_init_val.data('display-time');
            hoursRange=hoursRange.split('～');
            const hoursStart = Number(hoursRange[0]);
            const hoursEnd = Number(hoursRange[1]);
            for(let i=hoursStart; i<=hoursEnd; i++){
                hours.push(i);
            }
            switch (schedule_init_val.data('drag-accuracy-type')) {
                case 0:
                    drag_accuracy=1;
                    break;
                case 1:
                    drag_accuracy=5;
                    break;
                case 2:
                    drag_accuracy=10;
                    break;
                case 3:
                    drag_accuracy=15;
                    break;
            }

            let date = moment().format('YYYY-MM-DD');
            selectTime=date;
            date=date.split('-');
            time=[date[0],date[1],date[2]];
            scheduleDraw();
            $('.select-members').select2({
                maximumSelectionLength:25,
                placeholder:'アイテムを選択してください。',
            });
            $('#item-div .select-td').removeClass('select-td');
            $('#item-div td[data-time='+selectTime+']').addClass('select-td');

            toDay();
            $('.put-away-span').click(function () {
                $('#item-div table').hide();
                $('.item-sidebar').addClass('put-away-div');
                setTimeout(function () {
                    $('.item-sidebar').css({'width': '0px'})
                    $('.item-sidebar').removeClass('put-away-div');

                    $('.put-away-span').hide();
                    $('.unfold-span').show();
                    scheduleDraw();
                },105)
            });
            $('.unfold-span').click(function () {
                $('.item-sidebar').addClass('unfold-div');
                setTimeout(function () {
                    $('.item-sidebar').css('width','200px')
                    $('.item-sidebar').removeClass('unfold-div');
                    $('#item-div table').show();
                    $('.put-away-span').show();
                    $('.unfold-span').hide();
                    scheduleDraw();
                },105)
            });

            $('#edit-event').on('show.bs.modal',function () {
                $('#edit-event textarea').css('height','100px');
            });

            $('#edit-event').on('hide.bs.modal',function () {
                $('#edit-event').find('input[name=id]').val('');
                $('#calendar-select').hide();
                rightMenuHide();
                scheduleDraw();
            });

            $('#delete').on('hide.bs.modal',function () {
                $('#delete').data('id','');
                rightMenuHide();
            });

            document.oncontextmenu=function (e) {
                e.preventDefault();
            }
            $(document).on('mouseup','body',function(){
                if(adjustmentBoo){
                    var length=$("#schedule-title .adjustment").css('left');
                    length=parseInt(length);
                    hourCellWidth=6+2*length/100;
                    scheduleDraw();
                }
                adjustmentBoo=false;
            });
            $(document).on('mousemove','#schedule-title',function(){
                if(adjustmentBoo){
                    scaleMove();
                }
            });
            $(document).on('mousedown','#schedule-title .adjustment',function(){
                adjustmentBoo=true;
                $(this).ondragstart = function() {
                    return false;
                };
            });

        });

        function scheduleDraw(e=drawSchedule,memberId=member_id) {
            $('#schedule').html('');
            schedule = $('#'+e).schedule({
                showEventDiv: '#show-event',
                sourceUrl: '{{route('schedule.getSchedules')}}',
                ajaxData:{
                    'id':id,
                    'date':time,
                    'memberId':memberId,
                },
                hours:hours,
                hourCellWidth:hourCellWidth,

                appendEvent: function (data) {
                    const modal = $('#edit-event');
                    let startAt=dragAccuracy(data.startAt);
                    let endAt=dragAccuracy(data.endAt);
                    modal.find('input[name=schedule_time]').val(startAt+' ～ '+endAt);
                    let selectedId;
                    // const members = this.settings.members;
                    modal.find('select[name=member] option').remove();
                    // let ajaxData=this.settings.ajaxData;
                    if(memberId=='' && member_id==''){
                        selectedId = data.item.id;
                        modal.find('input[name=schedule_date_arr]').val(selectTime);
                    }else{
                        selectedId=memberId;
                        modal.find('input[name=schedule_date_arr]').val(data.item.name);
                    }
                    const members = schedule.settings.members;
                    members.forEach(function (temp) {
                        const option = $('<option value="'+temp.id+'">'+temp.name+'</option>');
                        if(temp.id == selectedId) option.prop('selected',true);
                        modal.find('select[name=member]').append(option);
                    });
                    modal.modal('show');
                },editEvent: function (data) {
                    const modal = $('#edit-event');
                    modal.find('input[name=schedule_time]').val(data.startAt+' ～ '+data.endAt);
                    let selectedId;
                    modal.find('select[name=member] option').remove();
                    if(memberId=='' && member_id==''){
                        selectedId = data.item.id;
                        modal.find('input[name=schedule_date_arr]').val(data.schedule_date);
                    }else{
                        selectedId=memberId;
                        modal.find('input[name=schedule_date_arr]').val(data.item.name);
                    }
                    const members = schedule.settings.members;
                    modal.find('select[name=member] option').remove();
                    members.forEach(function (temp) {
                        const option = $('<option value="'+temp.id+'">'+temp.name+'</option>');
                        if(temp.id == selectedId) option.prop('selected',true);
                        modal.find('select[name=member]').append(option);
                    });
                    modal.find('input[name=id]').val(data.dataId);
                    // modal.find('input[name=schedule_date_arr]').val(data.schedule_date);
                    modal.find('input[name=title]').val(data.title);
                    modal.find('input[name=user_id]').val(data.user_id);
                    modal.find('span[name=user_name]').val(data.user_name);
                    modal.find('button[data-id='+data.color_id+']').click();
                    modal.find('textarea[name=remark]').val(data.remark);
                    modal.modal('show');
                }
            });

            $('#toggle_btn').click(function () {
                setTimeout(function () {
                    schedule.event.reOffset();
                },1000);
            });
            window.onresize = function () {
                setTimeout(function () {
                    schedule.event.reOffset();
                },1000);
            }
            schedule.InitData(schedule.settings.data);
        }

        function dragAccuracy(time) {
            time=time.split(':');
            time[1]=time[1]-(time[1]%drag_accuracy);
            time[1]='00'+time[1];
            time[1]=time[1].substring(time[1].length-2);
            return time[0]+':'+time[1];
        }

        function cancelAppendEvent() {
            schedule.cancelAppendEvent();
            $('#edit-event').modal('hide');
        }
        function saveAppendEvent(e) {
            let form = $(e).parents('form');
            let data = form.serialize();
            let url = form.data('action');
            let memberIdArr=[];
            memberIdArr = form.find('select[name=member]').val();
            data=data+'&memberIdArr='+memberIdArr;

            $.ajax({
                url:url,
                type:"post",
                data: data,
                success: function (response) {
                    ajaxSuccessAction(response,function () {
                        scheduleDraw();
                        $('#edit-event').modal('hide');
                    });
                },
                error: function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                }
            });

        }


        function deleteSchedule() {
            var deleteModal=$('#delete');
            var dataId=deleteModal.data('id');
            if(dataId!=''){
                var dataRoute=deleteModal.data('route');
                $.ajax({
                    url:dataRoute,
                    type:"post",
                    data: {'id':dataId},
                    success: function (response) {
                        ajaxSuccessAction(response,function () {
                            scheduleDraw();
                        });
                    },
                    error: function (jqXHR, testStatus, error) {
                        ajaxErrorAction(jqXHR, testStatus, error);
                    }
                });
            }
            deleteModal.modal('hide');
        }

        function changeColor(e) {
            let id = $(e).data('id');
            let color = $(e).data('color');
            let parentForm = $(e).closest('form');
            parentForm.find('input[name=color_id]').val(id);
            parentForm.find('.color-show').css('background-color',color);
            parentForm.find('.color-name').html(color);
        }

        function dateSetFormat(e) {
            var dateFormat= /^(\d{4})-(\d{2})-(\d{2})$/;
            var dateArr=$(e).val();
            var dateVal='';
            dateArr=dateArr.split(',');
            dateArr=unique(dateArr);
            $.each(dateArr,function (index,date) {
                if(dateFormat.test(date)){
                    if(index==0){
                        dateVal=date;
                    }else{
                        dateVal=dateVal+','+date;
                    }
                }
            });
            $(e).val(dateVal);
        }

        function unique(arr) {
            return Array.from(new Set(arr));
        }
    </script>
@endsection
