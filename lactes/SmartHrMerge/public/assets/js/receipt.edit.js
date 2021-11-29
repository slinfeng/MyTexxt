let id;
$(function () {
    initSingleDatePicker('.datetime');
    showSeal();
    disableFormat();
    initCompanyInfoAndRemark();
    id = $('input[name=id]').val();
});

function addEditReceipt() {
    if($('input[name=id]').val()=== '0') $('input[name=id]').val(id);
    let url = $('#lop').data('store');
    let type = "POST";
    if (id !== 0 && id !== "0") {
        url = $('#lop').data('update');
        url = url.replace(':id', id);
        type = "PUT";
    }
    const obj = lockAjax();
    $.ajax({
        url: url,
        type: type,
        data: $('#aForm').serialize(),
        success: function (response) {
            ajaxSuccessAction(response,function () {
                if(id === '0') {
                    id=response[0];
                    $('input[name=id]').val(response[0]);
                }
            })
        },
        error: function (jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error);
        },complete:function () {
            unlockAjax(obj);
        }
    });
}

function changeNum(e) {
    if (e !== undefined) {
        var client_id = $(e).children("option:selected").val();
        if (client_id !== 0 && client_id !== '0') {
            var url = $(e).data('get-one-client');
            url = url.replace(':id', client_id);
            $.ajax({
                type: 'post',
                url: url,
                dataType: 'json',
                async: false,
                success: function (result) {
                    var companyName = result.client.client_name;
                    $('span[name=cname]').html(companyName);
                    $('input[name=client_name]').val(companyName);
                    $('input[name=client_name]').addClass('hide');
                    $('input[name=client_id]').val(client_id);
                },
                error: function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                }
            });
        } else {
            $('span[name=cname]').html('');
            $('input[name=client_name]').val('');
            $('input[name=client_name]').removeClass('hide');
            // $('span[name=cname]').addClass('hide');
            $('input[name=client_id]').val('');
        }
    }
}

function nameAndMemoChange(name, val) {
    $('input[name=' + name + ']').each(function () {
        this.value = val;
    });
}

function handleBeforePrint() {
    const cname_input = $('input[name=client_name]');
    if(!cname_input.is(':hidden')) cname_input.replaceWith('<span class="underline">'+cname_input.val()+'</span>');
    $('p[name=datetime]').html('領収日：' + $('input[name=receipt_date]').val()).removeClass('hide');
    $('input[name=receipt_amount]').val($('input[name=receipt_amount]').val() + '-');
    $('input[name=receipt_amount]').parent().css('font-size', '30px');
    $('.subject').css('font-size', '40px');
    var print_width = $('.print-receipt').width();
    var print_height = print_width * 0.72;
    $('.print-receipt').height(print_height);
}

function handleAfterPrint() {
    initSingleDatePicker('.datetime');
}
function getPdfName() {
    return $('input[name=name_or_memo]').val();
    $('input[name=id]').val(id);
}
