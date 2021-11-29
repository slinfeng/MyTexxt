var table = null;
const table_selector = '#leave-table';
var widthArr = ['5vw', '', '8vw', '8vw', '8vw', '8vw', '3vw'];
datatableErrorHandle();
table = $(table_selector).DataTable({
    dom: "rtip",
    scrollCollapse: true,
    paging: false,
    searching: true,
    serverSide: true,
    fixedHeader: {
        header: true,
        headerOffset: 60,
    },
    oLanguage: {
        sInfoEmpty: '総件数:0 件',
        sInfo: '総件数:_TOTAL_ 件',
        sZeroRecords: "表示するデータがありません",
        select: {
            rows: {
                _: "%d行を選択しました",
                0: ""
            }
        }
    },
    ajax: {
        url: $('#url').attr('url-getLeaves'),
        'data': function (d) {
            // d.yearSelect= $('#yearSelector').val();
            d.employeeType = $('[name="employeeType"]').is(':checked');
        },
        type: "get",
        dataType: "json"
    },
    columns: [
        {data: 'employee_code', name: 'employee_code', width: widthArr[0]},
        {data: 'apply_user', name: 'apply_user', width: widthArr[1]},
        {data: 'work_year', name: 'work_year', width: widthArr[2]},
        {data: 'annual_leave_days', name: 'annual_leave_days', width: widthArr[3]},
        {data: 'has_days', name: 'has_days', width: widthArr[4]},
        {data: 'status', name: 'status', width: widthArr[5]},
        {data: 'action', name: 'action', orderable: false, width: widthArr[6], className: 'width-action'}
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child',
    },
    order: [
        [5, 'desc']
    ]
});

window.onload = function () {
    initYearSelector();
    initTable();
    $(document).on('click', '#showLeaveModalBtn', function () {
        let url = $(this).attr('data-url');
        showModelForm(url);
    });
    adjustSidebarForFixedHeader();
};

function initTable() {
    table.draw();
}

function showModelForm(url) {
    $.ajax({
        url: url,
        beforeSend: function () {
            $('#loader').show();
        },
        success: function (result) {
            $('#leave_modal_content').html(result);
            $('#leave_modal').modal("show");
        },
        complete: function () {
            initDateRange();
            // $('input[name=annual_leave_days]').val($("#yukyu").attr('data-annual') + '日');
            $('#loader').hide();
        },
        error: function (jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error);
            $('#loader').hide();
        },
        timeout: 8000
    });
}

function addEditLeave() {
    let datetimes = $('.show input[name=datetimes]').val();
    if (datetimes === undefined) {
        datetimes = $('.show input[name=datetime]').val();
    }
    datetimes = datetimes.split("～");
    let leave_from = moment(datetimes[0], 'YYYY-MM-DD hh:mm').format('YYYY-MM-DD HH:mm');
    let leave_to = moment(datetimes[1], 'YYYY-MM-DD hh:mm').format('YYYY-MM-DD HH:mm');
    let employee_base_id = $('.show select[name=employee_base_id]').val();
    var url = $('#add_edit_form').attr('url-storeLeaves');
    var id= $('#edit_btn').attr('data-id');
    if (typeof id != 'undefined') {
        url = $('#add_edit_form').attr('url-updateLeaves');
        if(id==''){
            return false;
        }
        url = url.replace(':id', id);
    }
    if (leaveDateValidate(leave_from, leave_to, employee_base_id,id)) {
            return false;
        }
    const obj = lockAjax();
    $.ajax({
        url: url,
        type: "POST",
        data: $('#add_edit_form').serialize() + '&leave_from=' + leave_from + '&leave_to=' + leave_to,
        success: function (response) {
            if (response.status == "success") {
                $("#leave_modal").modal("hide");
                table.draw();
                $.notify(response.message);
                if (typeof id != 'undefined') {
                    $('input[name=annual_leave_days]').val(response.annualLeaveHasDays + '日');
                    historyTable.draw();
                }
            } else {
                printErrorMsg(response.message);
            }
        },
        error: function (jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error);
        }, complete: function () {
            unlockAjax(obj)
        }
    });

}

function editLeaveOne(id) {
    var url = $('#url').attr('url-editOne');
    url = url.replace(':id', id);
    $.ajax({
        url: url,
        type: "POST",
        success: function (result) {
            $('input[name=id]').val(id);
            $('input[name=employee_base_id]').val(result.employee_base_id);
            $('textarea[name=reason]').val(result.reason);
            $('input[name=datetime]').val(moment(result.leave_from, 'YYYY-MM-DD hh:mm').format('YYYY-MM-DD hh:00') + "～" + moment(result.leave_to, 'YYYY-MM-DD hh:mm').format('YYYY-MM-DD hh:00'));
            $('input[name=days_of_leave]').val(result.days_of_leave + '日');
            $('textarea[name=memo]').val(result.memo);
            $('#edit_btn').attr('data-id', id);
            if (result.status === 0) {
                $('input[type=radio]').each(function () {
                    if ($(this).val() == 0) $(this).attr("checked", "checked");
                    else $(this).removeAttr("checked");
                    $(this).removeAttr("disabled", " ");
                });
            } else {
                $('input[type=radio]').each(function () {
                    if ($(this).val() == result.status) {
                        $(this).prop("checked", true);
                    } else {
                        $(this).removeAttr("checked");
                    }
                    $(this).attr("disabled", "true");
                });
            }
        },
        error: function (jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error);
        }
    });
}

function deleteLeave(id) {
    $('#delete_leave').find('a').first().attr('data-id', id);
    $('#window-dialog-backdrop').removeClass('hide').addClass('show');
    $('#delete_leave').show();
}

function deleteLeaveAlert(e) {
    var delete_url = $('#url').attr('url-delLeaves');
    var id = $(e).attr('data-id');
    delete_url = delete_url.replace(':id', id);
    $.ajax({
        url: delete_url,
        type: 'delete',
        success: function (response) {
            ajaxSuccessAction(response, function () {
                updateLeaveDatesInfo(response);
                historyTable.draw();
                if (id == $('input[name=id]').val()) {
                    $('input[name=id]').val('');
                    $('input[name=employee_base_id]').val('');
                    $('textarea[name=reason]').val('').removeAttr("disabled");
                    $('textarea[name=memo]').val('');
                    $('#edit_btn').attr('data-id', '');
                    $('input[type=radio]').each(function () {
                        if ($(this).val() == 0) $(this).attr("checked", "checked");
                        else $(this).removeAttr("checked");
                        $(this).removeAttr("disabled", " ");
                    });
                }
                table.draw();
                cancelConfirm('#delete_leave');
            })
        }, error: function (jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error);
        }
    });
}

function cancelConfirm(e) {
    $('#window-dialog-backdrop').removeClass('show').addClass('hide');
    $(e).hide();
}

function updateLeaveDatesInfo(res) {
}

function editLeave(id) {
    var url = $('#url').attr('url-editLeaves');
    url = url.replace(':id', id);
    showModelForm(url);
}

function changeAlert(id, status) {
    $('#change_leave_status_modal').modal('show');
    $('#change_leave_status_modal').find("#change_leave_status_btn").off().click(function () {
        var url = $('#url').attr('url-statusChange');
        url = url.replace(':id', id);
        $.ajax({
            url: url,
            type: "POST",
            data: {
                status: status
            },
            success: function (response) {
                ajaxSuccessAction(response, function () {
                    $('#change_leave_status_modal').modal('hide');
                    updateLeaveDatesInfo(response);
                    table.draw();
                })
            },
            error: function (jqXHR, testStatus, error) {
                ajaxErrorAction(jqXHR, testStatus, error);
            }
        });

    });
}

function applyUserChange(e) {
    let getAnnualLeaveHasDays_url = $('#leave_modal').attr('url-getAnnualLeaveHasDays');
    var id = $(e).val();
    if (id == null || id == '') return;
    $.ajax({
        url: getAnnualLeaveHasDays_url,
        dataType: "JSON",
        async: true,
        data: {"id": id},
        type: "get",
        success: function (data) {
            $('input[name=annual_leave_days]').val(data.annualLeaveHasDays + '日');
            $('input[name=sum_days_of_leave]').val(data.sumDaysOfLeave + '日');
        }, error: function (jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error);
        }
    })
}

function leaveDateValidate(startDate, endDate, employee_base_id, id = '') {
    startDate = moment(startDate, 'YYYY-MM-DD hh:mm').format('YYYY-MM-DD HH:mm');
    endDate = moment(endDate, 'YYYY-MM-DD hh:mm').format('YYYY-MM-DD HH:mm');
    let hasLeave = false;
    let validate_action_url = $('#leave_modal').attr('url-leaveDateValidate');
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
            if (response.status == false) {
                hasLeave = true;
                printErrorMsg(response.message);
            }
        }, error: function (jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error);
        }
    })
    if (hasLeave) {
        return hasLeave;
    }
}

function initYearSelector() {
    let value = moment().format('YYYY');
    laydate.render({
        elem: '#yearSelector',
        lang: 'jp',
        type: 'year',
        theme: 'grid',
        format: 'yyyy年度',
        showBottom: false,
        change: function (val) {
            if (val == "") {
                $('#yearSelector').val(value);
            } else {
                $('#yearSelector').val(val);
            }
            table.draw();
        }
    });
}

function initDateRange() {
    let date = $('input[name="datetimes"]').val();
    if (date === undefined) {
        date = $('input[name="datetime"]').val();
    }
    let startDate = moment().format('YYYY-MM-DD 9:00');
    let endDate = moment().format('YYYY-MM-DD 18:00');
    if (date !== "" && date !== undefined) {
        startDate = moment(date.substr(0, 16), 'YYYY-MM-DD hh:mm').format('YYYY-MM-DD HH:mm');
        endDate = moment(date.substr(17), 'YYYY-MM-DD hh:mm').format('YYYY-MM-DD HH:mm');
    }
    $('input[name="datetimes"]').daterangepicker({
        showDropdowns: true,
        linkedCalendars: false,
        timePicker: true,
        timePicker24Hour: true,
        timePickerIncrement: 60,
        startDate: startDate,
        endDate: endDate,
        locale: {
            applyLabel: '応用',
            cancelLabel: 'キャンセル',
            format: 'YYYY-MM-DD HH:00',
            separator: "～",
            daysOfWeek: ['日', '月', '火', '水', '木', '金', '土'],
            monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月']
        }
    }).on('hide.daterangepicker', function (ev, picker) {
        let leaveStart = moment(picker.startDate, 'YYYY-MM-DD hh:mm');
        let leaveEnd = moment(picker.endDate, 'YYYY-MM-DD hh:mm');
        checkLeave(leaveStart, leaveEnd);
        changeLeaveInfo(leaveStart, leaveEnd);
        checkSubmit();
    });
}

function checkLeave(leaveStart, leaveEnd) {
    checkInOneYear(leaveStart, leaveEnd);
    checkInWorkingDay(leaveStart, leaveEnd);
    checkInWorkingTime(leaveStart, leaveEnd);
    updateDatetime(leaveStart, leaveEnd);
}

function updateDatetime(leaveStart, leaveEnd) {
    let newLeaveStart = leaveStart.format('YYYY-MM-DD HH:00');
    let newLeaveEnd = leaveEnd.format('YYYY-MM-DD HH:00');
    //選択欄の日付更新
    $('.show input[name=datetimes]').data('daterangepicker').setStartDate(newLeaveStart);
    $('.show input[name=datetimes]').data('daterangepicker').setEndDate(newLeaveEnd);
    //表示欄の日付更新
    $(".show input[name=datetimes]").val(newLeaveStart + '～' + newLeaveEnd);
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

function onLeaveTypeChange() {
    let leaveStart = moment($('.show input[name="datetimes"]').val().substr(0, 16), 'YYYY-MM-DD hh:mm');
    let leaveEnd = moment($('.show input[name="datetimes"]').val().substr(17), 'YYYY-MM-DD hh:mm');
    changeLeaveInfo(leaveStart, leaveEnd);
    checkSubmit();
}

function changeLeaveInfo(leaveStart, leaveEnd) {
    let leaveDates = calcLeave(leaveStart, leaveEnd);
    $(".show input[name=days_of_leave]").val(leaveDates + '日');
}

function checkSubmit() {
    let datetimes = $('.show input[name="datetimes"]').val();
    if (datetimes === undefined) {
        datetimes = $('.show input[name="datetime"]').val();
    }
    let leaveStart = moment(datetimes.substr(0, 16), 'YYYY-MM-DD hh:mm');
    let leaveEnd = moment(datetimes.substr(17), 'YYYY-MM-DD hh:mm');
    let employee_base_id = $('.show input[name=employee_base_id]').val();
    if (employee_base_id === undefined) {
        employee_base_id = $('.show select[name=employee_base_id]').val();
    }
    try {
        let id = '';
        if ($('.show input[name=id]').length > 0) {
            id = $('.show input[name=id]').val();
        }
        leaveDateValidate(leaveStart, leaveEnd, employee_base_id, id);
        // if ($('.show select[name=leave_type]').val() != '' && $('.show textarea[name=reason]').val().trim() != '') {
        //     $('.show .submit-section button').prop('disabled', false);
        // } else {
        //     $('.show .submit-section button').prop('disabled', true);
        // }
    } catch (e) {
        // $('.show .submit-section button').prop('disabled', true);
        console.log('break');
    }
}

function datatableErrorHandle() {
    $.fn.dataTable.ext.errMode = function (settings, tn, msg) {
        printErrorMsg('未知のエラーです。画面をリフレッシュしてください！');
    }
}
