let position_selector='select[name=position]';
let table_selector = '#invoices-table';
sort_id_name = 'accounts_invoices.id';
$(function () {
    initDatePicker('#startAndEndDate',function () {
        $(table_selector).DataTable().draw();
    });
    initDataTable();
    adjustSidebarForFixedHeader();
    showHeadButton();
    $('#exclude').on('show.bs.modal',function () {
        if($('select[name=position]').val()==1){
            $(this).find('.invoice-print').show();
        }
    })
    $('#exclude').on('hidden.bs.modal',function () {
        $(this).find('.invoice-print').hide();
        $(this).find('.invoice-print input').first().prop('checked',true);
        $(this).find('.invoice-print input').last().prop('checked',false);
    })
});

function whenNotSelected(i,shift_flag) {
    ableInput($(table_selector+' tbody').find('tr').eq(i).find('td.select-checkbox').first(),shift_flag,true);
}

function whenSelected(i,shift_flag) {
    ableInput($(table_selector+' tbody').find('tr').eq(i).find('td.select-checkbox').first(),shift_flag);
}

function whenClicked(obj,shift_flag) {
    showOptions(obj,true);
}

function hideInput(){
    $(table_selector).find('input').addClass('disable-input');
}

$(document).keydown(function (event) {
    if(event.keyCode===9){
        event.preventDefault();
        if($(':focus').attr('able-tab')===1){
            let input = $('input[able-tab=1]:not(.disable-input)');
            let index = input.index($(':focus'));
            if(index===input.length-1){
                index = -1;
            }
            input.eq(index+1).focus();
        }
    }
});
function calcSum(table) {
    let columnNumber=5;
    let columnNumberTwo=7;
    if(ONLY_VIEW){
        columnNumber=4;
        columnNumberTwo=6;
    }
    if($(position_selector).val()===undefined){
        columnNumber=3;
    }
    const sumA = table.column(columnNumber).data().reduce(function (a, b) {
        a = typeof a == 'number' ? a : 0;
        b = parseInt($(b).html().replace(REG,''));
        return a+b
    }, 0);
    if($(position_selector).val()!==undefined){
        const sumB = table.column(columnNumberTwo).data().reduce(function (a, b) {
            a = typeof a == 'number' ? a : 0;
            b = parseInt($(b).get(0).value.replace(REG,''));
            return a+b
        }, 0);
        $(table.column(7).footer()).html(CURRENCY+sumB.toLocaleString());
    }
    $(table.column(columnNumber-1).footer()).html('<div class="w-100 text-right">合計</div>');
    $(table.column(columnNumber).footer()).html(CURRENCY+sumA.toLocaleString());
}

function whenAllSelected() {
    $('#approve_invoice').addClass('hide');
    $(table_selector+' tbody tr').each(function () {
        const index = $(this).index();
        if(!trMap.has(index)){
            trMap.set(index,$(this).clone());
        }
        $(this).find('input').removeClass('disable-input');
        if($(this).find('td:nth-of-type(9)').text()==='承認待'){
            $(this).find('input').addClass('disable-input');
            $('#approve_invoice').removeClass('hide');
        }
    });
    showOptions();
    // $('.return_btn').addClass('return_btn_change');
    // $("#options").removeClass("hide");
    // $("#options span").removeClass("invisible");
}
function whenAllNotSelected() {
    // $('.return_btn').removeClass('return_btn_change');
    // $("#options").addClass("hide");
    // $("#options span").addClass("invisible");
    $(table_selector+' tbody tr.selected').each(function () {
        const index = $(this).index();
        recoveryWith($(this),trMap.get(index));
    });
    trMap.clear();
    $(table_selector+' input').addClass('disable-input');
    showOptions();
}
function changeTitle(position) {
    if(position!==undefined){
        let title1 = '支払期限日';
        let title2 = '支払額';
        // let title3 = '支払状態';
        if(position===POSITION_B){
            title1 = '入金予定日';
            title2 = '入金額';
            // title3 = '入金状態';
        }
        $(table_selector+' thead').find('th:nth-of-type(7)').html(title1);
        $(table_selector+' thead').find('th:nth-of-type(8)').html(title2);
        // $(table_selector+' thead').find('th:nth-of-type(9)').html(title3);
    }
}
function recoveryWith(tr,trClone) {
    tr.find('input[name=paid_total]').val(trClone.find('input[name=paid_total]').val());
}
function saveCheck() {
    let amountArr = [];
    let idArr = [];
    let statusArr = [];
    let trArr = [];
    $("tbody tr.selected").each(function () {
        const amount = parseInt($(this).find("input[name=paid_total]").val().replace(REG, ''));
        const invoice_total = $(this).find("td:nth-child(6) span").html().replace(REG, '');
        const statusKey=$(this).find('td:nth-of-type(9)').text();
        const paid_total=$(this).find("input[name=paid_total]").val();
        const id=$(this).find("input[name=id]").val();
            if(statusKey!=='承認待'){
                trArr.push(this);
                amountArr.push(paid_total);
                idArr.push(id);
                if (amount===0) {
                    statusArr.push(0);
                }else if(amount<=invoice_total && invoice_total-amount<=10000){
                    statusArr.push(1);
                }else{
                    statusArr.push(2);
                }
            }
    });
    saveModify(trArr,amountArr,idArr,statusArr);
}
/**
 * 編集の保存
 * @param trArr
 * @param amountArr
 * @param idArr
 * @param statusArr
 */
function saveModify(trArr,amountArr,idArr,statusArr) {
    const obj = lockAjax();
    const flag = $('input[name="position"]').val()==='1';
    const statusKeyVal={
        0:flag?'未支払':'未入金',
        1:flag?'支払済':'入金済',
        2:'<span style="color:red">要修正</span>',
        3:'作成中',
        4:'<span style="color:red">承認待</span>'
    }
    $.ajax({
        url: $('#save_invoice').data('route'),
        type: "post",
        data: {
            idArr: idArr,
            amountArr: amountArr,
            statusArr:statusArr,
            action:$(obj).html(),
        },
        success: function (response) {
            // trMap.clear();
            ajaxSuccessAction(response,function (response) {
                // for(let i=0;i<trArr.length;i++){
                //     $(trArr[i]).find('td:nth-of-type(9)').html(statusKeyVal[statusArr[i]]);
                //     $(trArr[i]).find('td:nth-of-type(1)').click();
                //     if(amountArr!==undefined) $(table_selector).DataTable().cell($(trArr[i]).index(),7).data('<input able-tab="1" data-sort="'+amountArr[i]+'" class="disable-input amount text-right" style="width: 100%" maxlength="8" size="10" name="paid_total" value="'+amountArr[i]+'"/>');
                //     if(statusArr[i]!==0) $(table_selector).DataTable().cell($(trArr[i]).index(),9).data(response.userName);
                //     else $(table_selector).DataTable().cell($(trArr[i]).index(),9).data('');
                // }
                $(table_selector).DataTable().draw();
                // $(table_selector+' thead tr').removeClass('selected');
                // $('.fixedHeader-floating thead tr').removeClass('selected');
                // calcSum($(table_selector).DataTable());
            });
        }, error: function (jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error);
        },complete: function () {
            unlockAjax(obj);
        }
    });
}

function saveApproveCheck() {
    let idArr = [];
    let statusArr = [];
    let trArr = [];
    $("tbody tr.selected").each(function () {
        const statusKey=$(this).find('td:nth-of-type(9)').text();
        const id=$(this).find("input[name=id]").val();
        if(statusKey==='承認待'){
            trArr.push(this);
            idArr.push(id);
            statusArr.push(0);
        }
    });
    saveModify(trArr,undefined,idArr,statusArr);
}

function approveRequest(e) {
    submitModify(e);
}

function delInvoiceClient(e) {
    $('#delete').data('route',$(e).data('href'));
    $('#delete').modal('show');
    // submitModify(e);
}

function requestCallBack(e) {
    submitModify(e);
}

function submitModify(e) {
    $.post($(e).data('href'),[],function (response) {
        ajaxSuccessAction(response,function (response) {
            $(table_selector).DataTable().draw();
        });
    });
}

/**
 * 操作項目を表示
 * @param e
 * @param able
 */
function showOptions(e,able=false) {
    var trSelected=$("tbody tr.selected");
    if((trSelected.length===1 && $(e).parent().hasClass('selected')) || (trSelected.length===0 && e==undefined)){
        $("#options").addClass("hide");
        $("#options span").addClass("invisible");
    }else{
        var statusKey;
        $("#options").removeClass("hide");
        $("#options span").removeClass("invisible");
        $('#approve_invoice').addClass('hide')
        if(!$(e).parent().hasClass('selected')){
            statusKey=$(e).parent('tr').find('td:nth-of-type(9)').text();
            if(statusKey==='承認待'){
                $('#approve_invoice').removeClass('hide');
            }
        }

        trSelected.each(function () {
            statusKey=$(this).find('td:nth-of-type(9)').text();
            if($(this).index()!==$(e).parent('tr').index()){
                if(statusKey==='承認待'){
                    $('#approve_invoice').removeClass('hide');
                }
            }
        });
    }

    if(able){
        ableInput(e);
    }
}
