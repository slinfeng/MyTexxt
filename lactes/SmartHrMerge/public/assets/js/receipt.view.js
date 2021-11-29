let table_selector = '#receipt-table';
$(function () {
    showHeadButton();
    initDatePicker('#startAndEndDate', function () {
        $(table_selector).DataTable().draw();
    });
    initDataTable();
    adjustSidebarForFixedHeader();

    $('#receipt_search_btn').click(function () {
        // let client_name = $('#client_search_client_name').val();
        $(table_selector).DataTable().draw();
    });
    $('#reset_search_btn').click(function () {
        $('#client_search_id').val('');
        $('#client_search_id').parent('div').removeClass('focused');
        $('#client_search_client_name').val('');
        $('#client_search_client_name').parent('div').removeClass('focused');
        $('#client_search_date').val('');
        $('#client_search_date').parent('div').parent('div').removeClass('focused');
        $('#client_search_position').val('0').trigger("change");
    });
})
