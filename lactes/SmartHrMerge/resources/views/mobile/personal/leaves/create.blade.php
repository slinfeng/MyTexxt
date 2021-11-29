@extends('layouts.backend')
@section('page_title', '新規休暇')
@section('css_append')
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select.dataTables.min.css') }}">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/mobileSelect.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables/fixedHeader.dataTables.min.css') }}">

    <!-- daterangepicker CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lactes.mobile.css') }}">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css')}}">
    <style type="text/css">
        .svg-plus {
            height: 50px;
            width: 50px;
            display: block;
            margin: 5px;
            pointer-events: none;
        }
        input {
            margin: 0;
            padding: 0;
        }
        input[readonly]{
            background-color: white!important;
        }
    </style>
@endsection
@section('content')
    <form method="POST" id='add_edit_form' url-storeLeaves="{{ route('leaves.store') }}"
          url-leaveDateValidate="{{route('leaves.leaveDateValidate')}}"
          url-updateLeaves="{{route('leaves.update',':id')}}"
          url-editOne="{{route('leaves.getLeaveOne',':id')}}">
        @csrf
        @if(isset($leave))
            @method('PUT')
        @endif
        <input type="hidden" name="employee_base_id" value="{{isset($employee)?$employee->id:0}}">
        <div class="row">
            <div class="form-group col-6">
                <label
                    for="created_at">{{ '請求日：'.(isset($leave)?(date('Y.m.d',strtotime($leave->created_at))):(date('Y.m.d'))) }}</label>
                <input name="created_at"
                       value="{{isset($leave)?(date('Y-m-d',strtotime($leave->created_at))):(date('Y-m-d'))}}" hidden>
                @error('created_at')
                <div class="invalid-div">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col-6">
                <label for="status">{{ '状態：'.(isset($leave)?($leave->status==1?'承認済':'確認中'):'確認中') }}</label>
                <input name="status" value="{{isset($leave)?($leave->status==1?1:0):0}}" hidden>
                @error('status')
                <div class="invalid-div">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="leave_from">{{ __('休暇開始日時') }}</label>
            <input class="form-control @error('leave_from') invalid-input @enderror " placeholder="選択"
                   value="{{isset($leave)?$leave->leave_from:date('Y-m-d 9:00')}}"
                   type="text" name="leave_from" id="leave_from" readonly autofocus required>
            @error('leave_from')
            <div class="invalid-div">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="leave_to">{{ __('休暇終了日時') }}</label>
            <input class="form-control @error('leave_to') invalid-input @enderror " placeholder="選択"
                   value="{{isset($leave)?$leave->leave_to:date('Y-m-d 18:00')}}"
                   type="text" name="leave_to" id="leave_to" readonly autofocus required>
        </div>

        <div class="form-group">
            <label for="reason">{{ __('休暇理由') }} </label>
            <textarea name="reason" type="text"
                      class="form-control @error('reason') invalid-input @enderror " rows="3"
                      placeholder="{{__('200文字まで記入してください')}}">{{isset($leave)?$leave->reason:''}}</textarea>
            <span class="error-message"></span>
        </div>
        <div class="row">
            <div class="form-group col-6" style="padding-right: 0;">
                <label for="days_of_leave">{{ __('今回休暇日数：') }}
                    <span name="days_of_leave">{{isset($leave)?$leave->days_of_leave.'日':'1日'}}</span></label>
                <input name="days_of_leave" value="{{isset($leave)?$leave->days_of_leave:1}}" hidden>
                @error('days_of_leave')
                <div class="invalid-div">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col-6" style="padding-right: 0;">
                <label for="sumDaysOfLeave">{{ __('本年度休暇総数：') }}
                    <span name="sumDaysOfLeave">{{$sumDaysOfLeave}}日</span></label>
            </div>
        </div>
        <div class="form-group">
            <label for="annualLeaveHasDays">{{ __('年休残日数：') }}
                <span name="annualLeaveHasDays">{{$annualLeaveHasDays}}日</span></label>
        </div>
        @if(isset($leave))
            <div class="submit-section m-1">
                <button class="btn btn-primary submit-btn" type="button"
                        onclick="addEditLeave({{isset($leave)?$leave->id:''}})">{{ __('保存') }}</button>
            </div>
        @else
            <div class="submit-section m-1">
                <button class="btn btn-primary submit-btn" type="button"
                        onclick="addEditLeave();return false">{{ __('申請') }}</button>
            </div>
        @endif
    </form>
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

    <!-- flatpickr JS -->
    <script src="{{asset('assets/plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{asset('assets/plugins/flatpickr/l10n/ja.js') }}"></script>

    <!-- daterangepicker JS -->
    <script src="{{asset('assets/js/moment.min.js')}}"></script>
    <script src="{{asset('assets/js/daterangepicker.min.js')}}"></script>
    <script src="{{asset('assets/js/laydate.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>

    <script src="{{ asset('assets/js/dataTables.select.min.js') }}"></script>

    <script src="{{ asset('assets/js/datatables/dataTables.fixedHeader.min.js') }}"></script>

    <script src="{{ asset('assets/js/bootstrap-notify-3.1.3/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/mobileSelect.min.js') }}"></script>

    <script src="{{ asset('assets/js/lactes.mobile.js') }}"></script>
    <script type="text/javascript">

        const dateTimeArrNew = [];
        for (let i = currYear; i >= currYear - 100; i--) {
            const year = {};
            year.value = i;
            year.childs = [];
            for (let j = 0; j <= 11; j++) {
                today2.year(i);
                today2.month(j);
                const month = {};
                month.value = today2.format('MM');
                month.childs = [];
                const days = parseInt(today2.endOf('month').format('D'));
                for (let k = 1; k <= days; k++) {
                    today2.date(k);
                    const day = {};
                    day.value = today2.format('DD');
                    day.childs = [];
                    for (let h = 9; h <= 18; h++) {
                        today2.hour(h);
                        const hour = {};
                        hour.value = today2.format('HH') + ':00';
                        day.childs.push(hour);
                    }
                    month.childs.push(day);
                }
                year.childs.push(month);
            }
            dateTimeArrNew.push(year);
        }

        const selectLeaveFrom = initDateTimeSelector('#leave_from', $('#leave_from').val(), '休暇開始日時');
        const selectLeaveTo = initDateTimeSelector('#leave_to', $('#leave_to').val(), '休暇終了日時');

        function initDateTimeSelector(e, initValue, title) {
            const position = getDatetimePosition(initValue);
            return new MobileSelect({
                trigger: e,
                title: title,
                wheels: [{
                    data: dateTimeArrNew,
                }],
                position: [position[0], position[1], position[2], position[3]],
                transitionEnd: function (indexArr, data) {
                    console.log(data);
                },
                callback: function (indexArr, data) {
                    $(e).val(data[0].value + '-' + data[1].value + '-' + data[2].value + ' ' + data[3].value);
                    var leaveFrom = moment($('input[name=leave_from]').val(), 'YYYY-MM-DD HH:mm').format('YYYY-MM-DD HH:00');
                    var leaveTo = moment($('input[name=leave_to]').val(), 'YYYY-MM-DD HH:mm').format('YYYY-MM-DD HH:00');
                    if (leaveFrom >= leaveTo) {
                        if ($(e).attr('name') === 'leave_from') {
                            leaveTo=moment(leaveFrom, 'YYYY-MM-DD HH:mm').format('YYYY-MM-DD 18:00');
                        } else {
                            leaveFrom=moment(leaveTo, 'YYYY-MM-DD HH:mm').format('YYYY-MM-DD 09:00');
                        }
                    }
                    checkLeave(leaveFrom, leaveTo);
                    changeLeaveInfo();
                },
            });
        }

        function updateDatetime(leaveStart, leaveEnd) {
            let newLeaveStart = leaveStart.format('YYYY-MM-DD HH:00');
            let newLeaveEnd = leaveEnd.format('YYYY-MM-DD HH:00');
            //表示欄の日付更新
            $("input[name=leave_from]").val(newLeaveStart);
            $("input[name=leave_to]").val(newLeaveEnd);
            changeDatetimePosition(selectLeaveTo, getDatetimePosition($('input[name=leave_to]').val()));
            changeDatetimePosition(selectLeaveFrom, getDatetimePosition($('input[name=leave_from]').val()));
        }

        function addEditLeave(id) {
            let employee_base_id = $('input[name=employee_base_id]').val();
            var leave_from = moment($('input[name=leave_from]').val(), 'YYYY-MM-DD HH:mm').format('YYYY-MM-DD HH:mm');
            var leave_to = moment($('input[name=leave_to]').val(), 'YYYY-MM-DD HH:mm').format('YYYY-MM-DD HH:mm');
            var url = $('#add_edit_form').attr('url-storeLeaves');
            if (typeof id != 'undefined') {
                url = $('#add_edit_form').attr('url-updateLeaves');
                url = url.replace(':id', id);
            }
            if (leaveDateValidate(leave_from, leave_to, employee_base_id,id)) return false;
            $.ajax({
                url: url,
                type: "POST",
                data: $('#add_edit_form').serialize(),
                success: function (response) {
                    ajaxSuccessAction(response,function () {
                        window.location.href = '{{route('leaves.index')}}';
                    })
                },
                error: function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                }
            });

        }

        function leaveDateValidate(startDate, endDate, employee_base_id, id = '') {
            startDate = moment(startDate, 'YYYY-MM-DD HH:mm').format('YYYY-MM-DD HH:mm');
            endDate = moment(endDate, 'YYYY-MM-DD HH:mm').format('YYYY-MM-DD HH:mm');
            let hasLeave = false;
            let validate_action_url = $('#add_edit_form').attr('url-leaveDateValidate');
            $.ajax({
                url: validate_action_url,
                type: "POST",
                async: false,
                data: {
                    startDate: startDate,
                    endDate: endDate,
                    employee_base_id: employee_base_id,
                    id: id
                },
                success: function (response) {
                    ajaxSuccessAction(response,function () {})
                    if (response.status !== 'success') {
                        hasLeave = true;
                    }
                }
            })
            if (hasLeave) {
                return hasLeave;
            }
        }

        function changeLeaveInfo() {
            var leave_from = moment($('input[name=leave_from]').val(), 'YYYY-MM-DD HH:mm');
            var leave_to = moment($('input[name=leave_to]').val(), 'YYYY-MM-DD HH:mm');
            if ((leave_from !== '') && (leave_to !== '')) {
                let leaveDates = calcLeave(leave_from, leave_to);
                $("input[name=days_of_leave]").val(leaveDates);
                $("span[name=days_of_leave]").html(leaveDates + '日');
            }
        }

        function getDatetimePosition(datetime) {
            datetime = moment(datetime, 'YYYY-MM-DD HH:00');
            return [parseInt(moment().add(-parseInt(datetime.format('YYYY')), 'year').format('YYYY')), parseInt(datetime.add(-1, 'month').format('MM')), parseInt(datetime.add(-1, 'day').format('DD')), parseInt(datetime.format('HH')) - 9];
        }

        function changeDatetimePosition(e, datetimePosition) {
            for (let i = 0; i < datetimePosition.length; i++) {
                e.locatePosition(i, datetimePosition[i]);
            }
        }

        function leaveDateFormat(e) {
            var leaveFrom=moment().format('YYYY-MM-DD 09:00');
            var leaveTo=moment().format('YYYY-MM-DD 18:00');
            var leaveVal=$(e).val();
            if((new Date(leaveVal).getDate())==(leaveVal.substring(8,10))){
                leaveFrom=$('input[name=leave_from]').val();
                leaveTo=$('input[name=leave_to]').val();
            }
            if (leaveFrom >= leaveTo) {
                if ($(e).attr('name') === 'leave_from') {
                    leaveTo=moment(leaveFrom, 'YYYY-MM-DD HH:mm').format('YYYY-MM-DD 18:00');
                } else {
                    leaveFrom=moment(leaveTo, 'YYYY-MM-DD HH:mm').format('YYYY-MM-DD 09:00');
                }
            }
            checkLeave(leaveFrom,leaveTo);
            changeLeaveInfo();
        }

        function checkLeave(leaveStart, leaveEnd) {
            leaveStart = moment(leaveStart, 'YYYY-MM-DD HH:00');
            leaveEnd = moment(leaveEnd, 'YYYY-MM-DD HH:00');
            checkInOneYear(leaveStart, leaveEnd);
            checkInWorkingDay(leaveStart, leaveEnd);
            checkInWorkingTime(leaveStart, leaveEnd);
            updateDatetime(leaveStart, leaveEnd);
        }
        function isMorning(hour) {
            return hour < 12;
        }

        function isAfternoon(hour) {
            return hour > 13;
        }

        function isWorkingTime(hour) {
            return hour > 9 && hour < 18;
        }

        function isWorkingDay(date) {
            return date.weekday() != 0 && date.weekday() != 6;
        }

        function isInOneYear(leaveStart, leaveEnd) {
            return leaveStart.year() == leaveEnd.year();
        }

        function checkInWorkingDay(leaveStart, leaveEnd) {
            if (!isWorkingDay(leaveStart) && !isWorkingDay(leaveEnd)) {
                changeToFriday(leaveStart);
                changeToFriday(leaveEnd);
            } else if (!isWorkingDay(leaveStart)) {
                changeToFriday(leaveStart);
            } else if (!isWorkingDay(leaveEnd)) {
                changeToFriday(leaveEnd);
            }
        }

        function checkInWorkingTime(leaveStart, leaveEnd) {
            let leaveStartHour = leaveStart.hour();
            let leaveEndHour = leaveEnd.hour();
            if (!isWorkingTime(leaveStartHour)) {
                leaveStart.hours(9);
            }
            if (!isWorkingTime(leaveEndHour)) {
                leaveEnd.hours(18);
            }
            if (leaveEndHour == 13) {
                leaveEnd.hours(12);
            }
            if (leaveStartHour == 12) {
                leaveStart.hours(13);
            }
            if (leaveStart.hour() > leaveEnd.hour() && leaveStart.day() == leaveEnd.day()) {
                leaveStart.hours(9);
                leaveEnd.hours(18);
            }
        }

        function checkInOneYear(leaveStart, leaveEnd) {
            if (!isInOneYear(leaveStart, leaveEnd)) {
                leaveEnd.year(leaveStart.year());
                leaveEnd.month(11);
                leaveEnd.date(31);
                leaveEnd.hours(18);
            }
        }

        function changeToFriday(date) {
            date.isoWeekday(5);
        }

        function calcLeave(leaveStart, leaveEnd) {
            let diffDays = leaveEnd.diff(leaveStart.format('YYYY-MM-DD'), "days");
            let leaveHours = diffDays == 0 ? calcLeaveInOneDay(leaveStart, leaveEnd) :
                calcLeaveOverOneDay(leaveStart, leaveEnd, diffDays);
            return (Math.ceil(leaveHours / 4)) / 2;
        }

        function calcLeaveInOneDay(leaveStart, leaveEnd) {
            let leaveHours = leaveEnd.hour() - leaveStart.hour();
            if (isMorning(leaveStart.hour()) && isAfternoon(leaveEnd.hour())) {
                leaveHours -= 1;
            }
            return leaveHours;
        }

        function calcLeaveOverOneDay(leaveStart, leaveEnd, diffDays) {
            let leaveHours = (leaveEnd.hour() - 9) + (18 - leaveStart.hour());
            if (isMorning(leaveStart.hour())) {
                leaveHours -= 1;
            }
            if (isAfternoon(leaveEnd.hour())) {
                leaveHours -= 1;
            }
            for (let i = 0; i < diffDays - 1; i++) {
                leaveStart.add(1, 'days');
                if (leaveStart.day() != 0 && leaveStart.day() != 6)
                    leaveHours += 8;
            }
            return leaveHours;
        }

        $(function () {
            $('input').each(
                function () {
                    $(this).focus(function () {
                        document.activeElement.blur();
                    });
                });
            });

    </script>
@endsection
