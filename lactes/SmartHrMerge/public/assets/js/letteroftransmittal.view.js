let table_selector = '#letteroftransmittal-table';
$(function () {
    showHeadButton();
    initDataTable();
    adjustSidebarForFixedHeader();
    initDatePicker('#startAndEndDate',function () {
        $(table_selector).DataTable().draw();
    });
    $('#client_search_btn').click(function () {
        $(table_selector).DataTable().draw();
    });
    $('#reset_search_btn').click(function () {
        $('#startAndEndDate').val('').parent('div').parent('div').removeClass('focused');
        $('#search_msg').val('').parent('div').removeClass('focused');
        $(table_selector).DataTable().draw();
    });
})

// function toEdit(e) {
//     window.location.href = $(e).data('href');
// }
// function toCreate(e) {
//     window.location.href = $(e).data('href');
// }
