$(function () {
    checkedToRadio();
});
function checkedToRadio() {
    $(document).on('change', '.radioBox', function () {
        $(this).parents('.checked-radio').find('.radioBox').prop('checked',false);
        $(this).prop('checked',true);
    });
}
