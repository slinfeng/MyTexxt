/**
 * 初期化
 */

let manage_code_selector = "input[name=invoice_manage_code]";
let date_selector = "input[name=created_date]";
let init_val_created_date = $(date_selector).val();
let init_val_manage_code = $(manage_code_selector).val();
let bankData;
const POSITION_IN = "2";
const LINE_HEIGHT = 40;
const FOLDER='請求書';
const ACCOUNT_NUM=$('#bank-accounts').find('tr').eq(2).find('td').html();
const ACCOUNT_NAME=$('#bank-accounts').find('tr').eq(3).find('td').html();
window.onload = function(){
    let for_print = window.location.href.indexOf('toPdf=true')>=0;
    if(!for_print){
        $(date_selector).each(function () {
            initSingleDatePicker(this,function () {changeNum()},"YYYY-MM-DD",true);
        });
        if(window.location.href.indexOf('create')>=0){
            let input_position = $("input[name='our_position_type']:checked");
            if(input_position.val()!==POSITION_IN)
                $("input[name='last_format']").val(1);
            disableFormat(input_position[0]);
        }
        $('input[name=pay_deadline]').each(function () {
            initSingleDatePicker(this, function () {
            }, "YYYY年MM月DD日", true,false);
        });
        if($('#form-A').length>0){
            $("input[name='employee_period[]']").each(function () {
                initDatePicker(this);
            });
            initAmountInput();
            calcAll($("input[name='unit_price_commuting_sub[]']")[0]);
            initDatePicker($("input[name=period]"),function () {},true);
            showSeal();
            ableOther($("select[name=contract_type]"));
            initCompanyInfoAndRemark();
            initTextarea();
            initBankAccounts();
        }
        changeNum();
    }else{
        initAmountInput();
        calcAll($("input[name='unit_price_commuting_sub[]']")[0]);
        showSeal();
        ableOther($("select[name=contract_type]"));
        initTextarea();
    }
    bankData = bankInfoToArray($("#bank-accounts").data('bank'));
    let bankUse =$("#bank-accounts").data('bankuse');
    if(bankUse!=undefined){
        bankInfoToSelect(bankData,0,'','','',bankUse);
    }
};

function initAmountInput(){
    $("input[name='unit_price_commuting_sub[]'][value!="+CURRENCY+"0]").each(function () {
        ableOtherSubMoney($(this));
    });
    $("input[name='unit_price_working_sub[]'][value!="+CURRENCY+"0]").each(function () {
        ableOtherSubMoney($(this));
    });
}

let tr_empty;

function handleBeforePrint() {
    let tr_employees = $('tr[name=employee_info]');
    tr_empty = tr_employees.first().clone();
    tr_empty.find('td').empty();
    tr_empty.find('td').height(LINE_HEIGHT);
    const num = tr_employees.length;
    let mainIndexArr = [];
    tr_employees.each(function (index) {
        if ($(this).find("input[name='employee_name[]']").val().trim() !== "" || $(this).find("textarea[name='employee_period_picker']").val().trim() !== "") {
            mainIndexArr.push(index);
        }
    });
    while (mainIndexArr[0] < num - 1) {
        let rowspan = 0;
        let removeStart = mainIndexArr[0] + 1;
        let removeEnd = 0;
        if (mainIndexArr.length > 1) {
            rowspan = mainIndexArr[1] - mainIndexArr[0];
            removeEnd = mainIndexArr[1] - 1;
            mainIndexArr.splice(0, 1);
        } else {
            rowspan = num - mainIndexArr[0];
            removeEnd = num - 1;
            mainIndexArr[0] = num - 1;
        }
        let td_picker = tr_employees.eq(removeStart - 1).find("td").eq(1);
        td_picker.find('textarea[name=employee_period_picker]').css('margin-top', td_picker.find('label').height() / 2 - 24);
        mergeRow(tr_employees,removeStart,removeEnd);
    }
    if (mainIndexArr[0] === num - 1) {
        tr_employees.eq(num - 1).find('td').eq(0).addClass('width-employee-name border-bottom-0');
        tr_employees.eq(num - 1).find('td').eq(1).addClass('width-employee-period border-bottom-0');
    }
}

function addEmptyLine() {
    const print = $('.print');
    const heightSum = print.height();
    const heightAll = print.width() * 1.625;
    let flag = true;
    let rows = (heightAll - heightSum) / LINE_HEIGHT;
    for (let i = rows; i > 0; i--) {
        let trc = tr_empty.clone();
        if (flag) {
            trc.find('td').first().remove();
            trc.find('td').first().attr('colspan', 2);
            trc.find('td').first().html('**以下空白**');
            flag = false;
        }
        trc.appendTo($('#invoice-details>tbody'));
    }
}

function mergeRow(tr_employees,removeStart,removeEnd) {
    let table = $('<table></table>');
    table.addClass('out-border-none');
    for (let i = removeStart-1; i <= removeEnd; i++) {
        let tr = $('<tr name="employee_info"></tr>');
        if(i === removeStart-1){
            let td = tr_employees.eq(i).find('td').eq(0).clone();
            td.attr('rowspan',removeEnd - removeStart +2);
            td.addClass('width-employee-name border-bottom-0');
            td.appendTo(tr);
            td = tr_employees.eq(i).find('td').eq(1).clone();
            td.attr('rowspan',removeEnd - removeStart +2);
            td.addClass('width-employee-period border-bottom-0');
            td.appendTo(tr);
        }
        for(let j=2;j<=4;j++){
            tr_employees.eq(i).find('td').eq(j).clone().addClass('width-employee-details').appendTo(tr);
        }
        table.append(tr);
    }
    for (let i = removeStart; i <= removeEnd; i++) {
        tr_employees.eq(i).remove();
    }
    tr_employees.eq(removeStart - 1).find("td").eq(4).remove();
    tr_employees.eq(removeStart - 1).find("td").eq(3).remove();
    tr_employees.eq(removeStart - 1).find("td").eq(2).remove();
    tr_employees.eq(removeStart - 1).find("td").eq(1).remove();
    let td = tr_employees.eq(removeStart - 1).find("td").eq(0);
    td.html(table);
    td.addClass('p-0');
    td.attr('colspan',5);
}

function handleAfterPrint() {
    initDatePicker($("input[name=period]"));
    $("input[name='employee_period[]']").each(function () {
        initDatePicker($(this));
    });
    initSingleDatePicker($(date_selector), function () {},"YYYY-MM-DD");
    initSingleDatePicker($("input[name=pay_deadline]"), function () {},"YYYY年MM月DD日");
}

/**
 * 派遣員を追加
 */
function addEmployee(e) {
    const tr = $("tr[name=employee_info]").first().clone();
    tr.find("input").each(function () {
        if ($(this).hasClass("amount")) {
            $(this).val(CURRENCY+"0");
        } else {
            $(this).val("");
        }
        if ($(this).prop("readonly") === true) {
            $(this).prop("readonly", false).show();
        }
    });
    tr.find('textarea[name="detail_content[]"]').val("");
    tr.find('textarea[name=employee_period_picker]').val('');
    initDatePicker(tr.find("input[name='employee_period[]']"));
    $(e).parents('tr').first().after(tr);
    changeLength(tr.find('textarea[name="detail_content[]"]').get(0));
    $(e).parents('tr').next().find('.linePoint').hide();
}

/**
 * 派遣員の削除
 * @param e
 */
function delTr(e) {
    if ($("tr[name=employee_info]").length > 1)
        $(e).parents("tr").first().remove();
    else{
        $("tr[name=employee_info] input").each(function () {
            $(this).val("");
            if ($(this).hasClass("amount")) {
                $(this).val(CURRENCY+"0");
                if($(this).attr('readonly')){
                    $(this).attr('readonly',false);
                }
            }
        });
        $('tr[name=employee_info] textarea').val('');
    }
    calcAll($("input[name='unit_price_commuting_sub[]']")[0]);
}

/**
 * 全ての金額に関する項目を自動計算
 * @param e
 */
function calcAll(e) {
    markCheckOnAmount(e);
    ableOtherSubMoney(e);
    let sumCommuting = 0;
    let sumWorking = 0;
    $("input[name='unit_price_commuting_sub[]']").each(function () {
        sumCommuting += parseInt(toNumberWithMinus($(this).val()));
    });
    $("input[name='unit_price_working_sub[]']").each(function () {
        sumWorking += parseInt(toNumber($(this).val()));
    });
    $("input[name=unit_price_commuting]").val(sumCommuting).trigger("blur");
    $("input[name=unit_price_working]").val(sumWorking).trigger("blur");
    let tax;
    if($("input[name=calc_type]").val() === '0'){
        tax = Math.round(sumWorking * TAX_RATE/100);
    }else{
        tax = Math.floor(sumWorking * TAX_RATE/100);
    }
    const sumOutTax = sumWorking + sumCommuting;
    const sumWithTax = sumOutTax + tax;
    $("input[name=unit_price_tax]").val(tax).trigger("blur");
    $("input[name=invoice_total]").val(sumWithTax).trigger("blur");
}

function handleWhenClientChanged(result) {
    changeCalcType(result.calc_type);
}

/**
 * 経費精算と給料精算は一つしか入力可能にしない
 * @param e
 */
function ableOtherSubMoney(e) {
    const name = $(e).attr("name");
    if ($(e).val().trim() !== CURRENCY+"0") {
        if (name === "unit_price_commuting_sub[]") {
            $(e).parents("tr").first().find("input[name='unit_price_working_sub[]']").prop("readonly", true).hide();
        } else {
            $(e).parents("tr").first().find("input[name='unit_price_commuting_sub[]']").prop("readonly", true).hide();
        }
    } else {
        if(!$(e).is(':read-only')){
            $(e).parents("tr").first().find("input:read-only").each(function () {
                $(this).prop("readonly", false).show();
            });
        }
    }
}

/**
 * 合計の計算
 * @param e
 */
function calcTotal(e) {
    markCheckOnAmount(e);
    $("input[name=invoice_total]").val($(e).val());
}

function unityDate() {
    const format = $("input[name=file_format_type]:checked").val();
    let input = $(date_selector);
    if (format === '0')
        input.val(input.first().val());
    else
        input.val(input.last().val());
    return input.val();
}

function changeLength(e) {
    $(e).height('auto');
    $(e).height(e.scrollHeight - 4);
    let lineHeight = parseFloat($(e).css('font-size'));
    if ($(e).attr('name') === 'detail_content[]') {
        let employee_period_picker = $(e).parents('tr[name=employee_info]').find('textarea[name=employee_period_picker]');
        if (e.scrollHeight <= 3*lineHeight) {
            employee_period_picker.css('margin-top', 7);
            if (e.scrollHeight <= lineHeight*2) {
                $(e).css('margin-top', 18);
            } else {
                $(e).css('margin-top', 7);
            }
        } else {
            $(e).css('margin-top', 7);
            employee_period_picker.css('margin-top', 7 + (e.scrollHeight - 48) / 2);
        }
    }
}

function changeCalcType(type) {
    const input = $("input[name=calc_type]");
    if(type !== input.val()) {
        input.val(type);
        calcAll($("input[name='unit_price_commuting_sub[]']")[0]);
    }
}
